<?php
include('../includes/db_connect.php');

// Parâmetros de visualização
$view = isset($_GET['view']) && in_array($_GET['view'], ['simplified', 'complete']) ? $_GET['view'] : 'complete';

// Filtro e Paginação
$filtro = isset($_GET['filtro']) ? $_GET['filtro'] : '';
$criterio = isset($_GET['criterio']) ? $_GET['criterio'] : 'nome';

// Consulta para buscar funcionários (pode ser simplificada ou completa)
$sql = $view === 'simplified' 
    ? "SELECT id, nome, cargo FROM funcionarios WHERE $criterio LIKE ? LIMIT 50" 
    : "SELECT id, nome, matricula, data_nascimento, cpf, identidade, data_admissao, email, telefone, cargo, filhos, genero, tipo, numero_registro FROM funcionarios WHERE $criterio LIKE ? LIMIT 50";

$stmt = $conn->prepare($sql);
$filtro_param = '%' . $filtro . '%';
$stmt->bind_param('s', $filtro_param);
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
        <input type="hidden" name="view" value="<?= htmlspecialchars($view) ?>">
    </form>

    <!-- Alteração de visualização -->
    <div class="view-toggle">
        <a href="?view=simplified&criterio=<?= $criterio ?>&filtro=<?= urlencode($filtro) ?>" <?= $view === 'simplified' ? 'class="active"' : '' ?>>Visualização Simplificada</a>
        |
        <a href="?view=complete&criterio=<?= $criterio ?>&filtro=<?= urlencode($filtro) ?>" <?= $view === 'complete' ? 'class="active"' : '' ?>>Visualização Completa</a>
    </div>

    <!-- Tabela de Funcionários -->
    <?php if ($result->num_rows > 0): ?>
    <table id="funcionarios-table">
        <thead>
            <tr>
                <?php if ($view === 'simplified'): ?>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Cargo</th>
                    <th>Ações</th>
                <?php else: ?>
                    <th>ID</th>
                    <th>Nome</th>
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
                    <th>Ações</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <?php if ($view === 'simplified'): ?>
                        <td><?= htmlspecialchars($row['id']) ?></td>
                        <td><?= htmlspecialchars($row['nome']) ?></td>
                        <td><?= htmlspecialchars($row['cargo']) ?></td>
                        <td>
                            <a href="funcionario_edicao.php?id=<?= $row['id'] ?>" class="edit">Editar</a>
                        </td>
                    <?php else: ?>
                        <td><?= htmlspecialchars($row['id']) ?></td>
                        <td><?= htmlspecialchars($row['nome']) ?></td>
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
                        <td>
                            <a href="funcionario_edicao.php?id=<?= $row['id'] ?>" class="edit">Editar</a>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <?php else: ?>
        <p>Nenhum funcionário encontrado.</p>
    <?php endif; ?>

    <a href="menu.php">Voltar ao Menu</a>
</body>
</html>
