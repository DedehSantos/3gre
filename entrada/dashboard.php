<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Dashboard | Sistema de Ponto</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="#">Sistema de Ponto</a>
        <div class="ml-auto text-white">
            Olá, <?= $_SESSION['usuario']; ?> | <a href="logout.php" class="text-danger">Sair</a>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <h3 class="mb-4">Painel Principal</h3>
    <p>Você está logado como <strong><?= $_SESSION['nivel_acesso']; ?></strong>.</p>

    <?php if ($_SESSION['nivel_acesso'] === 'admin'): ?>
        <a href="admin/usuarios.php" class="btn btn-outline-primary">Gerenciar Usuários</a>
    <?php endif; ?>

    <a href="quantidade.php" class="btn btn-outline-success">Visualizar Quantidade</a>
</div>
</body>
</html>
