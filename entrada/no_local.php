<?php
include_once "../conexao/conexao.php";
date_default_timezone_set('America/Sao_Paulo');
$data_hoje = date("d/m/Y");

$matricula_pesquisa = $_GET['matricula'] ?? '';
$nome_pesquisa = $_GET['nome'] ?? '';
$setor_pesquisa = $_GET['setor'] ?? '';

// Consulta todos os usuários
$sql = "SELECT matricula, nome, setor FROM usuarios_3gre WHERE 1=1";
if (!empty($matricula_pesquisa)) {
    $sql .= " AND matricula LIKE '%$matricula_pesquisa%'";
}
if (!empty($nome_pesquisa)) {
    $sql .= " AND nome LIKE '%$nome_pesquisa%'";
}
if (!empty($setor_pesquisa)) {
    $sql .= " AND setor = '$setor_pesquisa'";
}
$result = $conn->query($sql);
$funcionarios = [];

while ($row = $result->fetch_assoc()) {
    $matricula = $row['matricula'];
    $nome = $row['nome'];
    $setor = $row['setor'];

    // Busca registro de ponto para hoje
    $stmt = $conn->prepare("SELECT hora_entrada, hora_saida FROM registros_de_pontos WHERE matricula = ? AND data = ?");
    $stmt->bind_param("ss", $matricula, $data_hoje);
    $stmt->execute();
    $res = $stmt->get_result();
    $tem_registro = $res->num_rows > 0;

    $hora_entrada = '--';
    $hora_saida = '--';
    $horas_trabalhadas = '--';
    $status = 'Ausente';
    $classe = 'ausente';

    if ($tem_registro) {
        $registro = $res->fetch_assoc();
        $hora_entrada = $registro['hora_entrada'] ?? '--';
        $hora_saida = $registro['hora_saida'] ?? '--';

        if ($hora_entrada !== '--' && $hora_saida !== '--') {
            $entrada = strtotime($hora_entrada);
            $saida = strtotime($hora_saida);
            $segundos = max($saida - $entrada, 0);
            $horas_trabalhadas = sprintf('%02d:%02d', floor($segundos / 3600), ($segundos % 3600) / 60);
        }

        $status = 'Presente';
        $classe = 'presente';
    }

    $funcionarios[] = [
        'matricula' => $matricula,
        'nome' => $nome,
        'setor' => $setor,
        'hora_entrada' => $hora_entrada,
        'hora_saida' => $hora_saida,
        'horas_trabalhadas' => $horas_trabalhadas,
        'status' => $status,
        'data' => $data_hoje,
        'classStatus' => $classe
    ];

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Status dos Funcionários - <?= date('d/m/Y', strtotime($data_hoje)) ?></title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <style>
        .presente { background-color: #28a745; color: white; }
        .ausente { background-color: #e0e0e0; color: black; }
    </style>
</head>
<body>
<div class="container mt-5">
    <h2>Status dos Funcionários - <?= date('d/m/Y', strtotime($data_hoje)) ?></h2>

    <?php if (!empty($funcionarios)): ?>
        <table class="table table-bordered mt-3">
            <thead class="thead-light">
                <tr>
                    <th>Matrícula</th>
                    <th>Nome</th>
                    <th>Setor</th>
                    <th>Hora Entrada</th>
                    <th>Hora Saída</th>
                    <th>Horas Trabalhadas</th>
                    <th>Status</th>
                    <th>Data</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($funcionarios as $f): ?>
                    <tr class="<?= $f['classStatus'] ?>">
                        <td><?= htmlspecialchars($f['matricula']) ?></td>
                        <td><?= htmlspecialchars($f['nome']) ?></td>
                        <td><?= htmlspecialchars($f['setor']) ?></td>
                        <td><?= $f['hora_entrada'] ?></td>
                        <td><?= $f['hora_saida'] ?></td>
                        <td><?= $f['horas_trabalhadas'] ?></td>
                        <td><?= $f['status'] ?></td>
                        <td><?= date('d/m/Y', strtotime($f['data'])) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-info mt-4">Nenhum funcionário encontrado.</div>
    <?php endif; ?>
</div>
</body>
</html>
