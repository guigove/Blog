<?php

namespace App\Api;

use App\Controllers\ArticleController;
use App\Controllers\CategoryController;
use App\Controllers\TagController;
use App\Controllers\WikipediaController;

class Router {
    private $routes = [];
    private $controllers = [
        'categories' => CategoryController::class,
        'articles' => ArticleController::class,
        'tags' => TagController::class,
        'wikipedia' => WikipediaController::class
    ];

    public function __construct() {
        $this->setupRoutes();
    }

    private function setupRoutes() {
        // Categories routes
        $this->addRoute('GET', '/api/categories', 'categories', 'index');
        $this->addRoute('GET', '/api/categories/nested', 'categories', 'getNestedCategories');
        $this->addRoute('GET', '/api/categories/(\d+)', 'categories', 'show');
        $this->addRoute('POST', '/api/categories', 'categories', 'store');
        $this->addRoute('PUT', '/api/categories/(\d+)', 'categories', 'update');
        $this->addRoute('DELETE', '/api/categories/(\d+)', 'categories', 'delete');

        // Articles routes
        $this->addRoute('GET', '/api/articles', 'articles', 'index');
        $this->addRoute('GET', '/api/articles/(\d+)', 'articles', 'show');
        $this->addRoute('POST', '/api/articles', 'articles', 'store');
        $this->addRoute('PUT', '/api/articles/(\d+)', 'articles', 'update');
        $this->addRoute('DELETE', '/api/articles/(\d+)', 'articles', 'delete');

        // Tags routes
        $this->addRoute('GET', '/api/tags', 'tags', 'index');
        $this->addRoute('GET', '/api/tags/(\d+)', 'tags', 'show');
        $this->addRoute('POST', '/api/tags', 'tags', 'store');
        $this->addRoute('PUT', '/api/tags/(\d+)', 'tags', 'update');
        $this->addRoute('DELETE', '/api/tags/(\d+)', 'tags', 'delete');

        // Wikipedia routes
        $this->addRoute('GET', '/api/wikipedia/(.+)', 'wikipedia', 'getSummary');
        $this->addRoute('DELETE', '/api/wikipedia/(.+)', 'wikipedia', 'deleteSummary');
    }

    private function addRoute($method, $pattern, $controller, $action) {
        $this->routes[] = [
            'method' => $method,
            'pattern' => $pattern,
            'controller' => $controller,
            'action' => $action
        ];
    }

    public function handleRequest() {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Remove trailing slash
        $uri = rtrim($uri, '/');
        if (empty($uri)) {
            $uri = '/';
        }

        // Handle OPTIONS request for CORS
        if ($method === 'OPTIONS') {
            $this->handleCors();
            return;
        }

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            $pattern = str_replace('/', '\/', $route['pattern']);
            $pattern = '/^' . $pattern . '$/';

            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches); // Remove full match
                
                try {
                    $controllerClass = $this->controllers[$route['controller']];
                    $controller = new $controllerClass();
                    $action = $route['action'];
                    
                    return call_user_func_array([$controller, $action], $matches);
                } catch (\Exception $e) {
                    $this->handleError($e);
                    return;
                }
            }
        }

        $this->handleNotFound();
    }

    private function handleCors() {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
        header('Access-Control-Max-Age: 86400');
        http_response_code(204);
    }

    private function handleError(\Exception $e) {
        http_response_code(500);
        echo json_encode([
            'error' => true,
            'message' => 'Internal server error',
            'details' => $e->getMessage()
        ]);
    }

    private function handleNotFound() {
        http_response_code(404);
        echo json_encode([
            'error' => true,
            'message' => 'Route not found'
        ]);
    }
} 