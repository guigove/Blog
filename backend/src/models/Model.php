<?php

namespace App\Models;

abstract class Model {
    protected $attributes = [];
    protected $fillable = [];

    public function __construct(array $attributes = []) {
        $this->fill($attributes);
    }

    public function fill(array $attributes) {
        foreach ($attributes as $key => $value) {
            if (in_array($key, $this->fillable)) {
                $this->attributes[$key] = $value;
            }
        }
        return $this;
    }

    public function __get($key) {
        return $this->getAttribute($key);
    }

    public function __set($key, $value) {
        $this->setAttribute($key, $value);
    }

    public function __isset($key) {
        return isset($this->attributes[$key]);
    }

    public function getAttribute($key) {
        return $this->attributes[$key] ?? null;
    }

    public function setAttribute($key, $value) {
        if (in_array($key, $this->fillable)) {
            $this->attributes[$key] = $value;
        }
        return $this;
    }

    public function toArray() {
        return $this->attributes;
    }
} 