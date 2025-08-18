<?php

/**
 * Proxy para redirecionar requisições para a pasta ./site
 * Este arquivo serve como ponto de entrada principal
 */

// Definir o diretório base
define('SITE_DIR', __DIR__ . '/site');

// Obter a URL solicitada
$request_uri = $_SERVER['REQUEST_URI'];
$path = parse_url($request_uri, PHP_URL_PATH);
$query = parse_url($request_uri, PHP_URL_QUERY);

// Remover a raiz se necessário
$path = ltrim($path, '/');

// Se não houver path, redirecionar para o site
if (empty($path)) {
    $path = 'index.php';
}

// Verificar se o arquivo existe na pasta site
$file_path = SITE_DIR . '/' . $path;

// Se for um arquivo PHP, incluir diretamente
if (is_file($file_path) && pathinfo($file_path, PATHINFO_EXTENSION) === 'php') {
    // Definir o diretório de trabalho
    chdir(SITE_DIR);

    // Preservar os parâmetros GET
    if ($query) {
        parse_str($query, $_GET);
    }

    // Incluir o arquivo
    include $file_path;
    exit;
}

// Se for um arquivo estático (CSS, JS, etc.), servir diretamente
if (is_file($file_path)) {
    $extension = pathinfo($file_path, PATHINFO_EXTENSION);
    $mime_types = [
        'css' => 'text/css',
        'js' => 'application/javascript',
        'png' => 'image/png',
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'gif' => 'image/gif',
        'svg' => 'image/svg+xml',
        'ico' => 'image/x-icon',
        'woff' => 'font/woff',
        'woff2' => 'font/woff2',
        'ttf' => 'font/ttf',
        'eot' => 'application/vnd.ms-fontobject'
    ];

    if (isset($mime_types[$extension])) {
        header('Content-Type: ' . $mime_types[$extension]);
        readfile($file_path);
        exit;
    }
}

// Se não encontrar o arquivo, redirecionar para o site/index.php
chdir(SITE_DIR);
include 'index.php';
