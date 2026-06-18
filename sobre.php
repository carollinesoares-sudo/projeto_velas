<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sobre Nós | Maia Candle Co.</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..700;1,400..700&family=Plus+Jakarta+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
       
        :root {
            --bg-canvas: #f4f0ea;    /* Tom areia clássico */
            --bg-card: #fdfbfa;      /* Fundo puro dos blocos */
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

        
        .about-container { 
            display: flex; 
            max-width: 1200px; 
            margin: 60px auto; 
            padding: 0 30px; 
            gap: 80px; 
            align-items: center; 
        }
        
        
        .text-section { 
            flex: 1.1; 
        }
        .text-section h1 { 
            font-family: 'Playfair Display', serif;
            font-size: 42px; 
            font-weight: 400;
            line-height: 1.2;
            margin-bottom: 35px; 
            color: var(--text-dark);
        }
        .text-section p { 
            font-size: 15px; 
            line-height: 1.6; 
            color: var(--text-muted); 
            margin-bottom: 22px; 
            text-align: justify; 
        }
        
        
        .manifesto-list {
            margin-top: 30px;
            border-top: 1px solid var(--line-color);
            padding-top: 25px;
        }
        .manifesto-item {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 14px;
            font-weight: 500;
            color: var(--text-dark);
            margin-bottom: 12px;
        }
        .manifesto-item span {
            color: var(--accent-gold);
            font-size: 16px;
        }
        
        .image-section { 
            flex: 0.9; 
            aspect-ratio: 4/5; 
            background-color: var(--bg-card); 
            border: 1px solid var(--line-color); 
            position: relative; 
            width: 100%; 
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 30px rgba(26, 21, 18, 0.02);
            overflow: hidden; /* Garante que a foto não saia da moldura */
        }

        .image-section img {
            width: 100%;
            height: 100%;
            object-fit: cover; 
        }

        
        @media (max-width: 900px) {
            .about-container { 
                flex-direction: column-reverse; 
                gap: 40px; 
                margin: 40px auto;
            }
            .text-section h1 { font-size: 34px; margin-bottom: 25px; }
            .image-section { aspect-ratio: 1/1; }
        }
    </style>
</head>
<body>

    <nav class="navbar">
        <a href="produtos.php" class="logo">Maia Candle Co.</a>
        <div class="menu">
            <a href="produtos.php">Produtos</a>
            <a href="kits.php">Kits</a>
            <?php if (isset($_SESSION['cliente_logado']) && $_SESSION['cliente_logado'] === true): ?>
                <a href="pedidos.php">Meus Pedidos</a>
            <?php endif; ?>
            <a href="sobre.php" style="color: var(--text-dark); border-bottom: 1px solid var(--text-dark); padding-bottom: 4px;">Sobre Nós</a>
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

    <main class="about-container">
        <section class="text-section">
            <h1>Sobre a Maia Candle Co.</h1>
            <p>A Maia Candle Co. nasceu da paixão por transformar ambientes através de aromas, sensações e momentos especiais. Nossa marca é especializada em velas aromáticas artesanais, produzidas com cuidado, qualidade e atenção aos mínimos detalhes.</p>
            <p>Cada vela é desenvolvida para proporcionar conforto, bem-estar e personalidade aos espaços, combinando fragrâncias sofisticadas com um design elegante e acolhedor. Mais do que produtos, criamos experiências sensoriais que despertam memórias, emoções e aconchego.</p>
            <p>Acreditamos que pequenos rituais diários podem tornar a rotina mais leve, relaxante e inspiradora. Por isso, buscamos oferecer velas que transmitam tranquilidade, beleza e autenticidade em cada nota olfativa.</p>
            
            <div class="manifesto-list">
                <div class="manifesto-item">
                    <span>✨</span> Aromas que iluminam histórias e momentos.
                </div>
                <div class="manifesto-item">
                    <span>🕯️</span> Alquimia manual feita para transformar atmosferas.
                </div>
            </div>
        </section>
        
        <aside class="image-section" title="Espaço para fotografia do atelier">
            <img src="imagens/sobre.png" alt="Produção artesanal da Maia Candle Co.">
        </aside>
    </main>

</body>
</html>