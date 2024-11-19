<?php
session_start();
include('../includes/db_connect.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$userId = $_SESSION['user_id'];
$userType = '';

$sql = "SELECT tipo FROM usuario WHERE id = $userId";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $userType = $row['tipo'];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $userType == 'master') {
    if (isset($_POST['status']) && isset($_POST['agendamento_id'])) {
        $status = $_POST['status'];
        $agendamentoId = intval($_POST['agendamento_id']);

        $sql = "UPDATE agendamentos SET status = '$status' WHERE id = $agendamentoId";
        if ($conn->query($sql) === TRUE) {
            echo "<p>Status atualizado com sucesso!</p>";
        } else {
            echo "Erro: " . $conn->error;
        }
    }

    // Lógica para deletar um agendamento
    if (isset($_POST['delete']) && isset($_POST['agendamento_id'])) {
        $agendamentoId = intval($_POST['agendamento_id']);
        
        $sql = "DELETE FROM agendamentos WHERE id = $agendamentoId";
        if ($conn->query($sql) === TRUE) {
            echo "<p>Agendamento deletado com sucesso!</p>";
        } else {
            echo "Erro ao deletar: " . $conn->error;
        }
    }
}

$sql = "SELECT * FROM agendamentos";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Listar Agendamentos</title>
    <link rel="stylesheet" href="../styles/listar_agendamentos.css">
    <script>
        function confirmarDelecao(agendamentoId) {
            if (confirm("Você tem certeza que deseja deletar este agendamento?")) {
                document.getElementById('delete-form-' + agendamentoId).submit();
            }
        }
    </script>
</head>
<body>
    <div class="container">
        <h1>Listar Agendamentos</h1>

        <?php
        if ($result->num_rows > 0) {
            echo "<table>
                    <tr>
                        <th>ID</th>
                        <th>Data de Admissão</th>
                        <th>Período Aquisitivo</th>
                        <th>Período Concessivo</th>
                        <th>Dias Solicitados</th>
                        <th>Status</th>";
                        if ($userType == 'master') {
                            echo "<th>Ações</th>";
                        }
            echo    "</tr>";

            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['data_admissao']}</td>
                        <td>{$row['periodo_aquisitivo']}</td>
                        <td>{$row['periodo_concessivo']}</td>
                        <td>{$row['dias_solicitados']}</td>
                        <td>{$row['status']}</td>";
                        if ($userType == 'master') {
                            echo "<td>
                                    <form method='POST' style='display:inline;'>
                                        <input type='hidden' name='agendamento_id' value='{$row['id']}'>
                                        <button type='submit' name='status' value='deferido' class='deferido-button'>Deferido</button>
                                        <button type='submit' name='status' value='indeferido' class='indeferido-button'>Indeferido</button>
                                    </form>

                                    <form method='POST' id='delete-form-{$row['id']}' style='display:inline;'>
                                        <input type='hidden' name='agendamento_id' value='{$row['id']}'>
                                        <button type='button' onclick='confirmarDelecao({$row['id']})' class='delete-button'>Deletar</button>
                                        <input type='hidden' name='delete' value='1'>
                                    </form>
                                  </td>";
                        }
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>Nenhum agendamento encontrado.</p>";
        }

        $conn->close();
        ?>

        <a href="../pages/menu.php" class="back-button">Voltar ao Menu Principal</a>
    </div>
</body>
</html>
