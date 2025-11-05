<?php
/**
 * LISTAGEM DE ARQUIVOS DIGITAIS (PRODUTOS)
 * 
 * Explicação para crianças:
 * Esta página mostra todos os produtos da loja
 * É como ver todos os itens disponíveis em uma prateleira!
 */

require_once '../../includes/config.php';

$titulo_pagina = 'Listagem de Arquivos Digitais';

$mensagem = '';
if (isset($_GET['msg'])) {
    if ($_GET['msg'] == 'sucesso') {
        $mensagem = mostrar_mensagem('Operação realizada com sucesso!', 'success');
    } elseif ($_GET['msg'] == 'erro') {
        $mensagem = mostrar_mensagem('Erro ao realizar operação!', 'danger');
    }
}

// BUSCAR TODOS OS ARQUIVOS DIGITAIS COM SUAS CATEGORIAS
// JOIN = juntar informações de duas tabelas
$sql = "SELECT a.*, c.nome as categoria_nome 
        FROM Arquivo_Digital a 
        LEFT JOIN Categoria c ON a.id_categoria = c.id_categoria 
        ORDER BY a.id_arquivo DESC";
$arquivos = buscar_dados($conexao, $sql);

include '../../includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>
        <i class="bi bi-file-earmark-code text-primary"></i> Listagem de Arquivos Digitais
    </h2>
    <a href="inserir.php" class="btn btn-success">
        <i class="bi bi-plus-circle"></i> Novo Arquivo
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
                        <th>Título</th>
                        <th>Categoria</th>
                        <th>Preço</th>
                        <th>Vendas</th>
                        <th>Status</th>
                        <th>Data Cadastro</th>
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($arquivos) > 0): ?>
                        <?php foreach ($arquivos as $arquivo): ?>
                            <tr>
                                <td><?php echo $arquivo['id_arquivo']; ?></td>
                                <td>
                                    <i class="bi bi-file-earmark-text text-primary"></i>
                                    <strong><?php echo $arquivo['titulo']; ?></strong>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">
                                        <i class="bi bi-tag"></i>
                                        <?php echo $arquivo['categoria_nome'] ? $arquivo['categoria_nome'] : 'Sem categoria'; ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="text-success fw-bold">
                                        <i class="bi bi-currency-dollar"></i>
                                        R$ <?php echo number_format($arquivo['preco'], 2, ',', '.'); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-info">
                                        <i class="bi bi-graph-up"></i>
                                        <?php echo $arquivo['total_vendas']; ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($arquivo['ativo']): ?>
                                        <i class="bi bi-check-circle-fill icon-ativo"></i>
                                        <span class="text-success">Ativo</span>
                                    <?php else: ?>
                                        <i class="bi bi-x-circle-fill icon-inativo"></i>
                                        <span class="text-danger">Inativo</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <small>
                                        <i class="bi bi-calendar"></i>
                                        <?php echo date('d/m/Y', strtotime($arquivo['data_cadastro'])); ?>
                                    </small>
                                </td>
                                <td class="text-center table-actions">
                                    <a href="detalhes.php?id=<?php echo $arquivo['id_arquivo']; ?>" 
                                       class="btn btn-info btn-sm" 
                                       title="Ver Detalhes">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    
                                    <a href="editar.php?id=<?php echo $arquivo['id_arquivo']; ?>" 
                                       class="btn btn-warning btn-sm" 
                                       title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    
                                    <a href="excluir.php?id=<?php echo $arquivo['id_arquivo']; ?>" 
                                       class="btn btn-danger btn-sm" 
                                       title="Excluir"
                                       onclick="return confirmarExclusao('Deseja realmente excluir o arquivo <?php echo $arquivo['titulo']; ?>?')">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center">
                                <div class="alert alert-info mb-0">
                                    <i class="bi bi-info-circle"></i> Nenhum arquivo digital cadastrado.
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
        <strong><i class="bi bi-info-circle"></i> Total de arquivos:</strong> <?php echo count($arquivos); ?>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
