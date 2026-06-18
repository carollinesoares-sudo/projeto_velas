<?php
// login.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$erro = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email_digitado = $_POST['email'] ?? '';
    $senha_digitada = $_POST['senha'] ?? '';

    
    $email_salvo = $_SESSION['cadastro_email'] ?? null;
    $senha_salva = $_SESSION['cadastro_senha'] ?? null;

    if ($email_digitado === $email_salvo && $senha_digitada === $senha_salva && $email_salvo !== null) {
        $_SESSION['cliente_logado'] = true;
        $_SESSION['cliente_nome'] = $_SESSION['cadastro_nome'] ?? 'Cliente';
        
        header('Location: produtos.php');
        exit;
    } else {
        $erro = "E-mail ou senha incorretos! Crie uma conta primeiro ou verifique os dados.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Maia Candle Co.</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #fcfbf9; 
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .box {
            background: #ffffff;
            padding: 50px 40px;
            border-radius: 8px;
            box-shadow: 0 4px 25px rgba(0, 0, 0, 0.04); 
            max-width: 440px;
            width: 100%;
            border-top: 4px solid #c9bfb5; 
        }

        .cabecalho-login {
            text-align: center;
            margin-bottom: 35px;
        }

        .cabecalho-login h1 {
            font-family: "Georgia", serif;
            font-size: 24px;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #1a1512;
            margin-bottom: 5px;
        }

        .cabecalho-login p {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #8c8275;
        }

        .erro-box {
            background: #f8d7da;
            color: #721c24;
            padding: 12px;
            border: 1px solid #f5c6cb;
            font-size: 13px;
            margin-bottom: 25px;
            text-align: center;
            border-radius: 4px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #1a1512;
        }

        .form-control {
            width: 100%;
            padding: 14px;
            border: 1px solid #e6e1da;
            background-color: #fdfdfd;
            border-radius: 4px;
            font-size: 14px;
            color: #1a1512;
            outline: none;
            transition: 0.2s;
        }

        .form-control:focus {
            border-color: #1a1512;
            background-color: #ffffff;
        }

        .btn {
            background: #1a1512; 
            color: #ffffff;
            border: none;
            padding: 16px;
            width: 100%;
            font-size: 13px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
            cursor: pointer;
            border-radius: 4px;
            transition: 0.2s;
            margin-top: 5px;
        }

        .btn:hover {
            background: #332924;
        }

        .links {
            margin-top: 30px;
            font-size: 13px;
            display: flex;
            justify-content: space-between;
            border-top: 1px solid #f2ebe4;
            padding-top: 20px;
        }

        .links a {
            color: #1a1512;
            text-decoration: none;
            font-weight: 500;
        }

        .links a:hover {
            text-decoration: underline;
        }

        .btn-voltar {
            display: block;
            text-align: center;
            margin-top: 25px;
            font-size: 12px;
            color: #8c8275;
            text-decoration: none;
            transition: 0.2s;
        }

        .btn-voltar:hover {
            color: #1a1512;
        }
    </style>
</head>
<body>

    <div class="box">
        <div class="cabecalho-login">
            <h1>MAIA CANDLE CO.</h1>
            <p>Acessar Minha Conta</p>
        </div>

        <?php if (!empty($erro)): ?>
            <div class="erro-box"><?php echo $erro; ?></div>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <div class="form-group">
                <label>E-mail</label>
                <input type="email" name="email" class="form-control" placeholder="seuemail@exemplo.com" required>
            </div>
            
            <div class="form-group">
                <label>Senha</label>
                <input type="password" name="senha" class="form-control" placeholder="Digite sua senha" required>
            </div>
            
            <button type="submit" class="btn">Entrar na Conta</button>
        </form>

        <div class="links">
            <a href="cadastrar.php">Criar uma conta</a>
            <a href="redefinir.php" style="color: #8c8275;">Esqueci minha senha</a>
        </div>

        
        <a href="index.php" class="btn-voltar">← Voltar para a Início</a>
    </div>

</body>
</html>