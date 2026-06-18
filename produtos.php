<?php
// produtos.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Iniciamos a sessão no topo para conseguir checar se o cliente está logado
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$produtos = [];

try {
    include_once 'conexao.php';
    if (isset($pdo)) {
        $stmt = $pdo->query("SELECT * FROM public.velas ORDER BY nome ASC");
        $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} catch (Exception $e) {
    echo "<script>console.log('Erro na query: " . addslashes($e->getMessage()) . "');</script>";
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos | Maia Candle Co.</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..700;1,400..700&family=Plus+Jakarta+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        
        :root {
            --bg-canvas: #f4f0ea;    /* Tom areia clássico */
            --bg-card: #fdfbfa;      /* Fundo puro das imagens e blocos */
            --text-dark: #1a1512;    /* Marrom escuro botânico */
            --text-muted: #6e655f;   /* Legendagem terrosa */
            --accent-green: #3d5245; /* Verde assinatura Maia Candle */
            --accent-gold: #764a34;  /* Tom bronze/terracota */
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
        .navbar .logo { 
            font-family: 'Playfair Display', serif;
            font-size: 24px; 
            font-weight: 400; 
            text-decoration: none; 
            color: var(--text-dark); 
            letter-spacing: -0.5px;
        }
        .navbar .menu { display: flex; gap: 28px; }
        .navbar .menu a { 
            text-decoration: none; 
            color: var(--text-muted); 
            font-weight: 600; 
            text-transform: uppercase; 
            font-size: 11px; 
            letter-spacing: 1.5px;
            transition: color 0.3s;
        }
        .navbar .menu a:hover { color: var(--text-dark); }
        
        .nav-right { display: flex; align-items: center; font-size: 13px; gap: 20px; text-transform: uppercase; letter-spacing: 1px; }
        .nav-right a { color: var(--text-dark); text-decoration: none; font-weight: 500; }

        
        .main-layout { 
            display: flex; 
            max-width: 1200px; 
            margin: 50px auto; 
            padding: 0 30px; 
            gap: 60px; 
        }
        
       
        .sidebar { 
            width: 200px; 
            flex-shrink: 0; 
            text-align: left; 
        }
        
        .filter-section-title { 
            font-size: 11px; 
            font-weight: 600; 
            text-transform: uppercase; 
            letter-spacing: 2px; 
            margin-bottom: 20px; 
            margin-top: 30px; 
            color: var(--text-dark); 
            border-bottom: 1px solid var(--text-dark);
            padding-bottom: 6px;
        }
        .sidebar div:first-of-type.filter-section-title { margin-top: 0; }
        
        .filter-option { 
            display: flex; 
            align-items: center; 
            margin-bottom: 14px; 
            font-size: 14px; 
            color: var(--text-muted);
            cursor: pointer; 
            position: relative;
            transition: color 0.2s;
        }
        .filter-option:hover { color: var(--text-dark); }

       
        .filter-option input { 
            appearance: none;
            -webkit-appearance: none;
            width: 14px; 
            height: 14px; 
            border: 1px solid var(--text-muted);
            background-color: transparent;
            margin-right: 12px; 
            cursor: pointer; 
            position: relative;
            transition: all 0.2s;
        }
        .filter-option input:checked {
            background-color: var(--accent-green);
            border-color: var(--accent-green);
        }
        .filter-option input:checked::after {
            content: '';
            position: absolute;
            width: 4px;
            height: 8px;
            border: solid #ffffff;
            border-width: 0 1.5px 1.5px 0;
            transform: rotate(45deg);
            top: 1px;
            left: 4px;
        }
        
        .price-range { 
            font-size: 14px; 
            color: var(--text-muted); 
            font-style: italic;
        }

        
        .content-area { flex: 1; }
        
        .container-title { 
            text-align: left; 
            width: 100%; 
            margin-bottom: 45px; 
        }
        .page-title { 
            font-family: 'Playfair Display', serif;
            font-size: 38px; 
            font-weight: 400;
            letter-spacing: -0.5px;
        }
        
        
        .produtos-grid { 
            display: grid; 
            grid-template-columns: repeat(3, 1fr); 
            gap: 40px 30px; 
        }
        
        .produto-link { 
            text-decoration: none; 
            color: inherit; 
            display: block; 
        }
        
        .produto-card { 
            text-align: left; 
            display: flex; 
            flex-direction: column; 
            width: 100%; 
        }
        
        
        .image-placeholder { 
            width: 100%; 
            aspect-ratio: 4/5; 
            background-color: var(--bg-card); 
            border: 1px solid var(--line-color); 
            position: relative; 
            margin-bottom: 16px; 
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background-color 0.3s;
            box-shadow: 0 6px 20px rgba(26, 21, 18, 0.01);
            overflow: hidden;
        }
        .image-placeholder::before { 
            content: '🌿'; 
            font-size: 24px;
            opacity: 0.35;
        }
        .image-placeholder.has-image::before {
            content: none;
        }
        .image-placeholder img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }
        .produto-link:hover .image-placeholder { 
            background-color: #ede8e1;
        }
        
        .product-title { 
            font-family: 'Playfair Display', serif;
            font-size: 18px; 
            font-weight: 400;
            color: var(--text-dark); 
            margin-bottom: 6px;
            line-height: 1.3;
        }
        .produto-link:hover .product-title {
            color: var(--accent-green);
        }

        .product-size-tag {
            font-size: 11px;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px;
        }
        
        .product-price { 
            font-size: 15px; 
            font-weight: 600; 
            color: var(--accent-gold); 
        }

        @media (max-width: 900px) {
            .produtos-grid { grid-template-columns: repeat(2, 1fr); }
            .main-layout { gap: 30px; }
        }
        @media (max-width: 650px) {
            .main-layout { flex-direction: column; }
            .sidebar { width: 100%; }
            .produtos-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

    <nav class="navbar">
        
        <a href="produtos.php" class="logo">Maia Candle Co.</a>
        <div class="menu">
            <a href="produtos.php" style="color: var(--text-dark); border-bottom: 1px solid var(--text-dark); padding-bottom: 4px;">Produtos</a>
            <a href="kits.php">Kits</a>
            <?php if (isset($_SESSION['cliente_logado']) && $_SESSION['cliente_logado'] === true): ?>
                <a href="pedidos.php">Meus Pedidos</a>
            <?php endif; ?>
            <a href="sobre.php">Sobre Nós</a>
            <a href="contato.php">Contato</a>
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

    <div class="main-layout">
        
        <aside class="sidebar">
            <div class="filter-section-title">Fragrâncias</div>
            <label class="filter-option">
                <input type="checkbox" class="flavor-filter" value="citricas"> Cítricas
            </label>
            <label class="filter-option">
                <input type="checkbox" class="flavor-filter" value="florais"> Florais
            </label>
            <label class="filter-option">
                <input type="checkbox" class="flavor-filter" value="orientais"> Orientais
            </label>

            <div class="filter-section-title">Preços</div>
            <div class="price-range">R$ 50,00 a R$ 120,00</div>
        </aside>

        <main class="content-area">
            <div class="container-title">
                <h1 class="page-title">Criações Botânicas</h1>
            </div>
            
            <div class="produtos-grid" id="grid-produtos">
                <?php if (!empty($produtos)): ?>
                    <?php foreach ($produtos as $vela): ?>
                        <?php 
                            $aroma = strtolower($vela['aroma']);
                            $cat = 'outros';

                            if (strpos($aroma, 'bergamota') !== false || strpos($aroma, 'capim') !== false || strpos($aroma, 'laranja') !== false || strpos($aroma, 'pinheiro') !== false) {
                                $cat = 'citricas';
                            } elseif (strpos($aroma, 'lavanda') !== false || strpos($aroma, 'jasmim') !== false || strpos($aroma, 'cherry') !== false) {
                                $cat = 'florais';
                            } elseif (strpos($aroma, 'vanilla') !== false || strpos($aroma, 'canela') !== false || strpos($aroma, 'cafe') !== false || strpos($aroma, 'doce') !== false || strpos($aroma, 'sandalo') !== false) {
                                $cat = 'orientais';
                            }

                            $imagem_url = '';
                            if (!empty($vela['imagem'])) {
                                $imagem_saida = trim($vela['imagem']);
                                if (preg_match('#^https?://#i', $imagem_saida)) {
                                    $imagem_url = $imagem_saida;
                                } else {
                                    $imagem_saida = ltrim($imagem_saida, './\\');
                                    if (stripos($imagem_saida, 'imagens/') === 0) {
                                        $imagem_url = $imagem_saida;
                                    } else {
                                        $imagem_url = 'imagens/' . ltrim($imagem_saida, '/\\');
                                    }

                                    if (!file_exists(__DIR__ . '/' . $imagem_url)) {
                                        $imagem_url = '';
                                    }
                                }
                            }
                        ?>
                        
                        <a href="./detalhes.php?id=<?php echo $vela['id']; ?>" class="produto-link" data-tipo="<?php echo $cat; ?>">
                            <div class="produto-card">
                                <div class="image-placeholder<?php echo !empty($imagem_url) ? ' has-image' : ''; ?>">
                                    <?php if (!empty($imagem_url)): ?>
                                        <img src="<?php echo htmlspecialchars($imagem_url); ?>" alt="<?php echo htmlspecialchars($vela['nome']); ?>">
                                    <?php endif; ?>
                                </div>
                                <div class="product-size-tag"><?php echo htmlspecialchars($vela['tamanho']); ?></div>
                                <h3 class="product-title"><?php echo htmlspecialchars($vela['nome']); ?></h3>
                                <div class="product-price">
                                    R$ <?php echo number_format($vela['preco'], 2, ',', '.'); ?>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p style="grid-column: span 3; text-align: center; color: var(--text-muted); font-style: italic; padding: 40px 0;">Nenhuma criação olfativa disponível no momento.</p>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <script>
        const filters = document.querySelectorAll('.flavor-filter');
        const productLinks = document.querySelectorAll('.produto-link');

        filters.forEach(filter => {
            filter.addEventListener('change', () => {
                const activeFilters = Array.from(filters)
                    .filter(input => input.checked)
                    .map(input => input.value);

                productLinks.forEach(link => {
                    const cardType = link.dataset.tipo;
                    if (activeFilters.length === 0 || activeFilters.includes(cardType)) {
                        link.style.display = 'block';
                    } else {
                        link.style.display = 'none';
                    }
                });
            });
        });
    </script>
</body>
</html>