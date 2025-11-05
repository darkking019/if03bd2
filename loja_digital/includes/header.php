<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loja Digital - <?php echo isset($titulo_pagina) ? $titulo_pagina : 'Home'; ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <!-- CSS Customizado -->
    <style>
        body {
            background-color: #f8f9fa;
        }
        
        .navbar {
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
        }
        
        .card {
            transition: transform 0.2s;
            height: 100%;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0,0,0,.2);
        }
        
        .produto-card {
            margin-bottom: 20px;
        }
        
        .produto-img {
            height: 200px;
            object-fit: cover;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 3rem;
        }
        
        .badge-status {
            font-size: 0.9rem;
        }
        
        .table-actions {
            white-space: nowrap;
        }
        
        .icon-ativo {
            color: #28a745;
            font-size: 1.2rem;
        }
        
        .icon-inativo {
            color: #dc3545;
            font-size: 1.2rem;
        }
        
        .status-pendente {
            background-color: #ffc107;
        }
        
        .status-aprovado {
            background-color: #28a745;
        }
        
        .status-cancelado {
            background-color: #dc3545;
        }
    </style>
</head>
<body>
    <!-- Barra de Navegação -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
        <div class="container">
            <a class="navbar-brand" href="/loja_digital/index.php">
                <i class="bi bi-shop"></i> Loja Digital
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/loja_digital/index.php">
                            <i class="bi bi-house"></i> Home
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-list"></i> Listagens
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="/loja_digital/pages/usuario/listar.php">
                                <i class="bi bi-people"></i> Usuários
                            </a></li>
                            <li><a class="dropdown-item" href="/loja_digital/pages/categoria/listar.php">
                                <i class="bi bi-tags"></i> Categorias
                            </a></li>
                            <li><a class="dropdown-item" href="/loja_digital/pages/arquivo_digital/listar.php">
                                <i class="bi bi-file-earmark"></i> Arquivos Digitais
                            </a></li>
                            <li><a class="dropdown-item" href="/loja_digital/pages/compra/listar.php">
                                <i class="bi bi-cart"></i> Compras
                            </a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
    <div class="container">
