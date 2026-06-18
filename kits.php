<?php
// kits.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$kits = [
    [
        'id' => 901,
        'nome' => 'Kit Trio Clássico',
        'descricao' => 'Contém 3 velas de 150g das nossas fragrâncias mais vendidas.',
        'preco' => 135.00,
        'composicao' => '3x Velas de 150g',
        'imagem' => 'imagens/kit1.png'
    ],
    [
        'id' => 902,
        'nome' => 'Kit Dueto Relaxante',
        'descricao' => 'A combinação perfeita para o seu ritual de autocuidado.',
        'preco' => 98.00,
        'composicao' => '2x Velas de 200g',
        'imagem' => 'imagens/kit2.png'
    ],
    [
        'id' => 903,
        'nome' => 'Kit Coleção Outono/Inverno',
        'descricao' => 'Fragrâncias intensas e aconchegantes para dias frios.',
        'preco' => 115.00,
        'composicao' => '2x Velas de 250g',
        'imagem' => 'imagens/kit3.png'
    ]
];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kits Especiais | Maia Candle Co.</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..700;1,400..700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        
        :root {
            --bg-canvas: #f4f0ea;    
            --bg-card: #fdfbfa;      
            --text-dark: #1a1512;    
            --text-muted: #6e655f;   
            --accent-green: #3d5245; 
            --accent-gold: #764a34;  
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
        
        .nav-icons { display: flex; align-items: center; gap: 20px; }
        .nav-icons a, .nav-icons span { 
            text-decoration: none; 
            font-size: 18px; 
            color: var(--text-dark); 
            cursor: pointer; 
            transition: opacity 0.2s;
        }
        .nav-icons a:hover, .nav-icons span:hover { opacity: 0.7; }
        
        
        .container { 
            max-width: 1050px; 
            margin: 60px auto; 
            padding: 0 30px; 
        }
        
        .title-wrapper { 
            text-align: center; 
            margin-bottom: 60px; 
        }
        .page-title { 
            font-family: 'Playfair Display', serif;
            font-size: 42px;
            font-weight: 400;
            letter-spacing: -0.5px;
            display: inline-block; 
            position: relative;
            padding-bottom: 12px;
        }
        .page-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 35%;
            width: 30%;
            height: 1px;
            background-color: var(--accent-gold);
        }
        
        
        .kits-list { 
            display: flex; 
            flex-direction: column; 
            gap: 40px; 
        }
        
        .kit-card { 
            display: flex; 
            background: var(--bg-card); 
            border: 1px solid var(--line-color); 
            padding: 40px; 
            gap: 45px; 
            align-items: center; 
            box-shadow: 0 10px 30px rgba(26, 21, 18, 0.02);
            transition: transform 0.3s ease;
        }
        .kit-card:hover {
            transform: translateY(-2px);
        }
        
        .kit-link { text-decoration: none; color: inherit; display: block; }
        
        .kit-image-box { 
            width: 220px; 
            height: 220px;
            border: 1px solid rgba(26, 21, 18, 0.06); 
            position: relative; 
            flex-shrink: 0; 
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            
            background: linear-gradient(135deg, #ede8e1 0%, #dfd9cf 100%);
            transition: filter 0.3s ease;
        }
        .kit-image-box img {
            width: 100%;
            height: 100%;
            object-fit: cover; 
        }
        .kit-link:hover .kit-image-box { filter: brightness(0.95); }
        
        .kit-info { flex: 1; }
        
        .kit-name { 
            font-family: 'Playfair Display', serif;
            font-size: 26px; 
            margin-bottom: 4px; 
            font-weight: 400;
            letter-spacing: -0.5px;
            color: var(--text-dark);
            transition: color 0.2s;
        }
        .kit-link:hover .kit-name { color: var(--accent-green); }
        
        .kit-composicao { 
            font-size: 11px; 
            color: var(--accent-gold); 
            font-weight: 600; 
            text-transform: uppercase; 
            letter-spacing: 1.5px; 
            margin-bottom: 16px; 
        }
        
        .kit-desc { 
            font-size: 14px; 
            line-height: 1.6; 
            color: var(--text-muted); 
            margin-bottom: 24px; 
        }
        
        .kit-price { 
            font-family: 'Playfair Display', serif;
            font-size: 24px; 
            font-weight: 500; 
            margin-bottom: 24px; 
            color: var(--text-dark);
        }
        
        .btn-comprar { 
            background: var(--accent-green); 
            color: var(--bg-canvas); 
            border: none; 
            padding: 14px 35px; 
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 600; 
            text-transform: uppercase; 
            letter-spacing: 1.5px; 
            cursor: pointer; 
            font-size: 11px; 
            transition: all 0.3s ease;
        }
        .btn-comprar:hover { 
            background: var(--text-dark); 
            transform: translateY(-1px);
        }

        @media (max-width: 768px) {
            .navbar { padding: 20px 4%; }
            .navbar .menu { display: none; } 
            .kit-card { flex-direction: column; padding: 30px; text-align: center; gap: 25px; }
            .kit-image-box { width: 100%; max-width: 240px; height: 240px; }
            .btn-comprar { width: 100%; }
        }
    </style>
</head>
<body>

    <nav class="navbar">
        <a href="index.php" class="logo">Maia Candle Co.</a>
        <div class="menu">
            <a href="produtos.php">Produtos</a>
            <a href="kits.php" style="color: var(--text-dark); border-bottom: 1px solid var(--text-dark);">Kits</a>
            <a href="pedidos.php">Meus Pedidos</a> 
            <a href="sobre.php">Sobre Nós</a>
            <a href="contato.php">Contato</a>
        </div>
        <div class="nav-icons">
            <a href="index.php?action=logout" title="Sair da Conta">👤</a>
            <span onclick="location.href='carrinho.php'" title="Visualizar Carrinho">🛒</span>
        </div>
    </nav>

    <div class="container">
        <div class="title-wrapper">
            <h1 class="page-title">Kits Especiais</h1>
        </div>
        
        <div class="kits-list">
            <?php foreach ($kits as $kit): ?>
                <div class="kit-card">
                    
                    <a href="detalhes.php?id=<?php echo $kit['id']; ?>" class="kit-link">
                        <div class="kit-image-box">
                            <img src="<?php echo $kit['imagem']; ?>" alt="<?php echo htmlspecialchars($kit['nome']); ?>">
                        </div>
                    </a>
                    
                    <div class="kit-info">
                        <a href="detalhes.php?id=<?php echo $kit['id']; ?>" class="kit-link">
                            <h2 class="kit-name"><?php echo htmlspecialchars($kit['nome']); ?></h2>
                        </a>
                        
                        <div class="kit-composicao">Composição: <?php echo htmlspecialchars($kit['composicao']); ?></div>
                        <p class="kit-desc"><?php echo htmlspecialchars($kit['descricao']); ?></p>
                        <div class="kit-price">R$ <?php echo number_format($kit['preco'], 2, ',', '.'); ?></div>
                        
                        <form action="carrinho.php" method="POST">
                            <input type="hidden" name="produto_id" value="<?php echo $kit['id']; ?>">
                            <input type="hidden" name="acao" value="adicionar">
                            <input type="hidden" name="quantidade" value="1">
                            <button type="submit" class="btn-comprar">Adicionar Kit ao Carrinho</button>
                        </form>
                    </div>

                </div>
            <?php endforeach; ?>
        </div>
    </div>

</body>
</html>