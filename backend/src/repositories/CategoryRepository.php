<?php

namespace App\Repositories;

use App\Models\Category;
use App\Config\Database;
use App\Factories\CategoryFactory;
use App\Factories\ArticleFactory;
use PDO;
use PDOException;

class CategoryRepository implements RepositoryInterface {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findById(int $id): ?object {
        try {
            $stmt = $this->db->prepare("SELECT * FROM categories WHERE id = ?");
            if (!$stmt) {
                throw new PDOException("Failed to prepare statement for findById");
            }
            
            $stmt->execute([$id]);
            $data = $stmt->fetch();
            return $data ? CategoryFactory::createFromData($data) : null;
        } catch (PDOException $e) {
            throw new PDOException("Error finding category by ID: " . $e->getMessage());
        }
    }

    public function findAll(): array {
        try {
            $stmt = $this->db->query("SELECT * FROM categories ORDER BY name");
            if (!$stmt) {
                throw new PDOException("Failed to prepare statement for findAll");
            }

            $categories = [];
            while ($data = $stmt->fetch()) {
                $categories[] = CategoryFactory::createFromData($data);
            }
            return $categories;
        } catch (PDOException $e) {
            throw new PDOException("Error finding all categories: " . $e->getMessage());
        }
    }

    public function save(object $entity): bool {
        if (!($entity instanceof Category)) {
            throw new \InvalidArgumentException("Entity must be an instance of Category");
        }
        
        try {
            if ($entity->id) {
                return $this->update($entity);
            }
            return $this->insert($entity);
        } catch (PDOException $e) {
            throw new PDOException("Error saving category: " . $e->getMessage());
        }
    }

    private function insert(Category $category): bool {
        try {
            $stmt = $this->db->prepare("INSERT INTO categories (name, parent_id) VALUES (?, ?)");
            if (!$stmt) {
                throw new PDOException("Failed to prepare statement for insert");
            }

            $result = $stmt->execute([
                $category->name,
                $category->parent_id
            ]);

            if ($result) {
                $category->id = (int)$this->db->lastInsertId();
            }

            return $result;
        } catch (PDOException $e) {
            throw new PDOException("Error inserting category: " . $e->getMessage());
        }
    }

    private function update(Category $category): bool {
        try {
            $stmt = $this->db->prepare("UPDATE categories SET name = ?, parent_id = ? WHERE id = ?");
            if (!$stmt) {
                throw new PDOException("Failed to prepare statement for update");
            }

            return $stmt->execute([
                $category->name,
                $category->parent_id,
                $category->id
            ]);
        } catch (PDOException $e) {
            throw new PDOException("Error updating category: " . $e->getMessage());
        }
    }

    public function delete(int $id): bool {
        try {
            $stmt = $this->db->prepare("DELETE FROM categories WHERE id = ?");
            if (!$stmt) {
                throw new PDOException("Failed to prepare statement for delete");
            }

            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            throw new PDOException("Error deleting category: " . $e->getMessage());
        }
    }

    public function getParentCategories(): array {
        try {
            $stmt = $this->db->query("SELECT * FROM categories WHERE parent_id IS NULL ORDER BY name");
            if (!$stmt) {
                throw new PDOException("Failed to prepare statement for getParentCategories");
            }

            $parentCategories = [];
            while ($data = $stmt->fetch()) {
                $parentCategories[] = CategoryFactory::createFromData($data);
            }
            return $parentCategories;
        } catch (PDOException $e) {
            throw new PDOException("Error finding all parent categories: " . $e->getMessage());
        }
    }

    public function getChildrenByParentId(int $parentId): array {
        try {
            $stmt = $this->db->prepare("SELECT * FROM categories WHERE parent_id = ? ORDER BY name");
            if (!$stmt) {
                throw new PDOException("Failed to prepare statement for getChildrenByParentId");
            }

            $stmt->execute([$parentId]);
            $children = [];
            while ($data = $stmt->fetch()) {
                $children[] = CategoryFactory::createFromData($data);
            }
            return $children;
        } catch (PDOException $e) {
            throw new PDOException("Error getting children by parent ID: " . $e->getMessage());
        }
    }

    public function getArticlesByCategory(int $categoryId): array {
        try {
            $stmt = $this->db->prepare("SELECT * FROM articles WHERE category_id = ? ORDER BY created_at DESC");
            if (!$stmt) {
                throw new PDOException("Failed to prepare statement for getArticlesByCategory");
            }

            $stmt->execute([$categoryId]);
            $articles = [];
            while ($data = $stmt->fetch()) {
                $articles[] = ArticleFactory::createFromData($data);
            }
            return $articles;
        } catch (PDOException $e) {
            throw new PDOException("Error getting articles by category: " . $e->getMessage());
        }
    }
} 