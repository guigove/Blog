<?php

namespace App\Repositories;

use App\Models\Tag;
use App\Config\Database;
use App\Factories\TagFactory;
use PDO;
use PDOException;

class TagRepository implements RepositoryInterface {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findById(int $id): ?object {
        try {
            $stmt = $this->db->prepare("SELECT * FROM tags WHERE id = ?");
            if (!$stmt) {
                throw new PDOException("Failed to prepare statement for findById");
            }
            
            $stmt->execute([$id]);
            $data = $stmt->fetch();
            return $data ? TagFactory::createFromData($data) : null;
        } catch (PDOException $e) {
            throw new PDOException("Error finding tag by ID: " . $e->getMessage());
        }
    }

    public function findAll(): array {
        try {
            $stmt = $this->db->query("SELECT * FROM tags ORDER BY name");
            if (!$stmt) {
                throw new PDOException("Failed to prepare statement for findAll");
            }

            $tags = [];
            while ($data = $stmt->fetch()) {
                $tags[] = TagFactory::createFromData($data);
            }
            return $tags;
        } catch (PDOException $e) {
            throw new PDOException("Error finding all tags: " . $e->getMessage());
        }
    }

    public function save(object $entity): bool {
        if (!($entity instanceof Tag)) {
            throw new \InvalidArgumentException("Entity must be an instance of Tag");
        }
        
        try {
            if ($entity->id) {
                return $this->update($entity);
            }
            return $this->insert($entity);
        } catch (PDOException $e) {
            throw new PDOException("Error saving tag: " . $e->getMessage());
        }
    }

    private function insert(Tag $tag): bool {
        try {
            $stmt = $this->db->prepare("INSERT INTO tags (name) VALUES (?)");
            if (!$stmt) {
                throw new PDOException("Failed to prepare statement for insert");
            }

            $result = $stmt->execute([$tag->name]);

            if ($result) {
                $tag->id = (int)$this->db->lastInsertId();
            }

            return $result;
        } catch (PDOException $e) {
            throw new PDOException("Error inserting tag: " . $e->getMessage());
        }
    }

    private function update(Tag $tag): bool {
        try {
            $stmt = $this->db->prepare("UPDATE tags SET name = ? WHERE id = ?");
            if (!$stmt) {
                throw new PDOException("Failed to prepare statement for update");
            }

            return $stmt->execute([
                $tag->name,
                $tag->id
            ]);
        } catch (PDOException $e) {
            throw new PDOException("Error updating tag: " . $e->getMessage());
        }
    }

    public function delete(int $id): bool {
        try {
            $stmt = $this->db->prepare("DELETE FROM tags WHERE id = ?");
            if (!$stmt) {
                throw new PDOException("Failed to prepare statement for delete");
            }

            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            throw new PDOException("Error deleting tag: " . $e->getMessage());
        }
    }

    public function getByArticle(int $articleId): array {
        try {
            $stmt = $this->db->prepare("
                SELECT t.* FROM tags t
                INNER JOIN article_tags at ON t.id = at.tag_id
                WHERE at.article_id = ?
                ORDER BY t.name
            ");
            if (!$stmt) {
                throw new PDOException("Failed to prepare statement for getTags");
            }

            $stmt->execute([$articleId]);
            $tags = [];
            while ($data = $stmt->fetch()) {
                $tags[] = TagFactory::createFromData($data);
            }
            return $tags;
        } catch (PDOException $e) {
            throw new PDOException("Error getting article tags: " . $e->getMessage());
        }
    }
} 