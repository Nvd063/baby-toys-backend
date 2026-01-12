<?php

// config/cors.php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'], // Saare methods (GET, POST, etc.) allow karein

    'allowed_origins' => ['http://localhost:5173', 'http://127.0.0.1:5173'], // Apne frontend ka URL yahan likhein. Dev ke liye ['*'] bhi use kar sakte hain.

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'], // Saare headers allow karein

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false, // Agar cookies/auth use kar rahe hain to true karein
];