<?php
// 1. Lightweight .env file loader for local or custom environments
$envPath = __DIR__ . '/../../.env';
if (file_exists($envPath)) {
    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#')) {
            continue;
        }
        if (strpos($line, '=') !== false) {
            [$key, $val] = explode('=', $line, 2);
            $key = trim($key);
            $val = trim($val, " \t\n\r\0\x0B\"'");
            if (!array_key_exists($key, $_SERVER) && !array_key_exists($key, $_ENV)) {
                putenv("$key=$val");
                $_ENV[$key] = $val;
                $_SERVER[$key] = $val;
            }
        }
    }
}

// 2. Parse database credentials from standard URL (DATABASE_URL / MYSQL_URL) or individual variables
$databaseUrl = getenv('DATABASE_URL') ?: (getenv('MYSQL_URL') ?: '');

if (!empty($databaseUrl)) {
    $parsedUrl = parse_url($databaseUrl);
    $host = $parsedUrl['host'] ?? 'localhost';
    $port = (int) ($parsedUrl['port'] ?? 3306);
    $username = isset($parsedUrl['user']) ? urldecode($parsedUrl['user']) : 'root';
    $password = isset($parsedUrl['pass']) ? urldecode($parsedUrl['pass']) : '';
    $dbname = isset($parsedUrl['path']) ? ltrim($parsedUrl['path'], '/') : 'coaching_management';
} else {
    $host = getenv('DB_HOST') ?: 'localhost';
    $port = (int) (getenv('DB_PORT') ?: 3306);
    $dbname = getenv('DB_NAME') ?: 'coaching_management';
    $username = getenv('DB_USER') ?: 'root';
    $password = getenv('DB_PASS') !== false ? getenv('DB_PASS') : (getenv('DB_PASSWORD') !== false ? getenv('DB_PASSWORD') : '');
}

// 3. SSL Configuration (required by cloud providers like TiDB Cloud, Aiven, Railway)
$sslMode = getenv('DB_SSL');
$useSsl = ($sslMode === 'true' || $sslMode === '1' || $sslMode === 'required');

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

if ($useSsl) {
    $sslCa = getenv('DB_SSL_CA');
    if (!empty($sslCa) && file_exists($sslCa)) {
        $options[PDO::MYSQL_ATTR_SSL_CA] = $sslCa;
    } elseif (file_exists('/etc/ssl/certs/ca-certificates.crt')) {
        // Standard Linux CA bundle (Debian/Ubuntu/Docker on Render)
        $options[PDO::MYSQL_ATTR_SSL_CA] = '/etc/ssl/certs/ca-certificates.crt';
    } else {
        $options[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = false;
    }
}

// 4. Establish PDO connection
try {
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
    $db = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    error_log("Database connection failed: " . $e->getMessage());

    $isLocal = in_array($host, ['localhost', '127.0.0.1'], true) || getenv('APP_ENV') === 'development';
    if ($isLocal) {
        die("Database connection failed: " . $e->getMessage());
    } else {
        die("Database service is currently unavailable. Please verify connection credentials or contact the administrator.");
    }
}
?>