<?php
session_start();
include('../includes/db_connect.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$userId = $_SESSION['user_id'];

// Recupera as informações do funcionário
$sql = "SELECT * FROM usuario WHERE id = $userId";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $funcionario = $result->fetch_assoc();
} else {
    $funcionario = null;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $dataAdmissao = $_POST['data_admissao'];
    $periodoAquisitivo = $_POST['periodo_aquisitivo'];
    $periodoConcessivo = $_POST['periodo_concessivo'];
    $diasSolicitados = $_POST['dias_solicitados']; // Alterado para 'dias_solicitados'

    if ($funcionario) {
        $sql = "INSERT INTO agendamentos (usuario_id, data_admissao, periodo_aquisitivo, periodo_concessivo, dias_solicitados, status) 
                VALUES ('$userId', '$dataAdmissao', '$periodoAquisitivo', '$periodoConcessivo', '$diasSolicitados', 'Análise')";

        if ($conn->query($sql) === TRUE) {
            $successMessage = "Agendamento realizado com sucesso.";
        } else {
            $errorMessage = "Erro: " . $conn->error;
        }
    } else {
        $errorMessage = "Funcionário não encontrado.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Agendar Férias</title>
    <link rel="stylesheet" href="../styles/agendar_ferias.css">
    <script>
        function showConfirmation(message, redirectUrl) {
            if (confirm(message)) {
                window.location.href = redirectUrl;
            }
        }
    </script>
</head>
<body>
    <div class="container">
        <h1>Agendar Férias</h1>
        <form method="POST">
            <label for="data_admissao">Data de Admissão:</label>
            <input type="date" id="data_admissao" name="data_admissao" value="<?php echo isset($funcionario['data_admissao']) ? htmlspecialchars($funcionario['data_admissao']) : ''; ?>" required>
            
            <label for="periodo_aquisitivo">Período Aquisitivo:</label>
            <input type="text" id="periodo_aquisitivo" name="periodo_aquisitivo" value="<?php echo isset($funcionario['periodo_aquisitivo']) ? htmlspecialchars($funcionario['periodo_aquisitivo']) : ''; ?>" required>
            
            <label for="periodo_concessivo">Período Concessivo:</label>
            <input type="text" id="periodo_concessivo" name="periodo_concessivo" required>
            
            <label for="dias_solicitados">Dias Solicitados:</label>
            <input type="number" id="dias_solicitados" name="dias_solicitados" required>
            
            <button type="submit">Agendar</button>
        </form>
        
        <?php if (isset($successMessage)) : ?>
            <script>
                showConfirmation("<?php echo $successMessage; ?>", "menu.php");
            </script>
        <?php elseif (isset($errorMessage)) : ?>
            <p style="color: red;"><?php echo $errorMessage; ?></p>
        <?php endif; ?>
    </div>
    </div>
    <a href="../pages/menu.php" class="back-button">Voltar ao Menu Principal</a>
</body>
</html>
