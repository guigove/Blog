# Blog Management System

A full-stack blog management system built with Vanilla PHP (backend) and Vue.js (frontend). Features include recursive categories, Wikipedia integration, Redis caching, and a modern architecture.

## Features

- Infinite nested categories with tree view display
- Article management with Wikipedia summaries
- Tag system (many-to-many)
- Redis caching for performance
- Vue.js 3 frontend with Pinia and Vuetify
- RESTful API built with Vanilla PHP
- Postman collection for API testing

## Technologies Used

### Backend
- **PHP 8.2**
- **MySQL 8.0**
- **Redis 7.0**
- **Apache 2.4**

#### Architecture & Patterns
The backend follows the **Controller-Service-Repository** pattern:
- **Controller**: Handles HTTP requests, validates data, and returns responses.
- **Service**: Contains business logic, orchestrating operations between controllers and repositories.
- **Repository**: Responsible for data access and manipulation (CRUD), abstracting persistence.

Other points:
- Uses PDO for database access.
- RESTful principles for endpoints.
- Redis for caching external data (Wikipedia).
- Postman collection available at `backend/postman/Blog.postman_collection.json` for easy API testing and integration.

### Frontend
- **Vue.js 3**
- **Pinia** (state management)
- **Axios** (HTTP client)
- **Vuetify** (UI component framework)

#### Organization
- Reusable components for forms, tables, and dialogs.
- Pinia stores to centralize the state of articles, categories, and tags.
- Form validation and visual loading feedback.
- Wikipedia integration to display article summaries.
- Tree view component for displaying nested categories.

## Folder Structure

```
.
├── backend/                # PHP REST API
│   ├── src/               # Source code
│   │   ├── api/          # API endpoints
│   │   ├── config/       # Configurations (DB, Redis, etc)
│   │   ├── controllers/  # Request handlers
│   │   ├── factories/    # Object factories
│   │   ├── models/       # Data models
│   │   ├── repositories/ # Data access (repositories)
│   │   └── services/     # Business logic (services)
│   ├── public/           # Public entry point
│   ├── database/         # Database schema
│   ├── postman/          # Postman collection
│   ├── composer.json     # PHP dependencies
│   ├── .env.example      # Example environment variables
│   ├── docker/           # Docker configuration files
│   │   ├── apache/      # Apache Dockerfile/config
│   │   └── php/         # PHP Dockerfile/config
│   └── docker-compose.yml # Docker services configuration
├── frontend/             # Vue.js application
│   ├── src/             # Source code
│   │   ├── components/  # Vue components
│   │   ├── stores/      # Pinia stores
│   │   ├── views/       # Pages/views
│   │   ├── router/      # Vue Router configuration
│   │   ├── plugins/     # Vue plugins
│   │   ├── assets/      # Static assets
│   │   ├── App.vue      # Root component
│   │   └── main.js      # Entry point
│   ├── public/          # Public static files
│   ├── index.html       # HTML entry point
│   ├── package.json     # Node.js dependencies
│   └── vite.config.js   # Vite configuration
└── README.md            # Project documentation
```

## Project Setup

### Backend
1. Clone the repository:
   ```bash
   git clone https://github.com/guigove/Blog.git
   cd Blog
   ```
2. Copy the environment file to the backend folder:
   ```bash
   cd backend
   cp .env.example .env
   ```
3. Start the Docker containers (inside the backend folder):
   ```bash
   docker-compose up -d --build 
   ```

### Frontend
4. Install frontend dependencies:
   ```bash
   cd ../frontend
   npm install
   ```
5. Run the frontend in development mode or build it:
   ```bash
   npm run dev
   # or
   npm run build
   ```

6. Access the application:
   - Frontend: http://localhost:5173 (dev mode) or as configured
   - Backend API: http://localhost:8000

## API Documentation

### Main Endpoints

#### Categories
- `GET /api/categories` - List all categories
- `GET /api/categories/nested` - Get categories in tree format
- `GET /api/categories/{id}` - Get a specific category
- `POST /api/categories` - Create a new category
- `PUT /api/categories/{id}` - Update a category
- `DELETE /api/categories/{id}` - Delete a category

Relationships:
- `?include=children` - Include child categories
- `?include=articles` - Include related articles
- `?include=children,articles` - Include both children and articles

#### Articles
- `GET /api/articles` - List all articles
- `GET /api/articles/{id}` - Get a specific article
- `POST /api/articles` - Create a new article
- `PUT /api/articles/{id}` - Update an article
- `DELETE /api/articles/{id}` - Delete an article

Relationships:
- `?include=category` - Include the article's category
- `?include=tags` - Include related tags
- `?include=category,tags` - Include both category and tags

#### Tags
- `GET /api/tags` - List all tags
- `GET /api/tags/{id}` - Get a specific tag
- `POST /api/tags` - Create a new tag
- `PUT /api/tags/{id}` - Update a tag
- `DELETE /api/tags/{id}` - Delete a tag

Relationships:
- `?include=articles` - Include related articles

#### Wikipedia Integration
- `GET /api/wikipedia/{articleTitle}` - Get Wikipedia article summary
- `DELETE /api/wikipedia/{articleTitle}` - Remove Wikipedia article from cache

Example requests:
```bash
# Get a category with its children and articles
GET /api/categories/1?include=children,articles

# Get an article with its category and tags
GET /api/articles/1?include=category,tags

# Get a tag with its articles
GET /api/tags/1?include=articles

# Get Wikipedia article information
GET /api/wikipedia/PHP

# Remove Wikipedia article from cache
DELETE /api/wikipedia/PHP
```

## License

MIT License