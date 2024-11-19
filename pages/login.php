<?php
session_start();
include('../includes/db_connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Realize a consulta para verificar o email
    $sql = "SELECT id, senha FROM usuario WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Verifica a senha usando password_verify
        if (password_verify($senha, $row['senha'])) {
            $_SESSION['user_id'] = $row['id']; // Armazena o ID do usuário na sessão
            header('Location: menu.php');
        } else {
            echo "Usuário ou senha inválidos!";
        }
    } else {
        echo "Usuário ou senha inválidos!";
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="../styles/login.css">
</head>
<body>
    <div class="container">
        <h1>Login</h1>
        <form action="" method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="senha" placeholder="Senha" required>
            <button type="submit">Login</button>
        </form>
        <p>Não tem uma conta? <a href="register.php">Cadastre-se</a></p>
        <?php
            if (isset($_GET['error'])) {
                echo "<p style='color:red;'>".htmlspecialchars($_GET['error'])."</p>";
            }
        ?>
    </div>
</body>
</html>
