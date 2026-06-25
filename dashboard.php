<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require_once 'conexao.php';

try {
    // busca todas as velas
    $stmtListagem = $pdo->query("SELECT * FROM public.velas ORDER BY nome ASC");
    $velas = $stmtListagem->fetchAll(PDO::FETCH_ASSOC);

    $totalVelas = count($velas);
    
} catch (PDOException $e) {
    die("Erro no banco de dados: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Controle | Maia Candle Co.</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..700;1,400..700&family=Plus+Jakarta+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        
        :root {
            --bg-canvas: #f4f0ea;    /* Tom areia clássico */
            --bg-card: #fdfbfa;      /* Fundo dos blocos brancos */
            --text-dark: #1a1512;    /* Marrom escuro quase preto */
            --text-muted: #6e655f;   /* Tom terroso suave para legendas */
            --accent-green: #3d5245;  /* Verde botânico assinatura */
            --accent-brown: #764a34;  /* Terracota elegante */
            --line-color: rgba(26, 21, 18, 0.12);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: var(--bg-canvas); 
            color: var(--text-dark); 
            -webkit-font-smoothing: antialiased;
            padding: 40px 20px;
        }

      
        .container-painel { 
            max-width: 1200px; 
            margin: 0 auto; 
        }
        
        .card-painel-interno { 
            background: var(--bg-card); 
            border: 1px solid var(--line-color);
            padding: 50px;
            box-shadow: 0 8px 30px rgba(26, 21, 18, 0.02);
        }

   
        .header-painel-flex { 
            display: flex; 
            justify-content: space-between; 
            align-items: baseline; 
            border-bottom: 1px solid var(--text-dark);
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        
        .header-painel-flex h1 { 
            font-family: 'Playfair Display', serif; 
            font-size: 38px; 
            font-weight: 400; 
            letter-spacing: -0.5px;
        }
        
        .btn-sair-painel { 
            font-size: 12px; 
            text-transform: uppercase; 
            letter-spacing: 2px; 
            font-weight: 600; 
            color: var(--text-dark); 
            text-decoration: none;
            border-bottom: 1px solid var(--text-dark);
            padding-bottom: 2px;
            transition: color 0.3s, border-color 0.3s;
        }
        .btn-sair-painel:hover { 
            color: var(--accent-brown); 
            border-color: var(--accent-brown); 
        }

        .subtitulo { 
            font-size: 15px; 
            color: var(--text-muted); 
            margin-bottom: 35px;
        }
        .subtitulo strong { color: var(--text-dark); font-weight: 600; }

        
        .acoes-painel-topo { 
            margin-bottom: 25px; 
            display: flex;
            justify-content: flex-start;
        }
        
        .btn-adicionar-vela { 
            background-color: var(--accent-green); 
            color: var(--bg-canvas); 
            text-decoration: none; 
            padding: 14px 28px; 
            font-size: 11px; 
            font-weight: 600; 
            text-transform: uppercase; 
            letter-spacing: 2px; 
            transition: all 0.3s ease;
        }
        .btn-adicionar-vela:hover { 
            background-color: var(--text-dark); 
            transform: translateY(-1px);
        }

      
        .admin-table { 
            width: 100%; 
            border-collapse: collapse; 
            text-align: left; 
            margin-top: 10px;
        }
        
        .admin-table th { 
            font-size: 11px; 
            text-transform: uppercase; 
            letter-spacing: 1.5px; 
            font-weight: 600; 
            color: var(--text-muted); 
            padding: 15px 20px;
            border-bottom: 2px solid var(--line-color);
            background-color: rgba(244, 240, 234, 0.4);
        }
        
        .admin-table td { 
            padding: 18px 20px; 
            font-size: 14px; 
            border-bottom: 1px solid var(--line-color);
            color: var(--text-dark);
            vertical-align: middle;
        }
        
        .admin-table tr:hover td { 
            background-color: rgba(244, 240, 234, 0.2); 
        }

        
        .botao-tabela { 
            font-size: 12px; 
            font-weight: 600; 
            text-transform: uppercase; 
            letter-spacing: 1px; 
            text-decoration: none; 
            margin-right: 15px;
            transition: color 0.3s;
        }
        
        .btn-editar-vela { color: var(--accent-green); }
        .btn-editar-vela:hover { color: var(--text-dark); }
        
        .btn-excluir-vela { color: var(--accent-brown); }
        .btn-excluir-vela:hover { color: var(--text-dark); }

        .tabela-vazia-aviso { 
            text-align: center; 
            padding: 40px !important; 
            color: var(--text-muted); 
            font-style: italic; 
        }

      
        .badge-estoque {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 2px;
            font-size: 12px;
            font-weight: 500;
        }
        .estoque-normal { color: var(--text-dark); }
        .estoque-baixo { background-color: #fbeee6; color: #b75a32; font-weight: 600; }
    </style>
</head>
<body>

    <div class="container container-painel">
        <div class="card-painel-interno">
            
            <div class="header-painel-flex">
                <h1>Painel de Controle</h1>
                <a href="index.php" class="btn-sair-painel">Sair do Painel</a>
            </div>
            
            <p class="subtitulo">Sua curadoria conta atualmente com <strong><?php echo $totalVelas; ?></strong> criações botânicas listadas no catálogo.</p>
            
            <div class="acoes-painel-topo">
                <a href="cadastrar.php" class="btn-adicionar-vela">+ Nova Vela Artesanal</a>
            </div>

            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Nome do Produto</th>
                        <th>Família Olfativa</th>
                        <th>Volumetria</th>
                        <th>Preço Unitário</th>
                        <th>Disponibilidade</th>
                        <th>Ações de Curadoria</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($totalVelas > 0): ?>
                        <?php foreach ($velas as $vela): ?>
                            <tr>
                                <td><strong style="font-weight: 600; color: var(--text-dark);"><?php echo htmlspecialchars($vela['nome']); ?></strong></td>
                                <td style="color: var(--text-muted);"><?php echo htmlspecialchars($vela['aroma']); ?></td>
                                <td><?php echo htmlspecialchars($vela['tamanho']); ?></td>
                                <td style="font-weight: 500;">R$ <?php echo number_format($vela['preco'], 2, ',', '.'); ?></td>
                                <td>
                                    <?php if ($vela['estoque'] <= 3): ?>
                                        <span class="badge-estoque estoque-baixo"><?php echo $vela['estoque']; ?> un. (Baixo)</span>
                                    <?php else: ?>
                                        <span class="badge-estoque estoque-normal"><?php echo $vela['estoque']; ?> un.</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="editar.php?id=<?php echo $vela['id']; ?>" class="botao-tabela btn-editar-vela">Editar</a>
                                    <a href="excluir.php?id=<?php echo $vela['id']; ?>" onclick="return confirm('Tem certeza que deseja remover esta vela permanentemente do catálogo?')" class="botao-tabela btn-excluir-vela">Excluir</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="tabela-vazia-aviso">Nenhum produto foi encontrado no banco de dados.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            
        </div>
    </div>

</body>
</html>