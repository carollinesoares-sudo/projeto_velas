<?php

$host = 'localhost';          // Onde o banco está rodando (neste caso, na própria máquina atual)
$port = '5432';               // A porta padrão que o PostgreSQL usa para conversar
$dbname = 'projeto_velas';    // O nome específico do banco de dados do projeto
$user = 'postgres';           // O usuário administrador padrão do Postgres
$password = 'POSTGRES';       // A senha para validar o acesso desse usuário

try {
    //  Tenta estabelecer a conexão usando a extensão PDO (PHP Data Objects)
    // O PDO é ótimo porque torna o código mais seguro contra ataques e facilita se um dia você mudar de banco.
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $password);
    
    //  
    // ele joga um erro na tela (Exceção) em vez de falhar em silêncio. Isso poupa horas de testes.
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    //  Força o banco a conversar com o PHP usando criptografia UTF-8.
    // Isso evita que palavras com acentos ou cedilha (como "Baunilha" ou "Promoção") fiquem cheias de símbolos estranhos.
    $pdo->exec("SET client_encoding TO 'UTF8'");

} catch (PDOException $e) {
    //  Se a senha estiver errada, o banco estiver desligado ou o nome mudar,
    // o código para de rodar imediatamente (die) e mostra qual foi o problema técnico.
    die("Erro ao conectar ao banco de dados: " . $e->getMessage());
}