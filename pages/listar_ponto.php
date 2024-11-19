<?php
session_start();
include('../includes/db_connect.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$userId = $_SESSION['user_id'];
$sql = "SELECT tipo FROM usuario WHERE id = $userId";
$result = $conn->query($sql);
$userType = '';

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $userType = $row['tipo'];
}

// Consulta pontos dependendo do tipo de usuário
if ($userType == 'master') {
    $sqlPontos = "SELECT * FROM pontos"; // Exibe todos os pontos
} else {
    $sqlPontos = "SELECT * FROM pontos WHERE usuario_id = $userId"; // Apenas os pontos do usuário normal
}

$resultPontos = $conn->query($sqlPontos);

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar Pontos</title>
    <link rel="stylesheet" href="../styles/menu.css">
</head>
<body>
    <h1>Listar Pontos</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Usuário</th>
                <th>Data</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $resultPontos->fetch_assoc()) : ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= $row['usuario_id'] ?></td>
                <td><?= $row['data_ponto'] ?></td>
                <td><?= $row['status'] ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
