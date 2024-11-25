<?php
// Inclui o arquivo de conexão com o banco de dados
require_once '../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recebe os dados do formulário
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];  // Nova variável
    $cpf = trim($_POST['cpf']);  // Novo campo de CPF
    $setor = $_POST['setor'];

    // Confirmação de senha
    if ($password !== $confirm_password) {
        header("Location: ../pages/register.php?error=As senhas não coincidem.");
        exit();
    }

    // Define o código validador correto
    $codigoValidadorCorreto = 'C043n2024@2';

    // Verifica se o setor é RH ou TI
    if (in_array($setor, ['Setor de Gestão de Pessoas', 'Departamento de Tecnologia da Informação'])) {
        if (empty($_POST['codigo_validador']) || $_POST['codigo_validador'] !== $codigoValidadorCorreto) {
            header('Location: ../pages/register.php?error=Código validador incorreto ou em branco');
            exit();
        }
        $tipo = 'master';
    } else {
        $tipo = 'normal';
    }

    // Verificação do email
    if (strpos($email, '@coren-pe.gov.br') === false) {
        header("Location: ../pages/register.php?error=Email deve ser do domínio coren-pe.gov.br");
        exit();
    }

    // Verificação do CPF na tabela funcionários
    $checkCpfSql = "SELECT COUNT(*) FROM funcionarios WHERE cpf = ?";
    $stmtCheckCpf = $conn->prepare($checkCpfSql);
    $stmtCheckCpf->bind_param("s", $cpf);
    $stmtCheckCpf->execute();
    $stmtCheckCpf->bind_result($countCpf);
    $stmtCheckCpf->fetch();
    $stmtCheckCpf->close();

    if ($countCpf == 0) {
        header("Location: ../pages/register.php?error=CPF não cadastrado. Entre em contato com o RH.");
        exit();
    }

    // Verificação da senha
    if (!preg_match("/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/", $password)) {
        header("Location: ../pages/register.php?error=A senha deve ter pelo menos 8 caracteres, incluindo um caractere especial, um número e uma letra maiúscula.");
        exit();
    }

    // Criptografando a senha
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Inserir o usuário no banco de dados
    $sql = "INSERT INTO usuario (email, senha, cpf, setor, tipo) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("sssss", $email, $hashed_password, $cpf, $setor, $tipo);
        if ($stmt->execute()) {
            header("Location: ../pages/login.php?success=Cadastro realizado com sucesso. Faça login.");
        } else {
            header("Location: ../pages/register.php?error=Erro ao cadastrar usuário. Tente novamente.");
        }
        $stmt->close();
    } else {
        die("Erro na preparação da consulta SQL: " . $conn->error);
    }

    $conn->close();
}
?>
