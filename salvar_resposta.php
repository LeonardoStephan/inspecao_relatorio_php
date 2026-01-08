<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit;
}

$chave = $_POST['chave'] ?? null;
$valor = $_POST['valor'] ?? null;

if (!$chave) exit;

if ($chave === "op") {
    $ano = date("Y");
    $_SESSION['op'] = $ano . "/" . preg_replace('/\D/', '', $valor);
} else {
    $_SESSION[$chave] = $valor;
}

echo "OK";
