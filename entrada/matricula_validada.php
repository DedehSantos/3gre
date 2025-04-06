<?php
// Inclui o arquivo de conexão com o banco de dados
include_once "../conexao/conexao.php";

// Recebe a matrícula via parâmetro GET
$matricula = $_GET['matricula'] ?? '';

// Conecta ao banco de dados
$conn = mysqli_connect($servidor, $dbusuario, $dbsenha, $dbname);
mysqli_set_charset($conn, 'utf8');

// Verifica se a conexão falhou
if (!$conn) {
    die('Conexão falhou: ' . mysqli_connect_error());
}

// Busca os dados do usuário na tabela usuario
$sql_usuario = "SELECT nome, setor FROM usuarios_3gre WHERE matricula = ?";
$stmt_usuario = $conn->prepare($sql_usuario);
$stmt_usuario->bind_param("s", $matricula);
$stmt_usuario->execute();
$result_usuario = $stmt_usuario->get_result();

if ($usuario = $result_usuario->fetch_assoc()) {
    $nome = $usuario['nome'];
    $setor = $usuario['setor'];
    
    // Obtém a data e a hora atual
    date_default_timezone_set('America/Sao_Paulo');
    $data = date('Y-m-d');
    $hora_atual = date('H:i:s');
    
    // Verifica se já existe um registro de entrada para essa matrícula no dia atual
    $sql_verifica = "SELECT id, hora_entrada FROM registros_de_pontos WHERE matricula = ? AND data = ?";
    $stmt_verifica = $conn->prepare($sql_verifica);
    $stmt_verifica->bind_param("ss", $matricula, $data);
    $stmt_verifica->execute();
    $result_verifica = $stmt_verifica->get_result();
    
    if ($registro = $result_verifica->fetch_assoc()) {
        // Se já houver um registro de entrada, atualiza a hora de saída
        $sql_update = "UPDATE registros_de_pontos SET hora_saida = ? WHERE id = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("si", $hora_atual, $registro['id']);
        $stmt_update->execute();
    } else {
        // Se não houver registro, insere um novo com a hora de entrada
        $sql_insert = "INSERT INTO registros_de_pontos (matricula, nome, setor, data, hora_entrada) VALUES (?, ?, ?, ?, ?)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("sssss", $matricula, $nome, $setor, $data, $hora_atual);
        $stmt_insert->execute();
    }
    
    echo "<h2>Registro realizado com sucesso!</h2>";
    echo "<p>Nome: $nome</p>";
    echo "<p>Setor: $setor</p>";
    echo "<p>Data: $data</p>";
    echo "<p>Hora: $hora_atual</p>";
} else {
    echo "<h2>Matrícula não encontrada.</h2>";
}

// Fecha a conexão com o banco de dados
$conn->close();
?>
