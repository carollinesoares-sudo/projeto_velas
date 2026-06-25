<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $nome = $_POST['nome'] ?? '';
    $aroma = $_POST['aroma'] ?? '';
    $tamanho = $_POST['tamanho'] ?? '';
    $cor = $_POST['cor'] ?? '';
    $preco = isset($_POST['preco']) ? floatval($_POST['preco']) : 0.0;
    $estoque = isset($_POST['estoque']) ? intval($_POST['estoque']) : 0;
    $descricao = $_POST['descricao'] ?? '';

    // valida se o preço é maior que zero
    if ($preco <= 0) {
        die("Erro: O preço da vela deve ser maior que R$ 0,00.");
    }
    if ($estoque < 0) {
        die("Erro: A quantidade em estoque não pode ser negativa.");
    }

    try {
        $sql = "UPDATE public.velas SET 
                nome = :nome, 
                aroma = :aroma, 
                tamanho = :tamanho, 
                cor = :cor, 
                preco = :preco, 
                estoque = :estoque, 
                descricao = :descricao 
                WHERE id = :id";
                
        $stmt = $pdo->prepare($sql);
        
        $stmt->execute([
            ':nome' => $nome,
            ':aroma' => $aroma,
            ':tamanho' => $tamanho,
            ':cor' => !empty($cor) ? $cor : null,
            ':preco' => $preco,
            ':estoque' => $estoque,
            ':descricao' => $descricao,
            ':id' => $id
        ]);

        // volta pro dashboard se conseguiu
        header("Location: dashboard.php?sucesso=editado");
        exit;

    } catch (PDOException $e) {
        die("Erro ao atualizar dados no banco: " . $e->getMessage());
    }
} else {
    // acesso não foi via POST, volta pra home
    header("Location: dashboard.php");
    exit;
}
?>