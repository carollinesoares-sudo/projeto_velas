<?php

// Liga os alertas do PHP. Se algo quebrar ou sumir nas variáveis, a gente vê na hora.
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Ativa a sessão do navegador (essencial para lembrar quem está logado e o que está na sacola)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

//  Se o usuário não estiver logado, chuta ele de volta pro login.
if (!isset($_SESSION['cliente_logado']) || $_SESSION['cliente_logado'] !== true) {
    header('Location: login.php');
    exit;
}

// Se o carrinho estiver vazio E ele não acabou de fazer uma compra, 
// não faz sentido ele estar aqui. Manda ele de volta pra vitrine.
if ((!isset($_SESSION['carrinho']) || empty($_SESSION['carrinho'])) && !isset($_SESSION['ultimo_pedido_sucesso'])) {
    header('Location: produtos.php');
    exit;
}

// Puxa o arquivo que conecta com o banco de dados PostgreSQL
include_once 'conexao.php'; 

// Se o cliente não tiver um histórico simulado na sessão, 
// a gente cria dois pedidos fictícios só para a tela de "Meus Pedidos" não nascer sem nada.
if (!isset($_SESSION['meus_pedidos_simulados'])) {
    $_SESSION['meus_pedidos_simulados'] = [
        [
            'numero' => '8432',
            'data' => '10/06/2026',
            'itens' => '1x Vela Bergamota & Lima, 1x Vela Pera & Flor',
            'total' => 95.00,
            'status' => 'Em produção',
            'cor_status' => '#764a34'
        ],
        [
            'numero' => '8129',
            'data' => '28/05/2026',
            'itens' => '2x Vela Baunilha & Laranja',
            'total' => 144.00,
            'status' => 'Entregue',
            'cor_status' => '#3d5245'
        ]
    ];
}

// Inicializa as variáveis que vão listar os produtos na barra lateral e somar a conta
$produtos_checkout = [];
$total_pedido = 0;

// Se a sacola tiver coisas, vamos processar item por item para montar o resumo da fatura
if (!empty($_SESSION['carrinho'])) {
    
    // Os famosos Kits que não estão cadastrados no banco de dados (ficam fixos aqui no código)
    $kits_estaticos = [
        901 => ['nome' => 'Kit Trio Clássico', 'preco' => 135.00],
        902 => ['nome' => 'Kit Dueto Relaxante', 'preco' => 98.00],
        903 => ['nome' => 'Kit Coleção Outono/Inverno', 'preco' => 115.00]
    ];

    $ids_banco = [];
    
    // Varre o carrinho: o que for Kit calcula na hora; o que for vela avulsa, guarda o ID para rodar no banco
    foreach ($_SESSION['carrinho'] as $id_item => $qtd) {
        if (isset($kits_estaticos[$id_item])) {
            $subtotal = $kits_estaticos[$id_item]['preco'] * $qtd;
            $total_pedido += $subtotal;
            $produtos_checkout[] = [
                'nome' => $kits_estaticos[$id_item]['nome'],
                'quantidade' => $qtd,
                'subtotal' => $subtotal
            ];
        } else {
            $ids_banco[] = $id_item; // Vai pra fila do banco
        }
    }

    // Se tiver algum ID de vela comum na fila, vai buscar os preços reais no banco de dados
    if (!empty($ids_banco) && isset($pdo)) {
        $ids_string = implode(',', $ids_banco); // Junta tudo em formato "1,2,3"
        try {
            $stmt = $pdo->query("SELECT * FROM public.velas WHERE id IN ($ids_string)");
            $dados_velas = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Vincula o preço do banco com a quantidade que o usuário escolheu na sessão
            foreach ($dados_velas as $vela) {
                $qtd = $_SESSION['carrinho'][$vela['id']];
                $subtotal = $vela['preco'] * $qtd;
                $total_pedido += $subtotal;

                $produtos_checkout[] = [
                    'nome' => $vela['nome'],
                    'quantidade' => $qtd,
                    'subtotal' => $subtotal
                ];
            }
        } catch (Exception $e) {
            // Se o banco der pau, avisa o desenvolvedor escondido lá no console do navegador
            echo "<script>console.log('Erro no checkout: " . addslashes($e->getMessage()) . "');</script>";
        }
    }
}

// Flags para controlar se a compra deu certo e qual número o cliente ganhou
$sucesso = false;
$numero_pedido_gerado = '';


if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_SESSION['carrinho'])) {
    $nome_cliente = $_POST['nome'] ?? '';
    $email_cliente = $_POST['email'] ?? '';
    $forma_pagamento = $_POST['forma_pagamento'] ?? 'Cartão de Crédito';

    // Sorteia um número de pedido aleatório entre 8500 e 9999 para fingir um ID do banco
    $numero_pedido_gerado = (string)rand(8500, 9999);
    
    // Transforma a lista de produtos em um textão único separado por vírgula (Ex: "2x Vela de Canela, 1x Kit Trio")
    $nomes_itens = [];
    foreach ($produtos_checkout as $prod) {
        $nomes_itens[] = $prod['quantidade'] . "x " . $prod['nome'];
    }
    $string_itens = implode(', ', $nomes_itens);

    // Linhas de segurança (fallbacks) caso algo dê muito errado no cálculo
    if (empty($string_itens)) { $string_itens = "1x Vela Artesanal Maia Candle Co."; }
    if ($total_pedido == 0) { $total_pedido = 68.00; }

    // 💾 BANCO DE DADOS REAL: Tenta salvar o pedido gerado na tabela física do PostgreSQL
    try {
        if (isset($pdo)) {
            $stmt = $pdo->prepare("INSERT INTO public.pedidos (numero, data_pedido, itens, valor_total, status, cor_status) VALUES (:num, NOW(), :itens, :total, 'Em produção', '#764a34')");
            $stmt->execute([
                'num' => $numero_pedido_gerado,
                'itens' => $string_itens,
                'total' => $total_pedido
            ]);
        }
    } catch (Exception $e) {
        // Silencia o erro para não assustar o cliente na tela caso o insert falhe.
    }

    //: Monta o mesmo pedido e joga no topo do histórico simulado
    $novo_pedido = [
        'numero' => $numero_pedido_gerado,
        'data' => date('d/m/Y'),
        'itens' => $string_itens,
        'total' => $total_pedido,
        'status' => 'Em produção',
        'cor_status' => '#764a34'
    ];

    array_unshift($_SESSION['meus_pedidos_simulados'], $novo_pedido); // Joga pro começo do array
    $_SESSION['ultimo_pedido_sucesso'] = $numero_pedido_gerado; // Salva o token de sucesso

    //Limpa o carrinho para o cliente não comprar as mesmas coisas de novo sem querer
    $_SESSION['carrinho'] = [];
    $sucesso = true;
}

// Mantém o estado de sucesso ativo caso a página seja recarregada logo após a compra
if (isset($_SESSION['ultimo_pedido_sucesso'])) {
    $sucesso = true;
    $numero_pedido_gerado = $_SESSION['ultimo_pedido_sucesso'];
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finalizar Compra | Maia Candle Co.</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..700;1,400..700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

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
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: var(--bg-canvas); color: var(--text-dark); -webkit-font-smoothing: antialiased; }
        
        /* Barra de Navegação */
        .navbar { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            padding: 25px 6%; 
            background: transparent; 
            border-bottom: 1px solid var(--line-color); 
        }
        .navbar .logo { font-size: 22px; font-weight: 600; text-decoration: none; color: var(--text-dark); text-transform: uppercase; letter-spacing: 3px; font-family: 'Playfair Display', serif; }
        .navbar .menu a { margin: 0 18px; text-decoration: none; color: var(--text-muted); font-weight: 500; text-transform: uppercase; font-size: 12px; letter-spacing: 1.5px; }
        .nav-right { display: flex; align-items: center; font-size: 13px; gap: 20px; text-transform: uppercase; letter-spacing: 1px; }
        .nav-right a { color: var(--text-dark); text-decoration: none; font-weight: 500; }

        .container { max-width: 1150px; margin: 50px auto; padding: 0 30px; }
        
        .checkout-title-area { margin-bottom: 40px; }
        .checkout-title-area h1 { font-family: 'Playfair Display', serif; font-size: 40px; font-weight: 400; margin-bottom: 8px; }
        .checkout-title-area p { font-size: 14px; color: var(--text-muted); letter-spacing: 0.3px; }

        /* Grade que divide o formulário à esquerda e o resumo à direita */
        .checkout-grid { display: grid; grid-template-columns: 1.4fr 1fr; gap: 60px; align-items: start; }

        /* Estilização dos inputs elegantes (apenas uma linha embaixo, estilo premium) */
        .form-section { background: transparent; }
        .section-title { font-family: 'Playfair Display', serif; font-size: 22px; font-weight: 500; margin-bottom: 25px; padding-bottom: 10px; border-bottom: 1px solid var(--text-dark); }

        .form-group { margin-bottom: 24px; }
        .form-group label { display: block; font-size: 11px; text-transform: uppercase; letter-spacing: 1.5px; font-weight: 600; margin-bottom: 8px; color: var(--text-dark); }
        
        .form-group input, .form-group select { 
            width: 100%; padding: 12px 0px; border: none; border-bottom: 1px solid var(--text-dark); 
            background: transparent; font-family: inherit; font-size: 15px; color: var(--text-dark); outline: none; transition: border-color 0.3s;
        }
        .form-group input:focus { border-bottom: 1.5px solid var(--accent-brown); }
        .form-row { display: grid; grid-template-columns: 2fr 1fr; gap: 20px; }

        /* Caixas dinâmicas de pagamento (escondidas por padrão, o JS mostra depois) */
        .payment-box {
            background-color: var(--bg-card);
            border: 1px solid var(--line-color);
            padding: 25px;
            margin-top: 20px;
            display: none; 
            animation: fadeIn 0.4s ease forwards;
        }

        .credit-card-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }

        /* Container do layout do PIX */
        .pix-container { display: flex; flex-direction: column; align-items: center; text-align: center; gap: 15px; }
        
        /* Renderiza um QR Code falso usando vetores puros (SVG), sem precisar de API externa */
        .pix-qrcode-placeholder {
            width: 160px; height: 160px; border: 1px solid var(--text-dark);
            display: flex; align-items: center; justify-content: center;
            background: #fff; padding: 10px;
        }
        .pix-qrcode-placeholder svg { width: 100%; height: 100%; }

        .pix-copia-cola {
            background: #f4f0ea; border: 1px dashed var(--line-color); padding: 10px;
            font-size: 11px; font-family: monospace; width: 100%; word-break: break-all; color: var(--text-muted);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(5px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Lateral direita: Resumo da Sacola Fixo (Sticky) */
        .summary-section { background: var(--bg-card); border: 1px solid var(--line-color); padding: 40px; position: sticky; top: 40px; }
        .summary-item { display: flex; justify-content: space-between; align-items: center; padding: 14px 0; border-bottom: 1px solid var(--line-color); font-size: 14px; }
        .summary-item:last-of-type { border-bottom: none; }
        .summary-item .item-name { font-weight: 500; max-width: 70%; }
        .summary-item .item-qty { font-size: 12px; color: var(--text-muted); text-transform: uppercase; }

        .total-row { display: flex; justify-content: space-between; align-items: baseline; margin-top: 30px; padding-top: 20px; border-top: 1px solid var(--text-dark); }
        .total-row span { font-size: 13px; text-transform: uppercase; letter-spacing: 1px; font-weight: 600; color: var(--text-muted); }
        .total-row .total-price { font-family: 'Playfair Display', serif; font-size: 30px; font-weight: 500; color: var(--text-dark); }

        .btn-submit-checkout { 
            display: block; width: 100%; background-color: var(--accent-brown); color: var(--bg-canvas); 
            border: none; padding: 18px; font-size: 13px; font-weight: 600; text-transform: uppercase; 
            letter-spacing: 2px; cursor: pointer; transition: all 0.3s ease; margin-top: 30px; 
        }
        .btn-submit-checkout:hover { background-color: var(--text-dark); transform: translateY(-1px); }

        /* Tela de sucesso que aparece quando o pedido é concluído */
        .pedido-card { 
            text-align: center; max-width: 600px; margin: 60px auto; padding: 60px 50px; 
            background: var(--bg-card); border: 1px solid var(--line-color);
            box-shadow: 0 10px 30px rgba(26, 21, 18, 0.03);
        }
        .pedido-card h1 { font-family: 'Playfair Display', serif; font-size: 36px; font-weight: 400; color: var(--accent-green); margin-bottom: 20px; }
        .pedido-card p { font-size: 15px; color: var(--text-dark); line-height: 1.7; margin-bottom: 12px; }
        .btn-track { 
            display: inline-block; background-color: var(--text-dark); color: var(--bg-canvas); 
            padding: 16px 32px; font-size: 12px; font-weight: 600; text-transform: uppercase; 
            letter-spacing: 1.5px; text-decoration: none; margin-top: 25px; transition: 0.3s; 
        }
        .btn-track:hover { background-color: var(--accent-brown); }
        .link-shop-back { display: block; margin-top: 20px; color: var(--text-muted); font-size: 13px; text-decoration: underline; font-weight: 500; }
        .link-shop-back:hover { color: var(--text-dark); }
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
            <span>Olá, <strong><?php echo htmlspecialchars($_SESSION['cliente_nome'] ?? 'Cliente'); ?></strong></span>
            <a href="index.php?action=logout" title="Sair da Conta">🚪</a>
            <span style="font-size: 18px; cursor: pointer;" onclick="location.href='carrinho.php'" title="Carrinho">🛒</span>
        </div>
    </nav>

    <div class="container">
        
        <?php if ($sucesso): ?>
            <div class="pedido-card">
                <h1>🎉 Pedido Confirmado</h1>
                <p>Muito obrigado por apoiar a produção artesanal da <strong>Maia Candle Co.</strong></p>
                <p style="color: var(--text-muted);">
                    Sua ordem de serviço <strong>#<?php echo $numero_pedido_gerado; ?></strong> foi gerada com sucesso e já foi encaminhada para a nossa oficina botânica.
                </p>
                
                <a href="pedidos.php" class="btn-track">Acompanhar Meus Pedidos</a>
                <a href="produtos.php" class="link-shop-back">Voltar para a vitrine principal</a>
            </div>
            <?php unset($_SESSION['ultimo_pedido_sucesso']); ?>

        <?php else: ?>
            <div class="checkout-title-area">
                <h1>Finalizar Compra</h1>
                <p>Preencha os dados de envio e selecione a modalidade de pagamento desejada.</p>
            </div>

            <div class="checkout-grid">
                
                <div class="form-section">
                    <h2 class="section-title">Endereço de Envio</h2>
                    <form action="checkout.php" method="POST" id="formCheckout">
                        
                        <div class="form-group">
                            <label for="nome">Nome Completo do Destinatário</label>
                            <input type="text" id="nome" name="nome" required 
                                   value="<?php echo htmlspecialchars($_SESSION['cliente_nome'] ?? ''); ?>"
                                   pattern="[A-Za-zÀ-ÿ\s]+" title="O nome deve conter apenas letras."
                                   oninput="this.value = this.value.replace(/[0-9]/g, '')">
                        </div>
                        
                        <div class="form-group">
                            <label for="email">E-mail para Atualizações do Rastreio</label>
                            <input type="email" id="email" name="email" required 
                                   value="<?php echo htmlspecialchars($_SESSION['cliente_email'] ?? ''); ?>">
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="endereco">Logradouro e Número</label>
                                <input type="text" id="endereco" name="endereco" required placeholder="Ex: Rua das Camélias, 245">
                            </div>
                            <div class="form-group">
                                <label for="cep">CEP</label>
                                <input type="text" id="cep" name="cep" required placeholder="00000-000" maxlength="9">
                            </div>
                        </div>

                        <h2 class="section-title" style="margin-top: 40px;">Método de Pagamento</h2>
                        <div class="form-group">
                            <label for="forma_pagamento">Selecione a Opção</label>
                            <select id="forma_pagamento" name="forma_pagamento" required>
                                <option value="Cartão de Crédito" selected>Cartão de Crédito (Até 6x sem juros)</option>
                                <option value="PIX">PIX (Com 5% de desconto extra)</option>
                                <option value="Boleto Bancário">Boleto Bancário</option>
                            </select>
                        </div>

                        <div id="box-cartao" class="payment-box">
                            <div class="form-group">
                                <label>Número do Cartão</label>
                                <input type="text" placeholder="0000 0000 0000 0000" maxlength="19">
                            </div>
                            <div class="form-group">
                                <label>Nome Gravado no Cartão</label>
                                <input type="text" placeholder="EX: CAROLINE M SILVA">
                            </div>
                            <div class="credit-card-row">
                                <div class="form-group">
                                    <label>Validade</label>
                                    <input type="text" placeholder="MM/AA" maxlength="5">
                                </div>
                                <div class="form-group">
                                    <label>CVV</label>
                                    <input type="text" placeholder="000" maxlength="4">
                                </div>
                            </div>
                        </div>

                        <div id="box-pix" class="payment-box">
                            <div class="pix-container">
                                <p style="font-size: 13px; font-weight: 500; color: var(--accent-green);">Aproxime o seu celular para escanear o código:</p>
                                <div class="pix-qrcode-placeholder">
                                    <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                                        <rect width="100" height="100" fill="none"/>
                                        <path d="M0,0 h30 v30 h-30 z M10,10 h10 v10 h-10 z" fill="#1a1512"/>
                                        <path d="M70,0 h30 v30 h-30 z M80,10 h10 v10 h-10 z" fill="#1a1512"/>
                                        <path d="M0,70 h30 v30 h-30 z M10,80 h10 v10 h-10 z" fill="#1a1512"/>
                                        <path d="M40,10 h10 v10 h-10 z M50,30 h10 v20 h-10 z M30,50 h20 v10 h-20 z M70,70 h10 v20 h-10 z M85,85 h10 v10 h-10 z" fill="#1a1512"/>
                                    </svg>
                                </div>
                                <p style="font-size: 12px; color: var(--text-muted);">Ou utilize o Pix Copia e Cola abaixo:</p>
                                <div class="pix-copia-cola">00020101021226830014br.gov.bcb.pix256102maiacandleco...</div>
                            </div>
                        </div>

                        <div id="box-boleto" class="payment-box" style="text-align: center; color: var(--text-muted); font-size: 13px; font-style: italic;">
                            O boleto bancário será gerado para impressão na próxima tela após a confirmação.
                        </div>
                        
                        <button type="submit" class="btn-submit-checkout">Garantir Minhas Velas</button>
                    </form>
                </div>

                <div class="summary-section">
                    <h2 class="section-title" style="border-bottom: 1px solid var(--line-color); padding-bottom: 15px; margin-bottom: 15px;">Resumo da Sacola</h2>
                    
                    <?php foreach ($produtos_checkout as $prod): ?>
                        <div class="summary-item">
                            <div class="item-name">
                                <?php echo htmlspecialchars($prod['nome']); ?> <br>
                                <span class="item-qty">Qtd: <?php echo $prod['formatting_quantity'] ?? $prod['quantidade']; ?></span>
                            </div>
                            <div style="font-weight: 500;">R$ <?php echo number_format($prod['subtotal'], 2, ',', '.'); ?></div>
                        </div>
                    <?php endforeach; ?>

                    <div class="total-row">
                        <span>Total do Pedido:</span>
                        <div class="total-price">R$ <?php echo number_format($total_pedido, 2, ',', '.'); ?></div>
                    </div>
                </div>

            </div>
        <?php endif; ?>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const selectPagamento = document.getElementById("forma_pagamento");
            const boxCartao = document.getElementById("box-cartao");
            const boxPix = document.getElementById("box-pix");
            const boxBoleto = document.getElementById("box-boleto");

            // Função que esconde todo mundo e só mostra quem foi selecionado no <select>
            function gerenciarCamposPagamento() {
               
                boxCartao.style.display = "none";
                boxPix.style.display = "none";
                boxBoleto.style.display = "none";

                const valorSelecionado = selectPagamento.value;
                if (valorSelecionado === "Cartão de Crédito") {
                    boxCartao.style.display = "block";
                } else if (valorSelecionado === "PIX") {
                    boxPix.style.display = "block";
                } else if (valorSelecionado === "Boleto Bancário") {
                    boxBoleto.style.display = "block";
                }
            }

            // Ouve quando o cliente troca a opção no menu
            selectPagamento.addEventListener("change", gerenciarCamposPagamento);

            // Executa uma vez ao carregar a página para garantir que a opção padrão (Cartão) já apareça aberta
            gerenciarCamposPagamento();
        });
    </script>

</body>
</html>