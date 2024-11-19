<?php
include('../includes/db_connect.php');

// Definir o número de registros por página
$limite = isset($_GET['limite']) && in_array($_GET['limite'], [10, 50, 100, 500]) ? (int)$_GET['limite'] : 10;
$pagina_atual = isset($_GET['pagina']) && is_numeric($_GET['pagina']) && $_GET['pagina'] > 0 ? (int)$_GET['pagina'] : 1;
$offset = ($pagina_atual - 1) * $limite;

// Parâmetros de busca
$filtro = isset($_GET['filtro']) ? $_GET['filtro'] : '';
$criterio = isset($_GET['criterio']) ? $_GET['criterio'] : 'nome';

// Consulta para contar o total de registros (com filtro, se aplicável)
$total_sql = "SELECT COUNT(*) AS total FROM funcionarios WHERE $criterio LIKE ?";
$stmt = $conn->prepare($total_sql);
$filtro_param = '%' . $filtro . '%';
$stmt->bind_param('s', $filtro_param);
$stmt->execute();
$total_result = $stmt->get_result();
$total_registros = $total_result->fetch_assoc()['total'];
$stmt->close();

// Consulta para listar funcionários com limite, deslocamento e filtro
$sql = "SELECT id, nome, matricula, data_nascimento, cpf, identidade, data_admissao, email, telefone, cargo, filhos, genero, tipo, numero_registro 
        FROM funcionarios 
        WHERE $criterio LIKE ? 
        LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('sii', $filtro_param, $limite, $offset);
$stmt->execute();
$result = $stmt->get_result();
$total_paginas = ceil($total_registros / $limite);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Funcionários Cadastrados</title>
    <link rel="stylesheet" href="../styles/listar_funcionarios.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script>
        function toggleSelectAll(source) {
            checkboxes = document.querySelectorAll('input[type="checkbox"]');
            for (var i = 0; i < checkboxes.length; i++) {
                if (checkboxes[i] != source)
                    checkboxes[i].checked = source.checked;
            }
        }
    </script>
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

    <?php if ($result->num_rows > 0): ?>
    <form method="POST" action="">
        <table id="funcionarios-table">
            <thead>
                <tr>
                    <th><input type="checkbox" onclick="toggleSelectAll(this)"></th>
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
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td><input type='checkbox' name='delete[]' value='" . $row['id'] . "'></td>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . htmlspecialchars($row['nome'] ?: '') . "</td>";
                    echo "<td>" . htmlspecialchars($row['matricula'] ?: '') . "</td>";
                    echo "<td>" . htmlspecialchars($row['data_nascimento'] ?: '') . "</td>";
                    echo "<td>" . htmlspecialchars($row['cpf'] ?: '') . "</td>";
                    echo "<td>" . htmlspecialchars($row['identidade'] ?: '') . "</td>";
                    echo "<td>" . htmlspecialchars($row['data_admissao'] ?: '') . "</td>";
                    echo "<td>" . htmlspecialchars($row['email'] ?: '') . "</td>";
                    echo "<td>" . htmlspecialchars($row['telefone'] ?: '') . "</td>";
                    echo "<td>" . htmlspecialchars($row['cargo'] ?: '') . "</td>";
                    echo "<td>" . htmlspecialchars($row['filhos'] ?: '') . "</td>";
                    echo "<td>" . htmlspecialchars($row['genero'] ?: '') . "</td>";
                    echo "<td>" . htmlspecialchars($row['tipo'] ?: '') . "</td>";
                    echo "<td>" . htmlspecialchars($row['numero_registro'] ?: '') . "</td>";
                    echo "<td><a href='funcionario_edicao.php?id=" . $row['id'] . "' class='edit'><i class='fas fa-edit'></i></a></td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
        <button type="submit" onclick="return confirm('Tem certeza que deseja excluir os funcionários selecionados?')">Deletar Selecionados</button>
    </form>
    <?php else: ?>
        <p>Nenhum funcionário encontrado.</p>
    <?php endif; ?>

    <!-- Navegação de página -->
    <div class="pagination">
        <?php if ($pagina_atual > 1): ?>
            <a href="?pagina=<?= $pagina_atual - 1 ?>&limite=<?= $limite ?>&criterio=<?= $criterio ?>&filtro=<?= urlencode($filtro) ?>">&laquo; Anterior</a>
        <?php endif; ?>
        <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
            <a href="?pagina=<?= $i ?>&limite=<?= $limite ?>&criterio=<?= $criterio ?>&filtro=<?= urlencode($filtro) ?>" <?= $pagina_atual === $i ? 'class="active"' : '' ?>><?= $i ?></a>
        <?php endfor; ?>
        <?php if ($pagina_atual < $total_paginas): ?>
            <a href="?pagina=<?= $pagina_atual + 1 ?>&limite=<?= $limite ?>&criterio=<?= $criterio ?>&filtro=<?= urlencode($filtro) ?>">Próximo &raquo;</a>
        <?php endif; ?>
    </div>

    <a href="menu.php">Voltar ao Menu</a>
</body>
</html>
