<?php
// Inclui o arquivo de conexão com o banco de dados
include_once "../conexao/conexao.php";

// Obtém a data e hora atual no fuso horário de São Paulo
$timeZone = new \DateTimeZone('America/Sao_Paulo');
$dataAtual = new \DateTime();
$dataAtual->setTimezone($timeZone);
$data_hoje = $dataAtual->format('Y-m-d'); // Formato para comparação no banco
$hora_atual = $dataAtual->format('H:i:s');

// Inicializa variáveis para os filtros de pesquisa
$matricula_pesquisa = isset($_GET['matricula']) ? $_GET['matricula'] : '';
$nome_pesquisa = isset($_GET['nome']) ? $_GET['nome'] : '';
$setor_pesquisa = isset($_GET['setor']) ? $_GET['setor'] : '';

// Consulta para listar todos os funcionários
$sql_funcionarios = "SELECT matricula, nome, setor FROM registros_de_pontos WHERE 1=1";

if (!empty($matricula_pesquisa)) {
    $sql_funcionarios .= " AND matricula LIKE '%$matricula_pesquisa%'";
}
if (!empty($nome_pesquisa)) {
    $sql_funcionarios .= " AND nome LIKE '%$nome_pesquisa%'";
}
if (!empty($setor_pesquisa)) {
    $sql_funcionarios .= " AND setor = '$setor_pesquisa'";
}

$result_funcionarios = mysqli_query($conn, $sql_funcionarios);

if (mysqli_num_rows($result_funcionarios) > 0) {
    $funcionarios_comparados = [];

    while ($funcionario = mysqli_fetch_assoc($result_funcionarios)) {
        $matricula = $funcionario['matricula'];
        $nome = $funcionario['nome'];
        $setor = $funcionario['setor'];

        $status = 'Aguardando';
        $classStatus = 'aguardando';

        // Verifica se o funcionário tem registro de entrada na data de hoje
        $sql_entrada = "SELECT * FROM registros_de_pontos WHERE matricula = ? AND 'data_entrada' = ?";
        $stmt_entrada = $conn->prepare($sql_entrada);
        $stmt_entrada->bind_param("ss", $matricula, $data_hoje);
        $stmt_entrada->execute();
        $stmt_entrada->store_result();

        // Verifica se há registro de atraso
        $sql_atraso = "SELECT * FROM registros_atraso WHERE matricula = ? AND data_atraso = ?";
        $stmt_atraso = $conn->prepare($sql_atraso);
        $stmt_atraso->bind_param("ss", $matricula, $data_hoje);
        $stmt_atraso->execute();
        $stmt_atraso->store_result();

        if ($stmt_entrada->num_rows > 0) {
            $status = 'Presente';
            $classStatus = 'presente';
        }

        if ($stmt_atraso->num_rows > 0) {
            $status = 'Atrasado';
            $classStatus = 'atrasado';
        }

        // Verifica se há registro de saída
        $sql_saida = "SELECT * FROM registros_saida WHERE matricula = ? AND data_saida = ?";
        $stmt_saida = $conn->prepare($sql_saida);
        $stmt_saida->bind_param("ss", $matricula, $data_hoje);
        $stmt_saida->execute();
        $stmt_saida->store_result();

        if ($stmt_saida->num_rows > 0) {
            $status = 'Saiu';
            $classStatus = 'saiu';
        }

        $funcionarios_comparados[] = [
            'matricula' => $matricula,
            'nome' => $nome,
            'setor' => $setor,
            'status' => $status,
            'data' => $data_hoje,
            'classStatus' => $classStatus
        ];

        $stmt_entrada->close();
        $stmt_atraso->close();
        $stmt_saida->close();
    }
    ?>
    <!DOCTYPE html>
    <html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Status dos Funcionários - <?php echo $data_hoje; ?></title>
        <link rel="stylesheet" href="../css/bootstrap.min.css">
        <style>
            .aguardando { background-color: #808080; }
            .presente { background-color: #007f00; color: white; }
            .atrasado { background-color: #FFFF00; }
            .saiu { background-color: #0000FF; color: white; }
        </style>
    </head>
    <body>
        <div class="container mt-5">
            <h2>Status dos Funcionários - <?php echo $data_hoje; ?></h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Matrícula</th>
                        <th>Nome</th>
                        <th>Setor</th>
                        <th>Status</th>
                        <th>Data</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($funcionarios_comparados as $funcionario): ?>
                        <tr class="<?php echo $funcionario['classStatus']; ?>">
                            <td><?php echo $funcionario['matricula']; ?></td>
                            <td><?php echo $funcionario['nome']; ?></td>
                            <td><?php echo $funcionario['setor']; ?></td>
                            <td><?php echo $funcionario['status']; ?></td>
                            <td><?php echo $funcionario['data']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </body>
    </html>
    <?php
} else {
    echo "Nenhum funcionário encontrado.";
}

mysqli_close($conn);
?>