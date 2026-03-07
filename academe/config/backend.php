<?php

return [
    'mode' => env('APP_BACKEND', 'microservices'),
    'services' => [
        'student'    => env('STUDENT_SERVICE_URL', 'http://localhost:8001'),
        'course'     => env('COURSE_SERVICE_URL', 'http://localhost:8002'),
        'enrollment' => env('ENROLLMENT_SERVICE_URL', 'http://localhost:8003'),
    ],
];
