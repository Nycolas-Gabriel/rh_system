<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastro</title>
    <link rel="stylesheet" href="../styles/register.css">
    <script>
        function verificarSetor() {
            var setor = document.getElementById('setor').value;
            var validadorField = document.getElementById('codigoValidadorGroup');

            if (setor === 'Departamento de Tecnologia da Informação' || setor === 'Setor de Gestão de Pessoas') {
                validadorField.style.display = 'block';
            } else {
                validadorField.style.display = 'none';
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('setor').addEventListener('change', verificarSetor);
        });
    </script>
</head>
<body>
    <div class="container">
        <h1>Cadastro</h1>
        <form id="registerForm" action="../includes/register_user.php" method="POST">
            <div class="input-group" id="usernameGroup">
                <input type="text" name="username" placeholder="Nome de usuário" required>
            </div>
            <div class="input-group" id="emailGroup">
                <input type="email" name="email" placeholder="Email" required>
            </div>
            <div class="input-group" id="passwordGroup">
                <input type="password" name="password" placeholder="Senha" required>
            </div>
            <div class="input-group" id="setorGroup">
                <label for="setor">Setor:</label>
                <?php include '../includes/setores_select.php'; gerarSelectSetores(); ?>
            </div>

            <!-- Campo de código validador, oculto por padrão -->
            <div class="input-group" id="codigoValidadorGroup" style="display:none;">
                <input type="text" name="codigo_validador" placeholder="Código Validador">
            </div>

            <button type="submit">Cadastrar</button>
        </form>
        <p>Já tem uma conta? <a href="login.php">Faça login</a></p>
        <?php
            if (isset($_GET['error'])) {
                echo "<p style='color:red;'>".htmlspecialchars($_GET['error'])."</p>";
            }
        ?>
    </div>
</body>
</html>
