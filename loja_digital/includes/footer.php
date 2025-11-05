    </div> <!-- Fecha container -->
    
    <!-- Rodapé -->
    <footer class="bg-dark text-white text-center py-4 mt-5">
        <div class="container">
            <p class="mb-0">
                <i class="bi bi-shop"></i> Loja Digital &copy; 2024 - Sistema CRUD Completo
            </p>
            <p class="mb-0">
                <small>Desenvolvido com PHP, MySQL e Bootstrap 5</small>
            </p>
        </div>
    </footer>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Script para confirmação de exclusão -->
    <script>
        // Função para confirmar antes de excluir
        function confirmarExclusao(mensagem) {
            return confirm(mensagem || 'Tem certeza que deseja excluir este registro?');
        }
        
        // Auto-fechar alertas após 5 segundos
        setTimeout(function() {
            var alertas = document.querySelectorAll('.alert');
            alertas.forEach(function(alerta) {
                var bsAlert = new bootstrap.Alert(alerta);
                bsAlert.close();
            });
        }, 5000);
    </script>
</body>
</html>
