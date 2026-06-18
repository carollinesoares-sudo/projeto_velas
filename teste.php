<?php

error_reporting(E_ALL);// Ativa a exibição de todos os erros
ini_set('display_errors', 1);// Garante que os erros sejam mostrados no navegador
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">// Configurações de exibição de erros para diagnóstico
    <title>Diagnóstico de Infraestrutura | Maia Candle Co.</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500&family=Plus+Jakarta+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg-canvas: #f4f0ea;
            --bg-card: #fdfbfa;
            --text-dark: #1a1512;
            --text-muted: #6e655f;
            --success-color: #3d5245;
            --error-color: #764a34;
            --warning-color: #b5896b;
            --line-color: rgba(26, 21, 18, 0.12);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-canvas);
            color: var(--text-dark);
            padding: 40px 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            -webkit-font-smoothing: antialiased;
        }

        .diagnostico-card {
            background-color: var(--bg-card);
            border: 1px solid var(--line-color);
            padding: 40px;
            max-width: 600px;
            width: 100%;
            box-shadow: 0 12px 30px rgba(26, 21, 18, 0.03);
        }

        h1 {
            font-family: 'Playfair Display', serif;
            font-size: 26px;
            font-weight: 400;
            margin-top: 0;
            margin-bottom: 25px;
            border-bottom: 1px solid var(--line-color);
            padding-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .status-box {
            padding: 16px 20px;
            margin-bottom: 15px;
            font-size: 14px;
            line-height: 1.5;
            display: flex;
            align-items: flex-start;
            gap: 12px;
            border-left: 3px solid;
        }

        .status-box.success {
            background-color: #ebf0ec;
            color: var(--success-color);
            border-left-color: var(--success-color);
        }

        .status-box.error {
            background-color: #f7f1ed;
            color: var(--error-color);
            border-left-color: var(--error-color);
            font-family: monospace;
            font-size: 13px;
        }

        .status-box.warning {
            background-color: #fbf7f2;
            color: var(--warning-color);
            border-left-color: var(--warning-color);
        }

        .result-title {
            font-family: 'Playfair Display', serif;
            font-size: 20px;
            color: var(--success-color);
            margin-top: 25px;
            margin-bottom: 10px;
            font-weight: 500;
        }
    </style>
</head>
<body>

<div class="diagnostico-card">
    <h1>🛠️ Verificação de Ambiente</h1> 

    <?php
   
    if (!file_exists('conexao.php')) {
        echo "<div class='status-box error'>
                <strong>[FALHA INTERNA]</strong><br>O arquivo 'conexao.php' não foi localizado no diretório atual. Verifique o mapeamento das pastas no servidor.
              </div>";
        echo "</div></body></html>";// Encerra o script para evitar erros subsequentes
        die();
    }

    echo "<div class='status-box success'> 
            <strong>Mapeamento:</strong> Arquivo 'conexao.php' localizado. Tentando iniciar instância PDO... //
          </div>";

    
    include 'conexao.php';

    
    if (isset($pdo)) {
        echo "<div class='status-box success'>
                <strong>Handshake:</strong> Conexão PDO instanciada e autenticada com sucesso!
              </div>";
        
        try {
            
            $stmt = $pdo->query("SELECT COUNT(*) FROM public.velas");
            $total = $stmt->fetchColumn();
            
            echo "<div class='result-title'>🎉 Sucesso Operacional!</div>";
            echo "<p style='font-size: 14px; color: var(--text-muted); margin: 0;'>
                    A comunicação com o banco de dados está íntegra. Existem atualmente <strong>$total velas</strong> catalogadas na tabela <code>public.velas</code>.
                  </p>";
                  
        } catch (Exception $e) {
            echo "<div class='status-box error'>
                    <strong>[ERRO DE TABELA]</strong><br>Conexão feita, mas falha ao ler 'public.velas':<br>" . htmlspecialchars($e->getMessage()) . "
                  </div>";
        }
    } else {
        echo "<div class='status-box warning'>
                <strong>[ALERTA DE ARQUITETURA]</strong><br>O arquivo 'conexao.php' foi executado, porém a variável globbal <code>\$pdo</code> não foi inicializada. Certifique-se de que a instância do banco não está isolada em escopo privado.
              </div>";
    }
    ?>
</div>

</body>
</html>