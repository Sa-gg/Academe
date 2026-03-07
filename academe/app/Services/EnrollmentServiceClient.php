<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class EnrollmentServiceClient
{
    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('backend.services.enrollment');
    }

    public function all(): Collection
    {
        try {
            $response = Http::timeout(5)->get("{$this->baseUrl}/api/enrollments");

            if ($response->successful()) {
                return collect($response->json('data'))->map(function ($item) {
                    $obj = (object) $item;
                    if (isset($item['student'])) {
                        $obj->student = (object) $item['student'];
                    }
                    if (isset($item['course'])) {
                        $obj->course = (object) $item['course'];
                    }
                    if (isset($item['enrolled_at'])) {
                        $obj->created_at = new \Carbon\Carbon($item['enrolled_at']);
                    }
                    return $obj;
                });
            }

            return collect();
        } catch (\Exception $e) {
            throw new \RuntimeException('Could not connect to enrollment service');
        }
    }

    public function find($id): ?object
    {
        try {
            $response = Http::timeout(5)->get("{$this->baseUrl}/api/enrollments/{$id}");

            if ($response->status() === 404) {
                return null;
            }

            if ($response->successful()) {
                $data = $response->json('data');
                $obj = (object) $data;
                if (isset($data['student'])) {
                    $obj->student = (object) $data['student'];
                }
                if (isset($data['course'])) {
                    $obj->course = (object) $data['course'];
                }
                if (isset($data['enrolled_at'])) {
                    $obj->created_at = new \Carbon\Carbon($data['enrolled_at']);
                }
                return $obj;
            }

            return null;
        } catch (\Exception $e) {
            throw new \RuntimeException('Could not connect to enrollment service');
        }
    }

    public function create(array $data): object
    {
        try {
            $response = Http::timeout(5)->post("{$this->baseUrl}/api/enrollments", $data);

            if ($response->successful()) {
                return (object) $response->json('data');
            }

            throw new \RuntimeException($response->json('message') ?? 'Failed to create enrollment');
        } catch (\RuntimeException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new \RuntimeException('Could not connect to enrollment service');
        }
    }

    public function delete($id): bool
    {
        try {
            $response = Http::timeout(5)->delete("{$this->baseUrl}/api/enrollments/{$id}");

            if ($response->status() === 404) {
                return false;
            }

            return $response->successful();
        } catch (\Exception $e) {
            throw new \RuntimeException('Could not connect to enrollment service');
        }
    }

    public function byStudent($studentId): Collection
    {
        try {
            $response = Http::timeout(5)->get("{$this->baseUrl}/api/enrollments/student/{$studentId}");

            if ($response->successful()) {
                return collect($response->json('data'))->map(function ($item) {
                    $obj = (object) $item;
                    if (isset($item['student'])) {
                        $obj->student = (object) $item['student'];
                    }
                    if (isset($item['course'])) {
                        $obj->course = (object) $item['course'];
                    }
                    if (isset($item['enrolled_at'])) {
                        $obj->created_at = new \Carbon\Carbon($item['enrolled_at']);
                    }
                    return $obj;
                });
            }

            return collect();
        } catch (\Exception $e) {
            throw new \RuntimeException('Could not connect to enrollment service');
        }
    }
}
