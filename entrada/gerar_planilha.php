<?php
session_start();
include_once "../conexao/conexao.php";

// Força o charset para UTF-8
mysqli_set_charset($conn, "utf8");

// Nome do arquivo exportado
$arquivo = 'registros_de_pontos.xls';

// Início do HTML da tabela
$html = '';
$html .= '<meta charset="UTF-8">'; // define charset para Excel interpretar melhor
$html .= '<table border="1">';
$html .= '<tr>';
$html .= '<td colspan="7"><b>PLANILHA DE REGISTROS DE PONTO</b></td>';
$html .= '</tr>';

$html .= '<tr>';
$html .= '<td><b>ID</b></td>';
$html .= '<td><b>Matrícula</b></td>';
$html .= '<td><b>Nome</b></td>';
$html .= '<td><b>Setor</b></td>';
$html .= '<td><b>Data</b></td>';
$html .= '<td><b>Hora Entrada</b></td>';
$html .= '<td><b>Hora Saída</b></td>';
$html .= '<td><b>Horas Trabalhadas</b></td>';
$html .= '<td><b>horas extras</b></td>';
$html .= '<td><b>Percentual da Jornada</b></td>';
$html .= '<td><b>Faltas</b></td>';
$html .= '<td><b>Justificativa</b></td>';
$html .= '</tr>';

// Consulta os registros
$sql = "SELECT id, matricula, nome, setor, data, hora_entrada, hora_saida, horas_trabalhadas, hora_extra, percentual_jornada, faltas, justificado, justificativa FROM registros_de_pontos ORDER BY data DESC";
$result = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_assoc($result)) {
    $html .= '<tr>';
    $html .= '<td>' . $row["id"] . '</td>';
    $html .= '<td>' . $row["matricula"] . '</td>';
    $html .= '<td>' . $row["nome"] . '</td>';
    $html .= '<td>' . $row["setor"] . '</td>';
    $html .= '<td>' . date('d/m/Y', strtotime($row["data"])) . '</td>';
    $html .= '<td>' . $row["hora_entrada"] . '</td>';
    $html .= '<td>' . $row["hora_saida"] . '</td>';
    $html .= '<td>' . $row["horas_trabalhadas"] . '</td>';
    $html .= '<td>' . $row["hora_extra"] . '</td>';
    $html .= '<td>' . $row["percentual_jornada"] . '</td>';
    $html .= '<td>' . $row["faltas"] . '</td>';
    $html .= '<td>' . $row["justificativa"] . '</td>';
    $html .= '</tr>';
}

// Cabeçalhos para forçar o download como Excel com suporte a UTF-8
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D,d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
header("Content-Disposition: attachment; filename=\"{$arquivo}\"");
header("Content-Description: PHP Generated Data");

// Garante a codificação correta
echo "\xEF\xBB\xBF"; // BOM para UTF-8
echo $html;
exit;
?>
