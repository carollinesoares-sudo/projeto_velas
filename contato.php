<?php
// contato.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'conexao.php'; 

$mensagem_sucesso = "";
$mensagem_erro = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // recebe e limpa os dados
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $mensagem = trim($_POST['mensagem'] ?? '');

    if (!empty($nome) && !empty($email) && !empty($mensagem)) {
        try {
            // salva no banco
            $sql = "INSERT INTO contatos (nome, email, mensagem, data_envio) VALUES (:nome, :email, :mensagem, NOW())";
            $stmt = $pdo->prepare($sql);
            
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':mensagem', $mensagem);
            
            $stmt->execute();
            
            $mensagem_sucesso = "Sua mensagem foi enviada para a Maia Candle Co.! Entraremos em contato em breve. 🌿";
        } catch (PDOException $e) {
            $mensagem_erro = "Ops! Ocorreu um erro ao salvar sua mensagem. Tente novamente mais tarde.";
        }
    } else {
        $mensagem_erro = "Por favor, preencha todos os campos do formulário.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fale Conosco | Maia Candle Co.</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..700;1,400..700&family=Plus+Jakarta+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg-canvas: #f4f0ea; /* Fundo areia clássico */
            --text-dark: #1a1512;   /* Marrom escuro quase preto */
            --text-muted: #6e655f;  /* Tom cinza terroso para descrições */
            --accent-green: #3d5245; /* Verde botânico do Figma */
            --line-color: rgba(26, 21, 18, 0.15);
            --error-color: #8a3324;  /* Tom avermelhado sofisticado para erro */
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
            font-size: 22px; 
            font-weight: 600; 
            text-decoration: none; 
            color: var(--text-dark); 
            text-transform: uppercase; 
            letter-spacing: 3px; 
        }
        .navbar .menu a { 
            margin: 0 18px; 
            text-decoration: none; 
            color: var(--text-muted); 
            font-weight: 500; 
            text-transform: uppercase; 
            font-size: 12px; 
            letter-spacing: 1.5px; 
            transition: color 0.3s;
        }
        .navbar .menu a:hover { 
            color: var(--text-dark); 
        }
        .nav-right { 
            display: flex; 
            align-items: center; 
            font-size: 13px; 
            gap: 20px; 
            text-transform: uppercase; 
            letter-spacing: 1px; 
        }
        .nav-right a { 
            color: var(--text-dark); 
            text-decoration: none; 
            font-weight: 500; 
        }

        .contact-container { 
            max-width: 950px; 
            margin: 60px auto; 
            padding: 0 30px; 
            text-align: center; 
        }
        
        h1 { 
            font-family: 'Playfair Display', serif;
            font-size: 46px; 
            font-weight: 400; 
            margin-bottom: 6px; 
            letter-spacing: -0.5px;
        }
        
        .subtitle { 
            font-size: 12px; 
            text-transform: uppercase;
            letter-spacing: 2px;
            color: var(--text-muted);
            font-weight: 600;
            margin-bottom: 25px; 
        }
        
        .description { 
            font-size: 15px; 
            color: var(--text-muted);
            max-width: 580px; 
            margin: 0 auto 40px auto; 
            line-height: 1.6; 
        }

        /* 💬 Estilização dos Alertas de Feedback */
        .alert {
            padding: 16px;
            margin-bottom: 40px;
            font-size: 14px;
            text-align: left;
            letter-spacing: 0.5px;
        }
        .alert-success {
            background-color: rgba(61, 82, 69, 0.1);
            border-left: 4px solid var(--accent-green);
            color: var(--accent-green);
        }
        .alert-danger {
            background-color: rgba(138, 51, 36, 0.1);
            border-left: 4px solid var(--error-color);
            color: var(--error-color);
        }

        .form-header-line { 
            border-top: 1px solid var(--text-dark); 
            border-bottom: 1px solid var(--text-dark); 
            padding: 14px 10px; 
            text-align: left; 
            font-size: 11px; 
            text-transform: uppercase; 
            letter-spacing: 2px; 
            font-weight: 600;
            margin-bottom: 50px; 
        }

        .flex-container { 
            display: grid;
            grid-template-columns: 1.3fr 1fr;
            gap: 80px;
            text-align: left; 
            margin-bottom: 50px; 
        }
        
        .form-box { 
            width: 100%; 
        }
        
        .form-group { 
            display: flex; 
            flex-direction: column;
            margin-bottom: 30px; 
            border-bottom: 1px solid var(--text-dark); 
            padding-bottom: 8px; 
        }
        
        .form-group label { 
            font-size: 11px; 
            text-transform: uppercase;
            letter-spacing: 1.5px;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 6px;
        }
        
        .form-group input { 
            width: 100%;
            background: transparent; 
            border: none; 
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 16px; 
            outline: none; 
            padding: 4px 0; 
            color: var(--text-dark); 
        }

        .side-contacts { 
            padding-top: 5px; 
            border-left: 1px solid var(--line-color);
            padding-left: 40px;
        }
        
        .side-contacts-title { 
            font-size: 11px; 
            font-weight: 600; 
            text-transform: uppercase; 
            margin-bottom: 25px; 
            letter-spacing: 2px; 
            color: var(--text-dark);
        }
        
        .contact-item { 
            display: flex; 
            align-items: center; 
            margin-bottom: 20px; 
            font-size: 14px; 
            color: var(--text-muted);
        }
        
        .contact-icon { 
            font-size: 16px; 
            margin-right: 15px; 
            color: var(--text-dark);
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-container { 
            width: 100%; 
            text-align: left; 
            margin-top: 10px; 
        }
        
        .btn-submit { 
            background-color: var(--accent-green); 
            color: var(--bg-canvas);
            border: none; 
            padding: 16px 60px; 
            font-size: 12px; 
            font-weight: 600;
            text-transform: uppercase; 
            letter-spacing: 2px; 
            cursor: pointer; 
            transition: all 0.3s ease;
        }
        
        .btn-submit:hover {
            background-color: var(--text-dark);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        }

        @media (max-width: 768px) {
            .flex-container {
                grid-template-columns: 1fr;
                gap: 40px;
            }
            .side-contacts {
                border-left: none;
                padding-left: 0;
                border-top: 1px solid var(--line-color);
                padding-top: 30px;
            }
        }
    </style>
</head>
<body>

    <nav class="navbar">
        <a href="index.php" class="logo">Maia Candle Co.</a>
        <div class="menu">
            <a href="produtos.php">Produtos</a>
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

    <main class="contact-container">
        <h1>Fale conosco</h1>
        <div class="subtitle">Atendimento exclusivo</div>
        <p class="description">
            Deseja tirar alguma dúvida sobre nossas essências ou acompanhar um lote especial? Preencha os campos abaixo e nosso concierge botânico retornará em até 24 horas úteis.
        </p>

        <?php if (!empty($mensagem_sucesso)): ?>
            <div class="alert alert-success"><?php echo $mensagem_sucesso; ?></div>
        <?php endif; ?>

        <?php if (!empty($mensagem_erro)): ?>
            <div class="alert alert-danger"><?php echo $mensagem_erro; ?></div>
        <?php endif; ?>

        <div class="form-header-line">
            Formulário de Atendimento
        </div>

        <form action="contato.php" method="POST">
            <div class="flex-container">
                
                <div class="form-box">
                    <div class="form-group">
                        <label for="nome">Seu Nome Completo</label>
                        <input type="text" id="nome" name="nome" placeholder="Ex: Clara Mendes" required>
                    </div>
                    <div class="form-group">
                        <label for="email">E-mail de Contato</label>
                        <input type="email" id="email" name="email" placeholder="clara@exemplo.com" required>
                    </div>
                    <div class="form-group">
                        <label for="mensagem">Assunto ou Mensagem</label>
                        <input type="text" id="mensagem" name="mensagem" placeholder="Como podemos te ajudar?" required>
                    </div>
                    
                    <div class="btn-container">
                        <button type="submit" class="btn-submit">Enviar Mensagem</button>
                    </div>
                </div>

                <div class="side-contacts">
                    <div class="side-contacts-title">Canais Oficiais</div>
                    
                    <div class="contact-item">
                        <span class="contact-icon">
                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.9 3.9 0 0 0-1.417.923A3.9 3.9 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.9 3.9 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.444-.048-3.298c-.04-.851-.175-1.433-.372-1.941a3.9 3.9 0 0 0-.923-1.417A3.9 3.9 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599s.453.546.598.92c.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.5 2.5 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.5 2.5 0 0 1-.92-.598 2.5 2.5 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233s.008-2.388.046-3.231c.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92.28-.28.546-.453.92-.598.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92m-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217m0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334"/></svg>
                        </span> 
                        <span>@maiacandleco</span>
                    </div>
                    
                    <div class="contact-item">
                        <span class="contact-icon">
                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1zm13 2.383-4.708 2.825L15 11.105zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741M1 11.105l4.708-2.897L1 5.383z"/></svg>
                        </span> 
                        <span>contato@maiacandleco.com</span>
                    </div>
                </div>

            </div>
        </form>
    </main>

</body>
</html>