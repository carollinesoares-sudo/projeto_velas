<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: produtos.php");
    exit;
}

require_once 'conexao.php';

try {
   
    $nome      = trim($_POST['nome'] ?? '');
    $aroma     = trim($_POST['aroma'] ?? '');
    $tamanho   = trim($_POST['tamanho'] ?? '');
    $cor       = trim($_POST['cor'] ?? '');
    $estoque   = (int)($_POST['estoque'] ?? 0);
    $descricao = trim($_POST['descricao'] ?? '');

    
    $preco_cru = $_POST['preco'] ?? '0';
    $preco_limpo = str_replace(['R$', ' ', '.'], '', $preco_cru); // Remove R$, espaços e pontos de milhar
    $preco_formatado = str_replace(',', '.', $preco_limpo);        // Converte a vírgula decimal em ponto
    $preco = (float)$preco_formatado;

    
    if (empty($nome) || $preco <= 0) {
        throw new Exception("Dados obrigatórios do produto não foram preenchidos corretamente.");
    }

    
    $sql = "INSERT INTO public.velas
            (nome, aroma, tamanho, cor, preco, estoque, descricao)
            VALUES
            (:nome, :aroma, :tamanho, :cor, :preco, :estoque, :descricao)";

    $stmt = $pdo->prepare($sql);

   
    $stmt->execute([
        ':nome'      => $nome,
        ':aroma'     => $aroma,
        ':tamanho'   => $tamanho,
        ':cor'       => $cor,
        ':preco'     => $preco,
        ':estoque'   => $estoque,
        ':descricao' => $descricao
    ]);

    
    header("Location: produtos.php?sucesso=1");
    exit;

} catch (Exception $e) {
    
    error_log("Erro ao salvar vela: " . $e->getMessage());
    echo "<script>
            alert('Não foi possível salvar o produto. Verifique os dados e tente novamente.');
            window.location.href = 'produtos.php';
          </script>";
    exit;
}
?>