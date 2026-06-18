<?php


// Configura o PHP para reportar todos os tipos de erros (Essencial em ambiente de desenvolvimento)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Inicializa a sessão de forma segura se ela ainda não estiver ativa no servidor
if (session_status() === PHP_SESSION_NONE) { 
    session_start(); 
}

// Verifica a existência do arquivo de conexão antes de incluí-lo para evitar Fatal Errors catastróficos
if (file_exists('conexao.php')) { 
    require_once 'conexao.php'; 
} else { 
    die("Erro: 'conexao.php' não encontrado."); 
}

// Variáveis de controle para feedback visual das operações ao usuário
$mensagem = ""; 
$erro = "";

// Verifica se o formulário foi submetido via método POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
   
    // trim() remove espaços extras no início e fim. O operador '??' define um valor padrão caso o campo venha nulo.
    $nome = trim($_POST['nome'] ?? '');
    $aroma = trim($_POST['aroma'] ?? '');
    $tamanho = trim($_POST['tamanho'] ?? '');
    
    // Converte vírgulas em pontos para garantir que o valor seja interpretado corretamente como float/numeric no banco
    $preco = str_replace(',', '.', $_POST['preco'] ?? '0');
    
    // Força a conversão do estoque para um número inteiro puro (Segurança contra dados inválidos)
    $estoque = intval($_POST['estoque'] ?? 0);
    $nome_imagem = ""; 

    
    // Verifica se o arquivo foi enviado e se não houve nenhum erro no protocolo de transferência HTTP
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        
        // Extrai a extensão do arquivo original e converte para letras minúsculas
        $extensao = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
        
        // Whitelist (Lista Branca) de segurança de extensões aceitas pelo sistema
        $extensoes_permitidas = ['jpg', 'jpeg', 'png', 'webp'];

        if (in_array($extensao, $extensoes_permitidas)) {
            $pasta_destino = 'imagens/';
            
            // Cria o diretório de imagens dinamicamente com permissão de escrita, caso ele não exista na raiz
            if (!is_dir($pasta_destino)) { 
                mkdir($pasta_destino, 0777, true); 
            }

            // Gera um hash único baseado no tempo em microssegundos (Evita que imagens com o mesmo nome se sobrescrevam)
            $nome_imagem = uniqid('vela_') . '.' . $extensao;
            
            // Move o arquivo temporário armazenado pelo servidor para a pasta física definitiva
            move_uploaded_file($_FILES['foto']['tmp_name'], $pasta_destino . $nome_imagem);
        } else { 
            $erro = "Apenas imagens JPG, JPEG, PNG ou WEBP."; 
        }
    }

    
    // Só avança para o banco se não existirem erros de validação e se o nome do produto não estiver vazio
    if (empty($erro) && !empty($nome)) {
        try {
            // Sintaxe SQL utilizando "Prepared Statements" (:nome, :aroma...) para blindar a query contra SQL Injection
            $sql = "INSERT INTO velas (nome, aroma, tamanho, preco, estoque, imagem) VALUES (:nome, :aroma, :tamanho, :preco, :estoque, :imagem)";
            
            // Prepara a instrução SQL no servidor de banco de dados
            $stmt = $pdo->prepare($sql);
            
            // Executa a query injetando os valores higienizados e escapados com segurança nos placeholders
            $stmt->execute([
                ':nome' => $nome, 
                ':aroma' => $aroma, 
                ':tamanho' => $tamanho, 
                ':preco' => $preco, 
                ':estoque' => $estoque, 
                ':imagem' => $nome_imagem
            ]);
            
            $mensagem = "Vela cadastrada com sucesso! 🕯️";
        } catch (PDOException $e) { 
            // Captura qualquer exceção lançada pelo driver do PostgreSQL e isola o erro
            $erro = "Erro ao salvar: " . $e->getMessage(); 
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Vela | Maia Candle Co.</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">// Otimização de carregamento para fontes do Google
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..700;1,400..700&family=Plus+Jakarta+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <style>
        /* * DIRETRIZES DE DESIGN SYSTEM (Identidade Visual Aged Botanical)
         * Centralização de cores institucionais em variáveis nativas CSS para fácil manutenção.
         */
        :root {
            --bg-canvas: #f4f0ea;       /* Fundo cor de linho/pergaminho cru */
            --text-dark: #1a1512;       /* Preto carvão orgânico para alto contraste */
            --text-muted: #6e655f;      /* Cinza terroso para subtítulos e metadados */
            --accent-brown: #764a34;    /* Terracota/Marrom argila para botões de destaque */
            --accent-green: #3d5245;    /* Verde botânico profundo para elementos secundários */
            --line-color: rgba(26, 21, 18, 0.15); /* Linhas finas baseadas em opacidade */
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body { 
            background-color: var(--bg-canvas); 
            color: var(--text-dark); 
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* BARRA DE NAVEGAÇÃO DA RETAGUARDA (Área do Funcionário) */
        .navbar-adm { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            padding: 30px 6%; 
            background-color: transparent;
            border-bottom: 1px solid var(--line-color);
        }
        
        .logo-adm { 
            font-size: 20px; 
            font-weight: 600; 
            text-transform: uppercase; 
            letter-spacing: 3px; 
        }
        
        .menu-links { display: flex; gap: 40px; }
        
        .menu-links a { 
            text-decoration: none; 
            color: var(--text-muted); 
            font-size: 13px; 
            font-weight: 500; 
            text-transform: uppercase; 
            letter-spacing: 2px; 
            padding-bottom: 6px; 
            transition: all 0.3s ease;
        }
        
        .menu-links a:hover, .menu-links a.active { 
            color: var(--text-dark); 
            border-bottom: 1.5px solid var(--accent-brown); 
        }
        
        .btn-voltar-topo { 
            text-decoration: none; 
            color: var(--text-dark); 
            font-size: 13px; 
            letter-spacing: 1px;
            background: transparent; 
            padding: 10px 20px; 
            border: 1px solid var(--text-dark);
            border-radius: 0px; 
            transition: 0.3s ease;
        }
        
        .btn-voltar-topo:hover {
            background-color: var(--text-dark);
            color: var(--bg-canvas);
        }

        /* ESTRUTURA BI-PARTIDA ASYMMETRIC (Split Screen Layout) */
        .cadastro-main-wrapper {
            display: grid;
            grid-template-columns: 1fr 1.2fr; /* Lado visual ocupa 1 parte, lado do formulário ocupa 1.2 partes */
            min-height: calc(100vh - 82px);
        }

        /* COLUNA VISUAL: Gradiente Animado Estilo Aurora */
        .bloco-cadastro-visual { 
            background: linear-gradient(135deg, #c3b5a9 0%, #a3b899 50%, #764a34 100%);
            background-size: 200% 200%;
            animation: auroraMovement 15s ease infinite; /* Movimento orgânico lento em loop infinito */
            display: flex; 
            flex-direction: column; 
            justify-content: space-between; 
            padding: 80px 10%; 
            position: relative;
            overflow: hidden;
            border-right: 1px solid var(--line-color);
        }

        /* Camada de desfoque de vidro (Glassmorphism sutil) por cima do gradiente aurora */
        .bloco-cadastro-visual::after {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: backdrop-filter(blur(40px));
            background: rgba(244, 240, 234, 0.15);
            pointer-events: none;
        }
        
        /* Interpolação de posições de background para simular fluidos e luzes acesas */
        @keyframes auroraMovement {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .texto-lateral-cadastro { 
            font-size: 12px; 
            text-transform: uppercase; 
            letter-spacing: 5px; 
            color: var(--text-dark);
            font-weight: 600;
            z-index: 2; /* Garante que o texto fique acima do pseudo-elemento blur */
        }
        
        .titulo-grande-cadastro { 
            font-family: 'Playfair Display', serif;
            font-size: 56px; 
            font-weight: 400; 
            line-height: 1.1;
            letter-spacing: 1px; 
            color: var(--text-dark);
            z-index: 2;
        }

        /* COLUNA DA DIREITA: Formulário Editorial Minimalista */
        .formulario-secao {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 60px 12%;
            background-color: var(--bg-canvas);
        }

        .formulario-cadastro { 
            width: 100%;
            max-width: 520px;
            display: flex; 
            flex-direction: column; 
        }

        .titulo-interno-form {
            font-family: 'Playfair Display', serif;
            font-size: 28px;
            margin-bottom: 30px;
            font-weight: 500;
        }
        
        .campo-grupo { margin-bottom: 25px; position: relative; }
        
        .campo-grupo label { 
            display: block; 
            font-size: 11px; 
            text-transform: uppercase; 
            letter-spacing: 2px; 
            margin-bottom: 8px; 
            font-weight: 600; 
            color: var(--text-dark);
        }
        
        /* Inputs sem bordas fechadas, apenas linha inferior (Estilo alfaiataria/Figma Clean) */
        .campo-grupo input { 
            width: 100%; 
            padding: 14px 0px; 
            border: none; 
            border-bottom: 1px solid var(--text-dark); 
            background-color: transparent; 
            font-size: 15px; 
            color: var(--text-dark); 
            outline: none; 
            font-family: 'Plus Jakarta Sans', sans-serif;
            transition: border-color 0.3s ease;
        }

        .campo-grupo input:focus {
            border-bottom: 2px solid var(--accent-brown); /* Altera a cor e espessura da linha ao focar */
        }
        
        .grid-campos-duplos { display: grid; grid-template-columns: 1fr 1fr; gap: 30px; }
        
        /* Caixa Customizada para Upload de Imagens */
        .upload-wrapper {
            border: 1px dashed var(--text-dark);
            padding: 25px;
            text-align: center;
            margin-top: 10px;
            background-color: rgba(26, 21, 18, 0.02);
            transition: background 0.3s ease;
        }
        
        .upload-wrapper:hover {
            background-color: rgba(118, 74, 52, 0.05);
        }

        .upload-wrapper input[type="file"] {
            border: none;
            padding: 5px 0;
            font-size: 13px;
            color: var(--text-muted);
            cursor: pointer;
        }

        .btn-submit-cadastro { 
            background-color: var(--accent-brown); 
            border: none; 
            color: var(--bg-canvas); 
            padding: 18px; 
            font-size: 13px; 
            font-weight: 600; 
            text-transform: uppercase; 
            letter-spacing: 2px; 
            cursor: pointer; 
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1); 
            margin-top: 20px; 
        }
        
        .btn-submit-cadastro:hover { 
            background-color: var(--text-dark);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            transform: translateY(-1px);
        }
    </style>
</head>
<body>

    <nav class="navbar-adm">
        <div class="logo-adm">Maia Candle Co.</div>
        <div class="menu-links">
            <a href="painel_funcionario.php">ESTOQUE</a>
            <a href="painel_funcionario.php#quantidade">QUANTIDADE</a>
            <a href="cadastrar_vela.php" class="active">CADASTRAR</a>
        </div>
        <a href="painel_funcionario.php" class="btn-voltar-topo">Painel Gerencial 📊</a>
    </nav>

    <div class="cadastro-main-wrapper">
        <div class="bloco-cadastro-visual">
            <div class="texto-lateral-cadastro">Inventory Archive</div>
            <h1 class="titulo-grande-cadastro">Cadastro de<br>Nova Vela</h1>
            <div class="texto-lateral-cadastro">Aged Botanical Edition</div>
        </div>

        <div class="formulario-secao">
            <div class="formulario-cadastro">
                <h2 class="titulo-interno-form">Detalhes do Produto</h2>

                <?php if(!empty($mensagem)): ?><div class="alerta sucesso"><?= $mensagem; ?></div><?php endif; ?>
                <?php if(!empty($erro)): ?><div class="alerta erro"><?= $erro; ?></div><?php endif; ?>

                <form action="cadastrar_vela.php" method="POST" enctype="multipart/form-data">
                    <div class="campo-grupo">
                        <label>Nome da Vela</label>
                        <input type="text" name="nome" placeholder="Ex: Autumn Embers" required>
                    </div>
                    
                    <div class="campo-grupo">
                        <label>Fragrância / Notas Olfativas</label>
                        <input type="text" name="aroma" placeholder="Ex: Cedro, Canela & Cravo" required>
                    </div>
                    
                    <div class="grid-campos-duplos">
                        <div class="campo-grupo">
                            <label>Tamanho / Peso</label>
                            <input type="text" name="tamanho" placeholder="Ex: 240g" required>
                        </div>
                        <div class="campo-grupo">
                            <label>Preço de Venda (R$)</label>
                            <input type="text" name="preco" placeholder="Ex: 68.00" required>
                        </div>
                    </div>
                    
                    <div class="campo-grupo">
                        <label>Quantidade Inicial em Estoque</label>
                        <input type="number" name="estoque" value="10" min="0" required>
                    </div>
                    
                    <div class="campo-grupo">
                        <label>Fotografia Editorial</label>
                        <div class="upload-wrapper">
                            <input type="file" name="foto" accept="image/*" required>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn-submit-cadastro">Salvar Criação no Banco</button>
                </form>
            </div>
        </div>
    </div>

</body>
</html>