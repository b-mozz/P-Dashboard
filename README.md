# Personal Dashboard

  

A minimal, single-file personal dashboard built with PHP and JavaScript. This project was created to learn and practice PHP development while exploring how to combine server-side PHP with client-side JavaScript in a single file.

#### [Live Demonstration](https://bmozz.infy.uk/)
  

##  Learning Objectives

  

- **PHP Practice**: Learn server-side programming with PHP

- **Single-File Architecture**: Explore combining PHP backend logic with HTML/CSS/JavaScript frontend in one file

- **API Integration**: Practice consuming external APIs with PHP

- **Responsive Design**: Create a mobile-friendly interface with pure CSS

- **State Management**: Handle data flow between server-side and client-side code

  

## Features

  

- **📰 Latest News**: Real-time news headlines with clickable links

- **💭 Daily Inspiration**: Random motivational quotes with fallback sources

- **💰 Crypto Prices**: Live cryptocurrency market data (Bitcoin, Ethereum, Dogecoin)

- **🔗 Quick Links**: Fast access to frequently used websites

- **🕐 Multi-Timezone Clock**: Current time with multiple timezone display

- **🔍 Google Search**: Integrated search functionality

- **📊 System Status**: Real-time API health monitoring

- **🔄 Auto-Refresh**: Automatic data updates every 15 minutes with countdown

- **📱 Responsive Design**: Works seamlessly on desktop and mobile

  

##  Technical Architecture

  

### Single-File Design

The entire application runs from one file (`dashboard.php`) that contains:

- **PHP Backend**: API calls, data processing, configuration management

- **HTML Structure**: Semantic markup with modern CSS Grid layout

- **CSS Styling**: Minimal dark theme with glassmorphism effects

- **JavaScript Frontend**: Search functionality, auto-refresh, and user interactions

  

### API Integration

- **News API**: Fetches latest headlines with error handling

- **CoinGecko API**: Retrieves cryptocurrency prices (no API key required)

- **Quote APIs**: Multiple fallback sources for daily inspiration

- **Error Handling**: Graceful degradation when APIs are unavailable

  

## Quick Start

  

### 1. Clone the Repository

```bash

git clone https://github.com/your-username/personal-dashboard.git

cd personal-dashboard

```

  

### 2. Set Up Configuration

Create a `config.php` file with your API keys:

```php

<?php

return [

'news_api_key' => 'your_news_api_key_here',

'city' => 'Your City'

];

?>

```

  

### 3. Get API Keys (Free)

- **News API**: Sign up at [NewsAPI.org](https://newsapi.org/) (1000 requests/day free)

- **Crypto & Quotes**: No API keys required!

  

### 4. Deploy

Upload `dashboard.php` and `config.php` to any PHP hosting provider.

  

##  Configuration

  

### API Keys

- **News API**: Get free key from [NewsAPI.org](https://newsapi.org/)

- **Quote APIs**: Multiple sources with automatic fallbacks (no keys needed)

- **Crypto API**: Uses CoinGecko's free public API

  

### Customization

- **City**: Change location for timezone display

- **Quick Links**: Modify the links array in the HTML section

- **Refresh Interval**: Adjust auto-refresh timing in JavaScript

- **Styling**: Update CSS variables for different color schemes

  

### Security

- Never commit `config.php` with real API keys to version control

- Use environment variables for production deployments

- Keep API keys in separate configuration files

  

##  Project Structure

  

```

personal-dashboard/

├── dashboard.php # Main application file

├── config.php # Configuration (not in repo)

├── README.md # This file

└── .gitignore # Ignore sensitive files

```

  

##  Design Philosophy

  

- **Minimal Aesthetic**: Clean, dark interface inspired by modern developer tools

- **Single-File Simplicity**: Everything in one file for easy deployment and understanding

- **Progressive Enhancement**: Works with basic functionality even when APIs fail

- **Mobile-First**: Responsive design that works on all screen sizes

  

##  What I Learned

  

### PHP Skills Developed

- Server-side API consumption with `file_get_contents()` and cURL

- Error handling and fallback strategies

- Configuration management and security best practices

- JSON data processing and manipulation

  

### JavaScript Integration

- Seamless communication between PHP backend and JavaScript frontend

- Client-side functionality without framework dependencies

- DOM manipulation and event handling

- Asynchronous operations and timers

  

### Single-File Architecture Benefits

- **Simplicity**: Easy to understand, deploy, and maintain

- **Portability**: Can be uploaded to any PHP hosting provider

- **Learning**: Clear separation of concerns within one file

- **Rapid Prototyping**: Quick iterations and testing



##  Future Enhancements

  

- [ ] Add weather integration with different API providers

- [ ] Implement local storage for user preferences

- [ ] Add more quick link categories

- [ ] Create admin panel for easy customization

- [ ] Add PWA functionality for mobile app experience

- [ ] Integrate with productivity APIs (Todoist, Google Calendar)

  

##  Contributing

  

This is primarily a learning project, but suggestions and improvements are welcome! Feel free to:

- Open issues for bugs or feature requests

- Submit pull requests for enhancements

- Share your own dashboard modifications

  

##  License

  

This project is open source and available under the [MIT License](LICENSE).

  

##  Acknowledgments

  

- **Design Inspiration**: Modern developer [William Felker's](https://dribbble.com/gndclouds) dashboard.

- **API Providers**: NewsAPI, CoinGecko, and various quote APIs

- **Learning Resources**: PHP documentation, MDN Web Docs, and developer community

  

---
  
*If you found this project helpful for learning PHP, please consider giving it a star!*
