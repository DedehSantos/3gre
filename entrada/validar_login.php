<?php
session_start();
include_once "../conexao/conexao.php";

$email = $_POST['email'] ?? '';
$senha = $_POST['senha'] ?? '';

$conn = mysqli_connect($servidor, $dbusuario, $dbsenha, $dbname);
mysqli_set_charset($conn, 'utf8');

$sql = "SELECT matricula, nome, senha, nivel_acesso FROM usuarios_3gre WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($usuario = $result->fetch_assoc()) {
    if (password_verify($senha, $usuario['senha'])) {
        $_SESSION['usuario'] = [
            'matricula' => $usuario['matricula'],
            'nome' => $usuario['nome'],
            'nivel' => $usuario['nivel_acesso']
        ];
        header("Location: painel.php");
        exit;
    }
}

header("Location: login.php?erro=1");
exit;
