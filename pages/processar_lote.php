<?php
include('../includes/db_connect.php');
require 'vendor/autoload.php'; // Certifique-se de ajustar o caminho conforme necessário

use PhpOffice\PhpSpreadsheet\IOFactory;

// Função para remover acentos
function removerAcentos($string) {
    return preg_replace(
        '/[áàãâäéèêëíìîïóòõôöúùûü]/iu',
        ['a', 'a', 'a', 'a', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u'],
        $string
    );
}

// Verificar se o arquivo foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['arquivo_excel'])) {
    $arquivo = $_FILES['arquivo_excel']['tmp_name'];

    try {
        // Carregar o arquivo Excel
        $spreadsheet = IOFactory::load($arquivo);
        $sheet = $spreadsheet->getActiveSheet();
        $data = $sheet->toArray(null, true, true, true); // Força a leitura correta dos dados

        foreach ($data as $key => $funcionario) {
            // Ignorar a primeira linha se for cabeçalho
            if ($key === 1) { // Alterado para 1 já que a primeira linha do array é a linha 1
                continue;
            }

            // Extrair os dados do arquivo Excel e remover espaços extras
            $nome = removerAcentos(trim($funcionario['A'])); // Nome do Funcionário (ajustado para a coluna 'A')
            $matricula = trim($funcionario['B']); // Matrícula (ajustado para a coluna 'B')
            $data_nascimento = trim($funcionario['C']); // Data de Nascimento (ajustado para a coluna 'C')
            $cpf = trim($funcionario['D']); // CPF (ajustado para a coluna 'D')
            $identidade = trim($funcionario['E']); // Identidade (ajustado para a coluna 'E')
            $admissao = trim($funcionario['F']); // Admissão (ajustado para a coluna 'F')
            $email = trim($funcionario['G']); // E-mail (ajustado para a coluna 'G')
            $telefone = trim($funcionario['H']); // Telefone (ajustado para a coluna 'H')
            $cargo = trim($funcionario['I']); // Cargo (ajustado para a coluna 'I')
            $filhos = trim($funcionario['J']); // Filhos (ajustado para a coluna 'J')
            $id_genero = trim($funcionario['K']); // ID Gênero (ajustado para a coluna 'K')

            // Verifique se o nome tem um tamanho adequado
            if (strlen($nome) > 255) {
                $nome = substr($nome, 0, 255); // Se o nome for muito longo, corte-o para 255 caracteres
            }

            // Inserir no banco de dados, verificando duplicados com CPF e matrícula
            $sql = "INSERT IGNORE INTO funcionarios (nome_funcionario, matricula, data_nascimento, cpf, identidade, admissao, email, telefone, cargo, filhos, id_genero)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssssssis", $nome, $matricula, $data_nascimento, $cpf, $identidade, $admissao, $email, $telefone, $cargo, $filhos, $id_genero);

            if (!$stmt->execute()) {
                echo "Erro ao inserir registro: " . $stmt->error;
            }
        }

        echo json_encode(['status' => 'success', 'message' => 'Funcionários adicionados com sucesso.']);
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Erro ao processar o arquivo: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Nenhum arquivo recebido.']);
}
?>
