<?php
session_start();
include('../includes/db_connect.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$userId = $_SESSION['user_id'];
$userType = '';

// Recupera o tipo do usuário
$sql = "SELECT tipo FROM usuario WHERE id = $userId";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $userType = $row['tipo'];
}

// Lógica para deletar um atestado
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $userType == 'master') {
    if (isset($_POST['delete']) && isset($_POST['atestado_id'])) {
        $atestadoId = intval($_POST['atestado_id']);
        
        $sql = "DELETE FROM atestado WHERE id = $atestadoId";
        if ($conn->query($sql) === TRUE) {
            echo "<p>Atestado deletado com sucesso!</p>";
        } else {
            echo "Erro ao deletar: " . $conn->error;
        }
    }
}

// Consulta atestados
$sqlAtestados = ($userType == 'master') ? "SELECT * FROM atestado" : "SELECT * FROM atestado WHERE email = (SELECT email FROM usuario WHERE id = $userId)";
$resultAtestados = $conn->query($sqlAtestados);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Listar Atestados</title>
    <link rel="stylesheet" href="../styles/listar_atestados.css">
    <script>
        function confirmarDelecao(atestadoId) {
            if (confirm("Você tem certeza que deseja deletar este atestado?")) {
                document.getElementById('delete-form-' + atestadoId).submit();
            }
        }
    </script>
</head>
<body>
    <div class="container">
        <h1>Listar Atestados</h1>

        <?php
        if ($resultAtestados->num_rows > 0) {
            echo "<table>
                    <tr>
                        <th>ID</th>
                        <th>E-mail</th>
                        <th>Nome</th>
                        <th>Data de Submissão</th>
                        <th>Descrição</th>
                        <th>Comprovante</th>";
                        if ($userType == 'master') {
                            echo "<th>Ações</th>";
                        }
            echo    "</tr>";

            while ($row = $resultAtestados->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['email']}</td>
                        <td>{$row['nome']}</td>
                        <td>{$row['data_submissao']}</td>
                        <td>{$row['descricao']}</td>
                        <td><a href='../uploads/atestados/{$row['comprovante']}' target='_blank'>Ver Comprovante</a></td>";
                        if ($userType == 'master') {
                            echo "<td>
                                    <form method='POST' id='delete-form-{$row['id']}' style='display:inline;'>
                                        <input type='hidden' name='atestado_id' value='{$row['id']}'>
                                        <button type='button' onclick='confirmarDelecao({$row['id']})' class='delete-button'>Deletar</button>
                                        <input type='hidden' name='delete' value='1'>
                                    </form>
                                  </td>";
                        }
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>Nenhum atestado encontrado.</p>";
        }

        $conn->close();
        ?>

        <a href="menu.php" class="back-button">Voltar ao Menu Principal</a>
    </div>
</body>
</html>
