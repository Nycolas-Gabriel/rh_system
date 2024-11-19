<?php
include('../includes/db_connect.php');

// Se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['csv_file'])) {
    // Verifica se houve erro no upload do arquivo
    if ($_FILES['csv_file']['error'] == 0) {
        // Lê o arquivo CSV
        $file = $_FILES['csv_file']['tmp_name'];
        $handle = fopen($file, 'r');

        // Ignora o cabeçalho (caso exista)
        fgetcsv($handle);

        // Inicia uma variável para armazenar os erros
        $erros = [];
        
        while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
            // Atribui os valores do CSV a variáveis, tratando campos vazios
            $nome = !empty($data[0]) ? $data[0] : NULL;

            // Log para verificar o nome
            error_log("Nome lido: $nome"); // Adicione esta linha

            $matricula = !empty($data[1]) ? $data[1] : NULL;
            
            // Converte a data de nascimento, se houver
            $data_nascimento = !empty($data[2]) ? DateTime::createFromFormat('d/m/Y', $data[2])->format('Y-m-d') : NULL;
            
            $cpf = !empty($data[3]) ? $data[3] : NULL;
            $identidade = !empty($data[4]) ? $data[4] : NULL;
            
            // Converte a data de admissão, se houver
            $data_admissao = !empty($data[5]) ? DateTime::createFromFormat('d/m/Y', $data[5])->format('Y-m-d') : NULL;
            
            $email = !empty($data[6]) ? $data[6] : NULL;
            $telefone = !empty($data[7]) ? $data[7] : NULL;
            $cargo = !empty($data[8]) ? $data[8] : NULL;
            
            // Verifica o valor de 'filhos', define 'Não' como padrão se estiver vazio
            $filhos = !empty($data[9]) ? strtolower($data[9]) : 'não';
            if ($filhos !== 'sim' && $filhos !== 'não') {
                $erros[] = "Valor inválido para 'filhos' no registro de $nome: $filhos";
                continue; // Pula para o próximo registro
            }
            
            $genero = !empty($data[10]) ? $data[10] : NULL;
            $tipo = !empty($data[11]) ? $data[11] : NULL;
            $numero_registro = !empty($data[12]) ? $data[12] : NULL;

            // Insere os dados na tabela, ignorando duplicatas
            $sql = "INSERT IGNORE INTO funcionarios (nome, matricula, data_nascimento, cpf, identidade, data_admissao, email, telefone, cargo, filhos, genero, tipo, numero_registro) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssssssssss", $nome, $matricula, $data_nascimento, $cpf, $identidade, $data_admissao, $email, $telefone, $cargo, $filhos, $genero, $tipo, $numero_registro);

            if (!$stmt->execute()) {
                // Adiciona o erro à lista se a inserção falhar
                $erros[] = "Erro ao inserir registro para $nome: " . $stmt->error;
            }
        }

        fclose($handle);
        
        // Verifica se houve erros durante o processo
        if (empty($erros)) {
            echo "Todos os dados foram inseridos com sucesso!";
        } else {
            // Exibe os erros
            foreach ($erros as $erro) {
                echo $erro . "<br>";
            }
        }
    } else {
        echo "Erro no upload do arquivo: " . $_FILES['csv_file']['error'];
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Funcionários em Lote</title>
</head>
<body>
    <h2>Carregar Funcionários via CSV</h2>
    <form action="" method="post" enctype="multipart/form-data">
        <input type="file" name="csv_file" accept=".csv" required>
        <input type="submit" value="Carregar">
    </form>
    <div class="btn-container">
        <a href="menu.php" class="btn-voltar">Voltar ao Menu</a>
    </div>
</body>
</html>
