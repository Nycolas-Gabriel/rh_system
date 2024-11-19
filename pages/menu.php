<?php
session_start();
include('../includes/db_connect.php');

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Recupera o ID do usuário logado
$user_id = $_SESSION['user_id'];

// Consulta para buscar o tipo de usuário no banco de dados
$sql = "SELECT tipo FROM usuario WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Armazena o tipo de usuário em uma variável
$user_type = $user['tipo'];

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Principal</title>
    <link rel="stylesheet" href="../styles/menu.css">
</head>
<body>
    <h1>Bem-vindo ao Sistema de RH</h1>
    <p>Selecione uma opção:</p>
    <div class="container">
        <!-- Menu para funcionários e usuários (visível apenas para usuários master) -->
        <?php if ($user_type === 'master'): ?>
            <div class="menu-conjunto">
                <h2>Funcionários e Usuários</h2>
                <ul class="menu-principal">
                    <li><a href="funcionario_add.php">Adicionar Funcionário</a></li>
                    <li><a href="listar_funcionarios.php">Listar Funcionários</a></li>
                    <li><a href="usuario_add.php">Cadastrar Usuário</a></li>
                    <li><a href="listar_usuarios.php">Listar Usuários</a></li>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Menu para Férias e Atestados -->
        <div class="menu-conjunto">
            <h2><!--Férias e--> Atestados</h2>
            <ul class="menu-principal">
         <!--   <li><a href="agendar_ferias.php">Agendar Férias</a></li>
                <li><a href="listar_agendamentos.php">Listar Agendamentos de Férias</a></li> -->
                <li><a href="enviar_atestado.php">Enviar Atestado</a></li>
                <li><a href="listar_atestado.php">Listar Atestados</a></li>
            </ul>
        </div>

        <!-- Menu para Pontos e Plano de Saúde -->
        <div class="menu-conjunto">
            <h2><!--Pontos e -->Plano de Saúde</h2>
            <ul class="menu-principal">
            <!--<li><a href="ajuste_ponto.php">Ajuste de Ponto</a></li>
                <li><a href="listar_pontos.php">Listar Pontos</a></li>-->
                <li><a href="enviar_saude.php">Enviar Comprovante de Plano de Saúde</a></li>
                <li><a href="listar_saude.php">Listar Comprovantes de Plano de Saúde</a></li>
            </ul>
        </div>
    </div>
    
    <!-- Botão de Logout -->
    <a href="logout.php" class="logout-button">Logout</a>
</body>
</html>
