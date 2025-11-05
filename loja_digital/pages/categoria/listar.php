<?php
/**
 * LISTAGEM DE CATEGORIAS
 * 
 * Explicação para crianças:
 * Esta página mostra todas as categorias de produtos
 * É como organizar brinquedos em caixas diferentes!
 */

require_once '../../includes/config.php';

$titulo_pagina = 'Listagem de Categorias';

$mensagem = '';
if (isset($_GET['msg'])) {
    if ($_GET['msg'] == 'sucesso') {
        $mensagem = mostrar_mensagem('Operação realizada com sucesso!', 'success');
    } elseif ($_GET['msg'] == 'erro') {
        $mensagem = mostrar_mensagem('Erro ao realizar operação!', 'danger');
    }
}

// BUSCAR TODAS AS CATEGORIAS
$sql = "SELECT c.*, 
        (SELECT COUNT(*) FROM Arquivo_Digital WHERE id_categoria = c.id_categoria) as total_produtos
        FROM Categoria c 
        ORDER BY c.id_categoria DESC";
$categorias = buscar_dados($conexao, $sql);

include '../../includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>
        <i class="bi bi-tags-fill text-primary"></i> Listagem de Categorias
    </h2>
    <a href="inserir.php" class="btn btn-success">
        <i class="bi bi-plus-circle"></i> Nova Categoria
    </a>
</div>

<?php echo $mensagem; ?>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Descrição</th>
                        <th>Total Produtos</th>
                        <th>Status</th>
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($categorias) > 0): ?>
                        <?php foreach ($categorias as $categoria): ?>
                            <tr>
                                <td><?php echo $categoria['id_categoria']; ?></td>
                                <td>
                                    <i class="bi bi-tag-fill text-primary"></i>
                                    <strong><?php echo $categoria['nome']; ?></strong>
                                </td>
                                <td><?php echo substr($categoria['descricao'], 0, 50) . '...'; ?></td>
                                <td>
                                    <span class="badge bg-info">
                                        <i class="bi bi-file-earmark"></i>
                                        <?php echo $categoria['total_produtos']; ?> produtos
                                    </span>
                                </td>
                                <td>
                                    <?php if ($categoria['ativo']): ?>
                                        <i class="bi bi-check-circle-fill icon-ativo"></i>
                                        <span class="text-success">Ativo</span>
                                    <?php else: ?>
                                        <i class="bi bi-x-circle-fill icon-inativo"></i>
                                        <span class="text-danger">Inativo</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center table-actions">
                                    <a href="detalhes.php?id=<?php echo $categoria['id_categoria']; ?>" 
                                       class="btn btn-info btn-sm" 
                                       title="Ver Detalhes">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    
                                    <a href="editar.php?id=<?php echo $categoria['id_categoria']; ?>" 
                                       class="btn btn-warning btn-sm" 
                                       title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    
                                    <a href="excluir.php?id=<?php echo $categoria['id_categoria']; ?>" 
                                       class="btn btn-danger btn-sm" 
                                       title="Excluir"
                                       onclick="return confirmarExclusao('Deseja realmente excluir a categoria <?php echo $categoria['nome']; ?>?')">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">
                                <div class="alert alert-info mb-0">
                                    <i class="bi bi-info-circle"></i> Nenhuma categoria cadastrada.
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">
    <div class="alert alert-light">
        <strong><i class="bi bi-info-circle"></i> Total de categorias:</strong> <?php echo count($categorias); ?>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
