<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$mensagem = "";
$tipo = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email_digitado = $_POST['email'] ?? '';
    $nova_senha = $_POST['nova_senha'] ?? '';

    $email_salvo = $_SESSION['cadastro_email'] ?? null;

    if ($email_digitado === $email_salvo && $email_salvo !== null) {
        // Substitui a senha antiga pela nova na sessão
        $_SESSION['cadastro_senha'] = $nova_senha;

        $mensagem = "Senha alterada com sucesso! Redirecionando para o login...";
        $tipo = "sucesso";
        echo "<script>setTimeout(function(){ window.location.href = 'login.php'; }, 2000);</script>";
    } else {
        $mensagem = "E-mail não encontrado no sistema!";
        $tipo = "erro";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinir Senha | Maia Candle Co.</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght=0,400..700;1,400..700&family=Plus+Jakarta+Sans:wght=300;400;500;600&display=swap" rel="stylesheet">

    <style>
        
        :root {
            --bg-canvas: #f4f0ea;    /* Tom areia clássico */
            --bg-card: #fdfbfa;      /* Fundo puro do bloco */
            --text-dark: #1a1512;    /* Marrom escuro botânico */
            --text-muted: #6e655f;   /* Legendagem terrosa */
            --accent-green: #3d5245; /* Verde assinatura Maia Candle */
            --accent-gold: #764a34;  /* Tom bronze/terracota */
            --line-color: rgba(26, 21, 18, 0.15);
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
            justify-content: center; 
            align-items: center; 
            min-height: 100vh;
            padding: 20px;
            -webkit-font-smoothing: antialiased;
        }

       
        .box { 
            background: var(--bg-card); 
            padding: 45px 35px; 
            border: 1px solid var(--line-color); 
            max-width: 420px; 
            width: 100%; 
            box-shadow: 0 15px 35px rgba(26, 21, 18, 0.03); 
        }
        
        h2 { 
            font-family: 'Playfair Display', serif; 
            font-weight: 400;
            text-transform: uppercase; 
            letter-spacing: 0.5px; 
            font-size: 20px; 
            border-bottom: 1px solid var(--text-dark); 
            padding-bottom: 12px; 
            margin-bottom: 25px;
        }

       
        .form-group { 
            margin-bottom: 20px; 
        }
        
        .form-group label { 
            display: block; 
            margin-bottom: 8px; 
            font-size: 11px; 
            font-weight: 600; 
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--text-muted);
        }
        
        .form-control { 
            width: 100%; 
            padding: 12px 14px; 
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 14px;
            background-color: transparent;
            border: 1px solid var(--line-color); 
            color: var(--text-dark);
            transition: border-color 0.3s ease;
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--accent-green);
        }
        
        
        .btn { 
            background: var(--accent-green); 
            color: #fff; 
            border: none; 
            padding: 14px; 
            width: 100%; 
            font-weight: 600; 
            text-transform: uppercase; 
            letter-spacing: 1.5px;
            font-size: 12px;
            cursor: pointer; 
            transition: background 0.3s;
            margin-top: 5px;
        }
        
        .btn:hover { 
            background: var(--text-dark); 
        }

       
        .msg { 
            padding: 12px; 
            margin-bottom: 20px; 
            text-align: center; 
            font-size: 13px; 
            font-weight: 500;
        }
        
        .erro { 
            background: #f7f1ed; 
            color: var(--accent-gold); 
            border: 1px solid rgba(118, 74, 52, 0.15);
        }
        
        .sucesso { 
            background: #ebf0ec; 
            color: var(--accent-green); 
            border: 1px solid rgba(61, 82, 69, 0.15);
        }

        .link-retorno {
            text-align: center; 
            font-size: 12px; 
            margin-top: 25px; 
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .link-retorno a {
            color: var(--text-muted);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s;
        }

        .link-retorno a:hover {
            color: var(--text-dark);
        }
    </style>
</head>
<body>

    <div class="box">
        <h2>Recuperar Acesso</h2>
        
        <?php if(!empty($mensagem)): ?>
            <div class="msg <?php echo $tipo; ?>">
                <?php echo $mensagem; ?>
            </div>
        <?php endif; ?>
        
        <form action="redefinir.php" method="POST">
            <div class="form-group">
                <label>E-mail Cadastrado</label>
                <input type="email" name="email" class="form-control" required autocomplete="email">
            </div>
            
            <div class="form-group">
                <label>Nova Senha</label>
                <input type="password" name="nova_senha" class="form-control" required>
            </div>
            
            <button type="submit" class="btn">Alterar Senha</button>
        </form>
        
        <div class="link-retorno">
            <a href="login.php">&larr; Voltar ao Login</a>
        </div>
    </div>

</body>
</html>