<?php

require_once 'conexao.php';


if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);

    try {
        
        $sql = "DELETE FROM public.velas WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        
        
        $stmt->execute([':id' => $id]);

        
        header("Location: dashboard.php?sucesso=excluido");
        exit;

    } catch (PDOException $e) {
        die("Erro ao excluir o produto: " . $e->getMessage());
    }
} else {
    
    header("Location: dashboard.php");
    exit;
}
?>