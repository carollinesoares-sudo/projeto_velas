<?php
// portal.php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal de Acesso | Maia Candle Co.</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..700;1,400..700&family=Plus+Jakarta+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        
        :root {
            --bg-canvas: #fcfbfa;     /* Fundo cru/off-white */
            --text-dark: #1a1512;     /* Marrom quase preto botânico */
            --text-muted: #8c8881;    /* Cinza terroso secundário */
            --accent-gold: #b5896b;   /* Terracota/Bronze suave */
            --card-cliente: #3b3f36;   /* Oliva profundo floresta */
            --card-admin: #2b332e;     /* Musgo/Sálvia escuro fechado */
            --line-color: #e8e5dd;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: var(--bg-canvas); 
            color: var(--text-dark);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            justify-content: space-between;
            -webkit-font-smoothing: antialiased;
        }

        
        .header-logo {
            text-align: center;
            padding-top: 60px;
        }

        .header-logo h1 {
            font-family: 'Playfair Display', serif;
            font-size: 46px;
            font-weight: 400;
            margin: 0;
            letter-spacing: -0.5px;
            color: #2e3d30; /* Verde assinatura */
        }

        .header-logo .subtitle {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 3px;
            color: var(--accent-gold);
            margin-top: 8px;
            font-weight: 600;
        }

       
        .portal-container {
            display: flex;
            justify-content: center;
            gap: 40px;
            max-width: 1000px;
            margin: 40px auto;
            padding: 0 24px;
            flex: 1;
            align-items: center;
        }

        .portal-card {
            flex: 1;
            max-width: 420px;
            height: 480px;
            padding: 45px 35px;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            position: relative;
            box-shadow: 0 20px 40px rgba(26, 21, 18, 0.04);
            border: 1px solid rgba(26, 21, 18, 0.05);
            transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .portal-card:hover {
            transform: translateY(-4px);
        }

        .card-title {
            font-family: 'Playfair Display', serif;
            color: #fff;
            font-size: 30px;
            margin-bottom: 12px;
            font-weight: 400;
            letter-spacing: -0.5px;
        }

        .card-text {
            color: rgba(255, 255, 255, 0.75);
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 35px;
            font-weight: 300;
        }

       
        .btn-portal {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 14px 28px;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            text-decoration: none;
            font-weight: 600;
            width: fit-content;
            transition: all 0.3s ease;
        }

        .btn-cliente {
            background-color: #fff;
            color: var(--text-dark);
        }

        .btn-cliente:hover {
            background-color: var(--bg-canvas);
            padding-right: 34px; 
        }

        .btn-funcionario {
            background-color: transparent;
            color: #fff;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .btn-funcionario:hover {
            background-color: rgba(255, 255, 255, 0.08);
            border-color: #fff;
        }

      
        .footer-portal {
            border-top: 1px solid var(--line-color);
            padding: 35px 8%;
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            color: var(--text-muted);
            letter-spacing: 0.5px;
        }

        .footer-portal a {
            color: var(--text-muted);
            text-decoration: none;
            margin-left: 25px;
            transition: color 0.2s;
        }

        .footer-portal a:hover {
            color: var(--text-dark);
        }

        @media (max-width: 768px) {
            .portal-container {
                flex-direction: column;
                margin: 40px auto;
            }
            .portal-card {
                width: 100%;
                height: 400px;
            }
            .footer-portal {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }
            .footer-portal div:last-child {
                display: flex;
                justify-content: center;
            }
        }
    </style>
</head>
<body>

    <div class="header-logo">
        <h1>Maia Candle Co.</h1>
        <div class="subtitle">Artesanal & Intencional</div>
    </div>

    <div class="portal-container">
        
        <div class="portal-card" style="background: linear-gradient(to bottom, rgba(59,63,54,0.3), rgba(59,63,54,0.95)), var(--card-cliente);">
            <h2 class="card-title">Entrar como Cliente</h2>
            <p class="card-text">Explore nossa coleção de velas perfumadas feitas à mão e encontre seu ritual de bem-estar.</p>
            <a href="./produtos.php" class="btn-portal btn-cliente">Explorar Coleção &nbsp;&rarr;</a>
        </div>

        <div class="portal-card" style="background: linear-gradient(to bottom, rgba(43,51,46,0.3), rgba(43,51,46,0.95)), var(--card-admin);">
            <h2 class="card-title">Entrar como Funcionário</h2>
            <p class="card-text">Gerencie os lotes de produção do ateliê, controle os níveis de estoque e despache pedidos.</p>
            <a href="./login_funcionario.php" class="btn-portal btn-funcionario">Painel de Gestão 🔒</a>
        </div>

    </div>

    <footer class="footer-portal">
        <div>&copy; 2026 Maia Candle Co. Hand-poured with intention.</div>
        <div>
            <a href="#">Sustentabilidade</a>
            <a href="#">Atacado</a>
            <a href="#">Contato</a>
        </div>
    </footer>

</body>
</html>