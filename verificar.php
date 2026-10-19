<?php
// GET-only landing page for the magic link. Deliberately does NOT consume the
// token here — email clients (Gmail/Workspace link-scanning) prefetch GET
// links to check for spam/safety before the user ever clicks, which would
// burn the single-use token before the real person sees it. The token is
// only actually consumed by confirmar.php, reached via a real POST form
// submission that scanners don't perform.
require_once __DIR__ . '/crece_auth_config.php';

$token = isset($_GET['token']) ? $_GET['token'] : '';

if ($token === '') {
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

http_response_code(200);
header('Content-Type: text/html; charset=utf-8');
$tokenEsc = htmlspecialchars($token, ENT_QUOTES, 'UTF-8');
echo '<!doctype html><html lang="es"><head><meta charset="utf-8">'
    . '<meta name="viewport" content="width=device-width, initial-scale=1">'
    . '<title>Confirmar acceso — Crece</title>'
    . '<style>body{font-family:sans-serif;background:#faf8f4;color:#323e45;'
    . 'display:flex;align-items:center;justify-content:center;min-height:100vh;margin:0;padding:24px;text-align:center;}'
    . '.box{max-width:420px}'
    . 'button{background:#C87F7F;color:#fff;border:none;padding:14px 28px;border-radius:8px;'
    . 'font-size:16px;font-weight:600;cursor:pointer;margin-top:12px}'
    . 'button:hover{opacity:0.9}</style></head><body>'
    . '<div class="box"><h2>Confirma tu acceso</h2>'
    . '<p>Por seguridad, tu correo revisa los enlaces automáticamente antes de que los abras — '
    . 'por eso necesitamos un clic más para confirmar que eres tú.</p>'
    . '<form method="POST" action="/confirmar.php">'
    . '<input type="hidden" name="token" value="' . $tokenEsc . '">'
    . '<button type="submit">Confirmar acceso</button>'
    . '</form></div></body></html>';
exit;
