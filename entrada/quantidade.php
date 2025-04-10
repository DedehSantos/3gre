<?php
include_once "../conexao/conexao.php";

// Data de hoje no fuso horário de São Paulo
$timeZone = new \DateTimeZone('America/Sao_Paulo');
$dataHoje = (new \DateTime('now', $timeZone))->format('d/m/Y');

// Totais gerais
$sqlEntradas = "SELECT COUNT(*) AS total FROM registros_de_pontos WHERE data = ? AND hora_entrada IS NOT NULL";
$stmt = $conn->prepare($sqlEntradas);
$stmt->bind_param("s", $dataHoje);
$stmt->execute();
$result = $stmt->get_result();
$quantidadeEntradas = $result->fetch_assoc()['total'] ?? 0;

$sqlSaidas = "SELECT COUNT(*) AS total FROM registros_de_pontos WHERE data = ? AND hora_saida IS NOT NULL AND hora_saida != '00:00:00'";
$stmt = $conn->prepare($sqlSaidas);
$stmt->bind_param("s", $dataHoje);
$stmt->execute();
$result = $stmt->get_result();
$quantidadeSaidas = $result->fetch_assoc()['total'] ?? 0;

$presentes = $quantidadeEntradas - $quantidadeSaidas;

// Agrupamento por setor - Entradas
$sqlSetoresEntradas = "SELECT setor, COUNT(*) AS total FROM registros_de_pontos WHERE data = ? AND hora_entrada IS NOT NULL GROUP BY setor";
$stmt = $conn->prepare($sqlSetoresEntradas);
$stmt->bind_param("s", $dataHoje);
$stmt->execute();
$resultEntradas = $stmt->get_result();
$setoresEntradas = $resultEntradas->fetch_all(MYSQLI_ASSOC);

// Agrupamento por setor - Saídas
$sqlSetoresSaidas = "SELECT setor, COUNT(*) AS total FROM registros_de_pontos WHERE data = ? AND hora_saida IS NOT NULL AND hora_saida != '00:00:00' GROUP BY setor";
$stmt = $conn->prepare($sqlSetoresSaidas);
$stmt->bind_param("s", $dataHoje);
$stmt->execute();
$resultSaidas = $stmt->get_result();
$setoresSaidas = $resultSaidas->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Quantidade de Funcionários</title>
  <link rel="stylesheet" href="../css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">
  <div class="container my-5">
    <div class="card shadow p-4">
      <h2 class="text-center text-primary mb-4"><i class="fas fa-user-check"></i> Controle de Ponto - <?php echo date("d/m/Y", strtotime($dataHoje)); ?></h2>

      <div class="row text-center mb-4">
        <div class="col-md-4">
          <div class="bg-success text-white p-3 rounded shadow-sm">
            <h5>Entradas</h5>
            <p class="display-5"><?php echo $quantidadeEntradas; ?></p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="bg-danger text-white p-3 rounded shadow-sm">
            <h5>Saídas</h5>
            <p class="display-5"><?php echo $quantidadeSaidas; ?></p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="bg-info text-white p-3 rounded shadow-sm">
            <h5>Presentes no momento</h5>
            <p class="display-5"><?php echo $presentes; ?></p>
          </div>
        </div>
      </div>

      <div class="row mt-4">
        <div class="col-md-6">
          <h4 class="text-success"><i class="fas fa-sign-in-alt"></i> Entradas por Setor</h4>
          <ul class="list-group mt-2">
            <?php foreach ($setoresEntradas as $setor): ?>
              <li class="list-group-item d-flex justify-content-between align-items-center">
                <a href="detalhes_setor.php?setor=<?php echo urlencode($setor['setor']); ?>&tipo=entrada" class="text-decoration-none">
                  <?php echo htmlspecialchars($setor['setor']); ?>
                </a>
                <span class="badge bg-success rounded-pill"><?php echo $setor['total']; ?></span>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>

        <div class="col-md-6">
          <h4 class="text-danger"><i class="fas fa-sign-out-alt"></i> Saídas por Setor</h4>
          <ul class="list-group mt-2">
            <?php foreach ($setoresSaidas as $setor): ?>
              <li class="list-group-item d-flex justify-content-between align-items-center">
                <a href="detalhes_setor.php?setor=<?php echo urlencode($setor['setor']); ?>&tipo=saida" class="text-decoration-none">
                  <?php echo htmlspecialchars($setor['setor']); ?>
                </a>
                <span class="badge bg-danger rounded-pill"><?php echo $setor['total']; ?></span>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>
    </div>
  </div>

  <script src="../js/bootstrap.bundle.min.js"></script>
</body>
</html>
