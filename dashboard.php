<?php
// Personal Dashboard - Single File
// Load configuration from separate file
$config = include 'config.php';
$WEATHER_API_KEY = $config['weather_api_key'];
$NEWS_API_KEY = $config['news_api_key'];
$CITY = $config['city'];



// Enhanced function to make API calls with fallbacks
function fetchAPI($url, $useBackup = true) {
    $context = stream_context_create([
        'http' => [
            'timeout' => 8,
            'user_agent' => 'Personal Dashboard',
            'ignore_errors' => true
        ]
    ]);
    
    $response = @file_get_contents($url, false, $context);
    
    if ($response === false && function_exists('curl_init') && $useBackup) {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 8,
            CURLOPT_USERAGENT => 'Personal Dashboard',
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_FOLLOWLOCATION => true
        ]);
        
        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        
        if ($httpCode !== 200) {
            $response = false;
        }
    }
    
    return $response ? json_decode($response, true) : null;
}

function getWeather($city, $apiKey) {
    if (empty($apiKey) || $apiKey === 'PUT_YOUR_OPENWEATHER_API_KEY_HERE') {
        return null;
    }
    
    $urls = [
        "http://api.openweathermap.org/data/2.5/weather?q={$city}&appid={$apiKey}&units=metric",
        "https://api.openweathermap.org/data/2.5/weather?q={$city}&appid={$apiKey}&units=metric"
    ];
    
    foreach ($urls as $url) {
        $result = fetchAPI($url);
        if ($result && isset($result['main'])) {
            return $result;
        }
    }
    return null;
}

function getNews($apiKey) {
    if (empty($apiKey) || $apiKey === 'PUT_YOUR_NEWS_API_KEY_HERE') {
        return null;
    }
    $url = "https://newsapi.org/v2/top-headlines?country=us&apiKey={$apiKey}&pageSize=6";
    return fetchAPI($url);
}

function getQuote() {
    $apis = [
        "https://zenquotes.io/api/random",
        "https://api.quotable.io/random"
    ];
    
    foreach ($apis as $url) {
        $result = fetchAPI($url);
        if ($result) {
            if (isset($result[0])) {
                return [
                    'content' => $result[0]['q'] ?? $result[0]['text'] ?? '',
                    'author' => $result[0]['a'] ?? $result[0]['author'] ?? 'Unknown'
                ];
            } elseif (isset($result['content'])) {
                return $result;
            }
        }
    }
    
    $localQuotes = [
        ["content" => "The only way to do great work is to love what you do.", "author" => "Steve Jobs"],
        ["content" => "Innovation distinguishes between a leader and a follower.", "author" => "Steve Jobs"],
        ["content" => "Success is not final, failure is not fatal: it is the courage to continue that counts.", "author" => "Winston Churchill"]
    ];
    
    return $localQuotes[array_rand($localQuotes)];
}

function getCrypto() {
    $url = "https://api.coingecko.com/api/v3/simple/price?ids=bitcoin,ethereum,dogecoin&vs_currencies=usd&include_24hr_change=true";
    return fetchAPI($url);
}

// Fetch all data
$weather = getWeather($CITY, $WEATHER_API_KEY);
$news = getNews($NEWS_API_KEY);
$quote = getQuote();
$crypto = getCrypto();

// Time zones for display
$timezones = [
    'Local' => date('H:i'),
    'SF' => date('H:i', time() + (-8 * 3600)),
    'NY' => date('H:i', time() + (-5 * 3600)),
    'LD' => date('H:i', time() + (0 * 3600))
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>nest</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background: #000;
            color: #fff;
            font-family: 'SF Pro Display', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            font-size: 14px;
            line-height: 1.4;
            overflow-x: hidden;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 40px;
            border-bottom: 1px solid #333;
        }
        
        .logo {
            font-size: 16px;
            font-weight: 400;
            line-height: 1.2;
        }
        
        .header-controls {
            flex: 1;
            display: flex;
            justify-content: center;
        }
        
        .refresh-btn {
            background: transparent;
            border: 1px solid #666;
            color: #fff;
            padding: 8px 12px;
            cursor: pointer;
            font-size: 16px;
            transition: all 0.2s ease;
        }
        
        .refresh-btn:hover {
            border-color: #fff;
            background: rgba(255, 255, 255, 0.1);
        }
        
        .time-zones {
            text-align: right;
            font-size: 12px;
            line-height: 1.6;
        }
        
        .main-time {
            font-size: 20px;
            margin-bottom: 5px;
        }
        
        .search-container {
            padding: 40px;
            display: flex;
            justify-content: center;
        }
        
        .search-bar {
            position: relative;
            width: 600px;
            max-width: 100%;
        }
        
        .search-input {
            width: 100%;
            background: transparent;
            border: 1px solid #fff;
            color: #fff;
            padding: 12px 50px 12px 20px;
            font-size: 14px;
            outline: none;
        }
        
        .search-input::placeholder {
            color: #999;
        }
        
        .search-btn {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #fff;
            cursor: pointer;
            font-size: 16px;
        }
        
        .dashboard {
            padding: 0 40px 40px 40px;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            max-width: 1400px;
            margin: 0 auto;
        }
        
        .card {
            border: 1px solid #333;
            background: rgba(0, 0, 0, 0.8);
            min-height: 200px;
            position: relative;
        }
        
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            border-bottom: 1px solid #333;
        }
        
        .card-title {
            font-size: 14px;
            font-weight: 400;
        }
        
        .card-subtitle {
            font-size: 12px;
            color: #666;
        }
        
        .card-content {
            padding: 15px;
        }
        
        .weather-display {
            text-align: center;
            padding: 20px 0;
        }
        
        .weather-temp {
            font-size: 48px;
            font-weight: 300;
            margin-bottom: 10px;
        }
        
        .weather-desc {
            color: #999;
            margin-bottom: 5px;
        }
        
        .news-item {
            padding: 6px 0;
            border-bottom: 1px solid #222;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .news-item:last-child {
            border-bottom: none;
        }
        
        .news-arrow {
            color: #666;
            font-size: 12px;
            flex-shrink: 0;
        }
        
        .news-link {
            color: inherit;
            text-decoration: none;
            flex: 1;
            transition: color 0.2s ease;
        }
        
        .news-link:hover {
            color: #999;
        }
        
        .news-title {
            font-size: 12px;
            line-height: 1.3;
        }
        
        .quote-content {
            font-style: italic;
            font-size: 16px;
            line-height: 1.5;
            margin-bottom: 15px;
            text-align: center;
        }
        
        .quote-author {
            text-align: right;
            color: #666;
            font-size: 12px;
        }
        
        .crypto-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #222;
        }
        
        .crypto-item:last-child {
            border-bottom: none;
        }
        
        .crypto-name {
            font-size: 13px;
        }
        
        .crypto-price {
            text-align: right;
            font-size: 12px;
        }
        
        .positive { color: #0f0; }
        .negative { color: #f00; }
        
        .todo-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 6px 0;
            font-size: 13px;
        }
        
        .checkbox {
            width: 14px;
            height: 14px;
            border: 1px solid #666;
            background: transparent;
            cursor: pointer;
        }
        
        .checkbox:checked {
            background: #fff;
        }
        
        .time-display {
            text-align: center;
            padding: 30px 0;
        }
        
        .current-time {
            font-size: 36px;
            font-weight: 300;
            margin-bottom: 10px;
        }
        
        .current-date {
            color: #666;
            font-size: 13px;
        }
        
        .footer {
            padding: 20px 40px;
            border-top: 1px solid #333;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 14px;
            color: #666;
        }
        
        .footer-icons {
            display: flex;
            gap: 15px;
        }
        
        .setup-notice {
            color: #999;
            font-size: 12px;
            text-align: center;
            padding: 20px;
        }
        
        .setup-notice a {
            color: #666;
            text-decoration: underline;
        }
        
        @media (max-width: 1200px) {
            .dashboard {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (max-width: 768px) {
            .dashboard {
                grid-template-columns: 1fr;
                padding: 0 20px 20px 20px;
            }
            
            .header {
                padding: 20px;
            }
            
            .search-container {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">
            <a href="https://github.com/b-mozz" target="_blank" style="color: inherit; text-decoration: none;">b-mozz</a>/<br>
            dashboard
        </div>
        <div class="header-controls">
            <button class="refresh-btn" onclick="window.location.reload()">↻</button>
        </div>
        <div class="time-zones">
            <div class="main-time"><?php echo $timezones['Local']; ?></div>
            <div>SF <?php echo $timezones['SF']; ?></div>
            <div>NY <?php echo $timezones['NY']; ?></div>
            <div>LD <?php echo $timezones['LD']; ?></div>
        </div>
    </div>
    
    <div class="search-container">
        <div class="search-bar">
            <input type="text" class="search-input" id="searchInput" placeholder="Search for your next adventure">
            <button class="search-btn" onclick="performSearch()">→</button>
        </div>
    </div>
    
    <div class="dashboard">
        <!-- Weather Card -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">weather</div>
                <div class="card-subtitle"><?php echo strtolower($CITY); ?></div>
            </div>
            <div class="card-content">
                <?php if ($weather && isset($weather['main'])): ?>
                    <div class="weather-display">
                        <div class="weather-temp"><?php echo round($weather['main']['temp']); ?>°</div>
                        <div class="weather-desc"><?php echo strtolower($weather['weather'][0]['description']); ?></div>
                        <div class="weather-desc">feels like <?php echo round($weather['main']['feels_like']); ?>°</div>
                    </div>
                <?php else: ?>
                    <div class="setup-notice">
                        api key required<br>
                        <a href="https://openweathermap.org/api" target="_blank">get weather api key</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- News/Inbox Card -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">news</div>
                <div class="card-subtitle">latest</div>
            </div>
            <div class="card-content">
                <?php if ($news && isset($news['articles'])): ?>
                    <?php foreach (array_slice($news['articles'], 0, 6) as $article): ?>
                        <div class="news-item">
                            <div class="news-arrow">→</div>
                            <a href="<?php echo htmlspecialchars($article['url']); ?>" target="_blank" class="news-link">
                                <div class="news-title"><?php echo htmlspecialchars(substr($article['title'], 0, 65)) . '...'; ?></div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="setup-notice">
                        api key required<br>
                        <a href="https://newsapi.org/" target="_blank">get news api key</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Quote/Inspiration Card -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">inspiration</div>
                <div class="card-subtitle">daily</div>
            </div>
            <div class="card-content">
                <?php if ($quote): ?>
                    <div class="quote-content">"<?php echo htmlspecialchars($quote['content']); ?>"</div>
                    <div class="quote-author">— <?php echo htmlspecialchars($quote['author']); ?></div>
                <?php else: ?>
                    <div class="setup-notice">quote service offline</div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Crypto/Markets Card -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">markets</div>
                <div class="card-subtitle">crypto</div>
            </div>
            <div class="card-content">
                <?php if ($crypto): ?>
                    <?php foreach ($crypto as $coin => $data): ?>
                        <div class="crypto-item">
                            <div class="crypto-name"><?php echo $coin; ?></div>
                            <div class="crypto-price">
                                $<?php echo number_format($data['usd'], 0); ?>
                                <span class="<?php echo $data['usd_24h_change'] > 0 ? 'positive' : 'negative'; ?>">
                                    <?php echo $data['usd_24h_change'] > 0 ? '+' : ''; ?><?php echo number_format($data['usd_24h_change'], 1); ?>%
                                </span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="setup-notice">markets offline</div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Time Card -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">time</div>
                <div class="card-subtitle">local</div>
            </div>
            <div class="card-content">
                <div class="time-display">
                    <div class="current-time"><?php echo date('H:i:s'); ?></div>
                    <div class="current-date"><?php echo strtolower(date('l, F j')); ?></div>
                    <div class="current-date">week <?php echo date('W'); ?> of <?php echo date('Y'); ?></div>
                </div>
            </div>
        </div>
        
        <!-- System/Rituals Card -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">system</div>
                <div class="card-subtitle">status</div>
            </div>
            <div class="card-content">
                <div class="todo-item">
                    <input type="checkbox" class="checkbox" <?php echo $weather ? 'checked' : ''; ?>>
                    <span>weather api</span>
                </div>
                <div class="todo-item">
                    <input type="checkbox" class="checkbox" <?php echo $news ? 'checked' : ''; ?>>
                    <span>news feed</span>
                </div>
                <div class="todo-item">
                    <input type="checkbox" class="checkbox" <?php echo $quote ? 'checked' : ''; ?>>
                    <span>inspiration</span>
                </div>
                <div class="todo-item">
                    <input type="checkbox" class="checkbox" <?php echo $crypto ? 'checked' : ''; ?>>
                    <span>market data</span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="footer-icons">
        <a href="https://github.com/b-mozz" target="_blank" style="color: #666; text-decoration: none; font-family: monospace;" title="GitHub">
            <span>Link to My GitHub</span>
        </a>
    </div>
    
    <script>
        function performSearch() {
            const query = document.getElementById('searchInput').value.trim();
            if (query) {
                window.open(`https://www.google.com/search?q=${encodeURIComponent(query)}`, '_blank');
                document.getElementById('searchInput').value = ''; // Clear the input
            }
        }
        
        // Handle Enter key press
        document.getElementById('searchInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                performSearch();
            }
        });
        
        // Focus search input when pressing "/" key (like GitHub)
        document.addEventListener('keypress', function(e) {
            if (e.key === '/' && e.target.tagName !== 'INPUT') {
                e.preventDefault();
                document.getElementById('searchInput').focus();
            }
        });
    </script>
</body>
</html>