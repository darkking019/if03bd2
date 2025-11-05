<?php
/**
 * LISTAGEM DE COMPRAS
 * 
 * Explicação para crianças:
 * Esta página mostra todas as compras realizadas na loja
 * É como ver o histórico de vendas de uma loja!
 */

require_once '../../includes/config.php';

$titulo_pagina = 'Listagem de Compras';

$mensagem = '';
if (isset($_GET['msg'])) {
    if ($_GET['msg'] == 'sucesso') {
        $mensagem = mostrar_mensagem('Operação realizada com sucesso!', 'success');
    } elseif ($_GET['msg'] == 'erro') {
        $mensagem = mostrar_mensagem('Erro ao realizar operação!', 'danger');
    }
}

// BUSCAR TODAS AS COMPRAS COM INFORMAÇÕES DO USUÁRIO
$sql = "SELECT c.*, u.nome as usuario_nome, u.email as usuario_email,
        (SELECT COUNT(*) FROM Item_Compra WHERE id_compra = c.id_compra) as total_itens
        FROM Compra c 
        LEFT JOIN Usuario u ON c.id_usuario = u.id_usuario 
        ORDER BY c.data_compra DESC";
$compras = buscar_dados($conexao, $sql);

include '../../includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>
        <i class="bi bi-cart-fill text-primary"></i> Listagem de Compras
    </h2>
    <a href="inserir.php" class="btn btn-success">
        <i class="bi bi-plus-circle"></i> Nova Compra
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
                        <th>Cliente</th>
                        <th>Data Compra</th>
                        <th>Valor Total</th>
                        <th>Itens</th>
                        <th>Status</th>
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($compras) > 0): ?>
                        <?php foreach ($compras as $compra): ?>
                            <tr>
                                <td>
                                    <strong>#<?php echo str_pad($compra['id_compra'], 5, '0', STR_PAD_LEFT); ?></strong>
                                </td>
                                <td>
                                    <i class="bi bi-person-circle"></i>
                                    <?php echo $compra['usuario_nome']; ?>
                                    <br>
                                    <small class="text-muted">
                                        <i class="bi bi-envelope"></i>
                                        <?php echo $compra['usuario_email']; ?>
                                    </small>
                                </td>
                                <td>
                                    <i class="bi bi-calendar"></i>
                                    <?php echo date('d/m/Y', strtotime($compra['data_compra'])); ?>
                                    <br>
                                    <small class="text-muted">
                                        <i class="bi bi-clock"></i>
                                        <?php echo date('H:i', strtotime($compra['data_compra'])); ?>
                                    </small>
                                </td>
                                <td>
                                    <span class="text-success fw-bold h5">
                                        <i class="bi bi-currency-dollar"></i>
                                        R$ <?php echo number_format($compra['valor_total'], 2, ',', '.'); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-info">
                                        <i class="bi bi-box"></i>
                                        <?php echo $compra['total_itens']; ?> item(ns)
                                    </span>
                                </td>
                                <td>
                                    <?php 
                                    $status_class = '';
                                    $status_icon = '';
                                    
                                    switch($compra['status']) {
                                        case 'pendente':
                                            $status_class = 'bg-warning';
                                            $status_icon = 'bi-clock-history';
                                            break;
                                        case 'aprovado':
                                            $status_class = 'bg-success';
                                            $status_icon = 'bi-check-circle';
                                            break;
                                        case 'cancelado':
                                            $status_class = 'bg-danger';
                                            $status_icon = 'bi-x-circle';
                                            break;
                                    }
                                    ?>
                                    <span class="badge <?php echo $status_class; ?>">
                                        <i class="bi <?php echo $status_icon; ?>"></i>
                                        <?php echo ucfirst($compra['status']); ?>
                                    </span>
                                </td>
                                <td class="text-center table-actions">
                                    <!-- Botão Ver Detalhes (Itens da Compra) -->
                                    <a href="detalhes.php?id=<?php echo $compra['id_compra']; ?>" 
                                       class="btn btn-info btn-sm" 
                                       title="Ver Itens da Compra">
                                        <i class="bi bi-eye"></i> Detalhes
                                    </a>
                                    
                                    <a href="editar.php?id=<?php echo $compra['id_compra']; ?>" 
                                       class="btn btn-warning btn-sm" 
                                       title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    
                                    <a href="excluir.php?id=<?php echo $compra['id_compra']; ?>" 
                                       class="btn btn-danger btn-sm" 
                                       title="Excluir"
                                       onclick="return confirmarExclusao('Deseja realmente excluir a compra #<?php echo $compra['id_compra']; ?>?')">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center">
                                <div class="alert alert-info mb-0">
                                    <i class="bi bi-info-circle"></i> Nenhuma compra cadastrada.
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Estatísticas -->
<div class="row mt-3">
    <div class="col-md-4">
        <div class="alert alert-success">
            <strong><i class="bi bi-check-circle"></i> Compras Aprovadas:</strong>
            <?php 
            $aprovadas = array_filter($compras, function($c) { return $c['status'] == 'aprovado'; });
            echo count($aprovadas);
            ?>
        </div>
    </div>
    <div class="col-md-4">
        <div class="alert alert-warning">
            <strong><i class="bi bi-clock-history"></i> Compras Pendentes:</strong>
            <?php 
            $pendentes = array_filter($compras, function($c) { return $c['status'] == 'pendente'; });
            echo count($pendentes);
            ?>
        </div>
    </div>
    <div class="col-md-4">
        <div class="alert alert-danger">
            <strong><i class="bi bi-x-circle"></i> Compras Canceladas:</strong>
            <?php 
            $canceladas = array_filter($compras, function($c) { return $c['status'] == 'cancelado'; });
            echo count($canceladas);
            ?>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
