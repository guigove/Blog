<?php

namespace App\Controllers;

class Controller {
    protected function json($data, $statusCode = 200) {
        http_response_code($statusCode);
        return json_encode($data);
    }

    protected function error($message, $statusCode = 400) {
        return $this->json(['error' => $message], $statusCode);
    }

    protected function success(string $message, $data = null, int $status = 200) {
        return $this->json([
            'message' => $message,
            'data' => $data
        ], $status);
    }

    protected function notFound($message = 'Resource not found') {
        return $this->error($message, 404);
    }

    protected function serverError($message = 'Internal server error') {
        return $this->error($message, 500);
    }

    protected function getRequestData(): array {
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';

        if (stripos($contentType, 'application/json') !== 0) {
            http_response_code(415); // Unsupported Media Type
            echo json_encode(['error' => 'Content-Type must be application/json']);
            exit;
        }

        $rawInput = file_get_contents('php://input');
        $data = json_decode($rawInput, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            http_response_code(400); // Bad Request
            echo json_encode(['error' => 'Invalid JSON: ' . json_last_error_msg()]);
            exit;
        }

        return $data;
    }

    protected function getIncludeRelations(): array {
        $include = $_GET['include'] ?? '';
        if (empty($include)) {
            return [];
        }
        return explode(',', $include);
    }

    protected function loadRelations($entity, array $relations, $service): void {
        foreach ($relations as $relation) {
            $method = 'load' . ucfirst($relation);
            if (method_exists($service, $method)) {
                $service->$method($entity);
            }
        }
    }

    protected function validateRequired($data, $fields) {
        $missing = [];
        foreach ($fields as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                $missing[] = $field;
            }
        }
        
        if (!empty($missing)) {
            $this->error('Missing required fields: ' . implode(', ', $missing));
        }
        
        return true;
    }
} 