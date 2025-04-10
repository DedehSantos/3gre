<?php
include_once "../conexao/conexao.php";

$id       = $_POST['id'];
$entrada  = $_POST['entrada'];
$saida    = $_POST['saida'];
$motivo   = $_POST['motivo'];

$sql = "UPDATE registros_de_pontos 
        SET hora_entrada = ?, hora_saida = ?, justificativa = ?, justificado = 1 
        WHERE id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sssi", $entrada, $saida, $motivo, $id);

if ($stmt->execute()) {
    echo "Justificativa salva com sucesso!";
} else {
    echo "Erro ao salvar justificativa.";
}
