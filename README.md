# DataForSEO Position Checker

A single-page Laravel application for checking website positions in Google organic search results using DataForSEO API v3.

## Description

The application allows you to:
- Enter search keyword, website domain, location, and language
- Get the position of a specified website in Google organic search results
- Display the result or a message that the website was not found

## Technical Requirements

- PHP >= 8.1
- Composer
- DataForSEO API credentials

## Installation Instructions

### 1. Clone repository and install dependencies

```bash
git clone https://github.com/mishhkaa/dataforseo-position-checker.git
cd dataforseo-position-checker
composer install
```

### 2. Environment Setup

Copy `.env.example` to `.env` and update it with your DataForSEO credentials:

```bash
cp .env.example .env
```

Edit the `.env` file and add your DataForSEO API credentials:

```env
DATAFORSEO_LOGIN=your_login@email.com
DATAFORSEO_PASSWORD=your_api_password
```

### 3. Generate Application Key

```bash
php artisan key:generate
```

### 4. Start Development Server

```bash
php artisan serve
```

The application will be available at: http://localhost:8000

## Usage

1. Open your browser and go to http://localhost:8000
2. Fill out the form:
   - **Search Keyword**: keyword to search for
   - **Website Domain**: domain of the website (e.g., example.com)
   - **Location**: country or city (e.g., Ukraine)
   - **Language**: search language (Ukrainian, English, Russian)
3. Click the "Search Position" button
4. Get the result with the website position or a message that the website was not found

## Project Structure

- `app/Http/Controllers/DataForSeoController.php` - main controller
- `resources/views/search.blade.php` - main page with form
- `routes/web.php` - application routes
- `config/services.php` - DataForSEO API configuration

## API Endpoints

The application uses the following DataForSEO API endpoints:

- `/v3/serp/google/locations` - getting location codes
- `/v3/serp/google/languages` - getting language codes
- `/v3/serp/google/organic/live/advanced` - performing search

## Implementation Features

- Automatic determination of location and language codes by names
- Error handling and display of clear messages
- Modern responsive design with Bootstrap 5
- Server-side form validation
- Clean code without unnecessary logging

## Possible Errors and Solutions

### "Location not found"
- Check the correct spelling of the location name
- Try using the English name (e.g., "Ukraine" instead of "Україна")

### "Language not found"
- Select a language from the dropdown list
- Make sure the language is supported by DataForSEO API

### "Error executing request"
- Check the correctness of DataForSEO API credentials in the `.env` file
- Make sure you have enough credits on your DataForSEO account
- Check your internet connection

## License

This project was created for a test assignment for the Junior PHP Developer position.

## Author

Test assignment for Softoria, DataForSEO project
