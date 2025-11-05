<?php
/**
 * DETALHES DA COMPRA - MOSTRA OS ITENS
 * 
 * Explicação para crianças:
 * Esta página mostra o que tem dentro de uma compra
 * É como abrir uma sacola de compras e ver o que tem dentro!
 */

require_once '../../includes/config.php';

$titulo_pagina = 'Detalhes da Compra';

// Pegar o ID da compra
$id_compra = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id_compra == 0) {
    redirecionar('listar.php?msg=erro');
}

// BUSCAR INFORMAÇÕES DA COMPRA
$sql_compra = "SELECT c.*, u.nome as usuario_nome, u.email as usuario_email
               FROM Compra c 
               LEFT JOIN Usuario u ON c.id_usuario = u.id_usuario 
               WHERE c.id_compra = $id_compra";
$resultado_compra = executar_query($conexao, $sql_compra);
$compra = mysqli_fetch_assoc($resultado_compra);

if (!$compra) {
    redirecionar('listar.php?msg=erro');
}

// BUSCAR ITENS DA COMPRA
$sql_itens = "SELECT ic.*, a.titulo, a.descricao, a.imagem_url
              FROM Item_Compra ic
              LEFT JOIN Arquivo_Digital a ON ic.id_arquivo = a.id_arquivo
              WHERE ic.id_compra = $id_compra";
$itens = buscar_dados($conexao, $sql_itens);

include '../../includes/header.php';
?>

<div class="mb-4">
    <a href="listar.php" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Voltar
    </a>
</div>

<!-- Informações da Compra -->
<div class="card mb-4">
    <div class="card-header bg-primary text-white">
        <h4 class="mb-0">
            <i class="bi bi-cart-fill"></i> 
            Compra #<?php echo str_pad($compra['id_compra'], 5, '0', STR_PAD_LEFT); ?>
        </h4>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h5><i class="bi bi-person-circle"></i> Informações do Cliente</h5>
                <p>
                    <strong>Nome:</strong> <?php echo $compra['usuario_nome']; ?><br>
                    <strong>Email:</strong> <?php echo $compra['usuario_email']; ?>
                </p>
            </div>
            <div class="col-md-6">
                <h5><i class="bi bi-info-circle"></i> Informações da Compra</h5>
                <p>
                    <strong>Data:</strong> 
                    <?php echo date('d/m/Y H:i', strtotime($compra['data_compra'])); ?><br>
                    
                    <strong>Status:</strong> 
                    <?php 
                    $status_class = '';
                    switch($compra['status']) {
                        case 'pendente':
                            $status_class = 'bg-warning';
                            break;
                        case 'aprovado':
                            $status_class = 'bg-success';
                            break;
                        case 'cancelado':
                            $status_class = 'bg-danger';
                            break;
                    }
                    ?>
                    <span class="badge <?php echo $status_class; ?>">
                        <?php echo ucfirst($compra['status']); ?>
                    </span>
                    <br>
                    
                    <strong>Valor Total:</strong> 
                    <span class="text-success h5">
                        R$ <?php echo number_format($compra['valor_total'], 2, ',', '.'); ?>
                    </span>
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Itens da Compra -->
<div class="card">
    <div class="card-header bg-info text-white">
        <h5 class="mb-0">
            <i class="bi bi-box"></i> Itens da Compra
        </h5>
    </div>
    <div class="card-body">
        <?php if (count($itens) > 0): ?>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>ID Item</th>
                            <th>Produto</th>
                            <th>Descrição</th>
                            <th class="text-center">Quantidade</th>
                            <th class="text-end">Preço Unitário</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $total_geral = 0;
                        foreach ($itens as $item): 
                            $subtotal = $item['preco_unitario'] * $item['quantidade'];
                            $total_geral += $subtotal;
                        ?>
                            <tr>
                                <td><?php echo $item['id_item']; ?></td>
                                <td>
                                    <strong>
                                        <i class="bi bi-file-earmark-text text-primary"></i>
                                        <?php echo $item['titulo']; ?>
                                    </strong>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        <?php echo substr($item['descricao'], 0, 60) . '...'; ?>
                                    </small>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-secondary">
                                        <?php echo $item['quantidade']; ?>
                                    </span>
                                </td>
                                <td class="text-end">
                                    R$ <?php echo number_format($item['preco_unitario'], 2, ',', '.'); ?>
                                </td>
                                <td class="text-end">
                                    <strong class="text-success">
                                        R$ <?php echo number_format($subtotal, 2, ',', '.'); ?>
                                    </strong>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        
                        <!-- Linha de Total -->
                        <tr class="table-success">
                            <td colspan="5" class="text-end">
                                <strong>TOTAL GERAL:</strong>
                            </td>
                            <td class="text-end">
                                <strong class="h5 text-success">
                                    R$ <?php echo number_format($total_geral, 2, ',', '.'); ?>
                                </strong>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-warning">
                <i class="bi bi-exclamation-triangle"></i> 
                Esta compra não possui itens cadastrados.
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Ações -->
<div class="mt-4">
    <a href="editar.php?id=<?php echo $compra['id_compra']; ?>" class="btn btn-warning">
        <i class="bi bi-pencil"></i> Editar Compra
    </a>
    
    <a href="excluir.php?id=<?php echo $compra['id_compra']; ?>" 
       class="btn btn-danger"
       onclick="return confirmarExclusao('Deseja realmente excluir esta compra?')">
        <i class="bi bi-trash"></i> Excluir Compra
    </a>
</div>

<?php include '../../includes/footer.php'; ?>
