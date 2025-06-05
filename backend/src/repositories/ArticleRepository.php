<?php

namespace App\Repositories;

use App\Models\Article;
use App\Config\Database;
use App\Factories\ArticleFactory;
use App\Factories\TagFactory;
use PDO;
use PDOException;

class ArticleRepository implements RepositoryInterface {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findById(int $id): ?object {
        try {
            $stmt = $this->db->prepare("SELECT * FROM articles WHERE id = ?");
            if (!$stmt) {
                throw new PDOException("Failed to prepare statement for findById");
            }
            
            $stmt->execute([$id]);
            $data = $stmt->fetch();
            return $data ? ArticleFactory::createFromData($data) : null;
        } catch (PDOException $e) {
            throw new PDOException("Error finding article by ID: " . $e->getMessage());
        }
    }

    public function findAll(): array {
        try {
            $stmt = $this->db->query("SELECT * FROM articles ORDER BY created_at DESC");
            if (!$stmt) {
                throw new PDOException("Failed to prepare statement for findAll");
            }

            $articles = [];
            while ($data = $stmt->fetch()) {
                $articles[] = ArticleFactory::createFromData($data);
            }
            return $articles;
        } catch (PDOException $e) {
            throw new PDOException("Error finding all articles: " . $e->getMessage());
        }
    }

    public function save(object $entity): bool {
        try {
            if (!($entity instanceof Article)) {
                throw new \InvalidArgumentException("Entity must be an instance of Article");
            }

            if ($entity->id === 0) {
                return $this->insert($entity);
            } else {
                return $this->update($entity);
            }
        } catch (PDOException $e) {
            throw new PDOException("Error saving article: " . $e->getMessage());
        }
    }

    private function insert(Article $article): bool {
        try {
            $stmt = $this->db->prepare(
                "INSERT INTO articles (title, body, category_id) VALUES (?, ?, ?)"
            );
            if (!$stmt) {
                throw new PDOException("Failed to prepare statement for insert");
            }

            $result = $stmt->execute([
                $article->title,
                $article->body,
                $article->category_id
            ]);

            if ($result) {
                $article->id = (int)$this->db->lastInsertId();
            }

            return $result;
        } catch (PDOException $e) {
            throw new PDOException("Error inserting article: " . $e->getMessage());
        }
    }

    private function update(Article $article): bool {
        try {
            $stmt = $this->db->prepare("
                UPDATE articles 
                SET title = ?, body = ?, category_id = ?
                WHERE id = ?
            ");
            if (!$stmt) {
                throw new PDOException("Failed to prepare statement for update");
            }

            return $stmt->execute([
                $article->title,
                $article->body,
                $article->category_id,
                $article->id
            ]);
        } catch (PDOException $e) {
            throw new PDOException("Error updating article: " . $e->getMessage());
        }
    }

    public function delete(int $id): bool {
        try {
            $stmt = $this->db->prepare("DELETE FROM articles WHERE id = ?");
            if (!$stmt) {
                throw new PDOException("Failed to prepare statement for delete");
            }

            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            throw new PDOException("Error deleting article: " . $e->getMessage());
        }
    }

    public function updateArticleTags(int $articleId, array $tagIds): bool {
        try {
            $this->db->beginTransaction();

            // Remove existing tags
            $stmt = $this->db->prepare("DELETE FROM article_tags WHERE article_id = ?");
            if (!$stmt) {
                throw new PDOException("Failed to prepare statement for deleting article tags");
            }
            $stmt->execute([$articleId]);

            // Add new tags
            if (!empty($tagIds)) {
                $values = array_fill(0, count($tagIds), "(?, ?)");
                $sql = "INSERT INTO article_tags (article_id, tag_id) VALUES " . implode(", ", $values);
                $stmt = $this->db->prepare($sql);
                if (!$stmt) {
                    throw new PDOException("Failed to prepare statement for inserting article tags");
                }

                $params = [];
                foreach ($tagIds as $tagId) {
                    $params[] = $articleId;
                    $params[] = $tagId;
                }
                $stmt->execute($params);
            }

            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            throw new PDOException("Error updating article tags: " . $e->getMessage());
        }
    }

    public function getByTag(int $tagId): array {
        try {
            $stmt = $this->db->prepare("
                SELECT a.* FROM articles a
                INNER JOIN article_tags at ON a.id = at.article_id
                WHERE at.tag_id = ?
                ORDER BY a.created_at DESC
            ");
            if (!$stmt) {
                throw new PDOException("Failed to prepare statement for getByTag");
            }

            $stmt->execute([$tagId]);
            $articles = [];
            while ($data = $stmt->fetch()) {
                $articles[] = ArticleFactory::createFromData($data);
            }
            return $articles;
        } catch (PDOException $e) {
            throw new PDOException("Error getting articles by tag: " . $e->getMessage());
        }
    }
} 