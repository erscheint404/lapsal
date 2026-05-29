<?php

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| The first thing we will do is create a new Laravel application instance
| which serves as the "glue" for all the components of Laravel, and is
| the IoC container for the system binding all of the various parts.
|
*/

$app = new Illuminate\Foundation\Application(
    $_ENV['APP_BASE_PATH'] ?? dirname(__DIR__)
);

/*
|--------------------------------------------------------------------------
| Vercel Serverless Path Overrides
|--------------------------------------------------------------------------
*/
$isVercel = isset($_SERVER['VERCEL_URL']) || isset($_ENV['VERCEL_URL']) || getenv('VERCEL_URL') !== false;

if ($isVercel) {
    // 1. Arahkan direktori storage utama ke /tmp
    $app->useStoragePath('/tmp/storage');
    
    // 2. Arahkan semua kompilasi bootstrap cache ke /tmp agar tidak menulis ke root yang read-only
    $tmpCaches = [
        'APP_CONFIG_CACHE' => '/tmp/config.php',
        'APP_SERVICES_CACHE' => '/tmp/services.php',
        'APP_PACKAGES_CACHE' => '/tmp/packages.php',
        'APP_ROUTES_CACHE' => '/tmp/routes.php',
        'APP_EVENTS_CACHE' => '/tmp/events.php',
        'VIEW_COMPILED_PATH' => '/tmp/storage/framework/views',
        'LOG_CHANNEL' => 'stderr',
        'CACHE_DRIVER' => 'file',
        'SESSION_DRIVER' => 'cookie',
        'QUEUE_CONNECTION' => 'sync',
    ];

    foreach ($tmpCaches as $key => $path) {
        $_ENV[$key] = $path;
        $_SERVER[$key] = $path;
        putenv("{$key}={$path}");
    }
    
    // 3. Pastikan folder-folder penting untuk Laravel ada di /tmp
    $dirs = [
        '/tmp/storage/framework/views',
        '/tmp/storage/framework/cache',
        '/tmp/storage/framework/cache/data',
        '/tmp/storage/framework/sessions',
        '/tmp/storage/logs',
    ];
    foreach ($dirs as $dir) {
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
    }
}

/*
|--------------------------------------------------------------------------
| Bind Important Interfaces
|--------------------------------------------------------------------------
|
| Next, we need to bind some important interfaces into the container so
| we will be able to resolve them when needed. The kernels serve the
| incoming requests to this application from both the web and CLI.
|
*/

$app->singleton(
    Illuminate\Contracts\Http\Kernel::class,
    App\Http\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

/*
|--------------------------------------------------------------------------
| Return The Application
|--------------------------------------------------------------------------
|
| This script returns the application instance. The instance is given to
| the calling script so we can separate the building of the instances
| from the actual running of the application and sending responses.
|
*/

return $app;
