<?php
include_once "../conexao/conexao.php";

date_default_timezone_set('America/Sao_Paulo');
$data_hoje = date("d/m/Y");
$hora_agora = date("H:i:s");

// Consulta todos os usuários cadastrados
$sql_usuarios = "SELECT matricula, nome, setor FROM usuarios_3gre";
$result_usuarios = mysqli_query($conn, $sql_usuarios);

if (mysqli_num_rows($result_usuarios) > 0) {
    while ($usuario = mysqli_fetch_assoc($result_usuarios)) {
        $matricula = $usuario['matricula'];
        $nome = $usuario['nome'];
        $setor = $usuario['setor'];

        // Verifica se o usuário tem registro de entrada na data atual
        $sql_check = "SELECT hora_entrada FROM registros_de_pontos WHERE matricula = ? AND data = ?";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bind_param("ss", $matricula, $data_hoje);
        $stmt_check->execute();
        $stmt_check->store_result();

        // Se não houver nenhum registro OU se a hora_entrada estiver vazia/null → considera faltante
        if ($stmt_check->num_rows == 0) {
            // Inserir como faltante
            $sql_insert = "INSERT INTO registros_de_pontos (matricula, nome, setor, data, faltas) 
                           SELECT ?, ?, ?, ?, 'Faltante' 
                           FROM DUAL 
                           WHERE NOT EXISTS (
                               SELECT 1 FROM registros_de_pontos WHERE matricula = ? AND data = ?
                           )";
            $stmt_insert = $conn->prepare($sql_insert);
            $stmt_insert->bind_param("ssssss", $matricula, $nome, $setor, $data_hoje, $matricula, $data_hoje);

            $stmt_insert->execute();
            $stmt_insert->close();
        }

        $stmt_check->close();
    }

    echo "Processo concluído. Faltantes registrados com sucesso.";
} else {
    echo "Nenhum usuário encontrado na tabela 'usuarios_3gre'.";
}

mysqli_close($conn);
?>
