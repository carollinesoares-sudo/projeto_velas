<?php


// Ativa a exibição de erros para facilitar o debug em ambiente de desenvolvimento
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Garante que a sessão do usuário está ativa para que possamos manipular os itens salvos
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Inicializa a estrutura de dados do carrinho na sessão como um array vazio, caso não exista
if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

// Inclui o arquivo de conexão PDO com o banco de dados
include_once 'conexao.php';

// --- MÁQUINA DE ESTADOS DO CARRINHO (PROCESSAMENTO POST) ---
// Centraliza todas as ações de mutação do carrinho nesta seção antes de renderizar o HTML
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao'])) {
    $acao = $_POST['acao'];
    $produto_id = isset($_POST['produto_id']) ? intval($_POST['produto_id']) : 0;

    // Só processa se houver um ID numérico válido
    if ($produto_id > 0) {
        
        // AÇÃO 1: ADICIONAR ITEM OU INCREMENTAR QUANTIDADE
        if ($acao === 'adicionar') {
            $qtd_adicionar = isset($_POST['quantidade']) ? intval($_POST['quantidade']) : 1;
            if ($qtd_adicionar < 1) { $qtd_adicionar = 1; } // Garante o piso mínimo de 1 item

            // Se o produto já está no carrinho, incrementa a quantidade; senão, cria a chave
            if (isset($_SESSION['carrinho'][$produto_id])) {
                $_SESSION['carrinho'][$produto_id] += $qtd_adicionar;
            } else {
                $_SESSION['carrinho'][$produto_id] = $qtd_adicionar;
            }
            
        // AÇÃO 2: REMOVER ITEM COMPLETAMENTE
        } elseif ($acao === 'remover') {
            unset($_SESSION['carrinho'][$produto_id]); // Desaloca a chave correspondente do array
            
        // AÇÃO 3: ATUALIZAR QUANTIDADE DIRETAMENTE PELO INPUT
        } elseif ($acao === 'atualizar') {
            $nova_qtd = isset($_POST['quantidade']) ? intval($_POST['quantidade']) : 1;
            if ($nova_qtd > 0) {
                $_SESSION['carrinho'][$produto_id] = $nova_qtd;
            } else {
                unset($_SESSION['carrinho'][$produto_id]); // Se a quantidade for zero ou menor, remove do carrinho
            }
        }
    }
    // Padrão Post-Redirect-Get: Redireciona para a mesma página via GET para limpar o payload do POST.
    // Isso evita que o usuário reenvie o formulário e duplique a ação ao atualizar a página (F5).
    header('Location: carrinho.php');
    exit;
}

// --- PREPARAÇÃO DOS DADOS PARA EXIBIÇÃO ---
$produtos_no_carrinho = [];
$total_carrinho = 0;

// Se o carrinho possuir itens guardados na sessão, inicia a montagem do extrato
if (!empty($_SESSION['carrinho'])) {

    // Mock/Array Estático de Kits: Produtos especiais que não estão na tabela principal de velas
    $kits_estaticos = [
        901 => ['nome' => 'Kit Trio Clássico', 'tamanho' => '3x Velas 150g', 'preco' => 135.00, 'imagem' => 'kit3.png'],
        902 => ['nome' => 'Kit Dueto Relaxante', 'tamanho' => '2x Velas 200g', 'preco' => 98.00, 'imagem' => 'kit2.png'], 
        903 => ['nome' => 'Kit Coleção Outono/Inverno', 'tamanho' => '2x Velas 250g', 'preco' => 115.00, 'imagem' => 'kit3.png']
    ];

    $ids_banco = [];
    
    // Separa os itens: o que for kit processa localmente; o que for vela comum joga na fila do banco de dados
    foreach (array_keys($_SESSION['carrinho']) as $id_item) {
        if (!isset($kits_estaticos[$id_item])) {
            $ids_banco[] = $id_item; // Guarda o ID para a query SQL posterior
        } else {
            // Processa o kit estático acumulando os subtotais diretamente
            $qtd = $_SESSION['carrinho'][$id_item];
            $subtotal = $kits_estaticos[$id_item]['preco'] * $qtd;
            $total_carrinho += $subtotal;

            $produtos_no_carrinho[] = [
                'id' => $id_item,
                'nome' => $kits_estaticos[$id_item]['nome'],
                'tamanho' => $kits_estaticos[$id_item]['tamanho'],
                'preco' => $kits_estaticos[$id_item]['preco'],
                'quantidade' => $qtd,
                'subtotal' => $subtotal,
                'imagem' => $kits_estaticos[$id_item]['imagem']
            ];
        }
    }

    // DISPARO DINÂMICO AO BANCO (Apenas se houverem IDs de velas comuns a buscar)
    if (!empty($ids_banco) && isset($pdo)) {
        // Converte o array de IDs em uma string separada por vírgulas (Ex: "1,2,5")
        $ids_string = implode(',', $ids_banco);
        try {
            // Busca os detalhes apenas das velas que estão contidas no carrinho usando a cláusula SQL "IN"
            $stmt = $pdo->query("SELECT id, nome, tamanho, preco, imagem FROM public.velas WHERE id IN ($ids_string) ORDER BY nome ASC");
            $dados_velas = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Vincula os metadados vindos do banco de dados com as quantidades guardadas na sessão
            foreach ($dados_velas as $vela) {
                $id = $vela['id'];
                $qtd = $_SESSION['carrinho'][$id];
                $subtotal = $vela['preco'] * $qtd;
                $total_carrinho += $subtotal; // Soma cumulativa do total geral da compra

                $produtos_no_carrinho[] = [
                    'id' => $id,
                    'nome' => $vela['nome'],
                    'tamanho' => $vela['tamanho'],
                    'preco' => $vela['preco'],
                    'quantidade' => $qtd,
                    'subtotal' => $subtotal,
                    'imagem' => $vela['imagem']
                ];
            }
        } catch (Exception $e) {
            // Captura erros silenciosamente no console do navegador para não quebrar o layout do cliente
            echo "<script>console.log('Erro no carrinho: " . addslashes($e->getMessage()) . "');</script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seu Carrinho | Maia Candle Co.</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..700;1,400..700&family=Plus+Jakarta+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        
        :root {
            --bg-canvas: #f4f0ea;
            --bg-paper: #fdfbfa;
            --text-dark: #1a1512;
            --text-muted: #6e655f;
            --accent-green: #3d5245;
            --accent-brown: #764a34;
            --line-color: rgba(26, 21, 18, 0.12);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: var(--bg-canvas); color: var(--text-dark); min-height: 100vh; }
        
        /* CABEÇALHO DA LOJA (Vitrine) */
        .navbar { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            padding: 25px 6%; 
            background: transparent; 
            border-bottom: 1px solid var(--line-color); 
        }
        .navbar .logo { font-size: 22px; font-weight: 600; text-decoration: none; color: var(--text-dark); text-transform: uppercase; letter-spacing: 3px; }
        .navbar .menu a { margin: 0 18px; text-decoration: none; color: var(--text-muted); font-weight: 500; text-transform: uppercase; font-size: 12px; letter-spacing: 1.5px; transition: color 0.3s ease; }
        .navbar .menu a:hover { color: var(--text-dark); }
        .nav-right { display: flex; align-items: center; font-size: 13px; gap: 20px; text-transform: uppercase; letter-spacing: 1px; }
        .nav-right a { color: var(--text-dark); text-decoration: none; font-weight: 500; }
        
        .container { max-width: 1100px; margin: 60px auto; padding: 0 30px; }
        h1 { font-family: 'Playfair Display', serif; font-size: 38px; font-weight: 400; margin-bottom: 40px; text-align: left; }
        
        /* TABELA DE ITENS (Layout Limpo / Tipográfico) */
        .carrinho-table { width: 100%; border-collapse: collapse; margin-bottom: 40px; background: transparent; }
        .carrinho-table th { padding: 18px 10px; text-align: left; border-bottom: 2px solid var(--text-dark); text-transform: uppercase; font-size: 11px; letter-spacing: 2px; font-weight: 600; color: var(--text-muted); }
        .carrinho-table td { padding: 25px 10px; text-align: left; border-bottom: 1px solid var(--line-color); font-size: 15px; vertical-align: middle; }
        
        .produto-info-td { display: flex; align-items: center; gap: 20px; }
        .cart-img-wrapper { width: 70px; height: 85px; background-color: rgba(26, 21, 18, 0.05); border: 1px solid var(--line-color); overflow: hidden; }
        .cart-img { width: 100%; height: 100%; object-fit: cover; }
        .produto-meta h3 { font-family: 'Playfair Display', serif; font-size: 18px; font-weight: 500; margin-bottom: 4px; }
        .produto-meta span { font-size: 12px; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; }
        
        .input-qtd { width: 45px; padding: 8px 4px; text-align: center; border: 1px solid var(--text-dark); background: transparent; font-family: inherit; font-size: 14px; outline: none; }
        
        /* ELEMENTOS DE CONTROLE / BOTÕES DA TABELA */
        .btn-update { background: transparent; border: none; color: var(--text-dark); cursor: pointer; font-size: 11px; text-transform: uppercase; letter-spacing: 1px; font-weight: 600; margin-left: 8px; border-bottom: 1px solid var(--text-dark); padding-bottom: 2px; }
        .btn-remove { background: transparent; border: none; color: #a44a4a; cursor: pointer; font-size: 11px; text-transform: uppercase; letter-spacing: 1px; font-weight: 600; border-bottom: 1px solid transparent; padding-bottom: 2px; transition: 0.3s; }
        .btn-remove:hover { border-bottom: 1px solid #a44a4a; }
        
        /* SEÇÃO DE FECHAMENTO (Resumo de Valores) */
        .carrinho-resumo { display: flex; justify-content: space-between; align-items: flex-start; padding-top: 30px; border-top: 1px solid var(--text-dark); }
        .btn-back { color: var(--text-dark); text-decoration: none; font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 1.5px; border-bottom: 1px solid var(--text-dark); padding-bottom: 4px; transition: opacity 0.3s; }
        .btn-back:hover { opacity: 0.7; }
        
        .resumo-checkout-box { text-align: right; max-width: 350px; width: 100%; }
        .total-box { font-family: 'Playfair Display', serif; font-size: 28px; font-weight: 400; margin-bottom: 20px; color: var(--text-dark); }
        .total-box span { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 14px; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); font-weight: 500; }
        
        .btn-checkout { display: block; text-align: center; background-color: var(--text-dark); color: var(--bg-canvas); border: none; padding: 18px 30px; font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 2px; cursor: pointer; text-decoration: none; transition: all 0.3s ease; }
        .btn-checkout:hover { background-color: var(--accent-brown); }
        
        /* BLOCO VAZIO (Fallback do Usuário) */
        .empty-message { text-align: center; padding: 80px 20px; border: 1px dashed var(--line-color); background: rgba(26, 21, 18, 0.01); }
        .empty-message p { font-family: 'Playfair Display', serif; font-size: 22px; color: var(--text-muted); margin-bottom: 20px; }
        .back-to-shop { display: inline-block; background-color: var(--text-dark); color: var(--bg-canvas); padding: 14px 28px; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 1.5px; text-decoration: none; transition: 0.3s; }
        .back-to-shop:hover { background-color: var(--accent-green); }
    </style>
</head>
<body>

    <nav class="navbar">
        <a href="produtos.php" class="logo">Maia Candle Co.</a>
        <div class="menu">
            <a href="produtos.php">Produtos</a>
            <a href="kits.php">Kits</a>
            <?php if (isset($_SESSION['cliente_logado']) && $_SESSION['cliente_logado'] === true): ?>
                <a href="pedidos.php">Meus Pedidos</a> // Adiciona link para pedidos apenas se o cliente estiver logado
            <?php endif; ?>
            <a href="sobre.php">Sobre Nós</a>
            <a href="contato.php">Contato</a>
        </div>
        
        <div class="nav-right">
            <?php if (isset($_SESSION['cliente_logado']) && $_SESSION['cliente_logado'] === true): ?>
                <span>Olá, <strong><?php echo htmlspecialchars($_SESSION['cliente_nome']); ?></strong></span>
                <a href="index.php?action=logout" title="Sair da Conta">🚪</a> // Adiciona link para logout apenas se o cliente estiver logado
            <?php else: ?>
                <a href="login.php">Entrar 👤</a>
            <?php endif; ?>
            <span style="font-size: 18px; cursor: pointer; position: relative;" onclick="location.href='carrinho.php'" title="Carrinho">🛒</span>
        </div>
    </nav>

    <div class="container">
        <h1>Seu Carrinho</h1>

        <?php if (!empty($produtos_no_carrinho)): ?>
            <table class="carrinho-table">
                <thead>
                    <tr>
                        <th style="width: 45%;">Produto</th>
                        <th style="width: 15%;">Preço</th>
                        <th style="width: 25%;">Quantidade</th>
                        <th style="width: 15%;">Subtotal</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($produtos_no_carrinho as $item): ?>
                        <tr>
                            <td>
                                <div class="produto-info-td">
                                    <div class="cart-img-wrapper">
                                        <?php if (!empty($item['imagem'])): ?>
                                            <img src="imagens/<?php echo $item['imagem']; ?>" alt="<?php echo htmlspecialchars($item['nome']); ?>" class="cart-img">
                                        <?php else: ?>
                                            <div style="width:100%; height:100%; background:#c9bfb5; display:flex; align-items:center; justify-content:center; font-size:10px; color:#1a1512; font-weight:600;">MAIA</div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="produto-meta">
                                        <h3><?php echo htmlspecialchars($item['nome']); ?></h3>
                                        <span><?php echo htmlspecialchars($item['tamanho']); ?></span>
                                    </div>
                                </div>
                            </td>
                            <td>R$ <?php echo number_format($item['preco'], 2, ',', '.'); ?></td>
                            <td>
                                <form action="carrinho.php" method="POST" style="display: flex; align-items: center;">
                                    <input type="hidden" name="produto_id" value="<?php echo $item['id']; ?>">
                                    <input type="hidden" name="acao" value="atualizar">
                                    <input type="number" name="quantidade" value="<?php echo $item['quantidade']; ?>" min="1" class="input-qtd">
                                    <button type="submit" class="btn-update">Alterar</button>
                                </form>
                            </td>
                            <td style="font-weight: 500;">R$ <?php echo number_format($item['subtotal'], 2, ',', '.'); ?></td>
                            <td style="text-align: right;">
                                <form action="carrinho.php" method="POST">
                                    <input type="hidden" name="produto_id" value="<?php echo $item['id']; ?>">
                                    <input type="hidden" name="acao" value="remover">
                                    <button type="submit" class="btn-remove" title="Remover item">Remover</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="carrinho-resumo">
                <a href="produtos.php" class="btn-back">← Voltar para as Velas</a>
                
                <div class="resumo-checkout-box">
                    <div class="total-box">
                        <span>Total estimado: </span><br>
                        R$ <?php echo number_format($total_carrinho, 2, ',', '.'); ?>
                    </div>
                    <a href="checkout.php" class="btn-checkout">Prosseguir para Checkout</a>
                </div>
            </div>

        <?php else: ?>
            <div class="empty-message">
                <p>Sua sacola de compras está vazia.</p>
                <a href="produtos.php" class="back-to-shop">Explorar Coleções</a>
            </div>
        <?php endif; ?>
    </div>

</body>
</html>