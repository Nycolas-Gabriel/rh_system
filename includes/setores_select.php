<?php
function gerarSelectSetores($setorSelecionado = '') {
    $setores = [
        "Assessoria de Plenário",
        "Assessoria Técnica",
        "Auditoria",
        "Câmara Técnica de Assistência à Saúde da Mulher",
        "Assessoria de Engenharia",
        "Câmara Técnica de Assistência à Atenção Básica",
        "Câmara Técnica de Assistência em Enfermagem",
        "Câmara Técnica de Atenção Básica",
        "Câmara Técnica de Empreendedorismo",
        "Câmara Técnica de Enf. Intelectual e Atenção às Populações Vulneráveis",
        "Câmara Técnica de Ensino e Pesquisa",
        "Câmara Técnica de Gestão em Enfermagem",
        "Câmara Técnica de Populações Vulneráveis",
        "Câmara Técnica de Residência e Pós",
        "CAPE - Comissão de Acompanhamento de Processos Éticos",
        "COFEN - Conselho Federal de Enfermagem",
        "Comissão de Assessoria ao Enfermeiro Empreendedor e Responsável Técnico",
        "Comissão de Assessoria ao Enfermeiro Residente e Pós-Graduando",
        "Comissão de Auxiliares e Técnicos de Enfermagem",
        "Comissão de Enfermagem Forense",
        "Comissão de Fase Interna de Licitação",
        "Comissão de Planejamento das Contratações",
        "Comissão de Práticas Integrativas e Complementares",
        "Comissão de Tratativas da Comissão Parlamentar",
        "Comissão Eleitoral 2023",
        "Comissão Permanente de Licitação",
        "Conselheiro - Ana Caroline",
        "Conselheiro - Antônio Carlos",
        "Conselheiro - Aracele Cavalcanti",
        "Conselheiro - Diego Moraes",
        "Conselheiro - Eduardo Quintas",
        "Conselheiro - Gidelson Gomes",
        "Conselheiro - Isabelle Braga",
        "Conselheiro - José Almir",
        "Conselheiro - Marcos Antônio",
        "Conselheiro - Sara Fontes",
        "Conselheiro - Severina Elelvina",
        "Conselheiro - Suzana Costa",
        "Conselheiro - Thaise Torres",
        "Coordenação de Câmaras Técnicas",
        "Denúncia",
        "Departamento Administrativo",
        "Departamento de Comunicação e Publicidade",
        "Departamento de Fiscalização Sede",
        "Departamento de Fiscalização Subseções",
        "Departamento de Licitações, Contratos e Convênios",
        "Departamento de Planejamento e Qualidade",
        "Departamento de Tecnologia da Informação",
        "Departamento do Exercício Profissional",
        "Departamento Financeiro",
        "Escritório de Integridade",
        "Externo",
        "Fiscalização - Catarina Ugiette",
        "Fiscalização - Eline Barbosa",
        "Fiscalização - Fernanda Cerqueira",
        "Fiscalização - Glovana Mastrangeli",
        "Fiscalização - Hélia Sibely",
        "Fiscalização - Ivana Andrade",
        "Fiscalização - José Gilmar",
        "Fiscalização - Kátia Sales",
        "Gabinete da Presidência",
        "NACE - Núcleo de Acompanhamento das Comissões de Ética",
        "NEDIP - Núcleo de Ética e Disciplina Profissional",
        "Ouvidoria Geral",
        "Procuradoria Geral",
        "Secretaria da Presidência",
        "Secretaria Geral",
        "Setor de Almoxarifado e Patrimônio",
        "Setor de Atendimento",
        "Setor de Compras e Contratações",
        "Setor de Contabilidade",
        "Setor de Gestão de Contratos",
        "Setor de Gestão de Pessoas",
        "Setor de Negociação",
        "Setor de Registro e Cadastro",
        "Subseção - Petrolina",
        "Setor de Serviços Diversos",
        "Setor de Tesouraria",
        "Setor de Transportes",
        "Subseção - Serra Talhada",
        "Subseção - Caruaru",
        "Subseção - Garanhuns",
        "Subseção - Limoeiro"
    ];

    echo '<select name="setor" class="form-select" id="setor">';
    echo '<option value="">Selecione o Setor</option>';

    foreach ($setores as $setor) {
        $selected = ($setor == $setorSelecionado) ? 'selected' : '';
        echo '<option value="' . htmlspecialchars($setor) . '" ' . $selected . '>' . htmlspecialchars($setor) . '</option>';
    }

    echo '</select>';
}
?>
