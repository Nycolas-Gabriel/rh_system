<?php
session_start();
include('../includes/db_connect.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$userId = $_SESSION['user_id'];

// Recupera o e-mail do usuário
$sql = "SELECT email FROM usuario WHERE id = $userId";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $usuario = $result->fetch_assoc();
} else {
    $usuario = null;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $horaAjuste = $_POST['hora_ajuste'];

    if ($usuario) {
        $sql = "INSERT INTO ajustes_ponto (usuario_id, hora_ajuste, email_usuario) 
                VALUES ('$userId', '$horaAjuste', '{$usuario['email']}')";

        if ($conn->query($sql) === TRUE) {
            $successMessage = "Ajuste de ponto realizado com sucesso.";
        } else {
            $errorMessage = "Erro ao salvar no banco de dados: " . $conn->error;
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Ajuste de Ponto</title>
    <link rel="stylesheet" href="../styles/menu.css">
</head>
<body>
    <div class="container">
        <h1>Ajuste de Ponto</h1>
        <form method="POST">
            <label for="hora_ajuste">Hora de Ajuste:</label>
            <input type="time" id="hora_ajuste" name="hora_ajuste" required>
            
            <button type="submit">Ajustar</button>
        </form>
        
        <?php if (isset($successMessage)) : ?>
            <p style="color: green;"><?php echo $successMessage; ?></p>
        <?php elseif (isset($errorMessage)) : ?>
            <p style="color: red;"><?php echo $errorMessage; ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
