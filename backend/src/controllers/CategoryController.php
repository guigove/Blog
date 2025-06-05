<?php

namespace App\Controllers;

use App\Services\CategoryService;

class CategoryController extends Controller {
    private CategoryService $categoryService;

    public function __construct() {
        $this->categoryService = new CategoryService();
    }

    public function index() {
        $categories = $this->categoryService->getAllCategories();
        $relations = $this->getIncludeRelations();
        
        foreach ($categories as $category) {
            $this->loadRelations($category, $relations, $this->categoryService);
        }
        
        return $this->success('Categories retrieved successfully', array_map(fn($category) => $category->toArray(), $categories));
    }

    public function show(int $id) {
        $category = $this->categoryService->getCategory($id);
        if (!$category) {
            return $this->notFound('Category not found');
        }

        $relations = $this->getIncludeRelations();
        $this->loadRelations($category, $relations, $this->categoryService);
        
        return $this->success('Category retrieved successfully', $category->toArray());
    }

    public function store() {
        $data = $this->getRequestData();
        
        if (!$this->validateRequired($data, ['name'])) {
            return;
        }

        try {
            $category = $this->categoryService->createCategory(
                $data['name'],
                $data['parent_id'] ?? null
            );

            if (!$category) {
                return $this->serverError('Failed to create category');
            }

            return $this->success('Category created successfully', $category->toArray(), 201);
        } catch (\Exception $e) {
            return $this->serverError($e->getMessage());
        }
    }

    public function update(int $id) {
        $data = $this->getRequestData();
        
        if (!$this->validateRequired($data, ['name'])) {
            return;
        }

        try {
            $category = $this->categoryService->updateCategory(
                $id,
                $data['name'],
                $data['parent_id'] ?? null
            );

            if (!$category) {
                return $this->notFound('Category not found');
            }

            return $this->success('Category updated successfully', $category->toArray());
        } catch (\Exception $e) {
            return $this->serverError($e->getMessage());
        }
    }

    public function delete(int $id) {
        try {
            $success = $this->categoryService->deleteCategory($id);
            if (!$success) {
                return $this->notFound('Category not found');
            }
            return $this->success('Category deleted successfully');
        } catch (\Exception $e) {
            return $this->serverError($e->getMessage());
        }
    }

    public function getNestedCategories() {
        try {
            $categories = $this->categoryService->getAllNestedCategories();
            $relations = $this->getIncludeRelations();
            
            foreach ($categories as $category) {
                $this->loadRelations($category, $relations, $this->categoryService);
            }
            
            return $this->success('Nested categories retrieved successfully', array_map(fn($category) => $category->toArray(), $categories));
        } catch (\Exception $e) {
            return $this->serverError($e->getMessage());
        }
    }
} 