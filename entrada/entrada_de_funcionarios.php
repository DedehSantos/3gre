<?php
date_default_timezone_set('America/Sao_Paulo');

include_once "../conexao/conexao.php";

$matricula = $_GET['busca'] ?? '';
$data_hoje = date("d/m/Y");
$hora_agora = date("H:i:s");

$conn = mysqli_connect($servidor, $dbusuario, $dbsenha, $dbname);
mysqli_set_charset($conn, 'utf8');

if (!$conn) {
    die('Conexão falhou: ' . mysqli_connect_error());
}

$nome = "Usuário não encontrado";
$hora_entrada = $hora_saida = $horas_finais = $hora_extra = "00:00";
$inicio_jornada = $final_jornada = "00:00";
$percentual = $percentual_dia = 0;
$segundos_trabalhados = $tempo_restante = $tempo_extra = 0;
$horas_restantes = $minutos_restantes = $horas_extras = $minutos_extras = 0;

// Buscar usuário
$sql_user = "SELECT nome, inicio_da_jornada, final_da_jornada FROM usuarios_3gre WHERE matricula = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("s", $matricula);
$stmt_user->execute();
$result_user = $stmt_user->get_result();

if ($linha_user = $result_user->fetch_assoc()) {
    $nome = $linha_user['nome'];
    $inicio_jornada = $linha_user['inicio_da_jornada'];
    $final_jornada = $linha_user['final_da_jornada'];
}

// Verifica se já tem batida hoje
$sql_check = "SELECT * FROM registros_de_pontos WHERE matricula = ? AND data = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("ss", $matricula, $data_hoje);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

if ($linha_registro = $result_check->fetch_assoc()) {
    // Já tem entrada -> atualiza saída com hora atual
    $hora_entrada = $linha_registro['hora_entrada'];
    $hora_saida = $hora_agora;

    $entrada = strtotime($hora_entrada);
    $saida = strtotime($hora_saida);
    $segundos_trabalhados = max($saida - $entrada, 0);
    $horas_finais = sprintf("%02d:%02d", floor($segundos_trabalhados / 3600), floor(($segundos_trabalhados % 3600) / 60));

    $inicio = strtotime($inicio_jornada);
    $fim = strtotime($final_jornada);
    $duracao_jornada = $fim - $inicio;

    $percentual = $duracao_jornada > 0 ? round(($segundos_trabalhados / $duracao_jornada) * 100) : 0;
    $percentual = max(0, min(100, $percentual));

    if ($percentual < 100) {
        $tempo_restante = $duracao_jornada - $segundos_trabalhados;
        $horas_restantes = floor($tempo_restante / 3600);
        $minutos_restantes = floor(($tempo_restante % 3600) / 60);
    }

    if ($percentual >= 100) {
        $tempo_extra = $segundos_trabalhados - $duracao_jornada;
        if ($tempo_extra > 0) {
            $horas_extras = floor($tempo_extra / 3600);
            $minutos_extras = floor(($tempo_extra % 3600) / 60);
            $hora_extra = sprintf("%02d:%02d", $horas_extras, $minutos_extras); // <-- NOVO
        }
    }

    // Atualiza dados
    $sql_update = "UPDATE registros_de_pontos 
                   SET hora_saida = ?, horas_trabalhadas = ?, percentual_jornada = ?, hora_extra = ? 
                   WHERE matricula = ? AND data = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("ssisss", $hora_saida, $horas_finais, $percentual, $hora_extra, $matricula, $data_hoje);
    $stmt_update->execute();
} else {
    // Primeira batida: insere com hora_entrada atual
    $hora_entrada = $hora_agora;
    $sql_insert = "INSERT INTO registros_de_pontos (matricula, data, nome, hora_entrada) VALUES (?, ?, ?, ?)";
    $stmt_insert = $conn->prepare($sql_insert);
    $stmt_insert->bind_param("ssss", $matricula, $data_hoje, $nome, $hora_entrada);
    $stmt_insert->execute();
}

// CALCULAR A PROGRESSÃO EM TEMPO REAL
$inicio = strtotime($inicio_jornada);
$fim = strtotime($final_jornada);
$agora = strtotime($hora_agora);
$duracao_total = $fim - $inicio;
$tempo_passado = max($agora - $inicio, 0);

if ($agora >= $inicio && $agora <= $fim) {
    $percentual_dia = round(($tempo_passado / $duracao_total) * 100);
} elseif ($agora > $fim) {
    $percentual_dia = 100;
}

if ($linha_registro = $result_check->fetch_assoc()) {
    // Já tem entrada -> atualiza saída com hora atual
    $hora_entrada = $linha_registro['hora_entrada'];
    $hora_saida = $hora_agora;

    $entrada = strtotime($hora_entrada);
    $saida = strtotime($hora_saida);
    $segundos_trabalhados = max($saida - $entrada, 0);

    

    // NOVO: variável com horas trabalhadas
    $horas_trabalhadas = sprintf("%02d:%02d", floor($segundos_trabalhados / 3600), floor(($segundos_trabalhados % 3600) / 60));
    $horas_finais = $horas_trabalhadas; // mantido por compatibilidade com o restante do código

    $inicio = strtotime($inicio_jornada);
    $fim = strtotime($final_jornada);
    $duracao_jornada = $fim - $inicio;

    $percentual = $duracao_jornada > 0 ? round(($segundos_trabalhados / $duracao_jornada) * 100) : 0;
    $percentual = max(0, min(100, $percentual));

    if ($percentual < 100) {
        $tempo_restante = $duracao_jornada - $segundos_trabalhados;
        $horas_restantes = floor($tempo_restante / 3600);
        $minutos_restantes = floor(($tempo_restante % 3600) / 60);
    }

    if ($percentual >= 100) {
        $tempo_extra = $segundos_trabalhados - $duracao_jornada;
        if ($tempo_extra > 0) {
            $horas_extras = floor($tempo_extra / 3600);
            $minutos_extras = floor(($tempo_extra % 3600) / 60);
            $hora_extra = sprintf("%02d:%02d", $horas_extras, $minutos_extras);
        }
    }

    // Atualiza dados
    $sql_update = "UPDATE registros_de_pontos 
                   SET hora_saida = ?, horas_trabalhadas = ?, percentual_jornada = ?, hora_extra = ? 
                   WHERE matricula = ? AND data = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("ssisss", $hora_saida, $horas_trabalhadas, $percentual, $hora_extra, $matricula, $data_hoje);
    $stmt_update->execute();
}


$conn->close();
?>



<!-- HTML abaixo -->
<!DOCTYPE html>
<html lang="pt_BR">
<head>
    <meta charset="UTF-8">
    <title>Resumo de Jornada</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <style>
        .container {
            max-width: 600px;
            margin: 50px auto;
            background: #f8f9fa;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0px 0px 12px rgba(0,0,0,0.1);
        }

        h2 {
            color: #007bff;
            font-weight: bold;
            margin-bottom: 25px;
        }

        .info {
            font-size: 18px;
            margin: 5px 0;
        }

        .nome-destaque {
            font-size: 24px;
            font-weight: bold;
            color: #343a40;
            background: #e9ecef;
            border-left: 6px solid #007bff;
            padding: 10px 15px;
            margin-bottom: 25px;
            border-radius: 6px;
        }

        .progress {
            height: 30px;
        }
    </style>
</head>
<body>

<div class="container text-center">
    <h2>Resumo da Jornada</h2>

    <div class="nome-destaque"><?= htmlspecialchars($nome); ?></div>

    <p class="info"><strong>Data:</strong> <?= date("d/m/Y"); ?></p>
    <p class="info"><strong>Entrada:</strong> <?= $hora_entrada; ?> | <strong>Saída:</strong> <?= $hora_saida; ?></p>
    <p class="info"><strong>Início da Jornada:</strong> <?= $inicio_jornada; ?> | <strong>Fim:</strong> <?= $final_jornada; ?></p>
    <p class="info"><strong>Horas Trabalhadas:</strong> <?= $horas_finais; ?></p>

    <div class="progress mt-4">
        <div class="progress-bar bg-success" role="progressbar" style="width: <?= $percentual ?>%;" aria-valuenow="<?= $percentual ?>" aria-valuemin="0" aria-valuemax="100">
            <?= $percentual ?>%
        </div>
    </div>

    <?php if ($tempo_restante > 0): ?>
        <div class="alert alert-warning mt-4">
            Jornada incompleta! Faltam aproximadamente 
            <?= sprintf("%02d:%02d", $horas_restantes, $minutos_restantes); ?> 
            para completar.
        </div>
    <?php elseif ($tempo_extra > 0): ?>
        <div class="alert alert-success mt-4">
            Jornada excedida! Você acumulou 
            <?= sprintf("%02d:%02d", $horas_extras, $minutos_extras); ?> 
            de banco de horas hoje. 👏
        </div>
    <?php endif; ?>
</div>

<script src="../js/bootstrap.min.js"></script>
</body>
</html>
<script>
    setTimeout(function() {
        window.location.href = "../index.php";
    }, 8000); // 8000 milissegundos = 8 segundos
</script>
