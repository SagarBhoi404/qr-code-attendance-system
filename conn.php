<?php

// Function to parse .env file and return an associative array
function parseEnv($filePath)
{
    $contents = file_get_contents($filePath);
    $lines = explode("\n", $contents);
    $env = [];

    foreach ($lines as $line) {
        $line = trim($line);

        // Skip comments and empty lines
        if ($line === '' || strpos($line, '#') === 0) {
            continue;
        }

        list($key, $value) = explode('=', $line, 2);
        $env[$key] = $value;
    }

    return $env;
}

// Load .env file
$envFilePath = __DIR__ . '/.env';
if (!file_exists($envFilePath)) {
    die('.env file not found');
}
$env = parseEnv($envFilePath);

// Example usage
// define('DB_HOST', $env['DB_HOST'] ?? null);
// define('DB_USER', $env['DB_USER'] ?? null);
// define('DB_PASS', $env['DB_PASS'] ?? null);
// define('DB_NAME', $env['DB_PASS'] ?? null);

// Or you can access values directly from the $env array
$conn = mysqli_connect($env['DB_HOST'], $env['DB_USER'], $env['DB_PASS'], $env['DB_NAME']);


// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

