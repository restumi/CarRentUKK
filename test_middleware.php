<?php

require 'vendor/autoload.php';

use App\Http\Middleware\RoleMiddleware;

try {
    $middleware = new RoleMiddleware();
    echo "RoleMiddleware berhasil di-load\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
} 