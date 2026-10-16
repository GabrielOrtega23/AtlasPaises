Atlas de Países

O Atlas de Países é uma aplicação web que permite pesquisar países e consultar informações sobre eles por meio de uma API pública. O projeto utiliza PHP para realizar requisições HTTP, processar os dados recebidos em formato JSON e apresentar os resultados dinamicamente na página.

🚀 Funcionalidades

🔎 Pesquisa de países pelo nome.

🌐 Consulta de informações por meio de uma API pública.

📄 Processamento dos dados recebidos em JSON com PHP.

📊 Exibição dinâmica das informações do país pesquisado.

💻 Interface web desenvolvida com HTML e CSS.

⚠️ Tratamento de erros para pesquisas inválidas ou falhas na consulta.

🛠️ Tecnologias utilizadas

HTML5: estrutura das páginas.

CSS3: estilização e organização visual.

PHP: processamento das pesquisas e comunicação com a API.

API pública: fornecimento dos dados dos países.

JSON: formato utilizado para receber e processar os dados da API.

XAMPP: ambiente local para executar o projeto com PHP.

📁 Estrutura do projeto

Atlas-de-Paises/
├── index.html      # Página inicial e formulário de pesquisa
├── consulta.php    # Consulta à API e exibição dos resultados
├── style.css       # Estilos da aplicação
└── README.md       # Documentação do projeto

⚙️ Como executar o projeto

1. Instale o XAMPP

Baixe e instale o XAMPP.

2. Copie os arquivos

Coloque a pasta do projeto dentro do diretório htdocs do XAMPP.

No Windows, normalmente o caminho é:

C:\xampp\htdocs\Atlas-de-Paises\

3. Inicie o servidor

Abra o painel de controle do XAMPP e inicie o Apache.

4. Acesse a aplicação

Abra o navegador e acesse:

http://localhost/Atlas-de-Paises/index.html

Digite o nome de um país no campo de pesquisa para consultar suas informações.

🔄 Como funciona

O usuário informa o nome de um país no formulário da página inicial.

O formulário envia os dados para o arquivo consulta.php.

O PHP realiza uma requisição HTTP à API pública.

A API retorna os dados do país em formato JSON.

O PHP processa a resposta e apresenta as informações na página de resultados.

🎯 Objetivo acadêmico

O projeto tem como objetivo praticar o desenvolvimento de aplicações web, a integração com APIs públicas, o envio de requisições HTTP, o processamento de dados JSON e a separação entre a estrutura HTML e a lógica de processamento em PHP.

📌 Observações

É necessário utilizar um servidor com suporte a PHP, como o XAMPP.

É necessária uma conexão com a internet para consultar a API.

A disponibilidade das informações depende do funcionamento da API pública utilizada.

Desenvolvido como projeto educacional para praticar tecnologias de desenvolvimento web.
