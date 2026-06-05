<?php

require_once __DIR__ . '/../App/config.php';

$hash = password_hash('admin123', PASSWORD_DEFAULT);

$stmt = $pdo->prepare(
    "UPDATE users SET password = ? WHERE email = ?"
);

$stmt->execute([
    $hash,
    'admin@test.com'
]);

echo "OK";