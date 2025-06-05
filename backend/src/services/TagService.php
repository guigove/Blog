<?php

namespace App\Services;

use App\Models\Tag;
use App\Repositories\TagRepository;
use App\Repositories\ArticleRepository;

class TagService {
    private TagRepository $tagRepository;
    private ArticleRepository $articleRepository;

    public function __construct() {
        $this->tagRepository = new TagRepository();
        $this->articleRepository = new ArticleRepository();
    }

    public function getTag(int $id): ?Tag {
        return $this->tagRepository->findById($id);
    }

    public function getAllTags(): array {
        return $this->tagRepository->findAll();
    }

    public function createTag(string $name): ?Tag {
        $tag = new Tag([
            'id' => 0,
            'name' => $name
        ]);

        if (!$this->tagRepository->save($tag)) {
            return null;
        }

        return $this->tagRepository->findById($tag->id);
    }

    public function updateTag(int $id, string $name): ?Tag {
        $tag = $this->tagRepository->findById($id);
        if (!$tag) {
            return null;
        }

        $tag->name = $name;

        if (!$this->tagRepository->save($tag)) {
            return null;
        }

        return $this->tagRepository->findById($tag->id);
    }

    public function deleteTag(int $id): bool {
        return $this->tagRepository->delete($id);
    }

    public function getByArticle(int $articleId): array {
        return $this->tagRepository->getByArticle($articleId);
    }

    public function loadArticles(Tag $tag): void {
        $articles = $this->articleRepository->getByTag($tag->id);
        $tag->setArticles($articles);
    }
} 