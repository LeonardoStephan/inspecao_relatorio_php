<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo "Método não permitido";
    exit;
}

$chave = $_POST['chave'] ?? null;
$valor = $_POST['valor'] ?? null;

if ($chave === null) {
    http_response_code(400);
    echo "Chave ausente";
    exit;
}

// Salva direto na sessão
// Para evitar colisão com outras chaves, a sua app pode prefixar (ex: 'form_' . $chave), mas aqui mantenho simples
$_SESSION[$chave] = $valor;

echo "OK";
