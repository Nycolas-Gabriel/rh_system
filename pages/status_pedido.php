<?php
session_start();
include('../includes/db_connect.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$userId = $_SESSION['user_id'];

// Consulta para obter os pedidos de férias do usuário logado
$sql = "SELECT * FROM agendamentos WHERE usuario_id = $userId";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Status do Pedido</title>
    <link rel="stylesheet" href="../styles/status_pedido.css">
</head>
<body>
    <h1>Status do Pedido de Férias</h1>
    
    <?php
    if ($result->num_rows > 0) {
        echo "<table border='1'>
                <tr>
                    <th>ID</th>
                    <th>Data de Admissão</th>
                    <th>Período Aquisitivo</th>
                    <th>Período Concessivo</th>
                    <th>Dias Solicitados</th>
                    <th>Status</th>
                </tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['data_admissao']}</td>
                    <td>{$row['periodo_aquisitivo']}</td>
                    <td>{$row['periodo_concessivo']}</td>
                    <td>{$row['dias_solicitados']}</td>
                    <td>{$row['status']}</td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "<p>Nenhum pedido encontrado.</p>";
    }

    $conn->close();
    ?>
</body>
</html>
