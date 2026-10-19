<?php
// Server-side proxy for the Crece dashboard data feed, so the n8n endpoint
// is never visible in the client's network/inspector tab.
require_once __DIR__ . '/crece_auth_config.php';
$crece_session = crece_require_session_or_401();

$target = 'https://wa.smartdevs.com.mx/webhook/crece-dashboard-data';

// A líder only sees their own Casa's data — filtered server-side so it's
// never reachable via the network tab, not just hidden in the UI.
function crece_filter_for_lider($json, $casa) {
    $data = json_decode($json, true);
    if (!is_array($data)) return $json;
    $data['casas'] = array_values(array_filter($data['casas'] ?? [], function ($c) use ($casa) {
        return ($c['casa'] ?? null) === $casa;
    }));
    $data['insights'] = array_values(array_filter($data['insights'] ?? [], function ($i) use ($casa) {
        return ($i['casa'] ?? null) === $casa;
    }));
    $data['visitasPersonas'] = array_values(array_filter($data['visitasPersonas'] ?? [], function ($v) use ($casa) {
        return ($v['casa'] ?? null) === $casa;
    }));
    // Not casa-attributable in the current data model — drop for a líder view.
    unset($data['visitantes'], $data['mensajes'], $data['asistencia']);
    return json_encode($data);
}

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    header('Content-Type: application/json');
    echo json_encode(['ok' => false, 'error' => 'method_not_allowed']);
    exit;
}

// Set up cache file path and TTL
$cacheDir = __DIR__ . '/cache';
$cacheFile = $cacheDir . '/dashboard-data.json';
$cacheTTL = 600; // 10 minutes

// Ensure cache directory exists
@mkdir($cacheDir, 0755, true);

// Check if valid cache exists
if (file_exists($cacheFile) && time() - filemtime($cacheFile) < $cacheTTL) {
    header('Content-Type: application/json');
    header('X-Cache: HIT');
    http_response_code(200);
    $body = file_get_contents($cacheFile);
    if ($crece_session['role'] === 'lider' && !empty($crece_session['casa'])) {
        $body = crece_filter_for_lider($body, $crece_session['casa']);
    }
    echo $body;
    exit;
}

// Fetch fresh data from upstream
$ch = curl_init($target);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 20,
]);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

header('Content-Type: application/json');

// Handle successful upstream response
if ($response !== false && $httpCode === 200) {
    // Write the FULL (unfiltered) response to cache — filtering happens per-request below
    file_put_contents($cacheFile, $response, LOCK_EX);
    header('X-Cache: MISS');
    http_response_code(200);
    $body = $response;
    if ($crece_session['role'] === 'lider' && !empty($crece_session['casa'])) {
        $body = crece_filter_for_lider($body, $crece_session['casa']);
    }
    echo $body;
    exit;
}

// Upstream failed: try to serve stale cache
if (file_exists($cacheFile)) {
    header('X-Cache: STALE');
    http_response_code(200);
    $body = file_get_contents($cacheFile);
    if ($crece_session['role'] === 'lider' && !empty($crece_session['casa'])) {
        $body = crece_filter_for_lider($body, $crece_session['casa']);
    }
    echo $body;
    exit;
}

// No cache at all: return error
http_response_code(502);
header('X-Cache: ERROR');
echo json_encode(['ok' => false, 'error' => 'upstream_unreachable']);
