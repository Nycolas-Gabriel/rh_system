<?php
include('../includes/db_connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $funcionario_id = intval($_POST['funcionario_id']);
    $data_inicio = $_POST['data_inicio'];
    $data_fim = $_POST['data_fim'];
    $dias = intval($_POST['dias']);
    $abono = isset($_POST['abono']) ? 1 : 0;
    $adiantamento_13 = isset($_POST['adiantamento_13']) ? 1 : 0;

    $sql = "INSERT INTO Ferias (funcionario_id, data_inicio, data_fim, dias, abono, adiantamento_13) 
            VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issiii", $funcionario_id, $data_inicio, $data_fim, $dias, $abono, $adiantamento_13);

    if ($stmt->execute()) {
        echo "Férias cadastradas com sucesso!";
    } else {
        echo "Erro ao cadastrar férias: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    $funcionario_id = intval($_GET['id']);

    $sql = "SELECT nome, matricula, data_contratacao FROM Funcionario WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $funcionario_id);
    $stmt->execute();
    $stmt->bind_result($nome, $matricula, $data_contratacao);
    $stmt->fetch();
    $stmt->close();
}
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Férias</title>
    <link rel="stylesheet" href="../styles/ferias.css">
</head>
<body>
    <div class="container">
        <h1>Cadastrar Férias</h1>
        <form method="POST" action="ferias.php">
            <input type="hidden" name="funcionario_id" value="<?php echo $funcionario_id; ?>">

            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" value="<?php echo $nome; ?>" readonly>

            <label for="matricula">Matrícula:</label>
            <input type="text" id="matricula" name="matricula" value="<?php echo $matricula; ?>" readonly>

            <label for="data_contratacao">Data de Contratação:</label>
            <input type="date" id="data_contratacao" name="data_contratacao" value="<?php echo $data_contratacao; ?>" readonly>

            <label for="ativo">Ativo:</label>
            <input type="checkbox" id="ativo" name="ativo" onclick="toggleDemissao(this)">

            <label for="data_demissao" id="label_data_demissao" style="display:none;">Data de Demissão:</label>
            <input type="date" id="data_demissao" name="data_demissao" style="display:none;">

            <label for="data_inicio">Data de Início das Férias:</label>
            <input type="date" id="data_inicio" name="data_inicio" required>

            <label for="data_fim">Data de Fim das Férias:</label>
            <input type="date" id="data_fim" name="data_fim" required>

            <label for="dias">Dias de Férias:</label>
            <input type="number" id="dias" name="dias" required>

            <label for="abono">Abono Pecuniário:</label>
            <input type="checkbox" id="abono" name="abono">

            <label for="adiantamento_13">Adiantamento 1/3 do 13º:</label>
            <input type="checkbox" id="adiantamento_13" name="adiantamento_13">

            <button type="submit">Cadastrar Férias</button>
        </form>
        <a href="menu.php" class="botao-voltar">Voltar ao Menu Principal</a>
    </div>

    <script>
        function toggleDemissao(checkbox) {
            var label = document.getElementById('label_data_demissao');
            var dataDemissao = document.getElementById('data_demissao');

            if (checkbox.checked) {
                label.style.display = 'block';
                dataDemissao.style.display = 'block';
            } else {
                label.style.display = 'none';
                dataDemissao.style.display = 'none';
            }
        }
    </script>
</body>
</html>
