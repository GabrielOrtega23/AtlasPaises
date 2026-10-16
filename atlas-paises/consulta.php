<?php
declare(strict_types=1);

function escapar(mixed $valor): string {
    return htmlspecialchars((string)$valor, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function normalizar(string $texto): string {
    $texto = mb_strtolower(trim($texto), 'UTF-8');
    $semAcento = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $texto);
    return preg_replace('/[^a-z0-9 ]/', '', $semAcento !== false ? $semAcento : $texto) ?? $texto;
}

function consultarApi(string $url): array {
    $contexto = stream_context_create(['http' => [
        'method' => 'GET',
        'timeout' => 12,
        'ignore_errors' => true,
        'header' => "Accept: application/json\r\nUser-Agent: AtlasDePaises/1.0\r\n"
    ], 'https' => ['verify_peer' => true, 'verify_peer_name' => true]]);

    if (function_exists('curl_init')) {
        $curl = curl_init($url);
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_CONNECTTIMEOUT => 6,
            CURLOPT_TIMEOUT => 12,
            CURLOPT_HTTPHEADER => ['Accept: application/json', 'User-Agent: AtlasDePaises/1.0'],
        ]);
        $corpo = curl_exec($curl);
        $status = (int)curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $erro = curl_error($curl);
        curl_close($curl);
        if ($corpo === false) throw new RuntimeException('Falha na conexão com a API. ' . $erro);
    } else {
        $corpo = @file_get_contents($url, false, $contexto);
        $status = 200;
        if ($corpo === false) throw new RuntimeException('Não foi possível conectar à API. Verifique a conexão e se o PHP permite requisições HTTPS.');
        if (isset($http_response_header)) {
            foreach ($http_response_header as $linha) if (preg_match('/^HTTP\/\S+\s+(\d+)/', $linha, $m)) $status = (int)$m[1];
        }
    }
    if ($status === 404) return [];
    if ($status < 200 || $status >= 300) throw new RuntimeException('A API respondeu com o código HTTP ' . $status . '.');
    $dados = json_decode((string)$corpo, true);
    if (json_last_error() !== JSON_ERROR_NONE || !is_array($dados)) throw new RuntimeException('A resposta da API não contém JSON válido.');
    return $dados;
}

function listaDeNomes(mixed $valor): array {
    if (is_string($valor) && $valor !== '') return [$valor];
    if (!is_array($valor)) return [];
    $nomes = [];
    foreach ($valor as $item) {
        if (is_string($item) && $item !== '') $nomes[] = $item;
        elseif (is_array($item)) {
            $nome = $item['name'] ?? $item['common'] ?? $item['official'] ?? null;
            if (is_string($nome) && $nome !== '') $nomes[] = $nome;
        }
    }
    return array_values(array_unique($nomes));
}

function nomeDoPais(array $pais): string {
    $nome = $pais['name'] ?? '';
    if (is_array($nome)) return (string)($pais['translations']['por']['common'] ?? $nome['common'] ?? $nome['official'] ?? 'País');
    return (string)($pais['translations']['por']['common'] ?? $nome ?: 'País');
}

function procurarPais(string $termo): array {
    $aliases = [
        'brasil'=>'Brazil','alemanha'=>'Germany','espanha'=>'Spain','estados unidos'=>'United States',
        'reino unido'=>'United Kingdom','japao'=>'Japan','coreia do sul'=>'South Korea','coreia do norte'=>'North Korea',
        'india'=>'India','egito'=>'Egypt','nova zelandia'=>'New Zealand','islandia'=>'Iceland','paises baixos'=>'Netherlands',
        'holanda'=>'Netherlands','suica'=>'Switzerland','belgica'=>'Belgium','irlanda'=>'Ireland','russia'=>'Russia',
        'mexico'=>'Mexico','argentina'=>'Argentina','bolivia'=>'Bolivia','chile'=>'Chile','colombia'=>'Colombia',
        'peru'=>'Peru','venezuela'=>'Venezuela','equador'=>'Ecuador','paraguai'=>'Paraguay','uruguai'=>'Uruguay',
        'republica tcheca'=>'Czechia','turquia'=>'Turkey','arabia saudita'=>'Saudi Arabia','africa do sul'=>'South Africa',
        'marrocos'=>'Morocco','tailandia'=>'Thailand','vietna'=>'Vietnam','filipinas'=>'Philippines','indonesia'=>'Indonesia'
    ];
    $consulta = $aliases[normalizar($termo)] ?? $termo;
    $url = 'https://countries.dev/name/' . rawurlencode($consulta);
    $dados = consultarApi($url);
    if (isset($dados['data']) && is_array($dados['data'])) $dados = $dados['data'];
    if (isset($dados['country']) && is_array($dados['country'])) $dados = $dados['country'];
    if (!array_is_list($dados)) $dados = [$dados];
    $alvo = normalizar($consulta);
    usort($dados, function($a, $b) use ($alvo) {
        $aNome = normalizar(is_array($a['name'] ?? null) ? ($a['name']['common'] ?? '') : (string)($a['name'] ?? ''));
        $bNome = normalizar(is_array($b['name'] ?? null) ? ($b['name']['common'] ?? '') : (string)($b['name'] ?? ''));
        return (($aNome === $alvo) ? 0 : 1) <=> (($bNome === $alvo) ? 0 : 1);
    });
    return array_values(array_filter($dados, 'is_array'));
}

$termo = '';
$pais = null;
$erro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $termo = trim((string)($_POST['pais'] ?? ''));
    if ($termo === '') {
        $erro = 'Digite o nome de um país para consultar.';
    } elseif (mb_strlen($termo, 'UTF-8') > 60) {
        $erro = 'Digite um nome de país com até 60 caracteres.';
    } else {
        try {
            $resultados = procurarPais($termo);
            if (!$resultados) $erro = 'Nenhum país encontrado. Confira o nome e tente novamente.';
            else $pais = $resultados[0];
        } catch (Throwable $e) {
            $erro = 'Não foi possível consultar a API neste momento. Verifique a conexão com a internet e tente novamente.';
            error_log('Atlas de Países: ' . $e->getMessage());
        }
    }
}

$nome = $pais ? nomeDoPais($pais) : '';
$nomeOficialRaw = $pais['name']['official'] ?? $pais['name'] ?? $nome;
$nomeOficial = is_array($nomeOficialRaw) ? (string)($nomeOficialRaw['official'] ?? $nome) : (string)$nomeOficialRaw;
$capital = listaDeNomes($pais['capital'] ?? []);
$idiomasRaw = $pais['languages'] ?? [];
$idiomas = [];
if (is_array($idiomasRaw)) {
    foreach ($idiomasRaw as $idioma) {
        if (is_string($idioma)) $idiomas[] = $idioma;
        elseif (is_array($idioma) && isset($idioma['name'])) $idiomas[] = (string)$idioma['name'];
    }
}
$moedasRaw = $pais['currencies'] ?? [];
$moedas = [];
if (is_array($moedasRaw)) {
    foreach ($moedasRaw as $codigo => $moeda) {
        if (is_array($moeda)) {
            $nomeMoeda = $moeda['name'] ?? $moeda['name']['common'] ?? '';
            $simbolo = $moeda['symbol'] ?? '';
            $codigoMoeda = $moeda['code'] ?? (is_string($codigo) ? $codigo : '');
            $moedas[] = trim((string)$nomeMoeda . ($simbolo ? ' (' . $simbolo . ')' : '') . ($codigoMoeda ? ' — ' . $codigoMoeda : ''));
        } elseif (is_string($moeda)) $moedas[] = $moeda;
    }
}
$regioes = ['Africa'=>'África','Americas'=>'Américas','Asia'=>'Ásia','Europe'=>'Europa','Oceania'=>'Oceania','Antarctic'=>'Antártida'];
$regiaoRaw = (string)($pais['region'] ?? $pais['continents'][0] ?? '');
$regiao = $regioes[$regiaoRaw] ?? $regiaoRaw ?: 'Não informado';
$populacao = isset($pais['population']) && is_numeric($pais['population']) ? number_format((float)$pais['population'], 0, ',', '.') : 'Não informada';
$area = isset($pais['area']) && is_numeric($pais['area']) ? number_format((float)$pais['area'], 0, ',', '.') . ' km²' : 'Não informada';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Resultado — Atlas de Países</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="grade" aria-hidden="true"></div>
<main class="pagina">
<header class="topo">
<p class="sobretitulo">Consulta de países</p>
<h1>Atlas <em>de Países</em></h1>
<p class="lead">Resultado da consulta realizada pelo PHP.</p>
</header>
<section class="resultado" aria-live="polite">
<?php if ($erro !== ''): ?>
<div class="alerta" role="alert"><h2>Não foi possível concluir</h2><p><?= escapar($erro) ?></p></div>
<?php elseif ($pais): ?>
<article class="pais" data-reveal>
<div class="pais-topo"><div class="identidade">
<span class="selo">Resultado da consulta</span>
<h2><?= escapar($nome) ?></h2>
<p class="oficial"><?= escapar($nomeOficial) ?></p>
<div class="codigos">
<?php if (!empty($pais['cca2'])): ?><span>Código: <?= escapar($pais['cca2']) ?></span><?php endif; ?>
<?php if (!empty($pais['cca3'])): ?><span>Sigla: <?= escapar($pais['cca3']) ?></span><?php endif; ?>
</div></div></div>
<div class="metricas">
<div class="metrica destaque"><span class="rotulo">Capital</span><strong><?= escapar(implode(', ', $capital) ?: 'Não informada') ?></strong></div>
<div class="metrica"><span class="rotulo">População</span><strong><?= escapar($populacao) ?></strong><small>habitantes</small></div>
<div class="metrica"><span class="rotulo">Área</span><strong><?= escapar($area) ?></strong></div>
<div class="metrica"><span class="rotulo">Continente</span><strong><?= escapar($regiao) ?></strong></div>
</div>
<div class="detalhes">
<section class="bloco"><h3>Idiomas</h3><div class="fichas"><?php if ($idiomas): foreach ($idiomas as $idioma): ?><span><?= escapar($idioma) ?></span><?php endforeach; else: ?><span>Não informado</span><?php endif; ?></div></section>
<section class="bloco"><h3>Moeda</h3><div class="fichas"><?php if ($moedas): foreach ($moedas as $moeda): ?><span><?= escapar($moeda) ?></span><?php endforeach; else: ?><span>Não informada</span><?php endif; ?></div></section>
</div>
</article>
<?php endif; ?>
<div style="text-align:center;margin-top:28px"><a class="botao link-botao" href="index.html">Voltar à pesquisa</a></div>
</section>
<footer class="rodape">Dados consultados em tempo real pela API pública Countries.dev.</footer>
</main>
</body>
</html>
