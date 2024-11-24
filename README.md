
## Backend Laravel Developer And News Aggregator API

This repository hosts the News Aggregator API, a backend system built with Laravel that fetches news and articles from third-party APIs, stores the data in a local database, and provides custom APIs to serve this content efficiently.


## Features

- User Authentication: Register, login, and reset password functionality.

- Rate Limiting: Custom rate limits for endpoints to ensure API stability.

- User Preferences:
  - Save and retrieve user preferences for news categories and sources.

  - Fetch personalized news feeds based on user preferences.

- News Articles:
  - Retrieve all articles with filtering and pagination.
  - Fetch articles by ID or slug.

- Secured Endpoints: Protected routes with authentication using Laravel Sanctum.
- Repository Design Pattern: Implements the repository design pattern for clean, reusable, and testable data handling.

## Setup and Installation

### Installation Steps:
#### Docker Setup:

For a seamless local environment, a Docker configuration is provided. 

If you don't have Docker, you can set up the project manually using the following prerequisites.

#### Prerequisites:

 - PHP: >=8.1
 - Laravel Framework: >=10.x
 - Database: MySQL (preferred) or SQLite

## Manual Setup Instructions

1. **Clone the Repository**

   Clone the repository to your local machine:

   ```bash
        git clone https://github.com/sadiqnoorw/innoscripta_aggregator_apis.git

        cd innoscripta_aggregator_apis
    ```

2. **Install Dependencies:**

```bash
    composer install
```
This will install the required dependencies.

3. **Set Up Environment Variables:**

- Copy .env.example to .env:

```bash
    cp .env.example .env
```

### Build and Run Docker Containers:

```bash
    ./vendor/bin/sail up
```


### Run Migrations:

```bash
    ./vendor/bin/sail php artisan migrate
```
***New you can access by*** http://localhost

## Data Synchronization

***This project fetches news data from third-party APIs and offers two options for keeping the data updated:***

1. **Using a Cron Job:**
Set up a cron job to periodically fetch data automatically. This ensures that the news feed is regularly updated.

Example cron job configuration:

```bash
    cd /path/to/your/laravel/project && php artisan schedule:run
```

2. **Direct API Call:**
Fetch news data manually by calling the fetchNews endpoint.

***Endpoint:***
```bash
   GET /api/fetchNews
```

## Access Application API Endpoints And Documentation

### swaggerhub Collection

```bash
    https://app.swaggerhub.com/apis/sadiqpk33/innoscripta_id/1.0.0
```
### Postman Collection
To make testing the API easier, a Postman collection is provided in the repository. You can find it in the following path:

```bash
    postman_collection/innoscripta_id.postman_collection.json
```


## Authentication

#### Register a new user:

| Method | Endpoint | Description |
| :---  |     :---:       |          ---:      |
| POST  | /api/register       | User registration  | 

#### Parameters post in body like:
```bash
    {
        "name": "John Doe",
        "email": "john@exampled.com",
        "password": "password",
        "password_confirmation": "password"
    }
```
#### Login:

| Method | Endpoint | Description |
| :---  |     :---:       |          ---:      |
| POST  | /api/login          | User login         | 

#### Parameters post in body like:
```bash
    {
        "email": "john@exampled.com",
        "password": "password"
    }
```

#### Reset Password :

| Method | Endpoint | Description |
| :---  |     :---:       |          ---:      |
| POST	| /api/reset-password |	Password reset     |

#### Parameters post in body like:
```bash
  {
    "email": "john@exampled.com",
    "password": "password",
    "password_confirmation": "password"
    }
```

## News Management

| Method | Endpoint              | Description         |
| :---   |     :---:             |          ---:       |
|  GET   | /api/fetchNews            | Fetch raw news data |
|  GET   | /api/articles/{id}        | Fetch a single article by ID    |
|  GET   | /api/articles/slug/{slug} | Fetch an article by slug|

#### Fetch all articles (with filters and pagination):
| Method | Endpoint              | Description         |
| :---   |     :---:             |          ---:       |
|  GET   | /api/articles             | Retrieve articles with pagination |

#### Query Parameters like:
```bash
  articles?keyword=technology&category=&source=&date=&per_page=10
```

## User Preferences (Protected Routes)

| Method | Endpoint                | Description                |
| :---   |     :---:               |          ---:              |
| POST	 | /api/auth/preferences	   | Save user preferences      |
| POST	 | /api/auth/logout	           | Logout the user            |
| GET	 | /api/auth/personalized-news | Get personalized news feed |


#### User Preferences (Authenticated):
| Method | Endpoint              | Description         |
| :---   |     :---:             |          ---:       |
| GET    |	/api/auth/preferences	   | Retrieve user preferences  |

#### Parameters post in body like:
```bash
    {
        "preferred_sources": ["guardian_api", "new_api"],
        "preferred_categories": ["Lifestyle", "News"]
    }
```

## Rate Limiting

- Public Routes: Default rate limits applied (e.g., 60 requests per minute).
- Authenticated Routes: Custom rate limits implemented based on user tiers.
- Configuration: Adjust settings in RouteServiceProvider.

## Testing

### Run the test suite to ensure all functionality works as expected:

1. **Run All Tests:**

```bash
    php artisan test
```

2. **Unit Tests:**

```bash
    php artisan test --filter=Unit    
```

3. **Feature Tests:**

```bash
    php artisan test --filter=Feature
```

