<?php
include('db_connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Verificar se o email já existe no banco de dados
    $sql = "SELECT * FROM Usuario WHERE email = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Verificar se a senha está correta
            if (password_verify($password, $user['senha'])) {
                // Login bem-sucedido, redireciona para a página de menu
                header("Location: ../pages/menu.php");
                exit();
            } else {
                header("Location: ../pages/login.php?error=Senha incorreta! Por favor, tente novamente.");
                exit();
            }
        } else {
            header("Location: ../pages/login.php?error=Usuário não encontrado! Por favor, cadastre-se.");
            exit();
        }

        $stmt->close();
    } else {
        // Exibir mensagem de erro se a preparação da consulta falhar
        die("Erro na consulta SQL: " . $conn->error);
    }

    $conn->close();
}
?>
