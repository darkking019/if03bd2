<?php
/**
 * INSERIR NOVO USUÁRIO
 * 
 * Explicação para crianças:
 * Esta página permite adicionar um novo usuário no sistema
 * É como cadastrar um novo aluno na escola!
 */

require_once '../../includes/config.php';

$titulo_pagina = 'Novo Usuário';

$mensagem = '';

// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Pegar os dados do formulário
    $nome = limpar_entrada($conexao, $_POST['nome']);
    $email = limpar_entrada($conexao, $_POST['email']);
    $senha = limpar_entrada($conexao, $_POST['senha']);
    $tipo = limpar_entrada($conexao, $_POST['tipo']);
    $ativo = isset($_POST['ativo']) ? 1 : 0;
    
    // Validar campos obrigatórios
    if (empty($nome) || empty($email) || empty($senha)) {
        $mensagem = mostrar_mensagem('Preencha todos os campos obrigatórios!', 'danger');
    } else {
        // Criptografar a senha com MD5
        $senha_criptografada = md5($senha);
        
        // Inserir no banco de dados
        $sql = "INSERT INTO Usuario (nome, email, senha, tipo, ativo) 
                VALUES ('$nome', '$email', '$senha_criptografada', '$tipo', $ativo)";
        
        if (executar_query($conexao, $sql)) {
            redirecionar('listar.php?msg=sucesso');
        } else {
            $mensagem = mostrar_mensagem('Erro ao cadastrar usuário!', 'danger');
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
    <div class="card-header bg-success text-white">
        <h4 class="mb-0">
            <i class="bi bi-person-plus"></i> Cadastrar Novo Usuário
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
                           placeholder="Digite o nome completo">
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
                           placeholder="exemplo@email.com">
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="senha" class="form-label">
                        <i class="bi bi-lock"></i> Senha *
                    </label>
                    <input type="password" 
                           class="form-control" 
                           id="senha" 
                           name="senha" 
                           required
                           placeholder="Digite a senha">
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="tipo" class="form-label">
                        <i class="bi bi-shield"></i> Tipo de Usuário *
                    </label>
                    <select class="form-select" id="tipo" name="tipo" required>
                        <option value="cliente">Cliente</option>
                        <option value="admin">Administrador</option>
                    </select>
                </div>
            </div>
            
            <div class="mb-3">
                <div class="form-check">
                    <input class="form-check-input" 
                           type="checkbox" 
                           id="ativo" 
                           name="ativo" 
                           checked>
                    <label class="form-check-label" for="ativo">
                        <i class="bi bi-check-circle"></i> Usuário Ativo
                    </label>
                </div>
            </div>
            
            <hr>
            
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-save"></i> Salvar Usuário
                </button>
                <a href="listar.php" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
