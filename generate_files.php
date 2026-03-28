<?php
header('Content-Type: application/json');

// CONFIG
$base_dir = __DIR__;
$base_url = "https://SEU_SITE/apiraife/com.raiferoleplay.game/"; // ⚠️ usa HTTPS

function listarArquivos($dir, $base_dir, $base_url) {
    $arquivos = [];
    $itens = scandir($dir);

    foreach ($itens as $item) {
        // ignora lixo
        if ($item === '.' || $item === '..') continue;
        if ($item === 'generate_files.php') continue;

        $caminho_completo = $dir . '/' . $item;
        $caminho_relativo = str_replace($base_dir . '/', '', $caminho_completo);

        // se for pasta, entra nela
        if (is_dir($caminho_completo)) {
            $arquivos = array_merge(
                $arquivos,
                listarArquivos($caminho_completo, $base_dir, $base_url)
            );
        } else {

            // ignora arquivos desnecessários
            if (pathinfo($item, PATHINFO_EXTENSION) === 'php') continue;

            $arquivos[] = [
                "name" => $item,
                "size" => filesize($caminho_completo),
                "path" => str_replace('\\', '/', $caminho_relativo),
                "url"  => $base_url . str_replace('\\', '/', $caminho_relativo)
            ];
        }
    }

    return $arquivos;
}

// saída final
echo json_encode(
    [
        "files" => listarArquivos($base_dir, $base_dir, $base_url)
    ],
    JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
);