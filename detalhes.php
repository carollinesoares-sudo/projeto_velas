<?php
// detalhes.php
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once 'conexao.php';

$vela = null;
$e_kit = false;

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = intval($_GET['id']);

    $kits_estaticos = [
        901 => [
            'id' => 901, 
            'nome' => 'Kit Trio Clássico',
            'aroma' => 'Baunilha Confort, Bergamota Fresca e Brisa de Verão',
            'tamanho' => '3 unidades de 150g',
            'descricao' => 'Contém 3 velas de 150g das nossas fragrâncias mais vendidas: Baunilha Confort, Bergamota Fresca e Brisa de Verão. Perfeito para presentear quem você ama ou perfumar múltiplos ambientes com sofisticação.',
            'preco' => 135.00,
            'composicao' => '3x Velas de 150g',
            'estoque' => 99,
            'imagem' => 'imagens/kit1.png'
        ],
        902 => [
            'id' => 902,
            'nome' => 'Kit Dueto Relaxante',
            'aroma' => 'Lavanda Calmante e Flor de Cerejeira',
            'tamanho' => '2 unidades de 200g',
            'descricao' => 'A combinação perfeita para o seu ritual de autocuidado noturno. Inclui uma vela de Lavanda Calmante de 200g e uma vela de Flor de Cerejeira de 200g, trazendo paz, relaxamento e harmonia ao seu espaço de repouso.',
            'preco' => 98.00,
            'composicao' => '2x Velas de 200g',
            'estoque' => 99,
            'imagem' => 'imagens/kit2.png'
        ],
        903 => [
            'id' => 903,
            'nome' => 'Kit Coleção Outono/Inverno',
            'aroma' => 'Canela e Cravo, Café Premium',
            'tamanho' => '2 unidades de 250g',
            'descricao' => 'Fragrâncias intensas e extremamente aconchegantes para dias frios. Contém 2 velas de 250g nos aromas Canela e Cravo e Café Premium. Sinta o abraço térmico e o conforto olfativo no ambiente.',
            'preco' => 115.00,
            'composicao' => '2x Velas de 250g',
            'estoque' => 99,
            'imagem' => 'imagens/kit3.png'
        ]
    ];

    if (array_key_exists($id, $kits_estaticos)) {
        $vela = $kits_estaticos[$id];
        $e_kit = true; 
    } else {
        try {
            if (isset($pdo)) {
                $stmt = $pdo->prepare("SELECT * FROM public.velas WHERE id = :id");
                $stmt->execute(['id' => $id]);
                $vela = $stmt->fetch(PDO::FETCH_ASSOC);
            }
        } catch (Exception $e) {
            echo "<p class='erro-banco'>Erro ao buscar detalhes: " . $e->getMessage() . "</p>";
        }
    }
}

$image_url = '';
if ($vela && !empty($vela['imagem'])) {
    $imagem_saida = trim($vela['imagem']);
    if (preg_match('#^https?://#i', $imagem_saida)) {
        $image_url = $imagem_saida;
    } else {
        $imagem_saida = ltrim($imagem_saida, './\\');
        if (stripos($imagem_saida, 'imagens/') === 0) {
            $image_url = $imagem_saida;
        } else {
            $image_url = 'imagens/' . ltrim($imagem_saida, '/\\');
        }
        if (!file_exists(__DIR__ . '/' . $image_url)) {
            $image_url = '';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $vela ? htmlspecialchars($vela['nome']) : 'Produto não encontrado'; ?> | Maia Candle Co.</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..700;1,400..700&family=Plus+Jakarta+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
       
        :root {
            --bg-canvas: #f4f0ea;
            --bg-card: #fdfbfa;
            --text-dark: #1a1512;
            --text-muted: #6e655f;
            --accent-green: #3d5245;
            --accent-brown: #764a34;
            --line-color: rgba(26, 21, 18, 0.12);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: var(--bg-canvas); 
            color: var(--text-dark); 
            -webkit-font-smoothing: antialiased; 
        }
        
       
        .navbar { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            padding: 25px 6%; 
            background: transparent; 
            border-bottom: 1px solid var(--line-color); 
        }
        .navbar .logo { font-size: 22px; font-weight: 600; text-decoration: none; color: var(--text-dark); text-transform: uppercase; letter-spacing: 3px; }
        .navbar .menu a { margin: 0 18px; text-decoration: none; color: var(--text-muted); font-weight: 500; text-transform: uppercase; font-size: 12px; letter-spacing: 1.5px; transition: color 0.3s; }
        .navbar .menu a:hover { color: var(--text-dark); }
        .nav-right { display: flex; align-items: center; font-size: 13px; gap: 20px; text-transform: uppercase; letter-spacing: 1px; }
        .nav-right a { color: var(--text-dark); text-decoration: none; font-weight: 500; }

       
        .container-detalhes { 
            max-width: 1150px; 
            margin: 60px auto; 
            padding: 0 30px; 
            display: grid; 
            grid-template-columns: 1.1fr 1fr; 
            gap: 70px; 
            align-items: start;
        }

        
        .product-image-area { display: flex; flex-direction: column; gap: 20px; }
        
        .link-retroceder-detalhes { 
            font-size: 11px; 
            text-transform: uppercase; 
            letter-spacing: 1.5px; 
            color: var(--text-muted); 
            text-decoration: none; 
            font-weight: 600;
            transition: color 0.2s;
        }
        .link-retroceder-detalhes:hover { color: var(--text-dark); }

        .image-placeholder { 
            width: 100%; 
            aspect-ratio: 4/5; 
            background-color: var(--bg-card); 
            border: 1px solid var(--line-color);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            box-shadow: 0 8px 25px rgba(26, 21, 18, 0.02);
        }
        .image-placeholder::before {
            content: "";
            font-size: 40px;
            opacity: 0.25;
        }

        .product-info-area { padding-top: 35px; }
        
        .product-title { 
            font-family: 'Playfair Display', serif; 
            font-size: 38px; 
            font-weight: 400; 
            line-height: 1.2;
            margin-bottom: 15px; 
            letter-spacing: -0.5px;
        }

        .product-price { 
            font-family: 'Playfair Display', serif; 
            font-size: 28px; 
            font-weight: 500; 
            color: var(--accent-brown); 
            margin-bottom: 30px; 
            padding-bottom: 20px;
            border-bottom: 1px solid var(--line-color);
        }

        
        .product-meta { 
            font-size: 13px; 
            line-height: 2; 
            color: var(--text-dark); 
            margin-bottom: 30px; 
        }
        .product-meta strong { 
            font-size: 11px; 
            text-transform: uppercase; 
            letter-spacing: 1px; 
            color: var(--text-muted); 
            display: inline-block;
            width: 160px;
        }

        
        .product-description { 
            font-size: 14px; 
            line-height: 1.7; 
            color: var(--text-muted); 
            margin-bottom: 40px; 
        }

     
        .compra-form { 
            display: flex; 
            align-items: flex-end; 
            gap: 20px; 
            border-top: 1px solid var(--line-color);
            padding-top: 30px;
        }
        
        .qtd-container { display: flex; flex-direction: column; gap: 8px; }
        .qtd-container label { font-size: 11px; text-transform: uppercase; letter-spacing: 1.5px; font-weight: 600; color: var(--text-muted); }
        
        .input-quantidade { 
            width: 70px; 
            padding: 14px 10px; 
            border: 1px solid var(--text-dark); 
            background: transparent; 
            font-family: inherit; 
            font-size: 14px; 
            text-align: center;
            outline: none;
        }

        .btn-add-carrinho { 
            flex: 1;
            background-color: var(--accent-green); 
            color: var(--bg-canvas); 
            border: none; 
            padding: 16px; 
            font-size: 12px; 
            font-weight: 600; 
            text-transform: uppercase; 
            letter-spacing: 2px; 
            cursor: pointer; 
            transition: all 0.3s ease; 
        }
        .btn-add-carrinho:hover { background-color: var(--text-dark); transform: translateY(-1px); }

        .out-of-stock { 
            font-size: 12px; 
            text-transform: uppercase; 
            letter-spacing: 1px; 
            font-weight: 600; 
            color: var(--accent-brown); 
            background: rgba(118, 74, 52, 0.08); 
            padding: 15px; 
            text-align: center; 
        }

        .container-produto-nao-encontrado { grid-column: span 2; text-align: center; padding: 80px 20px; }
        .container-produto-nao-encontrado h2 { font-family: 'Playfair Display', serif; font-size: 32px; font-weight: 400; margin-bottom: 10px; }
        .link-retroceder { display: inline-block; margin-top: 25px; color: var(--text-dark); font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 1.5px; }

        @media (max-width: 768px) {
            .container-detalhes { grid-template-columns: 1fr; gap: 40px; }
            .product-info-area { padding-top: 0; }
        }
    </style>
</head>
<body>

    <nav class="navbar">
        <a href="./index.php" class="logo">Maia Candle Co.</a>
        <div class="menu">
            <a href="./produtos.php">Produtos</a>
            <a href="./kits.php">Kits</a>
            <?php if (isset($_SESSION['cliente_logado']) && $_SESSION['cliente_logado'] === true): ?>
                <a href="./pedidos.php">Meus Pedidos</a>
            <?php endif; ?>
            <a href="./sobre.php">Sobre Nós</a>
            <a href="./contato.php">Contato</a>
        </div>
        
        <div class="nav-right">
            <?php if (isset($_SESSION['cliente_logado']) && $_SESSION['cliente_logado'] === true): ?>
                <span>Olá, <strong><?php echo htmlspecialchars($_SESSION['cliente_nome']); ?></strong></span>
                <a href="index.php?action=logout" title="Sair da Conta">🚪</a>
            <?php else: ?>
                <a href="login.php">Entrar 👤</a>
            <?php endif; ?>
            <span style="font-size: 18px; cursor: pointer;" onclick="location.href='carrinho.php'" title="Carrinho">🛒</span>
        </div>
    </nav>

    <div class="container container-detalhes">
        <?php if ($vela): ?>
            <div class="product-image-area">
                <a href="<?php echo $e_kit ? './kits.php' : './produtos.php'; ?>" class="link-retroceder-detalhes">
                    ← Voltar para a coleção de <?php echo $e_kit ? 'kits' : 'velas'; ?>
                </a>
                <div class="image-placeholder">
                    <?php if (!empty($image_url)): ?>
                        <img src="<?php echo htmlspecialchars($image_url); ?>" alt="<?php echo htmlspecialchars($vela['nome']); ?>" style="width:100%; height:100%; object-fit: cover;">
                    <?php else: ?>
                        <p style="color: var(--text-muted); font-size: 14px; text-align: center; padding: 20px;">Imagem não disponível</p>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="product-info-area">
                <h1 class="product-title"><?php echo htmlspecialchars($vela['nome']); ?></h1>
                
                <div class="product-price">
                    R$ <?php echo number_format($vela['preco'], 2, ',', '.'); ?>
                </div>

                <div class="product-meta">
                    <?php if ($e_kit): ?>
                        <div><strong>Composição:</strong><?php echo htmlspecialchars($vela['composicao']); ?></div>
                        <div><strong>Experiência Olfativa:</strong><?php echo htmlspecialchars($vela['aroma']); ?></div>
                        <div><strong>Disponibilidade:</strong>Pronta Entrega</div>
                    <?php else: ?>
                        <div><strong>Alquimia/Aroma:</strong><?php echo htmlspecialchars($vela['aroma']); ?></div>
                        <div><strong>Volume Líquido:</strong><?php echo htmlspecialchars($vela['tamanho']); ?></div>
                        <div><strong>Coloração da Cera:</strong><?php echo htmlspecialchars($vela['cor'] ?? 'Natural (Sem Corantes)'); ?></div>
                    <?php endif; ?>
                </div>
                
                <div class="product-description">
                    <p><?php echo nl2br(htmlspecialchars($vela['descricao'] ?? 'Cada vela da Maia Candle Co. é derramada à mão individualmente, unindo o cuidado artesanal a um design minimalista e elegante. Formulada com um blend de ceras 100% vegetais (livre de parafina), ela proporciona uma queima limpa, lenta e segura para a sua saúde e para o meio ambiente.

Nossas fragrâncias premium foram cuidadosamente selecionadas para transformar o seu ambiente em um verdadeiro santuário particular de bem-estar, trazendo harmonia, aconchego e sofisticação para os seus rituais diários.')); ?></p>
                </div>
                
                <?php if ($vela['estoque'] > 0): ?>
                    <form action="carrinho.php" method="POST" class="compra-form">
                        <input type="hidden" name="produto_id" value="<?php echo $vela['id']; ?>">
                        <input type="hidden" name="acao" value="adicionar">
                        
                        <div class="qtd-container">
                            <label for="quantidade">Qtd</label>
                            <input type="number" id="quantidade" name="quantidade" value="1" min="1" max="<?php echo $vela['estoque']; ?>" class="input-quantidade">
                        </div>
                        
                        <button type="submit" class="btn-add-carrinho">Adicionar à Sacola</button>
                    </form>
                <?php else: ?>
                    <p class="out-of-stock">🌿 Lote temporariamente esgotado. Nova produção em andamento.</p>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="container-produto-nao-encontrado">
                <h2>Criação não encontrada</h2>
                <p style="color: var(--text-muted); font-size: 15px;">O item que você está tentando acessar foi removido ou pertence a um lote sazonal encerrado.</p>
                <a href="./produtos.php" class="link-retroceder">Retornar às Obras Botânicas</a>
            </div>
        <?php endif; ?>
    </div>

</body>
</html>