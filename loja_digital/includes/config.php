<?php
/**
 * ARQUIVO DE CONFIGURAÇÃO E CONEXÃO COM BANCO DE DADOS
 * 
 * Este arquivo é responsável por conectar com o MySQL
 * Explicação para crianças: É como uma ponte que liga nosso site ao banco de dados
 */

// Configurações do banco de dados
define('DB_HOST', 'localhost:3306');  // Onde o banco está (computador local, porta 3306)
define('DB_USER', 'root');             // Usuário do banco (root é o padrão do XAMPP)
define('DB_PASS', '');                 // Senha (vazia no XAMPP)
define('DB_NAME', 'LOJA_DIGITAL');     // Nome do nosso banco de dados

// Criar conexão com o banco de dados
// mysqli_connect é como fazer uma ligação telefônica para o banco de dados
$conexao = mysqli_connect(DB_HOST, DB_USER, DB_PASS);

// Verificar se a conexão funcionou
if (!$conexao) {
    die("Erro ao conectar com o banco de dados: " . mysqli_connect_error());
}

// Selecionar o banco de dados que vamos usar
// É como escolher qual pasta abrir no computador
$db_selected = mysqli_select_db($conexao, DB_NAME);

if (!$db_selected) {
    die("Erro ao selecionar o banco de dados: " . mysqli_error($conexao));
}

// Configurar o charset para UTF-8 (para aceitar acentos e caracteres especiais)
mysqli_set_charset($conexao, "utf8");

/**
 * FUNÇÃO PARA PROTEGER CONTRA SQL INJECTION
 * 
 * SQL Injection é quando alguém mal-intencionado tenta colocar código malicioso
 * Explicação: É como limpar e verificar tudo que entra na nossa casa
 */
function limpar_entrada($conexao, $dados) {
    // mysqli_real_escape_string limpa caracteres perigosos
    $dados = mysqli_real_escape_string($conexao, $dados);
    $dados = trim($dados);  // Remove espaços extras
    $dados = stripslashes($dados);  // Remove barras invertidas
    return $dados;
}

/**
 * FUNÇÃO PARA EXECUTAR CONSULTAS SQL
 * 
 * Facilita a execução de comandos no banco de dados
 */
function executar_query($conexao, $sql) {
    // mysqli_query executa o comando SQL
    $resultado = mysqli_query($conexao, $sql);
    
    if (!$resultado) {
        echo "Erro na consulta: " . mysqli_error($conexao);
        return false;
    }
    
    return $resultado;
}

/**
 * FUNÇÃO PARA BUSCAR DADOS
 * 
 * Retorna todos os resultados de uma consulta em um array
 */
function buscar_dados($conexao, $sql) {
    $resultado = executar_query($conexao, $sql);
    $dados = array();
    
    if ($resultado) {
        // mysqli_fetch_assoc pega uma linha por vez do resultado
        // É como pegar um livro de cada vez de uma prateleira
        while ($linha = mysqli_fetch_assoc($resultado)) {
            $dados[] = $linha;
        }
    }
    
    return $dados;
}

/**
 * FUNÇÃO PARA REDIRECIONAR PÁGINAS
 */
function redirecionar($url) {
    header("Location: $url");
    exit();
}

/**
 * FUNÇÃO PARA MOSTRAR MENSAGENS
 */
function mostrar_mensagem($mensagem, $tipo = 'success') {
    $icone = $tipo == 'success' ? '✓' : '✗';
    $classe = $tipo == 'success' ? 'alert-success' : 'alert-danger';
    
    return "<div class='alert $classe alert-dismissible fade show' role='alert'>
                <strong>$icone</strong> $mensagem
                <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
            </div>";
}
?>
