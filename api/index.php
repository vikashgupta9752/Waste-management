<?php

// Vercel Serverless Function Entry Point
// Forwards all requests to Laravel's public/index.php

// Ensure the compiled views directory exists
if (!is_dir('/tmp/views')) {
    mkdir('/tmp/views', 0755, true);
}

require __DIR__ . '/../public/index.php';
