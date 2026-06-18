<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    unset($_SESSION['funcionario_logado']);
    unset($_SESSION['funcionario_nome']);
    session_destroy();
    header("Location: login_funcionario.php");
    exit;
}

$erro = "";


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $senha = trim($_POST['senha'] ?? '');

    
    if ($email === 'admin@maiacandle.com.br' && $senha === 'admin123') {
        $_SESSION['funcionario_logado'] = true;
        $_SESSION['funcionario_nome'] = 'Administrador Maia';
        
        header("Location: painel_funcionario.php");
        exit;
    } else {
        $erro = "Credenciais incorretas para o painel administrativo.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Administrativo | Maia Candle Co.</title>
    
   
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..700;1,400..700&family=Plus+Jakarta+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        
        :root {
            --bg-canvas: #f4f0ea;    /* Tom areia clássico */
            --bg-card: #fdfbfa;      /* Fundo do card interno */
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
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
            -webkit-font-smoothing: antialiased;
        }

        
        .card-login {
            max-width: 420px;
            width: 100%;
            background: var(--bg-card);
            padding: 50px 40px;
            border: 1px solid var(--line-color);
            box-shadow: 0 12px 40px rgba(26, 21, 18, 0.03);
            position: relative;
        }

        .logo-adm {
            text-align: center;
            font-family: 'Playfair Display', serif;
            font-size: 28px;
            font-weight: 400;
            letter-spacing: -0.5px;
            margin-bottom: 6px;
        }

        .subtitulo-adm {
            text-align: center;
            font-size: 11px;
            color: var(--accent-gold);
            text-transform: uppercase;
            letter-spacing: 2px;
            font-weight: 600;
            margin-bottom: 40px;
        }

       
        .erro-alerta {
            background-color: #f7e9e9;
            color: #8a3b3b;
            border: 1px solid rgba(138, 59, 59, 0.15);
            padding: 14px;
            margin-bottom: 25px;
            text-align: center;
            font-size: 13px;
            font-weight: 500;
        }

        
        .form-group {
            margin-bottom: 26px;
            display: flex;
            flex-direction: column;
            border-bottom: 1px solid var(--text-dark);
            padding-bottom: 6px;
        }

        .form-group label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            font-weight: 600;
            color: var(--text-muted);
            margin-bottom: 6px;
        }

        .form-group input {
            width: 100%;
            border: none;
            background: transparent;
            font-family: inherit;
            font-size: 15px;
            color: var(--text-dark);
            outline: none;
            padding: 2px 0;
        }
        
        
        .form-group input::placeholder {
            color: rgba(26, 21, 18, 0.3);
            font-weight: 300;
        }

        
        .btn-acessar {
            width: 100%;
            padding: 16px;
            background-color: var(--accent-green);
            color: var(--bg-canvas);
            border: none;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 2px;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 15px;
        }

        .btn-acessar:hover {
            background-color: var(--text-dark);
            transform: translateY(-1px);
        }

        .link-voltar {
            display: block;
            text-align: center;
            margin-top: 35px;
            font-size: 11px;
            font-weight: 600;
            color: var(--text-muted);
            text-decoration: none;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: color 0.2s;
        }

        .link-voltar:hover {
            color: var(--text-dark);
        }
    </style>
</head>
<body>

    <div class="card-login">
        <h2 class="logo-adm">Maia Candle Co.</h2>
        <p class="subtitulo-adm">Espaço Administrativo</p>

        <?php if(!empty($erro)): ?>
            <div class="erro-alerta">
                <?php echo $erro; ?>
            </div>
        <?php endif; ?>

        <form action="login_funcionario.php" method="POST">
            <div class="form-group">
                <label>E-mail Corporativo</label>
                <input type="email" name="email" placeholder="nome@maiacandle.com.br" required autocomplete="email">
            </div>
            
            <div class="form-group">
                <label>Senha de Acesso</label>
                <input type="password" name="senha" placeholder="••••••••••••" required autocomplete="current-senha">
            </div>
            
            <button type="submit" class="btn-acessar">
                Autenticar Credenciais
            </button>
        </form>

        <a href="index.php" class="link-voltar">← Retornar ao Portal inicial</a>
    </div>

</body>
</html>