<?php

namespace App\Models;


class Category extends Model {
    protected $fillable = [
        'id',
        'name',
        'parent_id',
        'created_at',
        'updated_at'
    ];

    private ?array $children = null;
    private ?array $articles = null;

    public function __construct(array $attributes = []) {
        parent::__construct($attributes);
    }

    public function getChildren(): array {
        return $this->children ?? [];
    }

    public function getArticles(): array {
        return $this->articles ?? [];
    }

    public function setChildren(array $children): void {
        $this->children = $children;
    }

    public function setArticles(array $articles): void {
        $this->articles = $articles;
    }

    public function toArray(): array {
        $data = parent::toArray();
        
        if ($this->children !== null) {
            $data['children'] = array_map(fn($child) => $child->toArray(), $this->children);
        }
        
        if ($this->articles !== null) {
            $data['articles'] = array_map(fn($article) => $article->toArray(), $this->articles);
        }
        
        return $data;
    }
} 