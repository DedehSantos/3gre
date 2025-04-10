<?php
include_once "../conexao/conexao.php";

// Pegando os parâmetros da URL
$setor = $_GET['setor'] ?? '';
$tipo = $_GET['tipo'] ?? 'entrada';

$timeZone = new \DateTimeZone('America/Sao_Paulo');
$dataHoje = (new \DateTime('now', $timeZone))->format('d/m/Y');

// Validação básica
if (!in_array($tipo, ['entrada', 'saida'])) {
    echo "Tipo inválido.";
    exit;
}

$coluna = $tipo === 'entrada' ? 'hora_entrada' : 'hora_saida';
$titulo = $tipo === 'entrada' ? 'Entraram' : 'Saíram';

$sql = "SELECT nome, matricula, $coluna AS hora 
        FROM registros_de_pontos 
        WHERE data = ? 
          AND setor = ?
          AND $coluna IS NOT NULL 
          " . ($tipo === 'saida' ? "AND $coluna != '00:00:00'" : "") . "
        ORDER BY hora ASC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $dataHoje, $setor);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title><?php echo "$titulo - $setor"; ?></title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">
<div class="container my-5">
    <h2 class="text-center text-primary">
        <i class="fas fa-users"></i> <?php echo "$titulo - Setor $setor"; ?>
    </h2>

    <?php if ($result->num_rows > 0): ?>
        <table class="table table-bordered table-hover mt-4 bg-white shadow">
            <thead class="table-<?php echo $tipo === 'entrada' ? 'success' : 'danger'; ?>">
                <tr>
                    <th>Nome</th>
                    <th>Matrícula</th>
                    <th>Hora <?php echo $tipo; ?></th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['nome']); ?></td>
                        <td><?php echo htmlspecialchars($row['matricula']); ?></td>
                        <td><?php echo date("H:i", strtotime($row['hora'])); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-warning mt-4">
            Nenhum funcionário <?php echo strtolower($titulo); ?> hoje no setor <?php echo htmlspecialchars($setor); ?>.
        </div>
    <?php endif; ?>

    <div class="text-center mt-4">
        <a href="quantidade.php" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Voltar
        </a>
    </div>
</div>

<script src="../js/bootstrap.bundle.min.js"></script>
</body>
</html>
