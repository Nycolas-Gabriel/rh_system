<?php
include('../includes/db_connect.php');

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "SELECT * FROM funcionarios WHERE id = $id";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "Funcionário não encontrado.";
        exit;
    }
}

// Atualizar informações do funcionário
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $matricula = $_POST['matricula'];
    $data_nascimento = $_POST['data_nascimento'];
    $cpf = $_POST['cpf'];
    $identidade = $_POST['identidade'];
    $data_admissao = $_POST['data_admissao'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $cargo = $_POST['cargo'];
    $filhos = $_POST['filhos'];
    $genero = $_POST['genero'];
    $tipo = $_POST['tipo'];
    $numero_registro = $_POST['numero_registro'];
    $status = $_POST['status'];

    // Atualiza os dados no banco
$sql = "UPDATE funcionarios SET 
        nome = '$nome', 
        matricula = '$matricula', 
        data_nascimento = '$data_nascimento', 
        cpf = '$cpf', 
        identidade = '$identidade', 
        data_admissao = '$data_admissao', 
        email = '$email', 
        telefone = '$telefone', 
        cargo = '$cargo', 
        filhos = '$filhos', 
        genero = '$genero', 
        tipo = '$tipo', 
        numero_registro = '$numero_registro', 
        status = '$status'
        WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        echo "Funcionário atualizado com sucesso!";
    } else {
        echo "Erro ao atualizar funcionário: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Funcionário</title>
    <link rel="stylesheet" href="../styles/funcionario_edicao.css">
</head>
<body>
    <div class="container">
        <h1>Editar Funcionário</h1>
        <form method="POST" action="">
            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($row['nome']); ?>" required>

            <label for="matricula">Matrícula:</label>
            <input type="text" id="matricula" name="matricula" value="<?php echo htmlspecialchars($row['matricula']); ?>" required>

            <label for="data_nascimento">Data de Nascimento:</label>
            <input type="date" id="data_nascimento" name="data_nascimento" value="<?php echo htmlspecialchars($row['data_nascimento']); ?>" required>

            <label for="cpf">CPF:</label>
            <input type="text" id="cpf" name="cpf" value="<?php echo htmlspecialchars($row['cpf']); ?>" required>

            <label for="identidade">Identidade:</label>
            <input type="text" id="identidade" name="identidade" value="<?php echo htmlspecialchars($row['identidade']); ?>" required>

            <label for="data_admissao">Data de Admissão:</label>
            <input type="date" id="data_admissao" name="data_admissao" value="<?php echo htmlspecialchars($row['data_admissao']); ?>" required>

            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($row['email']); ?>" required>

            <label for="telefone">Telefone:</label>
            <input type="text" id="telefone" name="telefone" value="<?php echo htmlspecialchars($row['telefone']); ?>" required>

            <label for="cargo">Cargo:</label>
            <input type="text" id="cargo" name="cargo" value="<?php echo htmlspecialchars($row['cargo']); ?>" required>

            <label for="filhos">Filhos:</label>
            <input type="text" id="filhos" name="filhos" value="<?php echo htmlspecialchars($row['filhos']); ?>">

            <label for="genero">Gênero:</label>
            <select id="genero" name="genero">
                <option value="MASCULINO" <?php echo ($row['genero'] == 'MASCULINO') ? 'selected' : ''; ?>>Masculino</option>
                <option value="FEMININO" <?php echo ($row['genero'] == 'FEMININO') ? 'selected' : ''; ?>>Feminino</option>
                <option value="OUTRO" <?php echo ($row['genero'] == 'OUTRO') ? 'selected' : ''; ?>>Outro</option>
            </select>

            <label for="tipo">Tipo:</label>
            <input type="text" id="tipo" name="tipo" value="<?php echo htmlspecialchars($row['tipo']); ?>" required>

            <label for="numero_registro">Número de Registro:</label>
            <input type="text" id="numero_registro" name="numero_registro" value="<?php echo htmlspecialchars($row['numero_registro'] ?? ''); ?>">

            <label for="status">Status:</label>
                <select id="status" name="status" required>
                <option value="Ativo" <?php echo ($row['status'] == 'Ativo') ? 'selected' : ''; ?>>Ativo</option>
                <option value="Inativo" <?php echo ($row['status'] == 'Inativo') ? 'selected' : ''; ?>>Inativo</option>
                <option value="Licença" <?php echo ($row['status'] == 'Licença') ? 'selected' : ''; ?>>Licença</option>
                <option value="Desligado" <?php echo ($row['status'] == 'Desligado') ? 'selected' : ''; ?>>Desligado</option>
            </select>

            <button type="submit">Salvar</button>
        </form>
        <a class="botao-voltar" href="listar_funcionarios.php">Voltar</a>
    </div>
</body>
</html>
