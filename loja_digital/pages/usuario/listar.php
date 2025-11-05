<?php
/**
 * LISTAGEM DE USUÁRIOS
 * 
 * Explicação para crianças:
 * Esta página mostra todos os usuários cadastrados na loja
 * É como uma lista de alunos da escola!
 */

require_once '../../includes/config.php';

$titulo_pagina = 'Listagem de Usuários';

// Verificar se há mensagem para mostrar
$mensagem = '';
if (isset($_GET['msg'])) {
    if ($_GET['msg'] == 'sucesso') {
        $mensagem = mostrar_mensagem('Operação realizada com sucesso!', 'success');
    } elseif ($_GET['msg'] == 'erro') {
        $mensagem = mostrar_mensagem('Erro ao realizar operação!', 'danger');
    }
}

// BUSCAR TODOS OS USUÁRIOS
// Vamos buscar tanto os ativos quanto os inativos
$sql = "SELECT * FROM Usuario ORDER BY id_usuario DESC";
$usuarios = buscar_dados($conexao, $sql);

include '../../includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>
        <i class="bi bi-people-fill text-primary"></i> Listagem de Usuários
    </h2>
    <a href="inserir.php" class="btn btn-success">
        <i class="bi bi-plus-circle"></i> Novo Usuário
    </a>
</div>

<?php echo $mensagem; ?>

<!-- Tabela de Usuários -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Tipo</th>
                        <th>Status</th>
                        <th>Data Cadastro</th>
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($usuarios) > 0): ?>
                        <?php foreach ($usuarios as $usuario): ?>
                            <tr>
                                <td><?php echo $usuario['id_usuario']; ?></td>
                                <td>
                                    <i class="bi bi-person-circle"></i>
                                    <?php echo $usuario['nome']; ?>
                                </td>
                                <td>
                                    <i class="bi bi-envelope"></i>
                                    <?php echo $usuario['email']; ?>
                                </td>
                                <td>
                                    <?php if ($usuario['tipo'] == 'admin'): ?>
                                        <span class="badge bg-danger">
                                            <i class="bi bi-shield-fill"></i> Admin
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-primary">
                                            <i class="bi bi-person"></i> Cliente
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($usuario['ativo']): ?>
                                        <i class="bi bi-check-circle-fill icon-ativo" title="Ativo"></i>
                                        <span class="text-success">Ativo</span>
                                    <?php else: ?>
                                        <i class="bi bi-x-circle-fill icon-inativo" title="Inativo"></i>
                                        <span class="text-danger">Inativo</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <i class="bi bi-calendar"></i>
                                    <?php echo date('d/m/Y H:i', strtotime($usuario['data_cadastro'])); ?>
                                </td>
                                <td class="text-center table-actions">
                                    <!-- Botão Ver Detalhes -->
                                    <a href="detalhes.php?id=<?php echo $usuario['id_usuario']; ?>" 
                                       class="btn btn-info btn-sm" 
                                       title="Ver Detalhes">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    
                                    <!-- Botão Editar -->
                                    <a href="editar.php?id=<?php echo $usuario['id_usuario']; ?>" 
                                       class="btn btn-warning btn-sm" 
                                       title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    
                                    <!-- Botão Excluir -->
                                    <a href="excluir.php?id=<?php echo $usuario['id_usuario']; ?>" 
                                       class="btn btn-danger btn-sm" 
                                       title="Excluir"
                                       onclick="return confirmarExclusao('Deseja realmente excluir o usuário <?php echo $usuario['nome']; ?>?')">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center">
                                <div class="alert alert-info mb-0">
                                    <i class="bi bi-info-circle"></i> Nenhum usuário cadastrado.
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Informações adicionais -->
<div class="mt-3">
    <div class="alert alert-light">
        <strong><i class="bi bi-info-circle"></i> Total de usuários:</strong> <?php echo count($usuarios); ?>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
