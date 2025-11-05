<?php
/**
 * EXCLUIR USUÁRIO
 * 
 * Explicação para crianças:
 * Esta página permite excluir um usuário de duas formas:
 * 1. Exclusão LÓGICA: Apenas marca como inativo (não apaga de verdade)
 * 2. Exclusão FÍSICA: Apaga completamente do banco de dados
 * 
 * É como riscar o nome de um aluno (lógica) ou apagar da lista (física)
 */

require_once '../../includes/config.php';

$titulo_pagina = 'Excluir Usuário';

$id_usuario = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id_usuario == 0) {
    redirecionar('listar.php?msg=erro');
}

// Buscar dados do usuário
$sql = "SELECT * FROM Usuario WHERE id_usuario = $id_usuario";
$resultado = executar_query($conexao, $sql);
$usuario = mysqli_fetch_assoc($resultado);

if (!$usuario) {
    redirecionar('listar.php?msg=erro');
}

$mensagem = '';

// Processar exclusão
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tipo_exclusao = $_POST['tipo_exclusao'];
    
    if ($tipo_exclusao == 'logica') {
        // EXCLUSÃO LÓGICA: apenas marca como inativo
        $sql = "UPDATE Usuario SET ativo = 0 WHERE id_usuario = $id_usuario";
        $msg_sucesso = 'Usuário desativado com sucesso!';
    } else {
        // EXCLUSÃO FÍSICA: apaga do banco de dados
        $sql = "DELETE FROM Usuario WHERE id_usuario = $id_usuario";
        $msg_sucesso = 'Usuário excluído permanentemente!';
    }
    
    if (executar_query($conexao, $sql)) {
        redirecionar('listar.php?msg=sucesso');
    } else {
        $mensagem = mostrar_mensagem('Erro ao excluir usuário!', 'danger');
    }
}

include '../../includes/header.php';
?>

<div class="mb-4">
    <a href="listar.php" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Voltar
    </a>
</div>

<div class="card border-danger">
    <div class="card-header bg-danger text-white">
        <h4 class="mb-0">
            <i class="bi bi-exclamation-triangle"></i> Excluir Usuário
        </h4>
    </div>
    <div class="card-body">
        <?php echo $mensagem; ?>
        
        <div class="alert alert-warning">
            <i class="bi bi-exclamation-triangle"></i>
            <strong>Atenção!</strong> Você está prestes a excluir o seguinte usuário:
        </div>
        
        <!-- Informações do Usuário -->
        <div class="card mb-4">
            <div class="card-body">
                <h5><i class="bi bi-person-circle"></i> Dados do Usuário</h5>
                <p>
                    <strong>ID:</strong> <?php echo $usuario['id_usuario']; ?><br>
                    <strong>Nome:</strong> <?php echo $usuario['nome']; ?><br>
                    <strong>Email:</strong> <?php echo $usuario['email']; ?><br>
                    <strong>Tipo:</strong> 
                    <span class="badge <?php echo $usuario['tipo'] == 'admin' ? 'bg-danger' : 'bg-primary'; ?>">
                        <?php echo ucfirst($usuario['tipo']); ?>
                    </span><br>
                    <strong>Status:</strong> 
                    <?php echo $usuario['ativo'] ? '<span class="text-success">Ativo</span>' : '<span class="text-danger">Inativo</span>'; ?>
                </p>
            </div>
        </div>
        
        <!-- Formulário de Exclusão -->
        <form method="POST" action="">
            <div class="mb-4">
                <label class="form-label">
                    <i class="bi bi-trash"></i> Escolha o tipo de exclusão:
                </label>
                
                <div class="form-check">
                    <input class="form-check-input" 
                           type="radio" 
                           name="tipo_exclusao" 
                           id="logica" 
                           value="logica" 
                           checked>
                    <label class="form-check-label" for="logica">
                        <strong>Exclusão Lógica</strong> 
                        <span class="text-muted">
                            (Apenas desativa o usuário, mantém no banco de dados)
                        </span>
                    </label>
                </div>
                
                <div class="form-check">
                    <input class="form-check-input" 
                           type="radio" 
                           name="tipo_exclusao" 
                           id="fisica" 
                           value="fisica">
                    <label class="form-check-label" for="fisica">
                        <strong>Exclusão Física</strong> 
                        <span class="text-danger">
                            (Remove permanentemente do banco de dados - NÃO PODE SER DESFEITO!)
                        </span>
                    </label>
                </div>
            </div>
            
            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i>
                <strong>Dica:</strong> Use a exclusão lógica para manter o histórico. 
                Use a exclusão física apenas quando tiver certeza absoluta.
            </div>
            
            <hr>
            
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-danger">
                    <i class="bi bi-trash"></i> Confirmar Exclusão
                </button>
                <a href="listar.php" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
