<?php
include('../includes/db_connect.php');
session_start(); // Certifique-se de que a sessão está ativa para verificar os dados do usuário logado.

if (!$conn) {
    die("Conexão falhou: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete'])) {
    $idsToDelete = $_POST['delete'];
    if (!empty($idsToDelete)) {
        $idsToDelete = implode(",", array_map('intval', $idsToDelete)); 
        $sql = "DELETE FROM usuario WHERE id IN ($idsToDelete)";

        if ($conn->query($sql) === TRUE) {
            echo "<p>Usuário(s) deletado(s) com sucesso!</p>";
        } else {
            echo "<p>Erro ao deletar usuário(s): " . $conn->error . "</p>";
        }
    }
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
$sql = "SELECT id, nome, email, setor FROM usuario";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar Usuários</title>
    <link rel="stylesheet" href="../styles/listar_funcionarios.css"> <!-- Reutilizando o mesmo estilo -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet"> <!-- Font Awesome -->
    <script>
        function confirmDelete() {
            return confirm('Tem certeza de que deseja deletar os usuários selecionados?');
        }
    </script>
</head>
<body>
    <div class="container">
    <h1>Lista de Usuários</h1>
    
    <?php if ($result->num_rows > 0): ?>
        <form method='POST' action='listar_usuarios.php' onsubmit='return confirmDelete();'>
            <table>
                <tr>
                    <th>Selecionar</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Setor</th>
                    <th>Editar</th>
                </tr>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><input type='checkbox' name='delete[]' value='<?= $row['id'] ?>'></td>
                        <td><?= $row['nome'] ?></td>
                        <td><?= $row['email'] ?></td>
                        <td><?= $row['setor'] ?></td>
                        <td>
                            <a href='usuario_edicao.php?id=<?= $row['id'] ?>' title='Editar'>
                                <i class='fa fa-pencil-alt' style='color: yellow;'></i>
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
            
            <?php if ($setor_usuario === 'Departamento de Tecnologia da Informação'): ?>
                <button type="submit" onclick="return confirm('Tem certeza que deseja excluir os usuários selecionados?')">Deletar Usuário(s) Selecionado(s)</button>
            <?php endif; ?>
        </form>
    <?php else: ?>
        <p>Nenhum usuário encontrado.</p>
    <?php endif; ?>
        <br><br>
    <a href="menu.php">Voltar ao Menu Principal</a>
    </div>
</body>
</html>

<?php
$conn->close();
?>
