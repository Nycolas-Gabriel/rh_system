<?php
session_start();
include('../includes/db_connect.php');

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$userId = intval($_SESSION['user_id']); // Sanitiza o valor de userId
$sql = "SELECT tipo FROM usuario WHERE id = $userId";
$result = $conn->query($sql);
$userType = '';

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $userType = $row['tipo'];
}

// Consulta comprovantes de plano de saúde dependendo do tipo de usuário
if ($userType == 'master') {
    $sqlPlanos = "SELECT * FROM plano_de_saude"; // Exibe todos os comprovantes
} else {
    $sqlPlanos = "SELECT * FROM plano_de_saude WHERE usuario_id = $userId"; // Agora usa 'usuario_id'
}

$resultPlanos = $conn->query($sqlPlanos);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar Comprovantes de Plano de Saúde</title>
    <link rel="stylesheet" href="../styles/menu.css">
</head>
<body>
    <h1>Listar Comprovantes de Plano de Saúde</h1>

    <?php if ($resultPlanos && $resultPlanos->num_rows > 0) : ?>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Usuário</th>
                <th>Data</th>
                <th>Status</th>
                <th>Comprovante</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $resultPlanos->fetch_assoc()) : ?>
            <tr>
                <td><?= htmlspecialchars($row['id']) ?></td>
                <td><?= htmlspecialchars($row['usuario_id']) ?></td> <!-- Agora correto -->
                <td><?= htmlspecialchars($row['data_submissao']) ?></td> <!-- Supondo que você queira mostrar a data de submissão -->
                <td><?= htmlspecialchars($row['status']) ?></td>
                <td><a href="../uploads/plano_de_saude/<?= htmlspecialchars($row['comprovante']) ?>" target="_blank">Ver Comprovante</a></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <?php else : ?>
        <p>Nenhum comprovante encontrado.</p>
    <?php endif; ?>

    <a href="menu.php">Voltar ao Menu Principal</a>

    <?php $conn->close(); // Fecha a conexão com o banco de dados ?>
</body>
</html>
