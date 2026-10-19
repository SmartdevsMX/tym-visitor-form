<?php
// Actually consumes the one-time login token. Only reached via a real POST
// form submission from verificar.php's confirmation button — see that file
// for why the consuming call isn't done directly on the GET landing page.
require_once __DIR__ . '/crece_auth_config.php';

$token = isset($_POST['token']) ? $_POST['token'] : '';

function crece_show_error_page() {
    http_response_code(200);
    header('Content-Type: text/html; charset=utf-8');
    echo '<!doctype html><html lang="es"><head><meta charset="utf-8">'
        . '<meta name="viewport" content="width=device-width, initial-scale=1">'
        . '<title>Enlace no válido — Crece</title>'
        . '<style>body{font-family:sans-serif;background:#faf8f4;color:#323e45;'
        . 'display:flex;align-items:center;justify-content:center;min-height:100vh;margin:0;padding:24px;text-align:center;}'
        . '.box{max-width:420px} a{color:#C87F7F;font-weight:600}</style></head><body>'
        . '<div class="box"><h2>Este enlace ya no es válido</h2>'
        . '<p>Puede que haya expirado o que ya se haya usado. Pide un enlace nuevo para entrar.</p>'
        . '<p><a href="/login.html">Volver a pedir acceso</a></p></div></body></html>';
    exit;
}

if ($token === '') {
    crece_show_error_page();
}

$target = 'https://wa.smartdevs.com.mx/webhook/crece-auth-verify?action=consume&token=' . urlencode($token);

$ch = curl_init($target);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 20,
]);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($response === false || $httpCode !== 200) {
    crece_show_error_page();
}

$data = json_decode($response, true);
if (!is_array($data) || empty($data['ok']) || !empty($data['peek']) || empty($data['email'])) {
    crece_show_error_page();
}

crece_issue_session_cookie($data['email'], $data['role'], isset($data['casa']) ? $data['casa'] : null);

header('Location: /dashboard.php');
exit;
