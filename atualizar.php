<?php

error_reporting(E_ALL); // Ativa a exibição de todos os erros   
ini_set('display_errors', 1);// Garante que os erros sejam mostrados no navegador

require_once 'conexao.php';// Inclui o arquivo de conexão com o banco de dados

if ($_SERVER['REQUEST_METHOD'] === 'POST') {// Verifica se o formulário foi enviado via POST
    
    
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;// Obtém o ID do produto a ser atualizado, garantindo que seja um inteiro
    $nome = $_POST['nome'] ?? '';// Obtém o nome da vela, ou define como string vazia se não for fornecido
    $aroma = $_POST['aroma'] ?? '';// Obtém o aroma da vela, ou define como string vazia se não for fornecido
    $tamanho = $_POST['tamanho'] ?? '';// Obtém o tamanho da vela, ou define como string vazia se não for fornecido
    $cor = $_POST['cor'] ?? '';// Obtém a cor da vela, ou define como string vazia se não for fornecido
    $preco = isset($_POST['preco']) ? floatval($_POST['preco']) : 0.0;// Obtém o preço da vela, ou define como 0.0 se não for fornecido
    $estoque = isset($_POST['estoque']) ? intval($_POST['estoque']) : 0;// Obtém a quantidade em estoque, ou define como 0 se não for fornecido
    $descricao = $_POST['descricao'] ?? '';// Obtém a descrição da vela, ou define como string vazia se não for fornecida

    
    if ($preco <= 0) {// Valida se o preço é maior que zero
        die("Erro: O preço da vela deve ser maior que R$ 0,00.");
    }
    if ($estoque < 0) {
        die("Erro: A quantidade em estoque não pode ser negativa.");// Valida se a quantidade em estoque é zero ou positiva
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

        header("Location: dashboard.php?sucesso=editado");// Redireciona de volta para o painel de controle com um parâmetro de sucesso
        exit;

    } catch (PDOException $e) {
        die("Erro ao atualizar dados no banco: " . $e->getMessage());// Exibe uma mensagem de erro detalhada em caso de falha na atualização
    }
} else {
    header("Location: dashboard.php");// Redireciona para o painel de controle se o acesso não for via POST
    exit;
}
?>