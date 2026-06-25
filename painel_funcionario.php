<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'conexao.php';

$velas = [];
$total_velas = 0;

try {
    if (isset($pdo)) {
        // busca todas as velas
        $stmt = $pdo->query("SELECT * FROM public.velas ORDER BY id DESC");
        $velas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // total de estoque
        $stmt_count = $pdo->query("SELECT SUM(estoque) FROM public.velas");
        $total_velas = (int)$stmt_count->fetchColumn();
    }
} catch (Exception $e) {
    error_log("Erro no painel do funcionário: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Administrativo | Maia Candle Co.</title>
    
   
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght=0,400..700;1,400..700&family=Plus+Jakarta+Sans:wght=300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        
        :root {
            --bg-canvas: #f4f0ea;    /* Areia suave de fundo */
            --bg-card: #fdfbfa;      /* Off-white dos blocos limpos */
            --text-dark: #1a1512;    /* Marrom botânico profundo */
            --text-muted: #6e655f;   /* Legendagem terrosa */
            --accent-green: #3d5245; /* Verde assinatura Maia Candle */
            --accent-gold: #764a34;  /* Bronze/Terracota para avisos e exclusão */
            --line-color: rgba(26, 21, 18, 0.12);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: var(--bg-canvas); 
            color: var(--text-dark); 
            -webkit-font-smoothing: antialiased;
            padding-bottom: 60px;
        }

        
        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 25px 5%;
            background: transparent;
            border-bottom: 1px solid var(--line-color);
        }

        .admin-header .panel-title {
            font-family: 'Playfair Display', serif;
            font-size: 22px;
            font-weight: 500;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .admin-nav {
            display: flex;
            gap: 30px;
            align-items: center;
        }

        .admin-nav a {
            text-decoration: none;
            color: var(--text-muted);
            font-weight: 600;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            transition: color 0.2s;
            padding-bottom: 4px;
        }

        .admin-nav a.active {
            color: var(--text-dark);
            border-bottom: 2px solid var(--text-dark);
        }

        .admin-nav a:hover { color: var(--text-dark); }

        .btn-logout {
            background-color: #ebdcd5;
            color: var(--accent-gold);
            padding: 10px 18px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            text-decoration: none;
            transition: all 0.2s;
            border: 1px solid rgba(118, 74, 52, 0.15);
        }
        .btn-logout:hover {
            background-color: var(--accent-gold);
            color: #fff;
        }

    
        .container-admin {
            max-width: 1250px;
            margin: 45px auto;
            padding: 0 30px;
        }

        .main-page-title {
            font-family: 'Playfair Display', serif;
            font-size: 32px;
            font-weight: 400;
            text-align: center;
            margin-bottom: 15px;
        }

        .page-subtitle {
            font-size: 14px;
            color: var(--text-muted);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 30px;
            border-bottom: 1px solid var(--line-color);
            padding-bottom: 10px;
        }

        .cards-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 25px;
            margin-bottom: 45px;
        }

        .metric-card {
            background-color: var(--bg-card);
            border: 1px solid var(--line-color);
            padding: 30px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            box-shadow: 0 4px 15px rgba(26, 21, 18, 0.01);
        }

        .metric-card .card-label {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: var(--text-muted);
            margin-bottom: 15px;
        }

        .metric-card .card-value {
            font-family: 'Playfair Display', serif;
            font-size: 40px;
            color: var(--text-dark);
            font-weight: 400;
        }

        .metric-card .card-value span {
            font-size: 16px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: var(--text-muted);
            margin-left: 5px;
        }

        .metric-card .card-subtext {
            font-size: 12px;
            color: var(--text-muted);
            font-style: italic;
            margin-top: 8px;
        }

        
        .bottom-layout {
            display: flex;
            gap: 30px;
            align-items: flex-start;
        }

        .side-ranking {
            width: 300px;
            flex-shrink: 0;
            background-color: var(--bg-card);
            border: 1px solid var(--line-color);
            padding: 30px;
        }

        .side-ranking h3 {
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            border-bottom: 1px solid var(--line-color);
            padding-bottom: 12px;
            margin-bottom: 20px;
        }

        .ranking-item {
            display: flex;
            align-items: flex-start;
            gap: 15px;
            padding-bottom: 18px;
            margin-bottom: 18px;
            border-bottom: 1px dashed var(--line-color);
        }
        .ranking-item:last-child { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }

        .ranking-position {
            font-family: 'Playfair Display', serif;
            font-size: 20px;
            font-style: italic;
            color: var(--accent-gold);
            font-weight: 600;
        }

        .ranking-details h4 {
            font-family: 'Playfair Display', serif;
            font-size: 15px;
            font-weight: 500;
        }

        .table-area {
            flex: 1;
            background-color: var(--bg-card);
            border: 1px solid var(--line-color);
            padding: 30px;
        }

        .table-area h3 {
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 20px;
        }

        .custom-table {
            width: 100%;
            border-collapse: collapse;
        }

        .custom-table th {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--text-muted);
            padding: 12px 15px;
            border-bottom: 1px solid var(--text-dark);
            text-align: left;
        }

        .custom-table td {
            padding: 16px 15px;
            font-size: 14px;
            border-bottom: 1px solid var(--line-color);
            color: var(--text-dark);
            vertical-align: middle;
        }

        .custom-table tr:hover td { background-color: #faf7f2; }

        .prod-name {
            font-family: 'Playfair Display', serif;
            font-size: 16px;
            font-weight: 500;
        }

        .action-links {
            display: flex;
            gap: 15px;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }
        .action-links a.edit { color: var(--accent-green); text-decoration: none; }
        .action-links a.delete { color: var(--accent-gold); text-decoration: none; }
        .action-links a:hover { text-decoration: underline; }

        @media (max-width: 950px) {
            .cards-grid { grid-template-columns: 1fr; }
            .bottom-layout { flex-direction: column; }
            .side-ranking { width: 100%; }
        }
    </style>
</head>
<body>

    <!-- Header Corporativo Alinhado -->
    <header class="admin-header">
        <div class="panel-title">Administrar</div>
        <nav class="admin-nav">
            <a href="painel_funcionario.php" class="active">Estoque</a>
            <a href="cadastrar_vela.php">Cadastrar Vela</a>
            <a href="portal.php" class="btn-logout">Sair do Painel 🚪</a>
        </nav>
    </header>

    <main class="container-admin">
        <h1 class="main-page-title">Estoque de Velas</h1>
        <div class="page-subtitle">Visão Geral do Site</div>

        <!-- Três Principais Indicadores Reestruturados -->
        <section class="cards-grid">
            <div class="metric-card">
                <div class="card-label">Quantidade de Velas Disponível</div>
                <div class="card-value"><?php echo $total_velas; ?><span>unidades</span></div>
                <div class="card-subtext">Total somado de todo o estoque ativo no banco.</div>
            </div>

            <div class="metric-card">
                <div class="card-label">Vendas do Mês</div>
                <div class="card-value">R$ 0,00</div>
                <div class="card-subtext">Nenhuma venda registrada este mês.</div>
            </div>

            <div class="metric-card">
                <div class="card-label">Saída de Velas do Estoque</div>
                <div class="card-value">0<span>itens</span></div>
                <div class="card-subtext">Retiradas e baixas manuais efetuadas.</div>
            </div>
        </section>

        <div class="bottom-layout">
            
            <!-- Vitrine de Destaques Internos -->
            <aside class="side-ranking">
                <h3>Mais Vendidos</h3>
                <div class="ranking-item">
                    <div class="ranking-position">#1</div>
                    <div class="ranking-details">
                        <h4 class="prod-name">Capim-Limão Real</h4>
                        <div style="font-size: 12px; color: var(--text-muted);">Líder de saídas</div>
                    </div>
                </div>
                <div class="ranking-item">
                    <div class="ranking-position">#2</div>
                    <div class="ranking-details">
                        <h4 class="prod-name">Lavanda de Campo</h4>
                        <div style="font-size: 12px; color: var(--text-muted);">Alta procura</div>
                    </div>
                </div>
            </aside>

            <!-- Tabela de Mapeamento Dinâmico do Banco de Dados -->
            <section class="table-area">
                <h3>Lista de Produtos Cadastrados (Estoque Real)</h3>
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th style="width: 60px;">ID</th>
                            <th>Nome</th>
                            <th>Aroma</th>
                            <th>Preço</th>
                            <th style="width: 80px;">Qtd</th>
                            <th style="width: 120px; text-align: right;">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($velas)): ?>
                            <?php foreach ($velas as $vela): ?>
                                <tr>
                                    <td><span style="color: var(--text-muted); font-size: 12px;">#<?php echo $vela['id']; ?></span></td>
                                    <td><strong class="prod-name"><?php echo htmlspecialchars($vela['nome']); ?></strong></td>
                                    <td><span style="color: var(--text-muted);"><?php echo htmlspecialchars($vela['aroma']); ?></span></td>
                                    <td><strong>R$ <?php echo number_format($vela['preco'], 2, ',', '.'); ?></strong></td>
                                    <td><?php echo $vela['estoque']; ?> un</td>
                                    <td style="text-align: right;">
                                        <div class="action-links" style="justify-content: flex-end;">
                                            <a href="editar_vela.php?id=<?php echo $vela['id']; ?>" class="edit">Editar</a>
                                            <a href="excluir.php?id=<?php echo $vela['id']; ?>" class="delete" onclick="return confirm('Tem certeza que deseja excluir esta vela?');">X</a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" style="text-align: center; color: var(--text-muted); font-style: italic; padding: 20px;">Nenhuma vela localizada no banco de dados.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </section>

        </div>
    </main>
</body>
</html>