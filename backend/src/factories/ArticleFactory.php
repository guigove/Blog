<?php

namespace App\Factories;

use App\Models\Article;

class ArticleFactory {
    public static function createFromData(array $data): Article {
        return new Article([
            'id' => $data['id'],
            'title' => $data['title'],
            'body' => $data['body'],
            'category_id' => $data['category_id'],
            'created_at' => $data['created_at'],
            'updated_at' => $data['updated_at']
        ]);
    }
} 