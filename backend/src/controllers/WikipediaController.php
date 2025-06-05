<?php

namespace App\Controllers;

use App\Services\WikipediaService;

class WikipediaController extends Controller {
    private WikipediaService $wikipediaService;

    public function __construct() {
        $this->wikipediaService = new WikipediaService();
    }

    public function getSummary(string $title) {
        try {
            $summary = $this->wikipediaService->getSummary($title);
            
            return $this->success('Wikipedia summary retrieved successfully', $summary);
        } catch (\Exception $e) {
            return $this->serverError($e->getMessage());
        }
    }

    public function deleteSummary(string $title) {
        try {
            $this->wikipediaService->clearSummary($title);
            return $this->success("Wikipedia {$title} summary deleted successfully");
        } catch (\Exception $e) {
            return $this->serverError($e->getMessage());
        }
    }
} 