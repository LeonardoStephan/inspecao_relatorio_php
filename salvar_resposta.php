<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit;
}

$chave = $_POST['chave'] ?? null;
$valor = $_POST['valor'] ?? null;

if ($chave === null) {
    exit;
}

$_SESSION[$chave] = $valor;

echo "OK";
