<?php
include('../includes/db_connect.php');

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Captura os dados do formulário
    $nome = $_POST['nome'];
    $matricula = $_POST['matricula'];
    $data_nascimento = $_POST['data_nascimento'];
    $cpf = $_POST['cpf'];
    $identidade = $_POST['identidade'];
    $data_admissao = $_POST['admissao'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $cargo = $_POST['cargo'];
    $filhos = $_POST['filhos']; // Agora capturamos "Sim" ou "Não"
    $genero = $_POST['genero'];
    $tipo = $_POST['tipo'];
    $numero_registro = $_POST['numero_registro'];

    // Verifica se o tipo é "Conselheiro" e se o número de registro foi preenchido
    if ($tipo == 'Conselheiro' && empty($numero_registro)) {
        echo "<script>alert('Número de registro é obrigatório para Conselheiros.');</script>";
    } else {
        // Insere os dados na tabela funcionarios
        $sql = "INSERT INTO funcionarios (nome, matricula, data_nascimento, cpf, identidade, data_admissao, email, telefone, cargo, filhos, genero, tipo, numero_registro) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        // Bind dos parâmetros (corrigido o número de parâmetros)
        $stmt->bind_param("ssssssssssiss", $nome, $matricula, $data_nascimento, $cpf, $identidade, $data_admissao, $email, $telefone, $cargo, $filhos, $genero, $tipo, $numero_registro);

        if ($stmt->execute()) {
            echo "<script>alert('Funcionário adicionado com sucesso!');</script>";
        } else {
            echo "<script>alert('Erro ao adicionar funcionário: " . $stmt->error . "');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/funcionario_add.css">
    <title>Adicionar Funcionário</title>
</head>
<body>
    <div class="container">
        <h1>Adicionar Funcionário</h1>
        <form action="" method="POST">
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" required placeholder="Ex: João Silva">

            <label for="matricula">Matrícula:</label>
            <input type="text" id="matricula" name="matricula" required placeholder="Ex: 123456">

            <label for="data_nascimento">Data de Nascimento:</label>
            <input type="date" id="data_nascimento" name="data_nascimento" required>

            <label for="cpf">CPF:</label>
            <input type="text" id="cpf" name="cpf" required placeholder="Ex: 123.456.789-00">

            <label for="identidade">Identidade:</label>
            <input type="text" id="identidade" name="identidade" required placeholder="Ex: 12.345.678-9">

            <label for="admissao">Data de Admissão:</label>
            <input type="date" id="admissao" name="admissao" required>

            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" required placeholder="Ex: email@empresa.com">

            <label for="telefone">Telefone:</label>
            <input type="text" id="telefone" name="telefone" required placeholder="Ex: (11) 99999-9999">

            <label for="cargo">Cargo:</label>
            <input type="text" id="cargo" name="cargo" required placeholder="Ex: Analista">

            <!-- Alterado para uma seleção Sim/Não para filhos -->
            <label for="filhos">Tem Filhos?</label>
            <select id="filhos" name="filhos" required>
                <option value="Sim">Sim</option>
                <option value="Não">Não</option>
            </select>

            <label for="genero">Gênero:</label>
            <select id="genero" name="genero" required>
                <option value="">Selecione...</option>
                <option value="Masculino">Masculino</option>
                <option value="Feminino">Feminino</option>
                <option value="Outro">Outro</option>
            </select>

            <label for="tipo">Tipo:</label>
            <select id="tipo" name="tipo" required onchange="toggleNumeroRegistro()">
                <option value="Funcionário">Funcionário</option>
                <option value="Conselheiro">Conselheiro</option>
            </select>

            <label for="numero_registro">Número de Registro:</label>
            <input type="text" id="numero_registro" name="numero_registro" 
                   <?php if (isset($_POST['tipo']) && $_POST['tipo'] == 'Conselheiro') echo 'required'; ?> placeholder="Ex: 123456">

            <button type="submit">Adicionar Funcionário</button>
        </form>
        <button onclick="window.location.href='menu.php'">Voltar ao Menu</button>
        <button onclick="window.location.href='adicionar_lote.php'">Enviar Lote</button>
    </div>

    <script>
        function toggleNumeroRegistro() {
            const tipoSelect = document.getElementById('tipo');
            const numeroRegistroInput = document.getElementById('numero_registro');
            numeroRegistroInput.required = tipoSelect.value === 'Conselheiro';
        }
    </script>
</body>
</html>
