<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$mensagem = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';

    if (!empty($email) && !empty($senha)) {
        // salva na sessão
        $_SESSION['cadastro_email'] = $email;
        $_SESSION['cadastro_senha'] = $senha;
        $_SESSION['cadastro_nome'] = $_POST['nome'] ?? 'Cliente';

        $mensagem = "Conta criada com sucesso! Redirecionando para o login... 🕯️";
        
        echo "<script>setTimeout(function(){ window.location.href = 'login.php'; }, 2000);</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Criar Conta | Maia Candle Co.</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2 family=Playfair+Display:ital,wght@0,400..700;1,400..700&family=Plus+Jakarta+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <style>
        
        :root {
            --bg-canvas: #f4f0ea;    /* Fundo creme de linho cru */
            --bg-card: #fdfbfa;      /* Fundo contrastante do formulário (Papel Puro) */
            --text-dark: #1a1512;    /* Tom de preto carvão para leitura confortável */
            --text-muted: #6e655f;   /* Marrom/cinza fosco para textos secundários */
            --accent-green: #3d5245; /* Verde botânico oficial do protótipo do Figma */
            --line-color: rgba(26, 21, 18, 0.15); /* Divisórias sutis com canal alfa */
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
        }

        /* CARD DE AUTENTICAÇÃO CENTRALIZADO */
        .box { 
            background: var(--bg-card); 
            padding: 50px 40px; 
            border: 1px solid var(--line-color); 
            max-width: 440px; 
            width: 100%; 
            box-shadow: 0 4px 20px rgba(26, 21, 18, 0.03);
            position: relative;
        }

        /* Chancelaria da marca aplicada via pseudo-elemento CSS (Watermark elegante) */
        .box::before {
            content: "MAIA CANDLE CO.";
            position: absolute;
            top: 20px;
            left: 40px;
            font-size: 10px;
            letter-spacing: 3px;
            color: var(--text-muted);
            font-weight: 500;
        }

        h2 { 
            font-family: 'Playfair Display', serif; 
            text-transform: capitalize; 
            font-size: 28px; 
            font-weight: 400;
            margin-top: 10px;
            margin-bottom: 30px; 
            padding-bottom: 12px;
            border-bottom: 1px solid var(--line-color);
        }

        .form-group { 
            margin-bottom: 25px; 
        }

        .form-group label { 
            display: block; 
            margin-bottom: 6px; 
            font-size: 11px; 
            text-transform: uppercase;
            letter-spacing: 1.5px;
            font-weight: 600; 
            color: var(--text-dark);
        }

        /*inputs minimalistas baseados apenas em bordas inferiores, simulando o Figma */
        .form-control { 
            width: 100%; 
            padding: 12px 0px; 
            border: none;
            border-bottom: 1px solid var(--text-dark); 
            background-color: transparent;
            font-size: 15px;
            color: var(--text-dark);
            outline: none;
            font-family: 'Plus Jakarta Sans', sans-serif;
            transition: all 0.3s ease;
        }

        /* Feedback visual focado mudando para o verde botânico da marca */
        .form-control:focus { 
            border-bottom: 1.5px solid var(--accent-green); 
        }

        /* Mensagem de alerta renderizada dinamicamente pelo backend */
        .sucesso { 
            background: #e2ede4; 
            color: var(--accent-green); 
            border: 1px solid rgba(61, 82, 69, 0.2);
            padding: 12px; 
            margin-bottom: 25px; 
            text-align: center; 
            font-size: 13px;
        }

        /* BOTÃO INTERATIVO PRINCIPAL */
        .btn { 
            background: var(--accent-green); 
            color: var(--bg-canvas); 
            border: none; 
            padding: 16px; 
            width: 100%; 
            font-weight: 600; 
            text-transform: uppercase; 
            letter-spacing: 2px;
            font-size: 13px;
            cursor: pointer; 
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        .btn:hover { 
            background: var(--text-dark);
            transform: translateY(-1px); /* Pequena elevação tátil no hover */
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .link-retorno {
            text-align: center; 
            font-size: 13px; 
            margin-top: 25px;
        }

        .link-retorno a {
            color: var(--text-muted);
            text-decoration: none;
            border-bottom: 1px solid transparent;
            transition: all 0.3s ease;
        }

        .link-retorno a:hover {
            color: var(--text-dark);
            border-bottom: 1px solid var(--text-dark);
        }
    </style>
</head>
<body>

    <div class="box">
        <h2>Criar conta</h2>
        
        <?php if(!empty($mensagem)): ?>
            <div class="sucesso"><?php echo $mensagem; ?></div>
        <?php endif; ?>
        
        <form action="cadastrar.php" method="POST">
            <div class="form-group">
                <label>Nome Completo</label>
                <input type="text" name="nome" class="form-control" placeholder="Ex: Ana Silva" required>
            </div>
            
            <div class="form-group">
                <label>E-mail</label>
                <input type="email" name="email" class="form-control" placeholder="seuemail@exemplo.com" required>
            </div>
            
            <div class="form-group">
                <label>Senha de Acesso</label>
                <input type="password" name="senha" class="form-control" placeholder="••••••••" required>
            </div>
            
            <button type="submit" class="btn">Criar Conta</button>
        </form>
        
        <p class="link-retorno">
            <a href="login.php">Já possui uma conta? Realizar login</a>
        </p>
    </div>

</body>
</html>