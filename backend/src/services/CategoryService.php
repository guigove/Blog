<?php

namespace App\Services;

use App\Models\Category;
use App\Repositories\CategoryRepository;

class CategoryService {
    private CategoryRepository $categoryRepository;

    public function __construct() {
        $this->categoryRepository = new CategoryRepository();
    }

    public function getCategory(int $id): ?Category {
        return $this->categoryRepository->findById($id);
    }

    public function getAllCategories(): array {
        return $this->categoryRepository->findAll();
    }

    public function getAllNestedCategories(): array {
        $parentCategories = $this->categoryRepository->getParentCategories();
        $nestedCategories = [];

        foreach ($parentCategories as $parent) {
            $this->loadChildrenRecursively($parent);
            $nestedCategories[] = $parent;
        }

        return $nestedCategories;
    }

    private function loadChildrenRecursively(Category $category): void {
        $children = $this->categoryRepository->getChildrenByParentId($category->id);
        $category->setChildren($children);

        foreach ($children as $child) {
            $this->loadChildrenRecursively($child);
        }
    }
    public function createCategory(string $name, ?int $parent_id = null): ?Category {
        $this->validateParentId($parent_id);

        $category = new Category([
            'id' => 0,
            'name' => $name,
            'parent_id' => $parent_id
        ]);

        if (!$this->categoryRepository->save($category)) {
            return null;
        }

        return $this->categoryRepository->findById($category->id);
    }

    public function updateCategory(int $id, string $name, ?int $parent_id = null): ?Category {
        $category = $this->categoryRepository->findById($id);
        if (!$category) {
            return null;
        }

        $this->validateParentId($parent_id, $id);

        $category->name = $name;
        $category->parent_id = $parent_id;

        if (!$this->categoryRepository->save($category)) {
            return null;
        }

        return $this->categoryRepository->findById($category->id);
    }

    public function deleteCategory(int $id): bool {
        return $this->categoryRepository->delete($id);
    }

    public function loadArticles(Category $category): void {
        $articles = $this->categoryRepository->getArticlesByCategory($category->id);
        $category->setArticles($articles);
    }

    public function loadChildren(Category $category): void {
        $children = $this->categoryRepository->getChildrenByParentId($category->id);
        $category->setChildren($children);
    }

    private function validateParentId(?int $parent_id, ?int $current_id = null): void {
        if ($parent_id === null) {
            return;
        }
    
        $parent = $this->getCategory($parent_id);
        if (!$parent) {
            throw new \Exception("Parent category not found");
        }
    
        if ($current_id !== null) {
            if ($parent_id === $current_id) {
                throw new \Exception("A category cannot be its own parent");
            }
    
            $current = $this->getCategory($current_id);
            if ($current && $this->isDescendant($parent_id, $current)) {
                throw new \Exception("Cannot set a descendant category as parent");
            }
        }
    }

    private function isDescendant(int $potentialDescendantId, Category $category): bool {
        $queue = [$category];
    
        while (!empty($queue)) {
            $current = array_shift($queue);
            $this->loadChildren($current);
    
            foreach ($current->getChildren() as $child) {
                if ($child->id === $potentialDescendantId) {
                    return true;
                }
                $queue[] = $child;
            }
        }
    
        return false;
    }

} 