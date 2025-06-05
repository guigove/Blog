<?php

namespace App\Models;


class Tag extends Model {
    protected $fillable = [
        'id',
        'name',
        'created_at',
        'updated_at'
    ];

    private ?array $articles = null;

    public function __construct(array $attributes = []) {
        parent::__construct($attributes);
    }

    public function getArticles(): array {
        return $this->articles ?? [];
    }

    public function setArticles(array $articles): void {
        $this->articles = $articles;
    }

    public function toArray(): array {
        $data = parent::toArray();
        
        if ($this->articles !== null) {
            $data['articles'] = array_map(fn($article) => $article->toArray(), $this->articles);
        }

        return $data;
    }
} 