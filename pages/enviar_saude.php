<?php
session_start();
include('../includes/db_connect.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$userId = $_SESSION['user_id'];

// Recupera o nome do usuário (opcional, dependendo da estrutura do banco de dados)
$sql = "SELECT nome FROM usuario WHERE id = $userId";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $usuario = $result->fetch_assoc();
} else {
    $usuario = null;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $status = $_POST['status'];
    $arquivoComprovante = $_FILES['arquivo_comprovante'];

    if ($usuario && $arquivoComprovante['error'] == UPLOAD_ERR_OK) {
        $nomeArquivo = basename($arquivoComprovante['name']);
        $diretorioDestino = '../uploads/plano_de_saude/' . $nomeArquivo;

        // Cria o diretório se não existir
        if (!file_exists('../uploads/plano_de_saude')) {
            mkdir('../uploads/plano_de_saude', 0777, true);
        }

        if (move_uploaded_file($arquivoComprovante['tmp_name'], $diretorioDestino)) {
            // Insere os dados na tabela `plano_de_saude`
            $sql = "INSERT INTO plano_de_saude (usuario_id, data_comprovante, status, comprovante) 
                    VALUES ('$userId', NOW(), '$status', '$nomeArquivo')";

            if ($conn->query($sql) === TRUE) {
                $successMessage = "Comprovante enviado com sucesso.";
            } else {
                $errorMessage = "Erro ao salvar no banco de dados: " . $conn->error;
            }
        } else {
            $errorMessage = "Erro ao fazer upload do arquivo.";
        }
    } else {
        $errorMessage = "Erro: " . $arquivoComprovante['error'];
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enviar Comprovante de Plano de Saúde</title>
    <link rel="stylesheet" href="../styles/enviar_saude.css">
</head>
<body>
    <h1>Enviar Comprovante de Plano de Saúde</h1>

    <?php if (isset($successMessage)) : ?>
        <p style="color: green;"><?= $successMessage ?></p>
    <?php elseif (isset($errorMessage)) : ?>
        <p style="color: red;"><?= $errorMessage ?></p>
    <?php endif; ?>

    <form action="enviar_saude.php" method="post" enctype="multipart/form-data">
        <label for="status">Status:</label>
        <input type="text" id="status" name="status" required><br><br>
        <label for="arquivo_comprovante">Comprovante:</label>
        <input type="file" id="arquivo_comprovante" name="arquivo_comprovante" accept="image/*,application/pdf" required><br><br>
        <input type="submit" value="Enviar Comprovante">
    </form>
    </div>
    <a href="menu.php" class="button">Voltar ao Menu Principal</a>
</body>
</html>
