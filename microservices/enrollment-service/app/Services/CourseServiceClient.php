<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class CourseServiceClient
{
    private string $baseUrl = 'http://localhost:8002/api';

    public function find($id): ?array
    {
        $response = Http::timeout(5)->get("{$this->baseUrl}/courses/{$id}");

        if ($response->status() === 404) {
            return null;
        }

        if ($response->successful()) {
            return $response->json('data');
        }

        return null;
    }
}
