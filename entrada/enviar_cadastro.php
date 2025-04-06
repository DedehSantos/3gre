<?php
// Conexão com o banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "3gre";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Captura os dados do formulário
$nome = $_POST['nome'];
$matricula = $_POST['matricula'];
$setor = $_POST['setor'];
$email = $_POST['email'];
$inicio_jornada = $_POST['inicio_jornada'];
$fim_jornada = $_POST['fim_jornada'];
$carga_horaria = $_POST['carga_horaria'];

// Prepara a query SQL para inserir os dados
$sql = "INSERT INTO usuarios_3gre (nome, matricula, setor, email, inicio_da_jornada, final_da_jornada, carga_horaria) 
        VALUES (?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssss", $nome, $matricula, $setor, $email, $inicio_jornada, $fim_jornada, $carga_horaria);

// Executa a query e verifica se foi bem-sucedida
if ($stmt->execute()) {
    echo "<script>alert('Usuário cadastrado com sucesso!'); window.location.href='./cadastrar_usuario.php';</script>";
} else {
    echo "<script>alert('Erro ao cadastrar usuário: " . $stmt->error . "'); window.history.back();</script>";
}

// Fecha a conexão
$stmt->close();
$conn->close();
?>


