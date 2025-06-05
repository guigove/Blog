<?php

namespace App\Models;

class Article extends Model {
    protected $fillable = [
        'id',
        'title',
        'body',
        'category_id',
        'created_at',
        'updated_at'
    ];

    private ?Category $category = null;
    private ?array $tags = null;

    public function __construct(array $attributes = []) {
        parent::__construct($attributes);
    }

    public function getCategory(): ?Category {
        return $this->category;
    }

    public function getTags(): array {
        return $this->tags ?? [];
    }

    public function setCategory(?Category $category): void {
        $this->category = $category;
    }

    public function setTags(array $tags): void {
        $this->tags = $tags;
    }

    public function toArray(): array {
        $data = parent::toArray();
        
        if ($this->category !== null) {
            $data['category'] = $this->category->toArray();
        }
        
        if ($this->tags !== null) {
            $data['tags'] = array_map(fn($tag) => $tag->toArray(), $this->tags);
        }
        
        return $data;
    }
} 