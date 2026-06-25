<?php

$host = 'localhost';          // localhost
$port = '5432';               // porta padrão postgres
$dbname = 'projeto_velas';    // nome do banco
$user = 'postgres';           // user
$password = 'POSTGRES';

try {
    // conecta no banco
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $password);
    
    // mostra erro se algo der ruim
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // utf8 pra aceitar acentuação
    $pdo->exec("SET client_encoding TO 'UTF8'");

} catch (PDOException $e) {
    die("Erro ao conectar ao banco de dados: " . $e->getMessage());
}