<?php
/**
 * PÁGINA PRINCIPAL - VITRINE DE PRODUTOS
 * 
 * Explicação para crianças:
 * Esta é a página inicial da nossa loja, como a vitrine de uma loja de verdade!
 * Mostramos os produtos mais vendidos e os últimos produtos cadastrados
 */

// Incluir arquivo de configuração e conexão
require_once 'includes/config.php';

$titulo_pagina = 'Home - Vitrine';

// BUSCAR OS 9 PRODUTOS MAIS VENDIDOS
// ORDER BY total_vendas DESC = ordenar do maior para o menor número de vendas
// LIMIT 9 = pegar apenas 9 produtos
$sql_mais_vendidos = "SELECT * FROM Arquivo_Digital 
                      WHERE ativo = 1 
                      ORDER BY total_vendas DESC 
                      LIMIT 9";
$mais_vendidos = buscar_dados($conexao, $sql_mais_vendidos);

// Pegar os IDs dos produtos mais vendidos para não repetir
$ids_mais_vendidos = array();
foreach ($mais_vendidos as $produto) {
    $ids_mais_vendidos[] = $produto['id_arquivo'];
}

// BUSCAR OS 3 ÚLTIMOS PRODUTOS CADASTRADOS (que não estão nos mais vendidos)
// ORDER BY data_cadastro DESC = ordenar do mais recente para o mais antigo
$sql_ultimos = "SELECT * FROM Arquivo_Digital 
                WHERE ativo = 1";

// Se tiver produtos mais vendidos, não mostrar eles aqui
if (count($ids_mais_vendidos) > 0) {
    $ids_string = implode(',', $ids_mais_vendidos);
    $sql_ultimos .= " AND id_arquivo NOT IN ($ids_string)";
}

$sql_ultimos .= " ORDER BY data_cadastro DESC LIMIT 3";
$ultimos_produtos = buscar_dados($conexao, $sql_ultimos);

// Incluir o cabeçalho
include 'includes/header.php';
?>

<!-- Seção de Boas-vindas -->
<div class="jumbotron bg-light p-5 rounded mb-4">
    <h1 class="display-4">
        <i class="bi bi-shop text-primary"></i> Bem-vindo à Loja Digital!
    </h1>
    <p class="lead">Encontre os melhores produtos digitais: cursos, e-books, músicas e muito mais!</p>
    <hr class="my-4">
    <p>Navegue pelas nossas categorias e descubra conteúdos incríveis.</p>
</div>

<!-- SEÇÃO: PRODUTOS MAIS VENDIDOS -->
<section class="mb-5">
    <h2 class="mb-4">
        <i class="bi bi-fire text-danger"></i> Produtos Mais Vendidos
    </h2>
    
    <div class="row">
        <?php if (count($mais_vendidos) > 0): ?>
            <?php foreach ($mais_vendidos as $produto): ?>
                <div class="col-md-4 produto-card">
                    <div class="card">
                        <!-- Imagem do produto (usando ícone como placeholder) -->
                        <div class="produto-img">
                            <i class="bi bi-file-earmark-code"></i>
                        </div>
                        
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $produto['titulo']; ?></h5>
                            <p class="card-text text-muted">
                                <?php echo substr($produto['descricao'], 0, 80) . '...'; ?>
                            </p>
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="h5 text-success mb-0">
                                    R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?>
                                </span>
                                <span class="badge bg-info">
                                    <i class="bi bi-graph-up"></i> 
                                    <?php echo $produto['total_vendas']; ?> vendas
                                </span>
                            </div>
                            
                            <div class="mt-3">
                                <a href="pages/arquivo_digital/detalhes.php?id=<?php echo $produto['id_arquivo']; ?>" 
                                   class="btn btn-primary btn-sm w-100">
                                    <i class="bi bi-eye"></i> Ver Detalhes
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> Nenhum produto encontrado.
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- SEÇÃO: ÚLTIMOS PRODUTOS CADASTRADOS -->
<section class="mb-5">
    <h2 class="mb-4">
        <i class="bi bi-clock-history text-primary"></i> Últimos Produtos Cadastrados
    </h2>
    
    <div class="row">
        <?php if (count($ultimos_produtos) > 0): ?>
            <?php foreach ($ultimos_produtos as $produto): ?>
                <div class="col-md-4 produto-card">
                    <div class="card border-primary">
                        <div class="card-header bg-primary text-white">
                            <i class="bi bi-star-fill"></i> Novo!
                        </div>
                        
                        <div class="produto-img">
                            <i class="bi bi-file-earmark-plus"></i>
                        </div>
                        
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $produto['titulo']; ?></h5>
                            <p class="card-text text-muted">
                                <?php echo substr($produto['descricao'], 0, 80) . '...'; ?>
                            </p>
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="h5 text-success mb-0">
                                    R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?>
                                </span>
                                <small class="text-muted">
                                    <i class="bi bi-calendar"></i>
                                    <?php echo date('d/m/Y', strtotime($produto['data_cadastro'])); ?>
                                </small>
                            </div>
                            
                            <div class="mt-3">
                                <a href="pages/arquivo_digital/detalhes.php?id=<?php echo $produto['id_arquivo']; ?>" 
                                   class="btn btn-outline-primary btn-sm w-100">
                                    <i class="bi bi-eye"></i> Ver Detalhes
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> Nenhum produto novo encontrado.
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Seção de Estatísticas -->
<section class="mb-5">
    <div class="row text-center">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <i class="bi bi-file-earmark display-4"></i>
                    <h3 class="mt-2">
                        <?php 
                        $total_produtos = mysqli_fetch_assoc(executar_query($conexao, "SELECT COUNT(*) as total FROM Arquivo_Digital WHERE ativo = 1"));
                        echo $total_produtos['total'];
                        ?>
                    </h3>
                    <p>Produtos Ativos</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <i class="bi bi-tags display-4"></i>
                    <h3 class="mt-2">
                        <?php 
                        $total_categorias = mysqli_fetch_assoc(executar_query($conexao, "SELECT COUNT(*) as total FROM Categoria WHERE ativo = 1"));
                        echo $total_categorias['total'];
                        ?>
                    </h3>
                    <p>Categorias</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <i class="bi bi-people display-4"></i>
                    <h3 class="mt-2">
                        <?php 
                        $total_usuarios = mysqli_fetch_assoc(executar_query($conexao, "SELECT COUNT(*) as total FROM Usuario WHERE ativo = 1"));
                        echo $total_usuarios['total'];
                        ?>
                    </h3>
                    <p>Usuários Ativos</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <i class="bi bi-cart display-4"></i>
                    <h3 class="mt-2">
                        <?php 
                        $total_compras = mysqli_fetch_assoc(executar_query($conexao, "SELECT COUNT(*) as total FROM Compra"));
                        echo $total_compras['total'];
                        ?>
                    </h3>
                    <p>Compras Realizadas</p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
// Incluir o rodapé
include 'includes/footer.php';
?>
