<?php

require_once 'conexao.php';


if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: dashboard.php");
    exit;
}

$id = intval($_GET['id']);

try {
    
    $sql = "SELECT * FROM public.velas WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
    $vela = $stmt->fetch(PDO::FETCH_ASSOC);

    
    if (!$vela) {
        header("Location: dashboard.php");
        exit;
    }
} catch (PDOException $e) {
    die("Erro ao carregar dados para edição: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Vela | Painel Administrativo</title>
    
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
            --line-color: rgba(26, 21, 18, 0.12);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: var(--bg-canvas); 
            color: var(--text-dark); 
            -webkit-font-smoothing: antialiased;
            padding: 60px 20px;
        }

        
        .container-cadastro {
            max-width: 750px;
            margin: 0 auto;
        }

        .card-cadastro-interno {
            background: var(--bg-card);
            border: 1px solid var(--line-color);
            padding: 50px;
            box-shadow: 0 8px 30px rgba(26, 21, 18, 0.02);
        }

        .card-cadastro-interno h1 {
            font-family: 'Playfair Display', serif;
            font-size: 36px;
            font-weight: 400;
            letter-spacing: -0.5px;
            margin-bottom: 8px;
        }

        .subtitulo {
            font-size: 14px;
            color: var(--text-muted);
            margin-bottom: 40px;
            line-height: 1.5;
        }


        .form-cadastro {
            display: flex;
            flex-direction: column;
        }

        .form-group {
            margin-bottom: 28px;
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

        .form-group textarea {
            width: 100%;
            border: none;
            background: transparent;
            font-family: inherit;
            font-size: 14px;
            line-height: 1.6;
            color: var(--text-dark);
            outline: none;
            resize: vertical;
            margin-top: 4px;
        }

     
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
        }

       
        .acoes-formulario {
            display: grid;
            grid-template-columns: 1.3fr 1fr;
            gap: 20px;
            margin-top: 15px;
            align-items: center;
        }

        .btn-salvar-cadastro {
            background-color: var(--accent-green);
            color: var(--bg-canvas);
            border: none;
            padding: 16px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 2px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
        }

        .btn-salvar-cadastro:hover {
            background-color: var(--text-dark);
            transform: translateY(-1px);
        }

        .btn-cancelar-cadastro {
            display: block;
            text-align: center;
            padding: 15px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: var(--text-muted);
            text-decoration: none;
            border: 1px solid var(--line-color);
            transition: all 0.3s;
        }

        .btn-cancelar-cadastro:hover {
            color: var(--text-dark);
            border-color: var(--text-dark);
        }

        @media (max-width: 600px) {
            .form-row, .acoes-formulario {
                grid-template-columns: 1fr;
                gap: 0;
            }
            .btn-cancelar-cadastro {
                margin-top: 15px;
            }
        }
    </style>
</head>
<body>

    <div class="container container-cadastro">
        <div class="card-cadastro-interno">
            
            <h1>Editar Vela Aromática</h1>
            <p class="subtitulo">Modifique as propriedades, valores comerciais e notas de composição do lote selecionado.</p>

            <form action="atualizar.php" method="POST" class="form-cadastro">
                
                <input type="hidden" name="id" value="<?php echo $vela['id']; ?>">

                <div class="form-group">
                    <label for="nome">Designação da Criação (Nome)</label>
                    <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($vela['nome']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="aroma">Família Olfativa / Aroma</label>
                    <input type="text" id="aroma" name="aroma" value="<?php echo htmlspecialchars($vela['aroma']); ?>" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="tamanho">Volume / Peso Líquido</label>
                        <input type="text" id="tamanho" name="tamanho" value="<?php echo htmlspecialchars($vela['tamanho']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="cor">Coloração da Cera</label>
                        <input type="text" id="cor" name="cor" value="<?php echo htmlspecialchars($vela['cor']); ?>" placeholder="Ex: Cera Natural">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="preco">Preço Unitário (R$)</label>
                        <input type="number" step="0.01" id="preco" name="preco" value="<?php echo number_format($vela['preco'], 2, '.', ''); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="estoque">Unidades em Estoque</label>
                        <input type="number" id="estoque" name="estoque" value="<?php echo $vela['estoque']; ?>" required>
                    </div>
                </div>

                <div class="form-group" style="border-bottom: 1px solid var(--line-color);">
                    <label for="descricao">Narrativa / Descrição Olfativa</label>
                    <textarea id="descricao" name="descricao" rows="4" required><?php echo htmlspecialchars($vela['descricao']); ?></textarea>
                </div>

                <div class="acoes-formulario">
                    <button type="submit" class="btn-salvar-cadastro">Salvar Alterações</button>
                    <a href="dashboard.php" class="btn-cancelar-cadastro">Descartar</a>
                </div>

            </form>
        </div>
    </div>

</body>
</html>