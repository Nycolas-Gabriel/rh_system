<?php
session_start();
include('../includes/db_connect.php');

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$userId = $_SESSION['user_id'];

// Recupera o nome e o e-mail do usuário
$sql = "SELECT nome, email FROM usuario WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $usuario = $result->fetch_assoc();
} else {
    $usuario = null;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $descricao = $_POST['descricao'];
    $arquivoAtestado = $_FILES['arquivo_atestado'];

    if ($usuario && $arquivoAtestado['error'] == UPLOAD_ERR_OK) {
        $nomeArquivo = basename($arquivoAtestado['name']);
        $diretorioDestino = '../uploads/atestados/' . $nomeArquivo;

        // Verifica se o diretório existe e, se não, cria
        if (!file_exists('../uploads/atestados')) {
            mkdir('../uploads/atestados', 0777, true);
        }

        if (move_uploaded_file($arquivoAtestado['tmp_name'], $diretorioDestino)) {
            // Insere os dados na tabela `atestado`
            $sql = "INSERT INTO atestado (email, nome, descricao, comprovante) 
                    VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ssss', $usuario['email'], $usuario['nome'], $descricao, $nomeArquivo);

            if ($stmt->execute()) {
                $successMessage = "Atestado enviado com sucesso.";
            } else {
                $errorMessage = "Erro ao salvar no banco de dados: " . $conn->error;
            }
        } else {
            $errorMessage = "Erro ao fazer upload do arquivo.";
        }
    } else {
        $errorMessage = "Erro: " . $arquivoAtestado['error'];
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enviar Atestado</title>
    <link rel="stylesheet" href="../styles/enviar_atestado.css">
</head>
<body>
    <div class="container">
        <h1>Enviar Atestado</h1>
        <form method="POST" enctype="multipart/form-data">
            <label for="descricao">Descrição:</label>
            <textarea id="descricao" name="descricao" required></textarea>
            
            <label for="arquivo_atestado">Anexar Atestado:</label>
            <input type="file" id="arquivo_atestado" name="arquivo_atestado" accept=".pdf,.jpg,.jpeg,.png" required>
            
            <button type="submit">Enviar</button>
        </form>
        
        <?php if (isset($successMessage)) : ?>
            <p class="message success"><?= $successMessage; ?></p>
        <?php elseif (isset($errorMessage)) : ?>
            <p class="message error"><?= $errorMessage; ?></p>
        <?php endif; ?>
    </div>
    <a href="menu.php" class="button">Voltar ao Menu Principal</a>
</body>
</html>
