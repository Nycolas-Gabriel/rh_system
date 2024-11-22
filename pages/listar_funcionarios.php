<?php
include('../includes/db_connect.php');
session_start(); // Iniciar a sessão

// Verificar se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Recuperar informações do usuário logado
$user_id = $_SESSION['user_id'];
$sql_user = "SELECT setor FROM usuario WHERE id = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param('i', $user_id);
$stmt_user->execute();
$result_user = $stmt_user->get_result();
$user_data = $result_user->fetch_assoc();

$setor_usuario = $user_data['setor'] ?? '';

// Parâmetros de visualização
$view = isset($_GET['view']) && in_array($_GET['view'], ['simplified', 'complete']) ? $_GET['view'] : 'complete';
$limite = isset($_GET['limite']) && in_array($_GET['limite'], [10, 50, 100, 500]) ? (int)$_GET['limite'] : 50;

// Filtro
$filtro = isset($_GET['filtro']) ? $_GET['filtro'] : '';
$criterio = isset($_GET['criterio']) ? $_GET['criterio'] : 'nome';

// Consulta para buscar funcionários
$sql = $view === 'simplified' 
    ? "SELECT id, nome, cargo FROM funcionarios WHERE $criterio LIKE ? LIMIT ?"
    : "SELECT id, nome, matricula, data_nascimento, cpf, identidade, data_admissao, email, telefone, cargo, filhos, genero, tipo, numero_registro FROM funcionarios WHERE $criterio LIKE ? LIMIT ?";

$stmt = $conn->prepare($sql);
$filtro_param = '%' . $filtro . '%';
$stmt->bind_param('si', $filtro_param, $limite);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Funcionários Cadastrados</title>
    <link rel="stylesheet" href="../styles/listar_funcionarios.css">
</head>
<body>
    <h1>Funcionários Cadastrados</h1>

    <!-- Filtro -->
    <form method="GET" action="">
        <label for="criterio">Filtrar por:</label>
        <select name="criterio" id="criterio">
            <option value="nome" <?= $criterio === 'nome' ? 'selected' : '' ?>>Nome</option>
            <option value="matricula" <?= $criterio === 'matricula' ? 'selected' : '' ?>>Matrícula</option>
            <option value="cargo" <?= $criterio === 'cargo' ? 'selected' : '' ?>>Cargo</option>
            <option value="cpf" <?= $criterio === 'cpf' ? 'selected' : '' ?>>CPF</option>
        </select>
        <input type="text" name="filtro" placeholder="Digite sua busca..." value="<?= htmlspecialchars($filtro) ?>">
        <button type="submit">Filtrar</button>
    </form>

    <!-- Seleção de quantidade -->
    <form method="GET" action="">
        <label for="limite">Exibir:</label>
        <select name="limite" id="limite" onchange="this.form.submit()">
            <option value="10" <?= $limite === 10 ? 'selected' : '' ?>>10</option>
            <option value="50" <?= $limite === 50 ? 'selected' : '' ?>>50</option>
            <option value="100" <?= $limite === 100 ? 'selected' : '' ?>>100</option>
            <option value="500" <?= $limite === 500 ? 'selected' : '' ?>>500</option>
        </select>
        <input type="hidden" name="filtro" value="<?= htmlspecialchars($filtro) ?>">
        <input type="hidden" name="criterio" value="<?= htmlspecialchars($criterio) ?>">
    </form>

    <!-- Alteração de visualização -->
    <div class="view-toggle">
        <a href="?view=simplified&criterio=<?= $criterio ?>&filtro=<?= urlencode($filtro) ?>&limite=<?= $limite ?>" <?= $view === 'simplified' ? 'class="active"' : '' ?>>Visualização Simplificada</a>
        <a href="?view=complete&criterio=<?= $criterio ?>&filtro=<?= urlencode($filtro) ?>&limite=<?= $limite ?>" <?= $view === 'complete' ? 'class="active"' : '' ?>>Visualização Completa</a>
    </div>

    <!-- Tabela de Funcionários -->
    <?php if ($result->num_rows > 0): ?>
    <form method="POST" action="">
        <table id="funcionarios-table">
            <thead>
                <tr>
                    <?php if ($setor_usuario === 'Departamento de Tecnologia da Informação'): ?>
                        <th><input type="checkbox" onclick="toggleSelectAll(this)"></th>
                    <?php endif; ?>
                    <th>ID</th>
                    <th>Nome</th>
                    <?php if ($view === 'complete'): ?>
                        <th>Matrícula</th>
                        <th>Data de Nascimento</th>
                        <th>CPF</th>
                        <th>Identidade</th>
                        <th>Data de Admissão</th>
                        <th>E-mail</th>
                        <th>Telefone</th>
                        <th>Cargo</th>
                        <th>Filhos</th>
                        <th>Gênero</th>
                        <th>Tipo</th>
                        <th>Número de Registro</th>
                    <?php else: ?>
                        <th>Cargo</th>
                    <?php endif; ?>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <?php if ($setor_usuario === 'Departamento de Tecnologia da Informação'): ?>
                            <td><input type="checkbox" name="delete[]" value="<?= $row['id'] ?>"></td>
                        <?php endif; ?>
                        <td><?= htmlspecialchars($row['id']) ?></td>
                        <td><?= htmlspecialchars($row['nome']) ?></td>
                        <?php if ($view === 'complete'): ?>
                            <td><?= htmlspecialchars($row['matricula']) ?></td>
                            <td><?= htmlspecialchars($row['data_nascimento']) ?></td>
                            <td><?= htmlspecialchars($row['cpf']) ?></td>
                            <td><?= htmlspecialchars($row['identidade']) ?></td>
                            <td><?= htmlspecialchars($row['data_admissao']) ?></td>
                            <td><?= htmlspecialchars($row['email']) ?></td>
                            <td><?= htmlspecialchars($row['telefone']) ?></td>
                            <td><?= htmlspecialchars($row['cargo']) ?></td>
                            <td><?= htmlspecialchars($row['filhos']) ?></td>
                            <td><?= htmlspecialchars($row['genero']) ?></td>
                            <td><?= htmlspecialchars($row['tipo']) ?></td>
                            <td><?= htmlspecialchars($row['numero_registro']) ?></td>
                        <?php else: ?>
                            <td><?= htmlspecialchars($row['cargo']) ?></td>
                        <?php endif; ?>
                        <td>
                            <a href="funcionario_edicao.php?id=<?= $row['id'] ?>" class="edit">Editar</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php if ($setor_usuario === 'Departamento de Tecnologia da Informação'): ?>
            <button type="submit" onclick="return confirm('Tem certeza que deseja excluir os funcionários selecionados?')">Deletar Selecionados</button>
        <?php endif; ?>
    </form>
    <?php else: ?>
        <p>Nenhum funcionário encontrado.</p>
    <?php endif; ?>

    <a href="menu.php">Voltar ao Menu</a>
</body>
</html>
