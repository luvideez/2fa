<?php
require_once 'GoogleAuthenticator.php';

if (isset($_GET['key'])) {
    $key = trim($_GET['key']);
    $ga = new PHPGangsta_GoogleAuthenticator();
    $code = $ga->getCode($key);
    header('Content-Type: application/json'); // Trả về JSON
    echo json_encode(['code' => $code]);
}
?>