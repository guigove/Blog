<?php

namespace App\Factories;

use App\Models\Tag;

class TagFactory {
    public static function createFromData(array $data): Tag {
        return new Tag([
            'id' => $data['id'],
            'name' => $data['name'],
            'created_at' => $data['created_at'],
            'updated_at' => $data['updated_at']
        ]);
    }
} 