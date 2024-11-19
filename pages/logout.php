<?php
session_start();

// Verifique se a sessão está iniciada e destrua-a
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    // Feche a conexão com o banco de dados, se necessário
    // $conn->close(); // Descomente isso se você estiver usando uma variável global para a conexão

    // Destrua a sessão e remova as variáveis de sessão
    session_unset();
    session_destroy();
}

// Redirecione para a página de login
header("Location: http://10.0.0.3/system/gerenciamento/pages/login.php");
exit();
?>
