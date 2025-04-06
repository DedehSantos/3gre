<?php
session_start();
if (isset($_SESSION['usuario'])) {
    header('Location: painel.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login - Registro de Ponto</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container mt-5" style="max-width: 400px;">
        <h3 class="text-center mb-4">Login</h3>
        <?php if (isset($_GET['erro'])): ?>
            <div class="alert alert-danger">Credenciais inválidas!</div>
        <?php endif; ?>
        <form method="POST" action="valida_login.php">
            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required />
            </div>
            <div class="mb-3">
                <label>Senha</label>
                <input type="password" name="senha" class="form-control" required />
            </div>
            <button type="submit" class="btn btn-primary w-100">Entrar</button>
        </form>
    </div>
</body>
</html>
