<?php

namespace App\Factories;

use App\Models\Category;

class CategoryFactory {
    public static function createFromData(array $data): Category {
        return new Category([
            'id' => $data['id'],
            'name' => $data['name'],
            'parent_id' => $data['parent_id'],
            'created_at' => $data['created_at'],
            'updated_at' => $data['updated_at']
        ]);
    }
} 