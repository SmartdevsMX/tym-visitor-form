<?php
require_once __DIR__ . '/crece_auth_config.php';
crece_clear_session_cookie();
header('Location: /login.html');
exit;
