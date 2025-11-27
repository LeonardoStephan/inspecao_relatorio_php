<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); echo "Método não permitido"; exit; }

$chave = $_POST['chave'] ?? null;
$valor = $_POST['valor'] ?? null;
if ($chave === null) { http_response_code(400); echo "Chave ausente"; exit; }

$_SESSION[$chave] = $valor;
echo "OK";
