<?php
session_start();
include_once "conexao/conexao.php";

$usuario = $_POST['usuario'];
$senha = $_POST['senha'];

$sql = "SELECT * FROM usuarios_3gre WHERE email = ? OR matricula = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $usuario, $usuario);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $dados = $result->fetch_assoc();
    if (password_verify($senha, $dados['senha'])) {
        $_SESSION['usuario'] = $dados['nome'];
        $_SESSION['nivel_acesso'] = $dados['nivel_acesso'];
        $_SESSION['matricula'] = $dados['matricula'];
        $_SESSION['setor'] = $dados['setor'];

        header("Location: dashboard.php");
        exit;
    }
}

echo "<script>alert('Usuário ou senha inválidos!'); history.back();</script>";
