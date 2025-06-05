<?php

namespace App\Controllers;

use App\Services\TagService;

class TagController extends Controller {
    private TagService $tagService;

    public function __construct() {
        $this->tagService = new TagService();
    }

    public function index() {
        $tags = $this->tagService->getAllTags();
        $relations = $this->getIncludeRelations();
        
        foreach ($tags as $tag) {
            $this->loadRelations($tag, $relations, $this->tagService);
        }
        
        return $this->success('Tags retrieved successfully', array_map(fn($tag) => $tag->toArray(), $tags));
    }

    public function show(int $id) {
        $tag = $this->tagService->getTag($id);
        if (!$tag) {
            return $this->notFound('Tag not found');
        }

        $relations = $this->getIncludeRelations();
        $this->loadRelations($tag, $relations, $this->tagService);
        
        return $this->success('Tag retrieved successfully', $tag->toArray());
    }

    public function store() {
        $data = $this->getRequestData();
        
        if (!$this->validateRequired($data, ['name'])) {
            return;
        }

        try {
            $tag = $this->tagService->createTag($data['name']);

            if (!$tag) {
                return $this->serverError('Failed to create tag');
            }

            return $this->success('Tag created successfully', $tag->toArray(), 201);
        } catch (\Exception $e) {
            return $this->serverError($e->getMessage());
        }
    }

    public function update(int $id) {
        $data = $this->getRequestData();
        
        if (!$this->validateRequired($data, ['name'])) {
            return;
        }

        try {
            $tag = $this->tagService->updateTag($id, $data['name']);

            if (!$tag) {
                return $this->notFound('Tag not found');
            }

            return $this->success('Tag updated successfully', $tag->toArray());
        } catch (\Exception $e) {
            return $this->serverError($e->getMessage());
        }
    }

    public function delete(int $id) {
        try {
            $success = $this->tagService->deleteTag($id);
            if (!$success) {
                return $this->notFound('Tag not found');
            }
            return $this->success('Tag deleted successfully');
        } catch (\Exception $e) {
            return $this->serverError($e->getMessage());
        }
    }
} 