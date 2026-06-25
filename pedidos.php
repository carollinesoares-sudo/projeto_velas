<?php
// pedidos.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once 'conexao.php';


$usuario_logado = isset($_SESSION['cliente_logado']) && $_SESSION['cliente_logado'] === true;


if ($usuario_logado && isset($_GET['action']) && $_GET['action'] === 'cancelar' && isset($_GET['numero'])) {
    $numero_para_cancelar = $_GET['numero'];
    
   
    if (isset($_SESSION['meus_pedidos_simulados'])) {
        foreach ($_SESSION['meus_pedidos_simulados'] as $key => $pedido) {
            if ($pedido['numero'] == $numero_para_cancelar) {
               
                $_SESSION['meus_pedidos_simulados'][$key]['status'] = 'Cancelado';
                $_SESSION['meus_pedidos_simulados'][$key]['cor_status'] = '#764a34'; 
            }
        }
    }

    
    try {
        if (isset($pdo)) {
            $stmt = $pdo->prepare("UPDATE public.pedidos SET status = 'Cancelado', cor_status = '#764a34' WHERE numero = :num");
            $stmt->execute(['num' => $numero_para_cancelar]);
        }
    } catch (Exception $e) {
   
    }

    header("Location: pedidos.php");
    exit();
}

$pedidos_para_exibir = [];
if ($usuario_logado) {
    if (!isset($_SESSION['meus_pedidos_simulados'])) {
        $_SESSION['meus_pedidos_simulados'] = [
            [
                'numero' => '8432',
                'data' => '10/06/2026',
                'itens' => '1x Vela Bergamota & Lima, 1x Vela Pera & Flor',
                'total' => 95.00,
                'status' => 'Em produção',
                'cor_status' => '#b5896b'
            ],
            [
                'numero' => '8129',
                'data' => '28/05/2026',
                'itens' => '2x Vela Baunilha & Laranja',
                'total' => 144.00,
                'status' => 'Enviado / A caminho',
                'cor_status' => '#3d5245'
            ]
        ];
    }
    $pedidos_para_exibir = $_SESSION['meus_pedidos_simulados'];
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Pedidos | Maia Candle Co.</title>
    
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
            padding: 30px 8%; 
            background: var(--bg-canvas); 
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
        
        .navbar .menu a { 
            margin-left: 35px; 
            text-decoration: none; 
            color: var(--text-dark); 
            font-weight: 600; 
            text-transform: uppercase; 
            font-size: 11px; 
            letter-spacing: 1.5px; 
            transition: color 0.2s;
        }

        .navbar .menu a:hover {
            color: var(--accent-gold);
        }
        
      
        .container { 
            max-width: 850px; 
            margin: 60px auto; 
            padding: 0 24px; 
        }
        
        h1 { 
            font-family: 'Playfair Display', serif; 
            font-weight: 400; 
            font-size: 32px; 
            margin-bottom: 40px; 
            letter-spacing: -0.5px;
        }
        
        .pedido-card { 
            background: var(--bg-card); 
            padding: 35px; 
            margin-bottom: 25px; 
            border: 1px solid var(--line-color); 
            box-shadow: 0 10px 30px rgba(26, 21, 18, 0.02);
        }
        
        .pedido-header { 
            display: flex; 
            justify-content: space-between; 
            align-items: baseline;
            border-bottom: 1px solid var(--line-color); 
            padding-bottom: 15px; 
            margin-bottom: 20px; 
        }
        
        .pedido-numero { 
            font-family: 'Playfair Display', serif;
            font-size: 20px; 
            font-weight: 400; 
        }
        
        .pedido-data { 
            color: var(--text-muted); 
            font-size: 12px; 
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .info-linha {
            font-size: 14px; 
            margin: 8px 0;
            color: var(--text-muted);
        }

        .info-linha strong {
            color: var(--text-dark);
            font-weight: 500;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 1px;
            display: inline-block;
            width: 70px;
        }
        
        
        .status-container {
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            margin-top: 30px; 
            padding-top: 20px; 
            border-top: 1px solid var(--line-color);
        }

        .status-badge {
            font-size: 13px;
            font-weight: 600;
        }

        .alerta-estorno {
            background-color: #f7f1ed;
            color: var(--accent-gold);
            border: 1px solid rgba(118, 74, 52, 0.15);
            padding: 10px 14px;
            margin-top: 10px;
            font-size: 12px;
            font-weight: 500;
            display: inline-block;
        }

        .btn-cancelar { 
            background: transparent; 
            color: var(--text-muted); 
            border: 1px solid var(--line-color); 
            padding: 10px 18px; 
            font-size: 11px; 
            text-transform: uppercase; 
            font-weight: 600; 
            letter-spacing: 1.5px;
            cursor: pointer; 
            text-decoration: none; 
            transition: all 0.3s ease; 
        }

        .btn-cancelar:hover { 
            border-color: var(--accent-gold);
            color: var(--accent-gold); 
            background-color: #faf6f2;
        }
        
        /* Tela de restrição / Login requerido */
        .card-restrito {
            text-align: center; 
            background: var(--bg-card); 
            padding: 60px 40px; 
            border: 1px solid var(--line-color);
        }

        .card-restrito p {
            font-size: 15px; 
            color: var(--text-muted); 
            margin-bottom: 25px;
        }

        .btn-login { 
            display: inline-block; 
            background: var(--accent-green); 
            color: var(--bg-canvas); 
            padding: 15px 30px; 
            text-decoration: none; 
            font-weight: 600; 
            text-transform: uppercase; 
            font-size: 11px; 
            letter-spacing: 2px;
            transition: background 0.3s;
        }

        .btn-login:hover { 
            background: var(--text-dark); 
        }
    </style>
</head>
<body>

    <nav class="navbar">
        <a href="index.php" class="logo">Maia Candle Co.</a>
        <div class="menu">
            <a href="index.php">Produtos</a>
            <a href="kits.php">Kits</a>
            <a href="pedidos.php" style="border-bottom: 1px solid var(--text-dark); padding-bottom: 4px;">Meus Pedidos</a>
            <a href="sobre.php">Sobre Nós</a>
            <a href="contato.php">Contato</a>
        </div>
    </nav>

    <div class="container">
        <h1>Seus Pedidos</h1>

        <?php if ($usuario_logado): ?>
            
            <?php if (!empty($pedidos_para_exibir)): ?>
                
                <?php foreach ($pedidos_para_exibir as $pedido): ?>
                    <div class="pedido-card">
                        <div class="pedido-header">
                            <span class="pedido-numero">Pedido #<?php echo (int)$pedido['numero']; ?></span>
                            <span class="pedido-data">Realizado em: <?php echo htmlspecialchars($pedido['data'], ENT_QUOTES, 'UTF-8'); ?></span>
                        </div>
                        
                        <p class="info-linha">
                            <strong>Itens</strong> <?php echo htmlspecialchars($pedido['itens'], ENT_QUOTES, 'UTF-8'); ?>
                        </p>
                        <p class="info-linha">
                            <strong>Total</strong> R$ <?php echo number_format($pedido['total'], 2, ',', '.'); ?>
                        </p>
                        
                        <div class="status-container">
                            <div>
                                <p style="margin: 0; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted);">
                                    Status: <span class="status-badge" style="color: <?php echo htmlspecialchars($pedido['cor_status'], ENT_QUOTES, 'UTF-8'); ?>;"><?php echo htmlspecialchars($pedido['status'], ENT_QUOTES, 'UTF-8'); ?></span>
                                </p>
                                
                                <?php if ($pedido['status'] === 'Cancelado'): ?>
                                    <div class="alerta-estorno">
                                        * O estorno do seu pagamento será processado em até 1 dia útil.
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <?php if ($pedido['status'] !== 'Cancelado' && $pedido['status'] !== 'Entregue'): ?>
                                <a href="pedidos.php?action=cancelar&numero=<?php echo urlencode($pedido['numero']); ?>" 
                                   class="btn-cancelar" 
                                   onclick="return confirm('Tem certeza que deseja solicitar o cancelamento do pedido #<?php echo (int)$pedido['numero']; ?>?');">
                                    Cancelar Pedido
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
                
            <?php else: ?>
                <p style="color: var(--text-muted); font-style: italic;">Você ainda não realizou nenhum pedido em nosso ateliê.</p>
            <?php endif; ?>

        <?php else: ?>
            
            <div class="card-restrito">
                <p>Para visualizar e acompanhar seus pedidos, por favor realize a autenticação em sua conta.</p>
                <a href="login.php" class="btn-login">Identificar-se</a>
            </div>

        <?php endif; ?>
    </div>
</body>
</html>