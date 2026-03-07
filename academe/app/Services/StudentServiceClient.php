<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class StudentServiceClient
{
    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('backend.services.student');
    }

    public function all(): Collection
    {
        try {
            $response = Http::timeout(5)->get("{$this->baseUrl}/api/students");

            if ($response->successful()) {
                return collect($response->json('data'))->map(fn ($item) => (object) $item);
            }

            return collect();
        } catch (\Exception $e) {
            throw new \RuntimeException('Could not connect to student service');
        }
    }

    public function find($id): ?object
    {
        try {
            $response = Http::timeout(5)->get("{$this->baseUrl}/api/students/{$id}");

            if ($response->status() === 404) {
                return null;
            }

            if ($response->successful()) {
                return (object) $response->json('data');
            }

            return null;
        } catch (\Exception $e) {
            throw new \RuntimeException('Could not connect to student service');
        }
    }

    public function create(array $data): object
    {
        try {
            $response = Http::timeout(5)->post("{$this->baseUrl}/api/students", $data);

            if ($response->successful()) {
                return (object) $response->json('data');
            }

            throw new \RuntimeException($response->json('message') ?? 'Failed to create student');
        } catch (\RuntimeException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new \RuntimeException('Could not connect to student service');
        }
    }

    public function update($id, array $data): ?object
    {
        try {
            $response = Http::timeout(5)->put("{$this->baseUrl}/api/students/{$id}", $data);

            if ($response->status() === 404) {
                return null;
            }

            if ($response->successful()) {
                return (object) $response->json('data');
            }

            throw new \RuntimeException($response->json('message') ?? 'Failed to update student');
        } catch (\RuntimeException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new \RuntimeException('Could not connect to student service');
        }
    }

    public function delete($id): bool
    {
        try {
            $response = Http::timeout(5)->delete("{$this->baseUrl}/api/students/{$id}");

            if ($response->status() === 404) {
                return false;
            }

            return $response->successful();
        } catch (\Exception $e) {
            throw new \RuntimeException('Could not connect to student service');
        }
    }
}
