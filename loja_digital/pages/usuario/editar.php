<?php
/**
 * EDITAR USUÁRIO
 * 
 * Explicação para crianças:
 * Esta página permite modificar informações de um usuário
 * É como corrigir dados de um aluno na lista da escola!
 */

require_once '../../includes/config.php';

$titulo_pagina = 'Editar Usuário';

$id_usuario = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id_usuario == 0) {
    redirecionar('listar.php?msg=erro');
}

$mensagem = '';

// Buscar dados do usuário
$sql = "SELECT * FROM Usuario WHERE id_usuario = $id_usuario";
$resultado = executar_query($conexao, $sql);
$usuario = mysqli_fetch_assoc($resultado);

if (!$usuario) {
    redirecionar('listar.php?msg=erro');
}

// Processar formulário
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = limpar_entrada($conexao, $_POST['nome']);
    $email = limpar_entrada($conexao, $_POST['email']);
    $tipo = limpar_entrada($conexao, $_POST['tipo']);
    $ativo = isset($_POST['ativo']) ? 1 : 0;
    
    // Verificar se vai alterar a senha
    $senha = limpar_entrada($conexao, $_POST['senha']);
    
    if (empty($nome) || empty($email)) {
        $mensagem = mostrar_mensagem('Preencha todos os campos obrigatórios!', 'danger');
    } else {
        // Montar SQL
        if (!empty($senha)) {
            // Se digitou nova senha, atualizar também
            $senha_criptografada = md5($senha);
            $sql = "UPDATE Usuario SET 
                    nome = '$nome',
                    email = '$email',
                    senha = '$senha_criptografada',
                    tipo = '$tipo',
                    ativo = $ativo
                    WHERE id_usuario = $id_usuario";
        } else {
            // Não alterar a senha
            $sql = "UPDATE Usuario SET 
                    nome = '$nome',
                    email = '$email',
                    tipo = '$tipo',
                    ativo = $ativo
                    WHERE id_usuario = $id_usuario";
        }
        
        if (executar_query($conexao, $sql)) {
            redirecionar('listar.php?msg=sucesso');
        } else {
            $mensagem = mostrar_mensagem('Erro ao atualizar usuário!', 'danger');
        }
    }
}

include '../../includes/header.php';
?>

<div class="mb-4">
    <a href="listar.php" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Voltar
    </a>
</div>

<div class="card">
    <div class="card-header bg-warning text-dark">
        <h4 class="mb-0">
            <i class="bi bi-pencil"></i> Editar Usuário
        </h4>
    </div>
    <div class="card-body">
        <?php echo $mensagem; ?>
        
        <form method="POST" action="">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nome" class="form-label">
                        <i class="bi bi-person"></i> Nome Completo *
                    </label>
                    <input type="text" 
                           class="form-control" 
                           id="nome" 
                           name="nome" 
                           required
                           value="<?php echo $usuario['nome']; ?>">
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label">
                        <i class="bi bi-envelope"></i> Email *
                    </label>
                    <input type="email" 
                           class="form-control" 
                           id="email" 
                           name="email" 
                           required
                           value="<?php echo $usuario['email']; ?>">
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="senha" class="form-label">
                        <i class="bi bi-lock"></i> Nova Senha
                    </label>
                    <input type="password" 
                           class="form-control" 
                           id="senha" 
                           name="senha"
                           placeholder="Deixe em branco para não alterar">
                    <small class="text-muted">
                        Deixe em branco se não quiser alterar a senha
                    </small>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="tipo" class="form-label">
                        <i class="bi bi-shield"></i> Tipo de Usuário *
                    </label>
                    <select class="form-select" id="tipo" name="tipo" required>
                        <option value="cliente" <?php echo $usuario['tipo'] == 'cliente' ? 'selected' : ''; ?>>
                            Cliente
                        </option>
                        <option value="admin" <?php echo $usuario['tipo'] == 'admin' ? 'selected' : ''; ?>>
                            Administrador
                        </option>
                    </select>
                </div>
            </div>
            
            <div class="mb-3">
                <div class="form-check">
                    <input class="form-check-input" 
                           type="checkbox" 
                           id="ativo" 
                           name="ativo"
                           <?php echo $usuario['ativo'] ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="ativo">
                        <i class="bi bi-check-circle"></i> Usuário Ativo
                    </label>
                </div>
            </div>
            
            <hr>
            
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-warning">
                    <i class="bi bi-save"></i> Atualizar Usuário
                </button>
                <a href="listar.php" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
