# Atlas de Países — HTML separado do PHP

## Arquivos
- `index.html`: página inicial e formulário de pesquisa.
- `consulta.php`: recebe o país enviado pelo formulário, consulta a API pública via HTTP, interpreta o JSON e exibe os dados.
- `style.css`: estilos compartilhados entre as páginas.

## Como executar
O PHP precisa ser executado por um servidor local; não abra os arquivos diretamente pelo explorador.
1. Copie a pasta `atlas-paises` para `C:\xampp\htdocs\`.
2. Inicie o Apache pelo XAMPP.
3. Acesse `http://localhost/atlas-paises/index.html`.
4. Pesquise um país e clique em **Consultar**.

A consulta à API é feita no servidor pelo PHP, não pelo JavaScript do navegador.
