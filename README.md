# Sistema de Gerenciamento de Velas Aromáticas
--Maia Candle Co--

# Descrição do Projeto
-O sistema Maia Candle Co é um sistema web completo desenvolvido para atender às necessidades de gerenciamento de uma loja especializada em velas aromáticas artesanais. O site centraliza informações essenciais para o controle do catálogo, estoque e organização dos produtos comercializados pela empresa.

O sistema foi projetado para oferecer uma experiência moderna, intuitiva e eficiente, permitindo que o administrador acompanhe todas as informações das velas cadastradas, realize pesquisas rápidas, mantenha o estoque atualizado e gerencie os produtos de forma segura e organizada.

Por meio das operações de CRUD e de funcionalidades complementares de consulta e controle, o sistema proporciona maior produtividade e confiabilidade na administração da loja.

# Objetivo do Projeto 
O objetivo desse projeto é desenvolver um sistema web utilizando PHP e MySQL, com foco no gerenciamento de produtos(velas aromáticas), permitindo:
-Cadastro completo de velas aromáticas;
-Consulta detalhada dos produtos cadastrados;
-Atualização de informações dos produtos;
-Exclusão segura de registros;
-Pesquisa avançada por nome e aroma;
-Controle de estoque;
-Organização automática dos produtos;
-Visualização rápida das informações principais;
-Validação de dados para garantir integridade das informações;
-Interface moderna e intuitiva para facilitar a utilização.

# Funcionalidades 
--Gestão de Produtos--
-Cadastro completo das velas
-Editar produtos
-Exclusão de produtos registrados
-Consulta detalhada de cada produto
-Visualização organizada
-exibição de informações completas do produto

# Controle do Estoque
-Registro da quantidade disponível de cada produto
-Atualização das informações de estoque
-Identificação de produtos com o estoque baixo
-Controle invidual de cada produto

# Sistema de Pesquisa
-Pesquisa por nome de vela
-Por aroma
-Exibição instantânea dos resultados que a no site

# Recursos de Usabilidade
-Mensagens de confirmação para operações realizadas
-Confirmação antes da exclusão de qualquer produto 
-Interface responsiva
-Navegação intuitiva
-Layout padronizado em todas as páginas

# Recursos Administrativos

-Visualização da quantidade total de produtos cadastrados
-Organização automática dos registros em orgem alfabética
-Controle centralizado das informações da loja

# Requisitos Funcionais
RF01: O sistema deve permitir cadastrar novas velas aromáticas.
RF02: O sistema deve listar todos os produtos cadastrados.
RF03: O sistema deve permitir visualizar informações detalhadas de cada produto.
RF04: O sistema deve permitir editar informações dos produtos.
RF05: O sistema deve permitir excluir produtos cadastrados.
RF06: O sistema deve permitir pesquisar produtos pelo nome.
RF07: O sistema deve permitir pesquisar produtos pelo aroma.
RF08: O sistema deve validar todos os campos obrigatórios antes do cadastro.
RF09: O sistema deve exibir mensagens de sucesso após operações concluídas.
RF10: O sistema deve exibir mensagens de erro quando houver inconsistências nos dados.
RF11: O sistema deve apresentar os produtos em ordem alfabética.
RF12: O sistema deve exibir a quantidade disponível em estoque.
RF13: O sistema deve permitir atualizar o estoque dos produtos.
RF14: O sistema deve exibir o total de produtos cadastrados.
RF15: O sistema deve solicitar confirmação antes da exclusão de um registro.

# Requisitos não Funcionais

RNF01: O sistema deve ser desenvolvido utilizando PHP.
RNF02: O banco de dados utilizado deve ser MySQL.
RNF03: A interface deve possuir design moderno e intuitivo.
RNF04: O sistema deve utilizar HTML5 e CSS3.
RNF05: O código deve ser organizado em módulos e arquivos separados.
RNF06: O sistema deve apresentar boa performance durante consultas e operações.
RNF07: O layout deve ser responsivo para diferentes tamanhos de tela.
RNF08: O sistema deve garantir integridade dos dados armazenados.
RNF09: O sistema deve possuir padronização visual em todas as páginas.
RNF10: O sistema deve apresentar mensagens claras ao usuário.

# Regras de Negócio
N01: Toda vela deve possuir um nome válido.
RN02: O aroma da vela é obrigatório.
RN03: O tamanho da vela deve ser informado.
RN04: O preço deve ser maior que R$ 0,00.
RN05: O estoque não pode possuir valores negativos.
RN06: A descrição do produto é obrigatória.
RN07: Não será permitido cadastrar registros com campos vazios.
RN08: Apenas produtos cadastrados poderão ser editados.
RN09: Apenas produtos cadastrados poderão ser excluídos.
RN10: O sistema deverá solicitar confirmação antes da exclusão.
RN11: Os produtos deverão ser exibidos em ordem alfabética.
RN12: O estoque deverá ser atualizado sempre que houver alteração no cadastro.
RN13: O identificador do produto deverá ser único.
RN14: O sistema deverá impedir o cadastro de preços inválidos.
RN15: O sistema deverá registrar corretamente todas as alterações realizadas nos produtos.

# Estrutura do Banco de Dados

Campo	Tipo	Descrição
id	INT	Identificador único do produto
nome	VARCHAR(100)	Nome da vela
aroma	VARCHAR(50)	Aroma principal
tamanho	VARCHAR(20)	Tamanho da vela
cor	VARCHAR(30)	Cor predominante
preco	DECIMAL(10,2)	Valor de venda
estoque	INT	Quantidade disponível
descricao	TEXT	Descrição detalhada do produto

# Casos de Uso 
Cadastrar Vela 
- O administrador acessa a tela de cadastro 
- Informa todos os dados do produto que ele deseja
- O sistema valida os campos obrigatórios
- O sistema verifica a consistência dos dados
-O registro é armazenado no bando de dados
- O sistema exibe mensagem de sucesso
- O produto passa a integrar o catálogo da loja

# Consultar Produtos
- O administrador informa um termo de pesquisa.
- O sistema realiza a busca pelo nome ou aroma.
- Os resultados encontrados são exibidos instantaneamente.
- O usuário pode selecionar um produto para visualizar ou editar.

# Editar Produto
- Seleciona um produto
- O sistema carrega os dados
- As informações são alteradas
- O sistema valida os novos dados
- As alterações são gravadas no bando de dados
- O sistema exibe uma mensgame confirmando que o produto foi editado

# Excluir Produto
- Selecione um produto
- O sistema solicita a exclusão do produto
- Após confirmação o registro é removido
- O sistema atualiza a listagem automaticamente
- Uma mensagem de sucesso é exibida ao usuário

# Gerenciar Estoque 
- Acesse os dados do produto
- Atualiza a quantidade disponível
- O sistema valida o valor informado
- O estoque é atualizado no banco de dados
- As novas informações são exibidas na listagem

# Tecnologias utilizadas 
- PHP
-mYSQL
-html
-css
-Vscode

# Senha para acessar login funcionario
email: admin@maiacandle.com.br
senha: admin123

# Para acessar o SITE
http://localhost:8000/portal.php

# Estrtura do Projeto
maiacandle/ 
├── css/ │ 
└── estilo.css │
 ├── conexao.php │
  ├── index.php │ 
  ├── cadastrar.php │ 
  ├── salvar.php │
   ├── visualizar.php │
    ├── editar.php │ 
    ├── atualizar.php │
     ├── excluir.php │
      ├── pesquisar.php │
       ├── componentes/ │
        ├── header.php │
         └── footer.php │
          ├── banco/ │
           └── dump.sql │
            └── README.md

# Como acessar o BANCOMYSQL do projeto
Passo 1: Inicialização do Banco de Dados
A aplicação utiliza o banco de dados MySQL para a persistência dos dados. Certifique-se de que o serviço do MySQL está ativo no seu servidor local (operando por padrão na porta 3306) e execute o script contido em banco/dump.sql através do seu gerenciador (como phpMyAdmin, Workbench ou CLI) para provisionar a base de dados maiacandle_db.

Passo 2: Parametrização do Arquivo de Conexão
Abra o arquivo conexao.php localizado na raiz do projeto e certifique-se de que as credenciais apontam para o seu ambiente local do MySQL. O arquivo deve seguir a estrutura homologada abaixo:

PHP
$host    = 'localhost';
$dbname  = 'projeto_velas';
$user    = 'postgres';          
$password = 'POSTGRES';   

# Como acessar meu site na WEB
Passo 3: Inicialização do Servidor na Porta 8000
Abra o seu terminal de comandos, navegue até a pasta raiz do projeto (maiacandle/) e inicialize o servidor de desenvolvimento embutido do PHP apontando explicitamente para a porta 8000:

Bash
php -S localhost:8000
Se o comando for executado com sucesso, o terminal exibirá a mensagem de que o servidor está ativo e ouvindo a porta indicada.

Passo 4: Acesso à Aplicação via Navegador
Com o servidor PHP ativo no terminal e o serviço MySQL rodando em segundo plano, abra o navegador web de sua preferência e acesse o sistema através do endereço unificado:

Plaintext
http://localhost:8000

# Considerações Finais 
O Maia Candle Co foi criado como uma solução completa para o gerenciamneto de produtos de uma loja especializada em velas aromáticas artesanais. O sistema reúne funcionalidades de cadastro, consulta, pesquisa, atualização e controle de estoque em uma única plataforma, proporcionando maior organização, produtividade e confiabilidade na administração do catálogo de produtos.

Além de atender aos requisitos funcionais de um sistema CRUD, o projeto incorpora recursos adicionais de pesquisa, validação, controle e usabilidade, tornando-se uma aplicação mais profissional, estruturada e alinhada às necessidades reais de um negócio.


