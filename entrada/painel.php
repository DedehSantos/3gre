<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit;
}

$usuario = $_SESSION['usuario'];
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Painel - Registro de Ponto</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container mt-5 text-center">
        <h2>Bem-vindo, <?= htmlspecialchars($usuario['nome']) ?>!</h2>
        <p>Nível de acesso: <strong><?= $usuario['nivel'] ?></strong></p>

        <a href="registrar_ponto.php?busca=<?= $usuario['matricula'] ?>" class="btn btn-success mt-3">Registrar Ponto</a>

        <?php if ($usuario['nivel'] == 'admin' || $usuario['nivel'] == 'rh'): ?>
            <a href="relatorios.php" class="btn btn-primary mt-3">Ver Relatórios</a>
        <?php endif; ?>

        <a href="logout.php" class="btn btn-danger mt-3">Sair</a>
    </div>
</body>
</html>
