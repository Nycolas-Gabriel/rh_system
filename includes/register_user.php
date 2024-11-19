<?php
// Inclui o arquivo de conexão com o banco de dados
require_once '../includes/db_connect.php';  // Ajuste o caminho conforme a estrutura do seu projeto

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recebe os dados do formulário
    $username = trim($_POST['username']); // trim para remover espaços em branco acidentais
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $setor = $_POST['setor'];  // Adiciona a variável para o setor

    // Define o código validador correto
    $codigoValidadorCorreto = 'C043n2024@2';

    // Verifica se o setor é RH ou TI
    if (in_array($setor, ['Setor de Gestão de Pessoas', 'Departamento de Tecnologia da Informação'])) {
        // Verifica o código validador
        if (empty($_POST['codigo_validador']) || $_POST['codigo_validador'] !== $codigoValidadorCorreto) {
            header('Location: ../pages/register.php?error=Código validador incorreto ou em branco');
            exit();
        }
        // Define o tipo como 'master'
        $tipo = 'master';
    } else {
        // Define o tipo como 'normal'
        $tipo = 'normal';
    }

    // Verificação do email
    if (strpos($email, '@coren-pe.gov.br') === false) {
        header("Location: ../pages/register.php?error=Email deve ser do domínio coren-pe.gov.br");
        exit();
    }

    // Verificação da senha
    if (!preg_match("/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/", $password)) {
        header("Location: ../pages/register.php?error=A senha deve ter pelo menos 8 caracteres, incluindo um caractere especial, um número e uma letra maiúscula.");
        exit();
    }

    // Verifica se o e-mail já está cadastrado na tabela funcionarios
    $checkEmailSql = "SELECT COUNT(*) FROM funcionarios WHERE email = ?";
    $stmtCheckEmail = $conn->prepare($checkEmailSql);
    $stmtCheckEmail->bind_param("s", $email);
    $stmtCheckEmail->execute();
    $stmtCheckEmail->bind_result($count);
    $stmtCheckEmail->fetch();
    $stmtCheckEmail->close();

    if ($count == 0) {
        header("Location: ../pages/register.php?error=Email não cadastrado entre em contato com seu RH.");
        exit();
    }

    // Criptografando a senha
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Inserir o usuário no banco de dados
    $sql = "INSERT INTO usuario (nome, email, senha, setor, tipo) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        // Corrige o possível truncamento do nome
        $stmt->bind_param("sssss", $username, $email, $hashed_password, $setor, $tipo);
        if ($stmt->execute()) {
            header("Location: ../pages/login.php?success=Cadastro realizado com sucesso. Faça login.");
        } else {
            header("Location: ../pages/register.php?error=Erro ao cadastrar usuário. Tente novamente.");
        }
        $stmt->close();
    } else {
        // Exibir mensagem de erro se a preparação da consulta falhar
        die("Erro na preparação da consulta SQL: " . $conn->error);
    }

    $conn->close();
}
?>
