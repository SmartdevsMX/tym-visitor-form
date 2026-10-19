<?php
// Server-side proxy for the magic-link login request, so the n8n endpoint
// is never visible in the client's network/inspector tab.
$target = 'https://wa.smartdevs.com.mx/webhook/crece-auth-request';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Content-Type: application/json');
    echo json_encode(['ok' => false, 'error' => 'method_not_allowed']);
    exit;
}

$body = file_get_contents('php://input');

$ch = curl_init($target);
curl_setopt_array($ch, [
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $body,
    CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 15,
]);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

header('Content-Type: application/json');

if ($response === false) {
    http_response_code(502);
    echo json_encode(['ok' => false, 'error' => 'upstream_unreachable']);
    exit;
}

// Always respond generically so the client can't distinguish a registered
// email from an unregistered one just from timing/response shape.
http_response_code(200);
echo json_encode(['ok' => true]);
