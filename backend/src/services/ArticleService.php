<?php

namespace App\Services;

use App\Models\Article;
use App\Repositories\ArticleRepository;
use App\Services\CategoryService;
use App\Services\TagService;

class ArticleService {
    private ArticleRepository $articleRepo;
    private CategoryService $categoryService;
    private TagService $tagService;

    public function __construct() {
        $this->articleRepo = new ArticleRepository();
        $this->categoryService = new CategoryService();
        $this->tagService = new TagService();
    }

    public function getArticle(int $id): ?Article {
        return $this->articleRepo->findById($id);
    }

    public function getAllArticles(): array {
        return $this->articleRepo->findAll();
    }

    public function createArticle(
        string $title,
        string $body,
        int $category_id
    ): ?Article {
        if (!$this->categoryService->getCategory($category_id)) {
            throw new \InvalidArgumentException("Category not found");
        }

        $article = new Article([
            'id' => 0,
            'title' => $title,
            'body' => $body,
            'category_id' => $category_id
        ]);

        if (!$this->articleRepo->save($article)) {
            return null;
        }

        return $this->articleRepo->findById($article->id);
    }

    public function updateArticle(
        int $id,
        string $title,
        string $body,
        int $category_id
    ): ?Article {
        if (!$this->categoryService->getCategory($category_id)) {
            throw new \InvalidArgumentException("Category not found");
        }

        $article = $this->articleRepo->findById($id);
        if (!$article) {
            return null;
        }

        $article->title = $title;
        $article->body = $body;
        $article->category_id = $category_id;

        if (!$this->articleRepo->save($article)) {
            return null;
        }

        return $this->articleRepo->findById($article->id);
    }

    public function deleteArticle(int $id): bool {
        return $this->articleRepo->delete($id);
    }

    public function updateArticleTags(int $articleId, array $tagIds): bool {
        if (!$this->articleRepo->findById($articleId)) {
            throw new \InvalidArgumentException("Article not found");
        }

        foreach ($tagIds as $tagId) {
            if (!$this->tagService->getTag($tagId)) {
                throw new \InvalidArgumentException("Tag with ID {$tagId} not found");
            }
        }

        return $this->articleRepo->updateArticleTags($articleId, $tagIds);
    }

    public function loadTags(Article $article): void {
        $tags = $this->tagService->getByArticle($article->id);
        $article->setTags($tags);
    }

    public function loadCategory(Article $article): void {
        $category = $this->categoryService->getCategory($article->category_id);
        $article->setCategory($category);
    }
} 