<?php
// Conectando ao banco de dados
include_once "../conexao/conexao.php";

// Data e hora atual no fuso de São Paulo
$timeZone = new \DateTimeZone('America/Sao_Paulo'); 
$dataAtual = new \DateTime();
$dataAtual->setTimezone($timeZone);
$data_hoje = $dataAtual->format('d/m/Y');
$hora_atual = $dataAtual->format('H:i:s');

// Hora limite para considerar atraso
$limiteHorario = "07:15:00";

// Entradas no horário
$sqlEntradas = "
    SELECT COUNT(*) AS total 
    FROM registros_de_pontos 
    WHERE data = ? AND hora_entrada IS NOT NULL AND hora_entrada <> '00:00:00' AND hora_entrada <= ?";
$stmt = $conn->prepare($sqlEntradas);
$stmt->bind_param("ss", $data_hoje, $limiteHorario);
$stmt->execute();
$resultadoEntradas = $stmt->get_result();
$quantidadeEntradas = $resultadoEntradas ? $resultadoEntradas->fetch_assoc()['total'] : 0;
$stmt->close();

// Atrasos
$sqlAtrasos = "
    SELECT COUNT(*) AS total 
    FROM registros_de_pontos 
    WHERE data = ? AND hora_entrada > ?";
$stmt = $conn->prepare($sqlAtrasos);
$stmt->bind_param("ss", $data_hoje, $limiteHorario);
$stmt->execute();
$resultadoAtrasos = $stmt->get_result();
$quantidadeAtrasos = $resultadoAtrasos ? $resultadoAtrasos->fetch_assoc()['total'] : 0;
$stmt->close();

// Saídas
$sqlSaidas = "
    SELECT COUNT(*) AS total 
    FROM registros_de_pontos 
    WHERE data = ? AND hora_saida IS NOT NULL AND hora_saida <> '00:00:00'";
$stmt = $conn->prepare($sqlSaidas);
$stmt->bind_param("s", $data_hoje);
$stmt->execute();
$resultadosaidas = $stmt->get_result();
$quantidadesaidas = $resultadosaidas ? $resultadosaidas->fetch_assoc()['total'] : 0;
$stmt->close();

// Na escola
$quantidadeTotal = $quantidadeEntradas + $quantidadeAtrasos;
$alunosnaescola = $quantidadeTotal - $quantidadesaidas;

// Faltas por turma (usando registros_de_pontos)
$sqlFaltas = "
    SELECT u.setor AS turma, COUNT(*) AS total_faltas 
    FROM registros_de_pontos r
    INNER JOIN usuarios_3gre u ON r.matricula = u.matricula
    WHERE r.data = ? AND r.faltas = 'Faltante'
    GROUP BY u.setor";

$stmt = $conn->prepare($sqlFaltas);
$stmt->bind_param("s", $data_hoje);
$stmt->execute();
$resultadoFaltas = $stmt->get_result();
$faltasPorTurma = [];

if ($resultadoFaltas) {
    while ($linhaFaltas = $resultadoFaltas->fetch_assoc()) {
        $faltasPorTurma[] = [
            'turma' => $linhaFaltas['turma'],
            'total_faltas' => $linhaFaltas['total_faltas']
        ];
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../img/nova_logo.png" type="image/png">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/quantidade.css">
    <title>Quantidade de Alunos</title>
</head>
<body class="bg-light">
    <div class="container my-5">
        <div class="card shadow p-4 mb-4">
            <h2 class="text-center text-primary mb-4">Contagem Hoje (<?php echo $data_hoje; ?>)</h2>
            <div class="row text-center">
                <div class="col-md-6 col-lg-4 mb-3">
                    <div class="p-3 bg-info text-white rounded">
                        <h5>Entraram no horário</h5>
                        <p class="display-4"><?php echo $quantidadeEntradas; ?></p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 mb-3">
                    <div class="p-3 bg-warning text-white rounded">
                        <h5>Entraram atrasados</h5>
                        <p class="display-4"><?php echo $quantidadeAtrasos; ?></p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 mb-3">
                    <div class="p-3 bg-danger text-white rounded">
                        <h5>Saíram</h5>
                        <p class="display-4"><?php echo $quantidadesaidas; ?></p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-6 mb-3">
                    <div class="p-3 bg-success text-white rounded">
                        <h5>Na 3GRE hoje</h5>
                        <p class="display-4"><?php echo $alunosnaescola; ?></p>
                    </div>
                </div>
            </div>

            
            </ul>
        </div>
    </div>

<script src="../js/jquery-3.3.1.slim.min.js" crossorigin="anonymous"></script>
<script src="../js/popper.min.js" crossorigin="anonymous"></script>
<script src="../js/bootstrap.min.js" crossorigin="anonymous"></script>
</body>
</html>