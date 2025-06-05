<?php

namespace App\Controllers;

use App\Services\ArticleService;

class ArticleController extends Controller {
    private ArticleService $articleService;

    public function __construct() {
        $this->articleService = new ArticleService();
    }

    public function index() {
        $articles = $this->articleService->getAllArticles();
        $relations = $this->getIncludeRelations();
        
        foreach ($articles as $article) {
            $this->loadRelations($article, $relations, $this->articleService);
        }
        
        return $this->success('Articles retrieved successfully', array_map(fn($article) => $article->toArray(), $articles));
    }

    public function show(int $id) {
        $article = $this->articleService->getArticle($id);
        if (!$article) {
            return $this->notFound('Article not found');
        }

        $relations = $this->getIncludeRelations();
        $this->loadRelations($article, $relations, $this->articleService);
        
        return $this->success('Article retrieved successfully', $article->toArray());
    }

    public function store() {
        $data = $this->getRequestData();
        
        if (!$this->validateRequired($data, ['title', 'body', 'category_id'])) {
            return;
        }

        if (isset($data['tag_ids']) && !is_array($data['tag_ids'])) {
            return $this->error('tag_ids must be an array', 400);
        }

        try {
            $article = $this->articleService->createArticle(
                $data['title'],
                $data['body'],
                (int)$data['category_id']
            );

            if (!$article) {
                return $this->serverError('Failed to create article');
            }
            
            if (isset($data['tag_ids'])) {
                $this->articleService->updateArticleTags($article->id, $data['tag_ids']);
            }

            return $this->success('Article created successfully', $article->toArray(), 201);
        } catch (\Exception $e) {
            return $this->serverError($e->getMessage());
        }
    }

    public function update(int $id) {
        $data = $this->getRequestData();
        
        if (!$this->validateRequired($data, ['title', 'body', 'category_id'])) {
            return;
        }

        if (isset($data['tag_ids']) && !is_array($data['tag_ids'])) {
            return $this->error('tag_ids must be an array', 400);
        }

        try {
            $article = $this->articleService->updateArticle(
                $id,
                $data['title'],
                $data['body'],
                (int)$data['category_id']
            );

            if (!$article) {
                return $this->notFound('Article not found');
            }

            if (isset($data['tag_ids'])) {
                $this->articleService->updateArticleTags($id, $data['tag_ids']);
            }

            return $this->success('Article updated successfully', $article->toArray());
        } catch (\Exception $e) {
            return $this->serverError($e->getMessage());
        }
    }

    public function delete(int $id) {
        try {
            $success = $this->articleService->deleteArticle($id);
            if (!$success) {
                return $this->notFound('Article not found');
            }
            return $this->success('Article deleted successfully');
        } catch (\Exception $e) {
            return $this->serverError($e->getMessage());
        }
    }
} 