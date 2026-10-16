
# 🌎 Atlas de Países

O **Atlas de Países** é uma aplicação web que permite pesquisar países e consultar informações por meio de uma API pública. O projeto utiliza PHP para realizar requisições HTTP, processar os dados recebidos em formato JSON e apresentar os resultados dinamicamente.

## 🚀 Funcionalidades

- 🔎 Pesquisa de países pelo nome.
- 🌐 Consulta de informações por meio de uma API pública.
- 📄 Processamento de dados JSON com PHP.
- 📊 Exibição das informações do país pesquisado.
- 💻 Interface desenvolvida com HTML e CSS.
- ⚠️ Tratamento de erros em pesquisas inválidas ou falhas na consulta.

## 🛠️ Tecnologias utilizadas

- **HTML5:** estrutura das páginas.
- **CSS3:** estilização da aplicação.
- **PHP:** processamento das pesquisas e comunicação com a API.
- **API pública:** fornecimento dos dados dos países.
- **JSON:** formato dos dados recebidos.
- **XAMPP:** ambiente local para executar o projeto.

## 📁 Estrutura do projeto

```text
Atlas-de-Paises/
├── index.html      # Página inicial e formulário de pesquisa
├── consulta.php    # Consulta à API e exibição dos resultados
├── style.css       # Estilos da aplicação
└── README.md       # Documentação do projeto
```

## ⚙️ Como executar o projeto

### 1. Instale o XAMPP

Baixe e instale o [XAMPP](https://www.apachefriends.org/).

### 2. Copie os arquivos

Coloque a pasta do projeto dentro do diretório `htdocs` do XAMPP.

No Windows, o caminho normalmente é:

```text
C:\xampp\htdocs\Atlas-de-Paises\
```

### 3. Inicie o servidor

Abra o painel de controle do XAMPP e inicie o **Apache**.

### 4. Acesse a aplicação

Abra o navegador e acesse:

```text
http://localhost/Atlas-de-Paises/index.html
```

Digite o nome de um país no campo de pesquisa para consultar suas informações.

## 🔄 Como funciona

1. O usuário informa o nome de um país no formulário.
2. O formulário envia os dados para o arquivo `consulta.php`.
3. O PHP realiza uma requisição HTTP à API pública.
4. A API retorna os dados do país em formato JSON.
5. O PHP processa a resposta e apresenta as informações na página.

## 🎯 Objetivo acadêmico

O projeto tem como objetivo praticar o desenvolvimento de aplicações web, a integração com APIs públicas, o envio de requisições HTTP, o processamento de dados JSON e a separação entre a estrutura HTML e a lógica de processamento em PHP.

## 📌 Observações

- É necessário utilizar um servidor com suporte a PHP, como o XAMPP.
- É necessária uma conexão com a internet para consultar a API.
- A disponibilidade das informações depende do funcionamento da API pública utilizada.

---

Desenvolvido como projeto educacional para praticar tecnologias de desenvolvimento web.

