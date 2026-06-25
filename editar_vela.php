<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


if (!isset($_SESSION['funcionario_logado']) || $_SESSION['funcionario_logado'] !== true) {
    header("Location: login_funcionario.php");
    exit;
}

if (file_exists('conexao.php')) { 
    require_once 'conexao.php'; 

    $pdo->exec("SET client_encoding TO 'UTF8'");
} else { 
    die("Erro crítico: O arquivo 'conexao.php' não foi encontrado na raiz."); 
}

$mensagem = "";
$erro = "";

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: painel_funcionario.php");
    exit;
}

$id = intval($_GET['id']);


$vela = [];
try {
    $stmt = $pdo->prepare("SELECT * FROM velas WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $dados_vela = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($dados_vela) {
        $vela = $dados_vela;
    }
} catch (PDOException $e) {
    die("Erro ao buscar dados: " . $e->getMessage());
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $aroma = trim($_POST['aroma'] ?? '');
    $tamanho = trim($_POST['tamanho'] ?? '');
    $preco = str_replace(',', '.', $_POST['preco'] ?? '0');
    $estoque = intval($_POST['estoque'] ?? 0);
    
   
    $nome_imagem = (!empty($vela['imagem'])) ? $vela['imagem'] : 'default.jpg';
    $cor = (!empty($vela['cor'])) ? $vela['cor'] : 'Padrão';

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $extensao = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
        $extensoes_permitidas = ['jpg', 'jpeg', 'png', 'webp'];

        if (in_array($extensao, $extensoes_permitidas)) {
            $pasta_destino = 'imagens/';
            if (!is_dir($pasta_destino)) {
                mkdir($pasta_destino, 0777, true);
            }
            
            $nome_imagem = uniqid('vela_') . '.' . $extensao;
            move_uploaded_file($_FILES['foto']['tmp_name'], $pasta_destino . $nome_imagem);
            
            if (!empty($vela['imagem']) && file_exists($pasta_destino . $vela['imagem']) && !str_contains($vela['imagem'], '.jpg')) {
                @unlink($pasta_destino . $vela['imagem']);
            }
        } else {
            $erro = "Apenas imagens JPG, JPEG, PNG ou WEBP são aceitas.";
        }
    }

    if (empty($erro) && !empty($nome)) {
        try {
            
            $sql = "UPDATE velas SET nome = :nome, aroma = :aroma, tamanho = :tamanho, preco = :preco, estoque = :estoque, imagem = :imagem, cor = :cor WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':nome' => $nome,
                ':aroma' => $aroma,
                ':tamanho' => $tamanho,
                ':preco' => $preco,
                ':estoque' => $estoque,
                ':imagem' => $nome_imagem,
                ':cor' => $cor,
                ':id' => $id
            ]);
            
            $mensagem = "Vela atualizada com sucesso! ✨";
            
            $vela['nome'] = $nome;
            $vela['aroma'] = $aroma;
            $vela['tamanho'] = $tamanho;
            $vela['preco'] = $preco;
            $vela['estoque'] = $estoque;
            $vela['imagem'] = $nome_imagem;
            $vela['cor'] = $cor;
            
        } catch (PDOException $e) {
            $erro = "Erro ao atualizar dados: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Vela | Curadoria Maia Candle Co.</title>
    
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
            --accent-brown: #764a34;
            --line-color: rgba(26, 21, 18, 0.12);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: var(--bg-canvas); 
            color: var(--text-dark); 
            -webkit-font-smoothing: antialiased; 
        }

        
        .navbar-adm { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            padding: 25px 6%; 
            background-color: transparent; 
            border-bottom: 1px solid var(--line-color);
        }
        .logo-adm { 
            font-size: 14px; 
            font-weight: 600; 
            text-transform: uppercase; 
            letter-spacing: 3px; 
            color: var(--text-dark);
        }
        .menu-links { display: flex; gap: 30px; }
        .menu-links a { 
            text-decoration: none; 
            color: var(--text-muted); 
            font-size: 11px; 
            font-weight: 600; 
            text-transform: uppercase; 
            letter-spacing: 1.5px; 
            transition: color 0.3s;
        }
        .menu-links a:hover { color: var(--text-dark); }
        
        .btn-voltar { 
            text-decoration: none; 
            color: var(--text-dark); 
            font-size: 11px; 
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            border-bottom: 1px solid var(--text-dark);
            padding-bottom: 2px;
            transition: color 0.3s, border-color 0.3s;
        }
        .btn-voltar:hover { color: var(--accent-brown); border-color: var(--accent-brown); }

        
        .editar-container { 
            max-width: 1100px; 
            margin: 60px auto; 
            display: grid; 
            grid-template-columns: 1fr 1.4fr; 
            gap: 70px; 
            padding: 0 30px; 
        }

        
        .bloco-imagem-visual { display: flex; flex-direction: column; align-items: flex-start; }
        
        .quadrado-foto { 
            width: 100%; 
            aspect-ratio: 4/5; 
            background-color: var(--bg-card); 
            border: 1px solid var(--line-color); 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            overflow: hidden; 
            position: relative; 
            box-shadow: 0 8px 25px rgba(26, 21, 18, 0.02);
            margin-bottom: 20px;
        }
        .quadrado-foto img { width: 100%; height: 100%; object-fit: cover; }
        
        .sem-foto { 
            font-size: 11px; 
            text-transform: uppercase; 
            letter-spacing: 1.5px; 
            color: var(--text-muted); 
            text-align: center; 
            line-height: 1.6;
        }

        
        .upload-btn-wrapper {
            position: relative;
            overflow: hidden;
            display: inline-block;
            width: 100%;
        }
        .btn-upload-custom {
            border: 1px solid var(--text-dark);
            color: var(--text-dark);
            background-color: transparent;
            padding: 12px 20px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            width: 100%;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
        }
        .upload-btn-wrapper:hover .btn-upload-custom {
            background-color: var(--text-dark);
            color: var(--bg-canvas);
        }
        .upload-btn-wrapper input[type=file] {
            font-size: 100px;
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
            cursor: pointer;
        }
        
        .upload-hint {
            font-size: 11px; 
            color: var(--text-muted); 
            margin-top: 8px;
        }

        
        .formulario-editar { display: flex; flex-direction: column; }
        
        .titulo-editar { 
            font-family: 'Playfair Display', serif;
            font-size: 38px; 
            margin-bottom: 8px; 
            font-weight: 400; 
            letter-spacing: -0.5px;
        }
        .subtitulo-editar { 
            font-size: 14px; 
            color: var(--text-muted); 
            margin-bottom: 40px; 
            line-height: 1.6; 
        }

       
        .alerta { 
            padding: 15px; 
            margin-bottom: 30px; 
            font-size: 13px; 
            text-align: center; 
            font-weight: 500;
            letter-spacing: 0.5px;
        }
        .sucesso { background-color: #e2ede4; color: #2e5437; border: 1px solid rgba(46, 84, 55, 0.15); }
        .erro { background-color: #f7e9e9; color: #8a3b3b; border: 1px solid rgba(138, 59, 59, 0.15); }

        
        .campo-grupo { 
            margin-bottom: 28px; 
            display: flex;
            flex-direction: column;
            border-bottom: 1px solid var(--text-dark);
            padding-bottom: 6px;
        }
        .campo-grupo label { 
            font-size: 11px; 
            text-transform: uppercase; 
            letter-spacing: 1.5px; 
            margin-bottom: 6px; 
            font-weight: 600; 
            color: var(--text-muted);
        }
        .campo-grupo input { 
            width: 100%; 
            border: none; 
            background-color: transparent; 
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 15px; 
            color: var(--text-dark); 
            outline: none; 
            padding: 2px 0;
        }
        
        .grid-campos-duplos { 
            display: grid; 
            grid-template-columns: 1fr 1fr; 
            gap: 30px; 
        }
        
        
        .botoes-acoes-grid { 
            display: grid; 
            grid-template-columns: 1.2fr 1fr; 
            gap: 20px; 
            margin-top: 20px; 
        }
        
        .btn-figma { 
            padding: 16px; 
            font-size: 11px; 
            font-weight: 600; 
            text-transform: uppercase; 
            letter-spacing: 2px; 
            cursor: pointer; 
            text-align: center; 
            border: none; 
            text-decoration: none; 
            transition: all 0.3s ease;
        }
        .btn-atualizar { 
            background-color: var(--accent-green); 
            color: var(--bg-canvas); 
        }
        .btn-atualizar:hover { 
            background-color: var(--text-dark); 
            transform: translateY(-1px);
        }
        .btn-descartar { 
            background-color: transparent; 
            color: var(--text-muted); 
            border: 1px solid var(--line-color);
            display: flex; 
            align-items: center; 
            justify-content: center; 
        }
        .btn-descartar:hover { 
            color: var(--text-dark);
            border-color: var(--text-dark); 
        }

        @media (max-width: 768px) {
            .editar-container { grid-template-columns: 1fr; gap: 40px; }
            .grid-campos-duplos, .botoes-acoes-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

    <nav class="navbar-adm">
        <div class="logo-adm">Painel de Curadoria</div>
        <div class="menu-links">
            <a href="painel_funcionario.php">Estoque Geral</a>
            <a href="cadastrar_vela.php">Cadastrar Nova</a>
        </div>
        <a href="painel_funcionario.php" class="btn-voltar">← Retornar ao Painel</a>
    </nav>

    <form action="editar_vela.php?id=<?php echo $id; ?>" method="POST" enctype="multipart/form-data">
        <div class="editar-container">
            
            <div class="bloco-imagem-visual">
                <div class="quadrado-foto">
                    <?php if(!empty($vela['imagem']) && file_exists('imagens/' . $vela['imagem'])): ?>
                        <img src="imagens/<?php echo $vela['imagem']; ?>" alt="Foto do Produto">
                    <?php else: ?>
                        <div class="sem-foto">🌿 Sem Imagem Vinculada<br><span style="font-size: 10px; text-transform: none;">Utilize o seletor abaixo</span></div>
                    <?php endif; ?>
                </div>
                
                <div class="upload-btn-wrapper">
                    <button class="btn-upload-custom">Escolher Nova Imagem</button>
                    <input type="file" name="foto" accept="image/*">
                </div>
                <div class="upload-hint">Formatos aceitos: JPG, PNG ou WEBP.</div>
            </div>

            <div class="formulario-editar">
                <h1 class="titulo-editar">Editar Vela</h1>
                <p class="subtitulo-editar">Modifique as propriedades olfativas, precificação e controle de volumetria física do lote selecionado.</p>

                <?php if(!empty($mensagem)): ?>
                    <div class="alerta sucesso"><?php echo $mensagem; ?></div>
                <?php endif; ?>

                <?php if(!empty($erro)): ?>
                    <div class="alerta erro"><?php echo $erro; ?></div>
                <?php endif; ?>

                <div class="campo-grupo">
                    <label>Designação do Produto (Nome)</label>
                    <input type="text" name="nome" value="<?php echo htmlspecialchars($vela['nome'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                </div>

                <div class="grid-campos-duplos">
                    <div class="campo-grupo">
                        <label>Massa Líquida / Peso</label>
                        <input type="text" name="tamanho" value="<?php echo htmlspecialchars($vela['tamanho'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="Ex: 180g" required>
                    </div>
                    <div class="campo-grupo">
                        <label>Especificação de Recipiente</label>
                        <input type="text" value="Pote Hermético Padrão" readonly style="color: var(--text-muted); cursor: not-allowed;">
                    </div>
                </div>

                <div class="grid-campos-duplos">
                    <div class="campo-grupo">
                        <label>Preço de Venda (R$)</label>
                        <input type="text" name="preco" value="<?php echo isset($vela['preco']) ? number_format($vela['preco'], 2, '.', '') : '0.00'; ?>" required>
                    </div>
                    <div class="campo-grupo">
                        <label>Unidades em Estoque</label>
                        <input type="number" name="estoque" value="<?php echo $vela['estoque'] ?? 0; ?>" required>
                    </div>
                </div>

                <div class="campo-grupo">
                    <label>Família Olfativa / Notas de Topo e Corpo</label>
                    <input type="text" name="aroma" value="<?php echo htmlspecialchars($vela['aroma'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                </div>

                <div class="botoes-acoes-grid">
                    <button type="submit" class="btn-figma btn-atualizar">Salvar Alterações</button>
                    <a href="painel_funcionario.php" class="btn-figma btn-descartar">Descartar</a>
                </div>

            </div>

        </div>
    </form>

</body>
</html>