<?php
include_once "../conexao/conexao.php";

// Recupera o nome pesquisado (parcial ou completo)
$nome = $_GET['nome'] ?? '';

// Prepara a consulta usando LIKE para buscas parciais
$sql = "SELECT * FROM registros_de_pontos WHERE nome LIKE ? ORDER BY data DESC";
$stmt = $conn->prepare($sql);
$nomeLike = "%$nome%";
$stmt->bind_param("s", $nomeLike);
$stmt->execute();
$result = $stmt->get_result();

// Se não encontrar registros
if ($result->num_rows === 0) {
    echo "<p>Nenhum registro encontrado.</p>";
    exit;
}

// Exibe a tabela com uma nova coluna: Nome
echo "<table class='table'>
<thead>
<tr>
  <th>Nome</th>
  <th>Data</th>
  <th>Entrada</th>
  <th>Saída</th>
  <th>Status</th>
  <th>Ação</th>
</tr>
</thead>
<tbody>";

// Loop de exibição dos registros
while ($r = $result->fetch_assoc()) {
    echo "<tr>
      <td>" . htmlspecialchars($r['nome']) . "</td>
      <td>" . date("d/m/Y", strtotime($r['data'])) . "</td>
      <td>" . ($r['hora_entrada'] ?: '—') . "</td>
      <td>" . ($r['hora_saida'] ?: '—') . "</td>
      <td>" . ($r['justificado'] ? '<i class=\"fas fa-check-circle\" style=\"color:green\"></i> Justificado' : '—') . "</td>
      <td><button class='btn btn-warning btn-justificar'><i class='fas fa-edit'></i> Justificar</button></td>
    </tr>";

    // Formulário para editar/justificar
    echo "<tr class='form-row' style='display:none;'>
      <td colspan='6'>
        <form class='form-justificativa'>
          <input type='hidden' name='id' value='{$r['id']}'>
          <label>Entrada:</label>
          <input type='time' name='entrada' value='{$r['hora_entrada']}' required>
          <label>Saída:</label>
          <input type='time' name='saida' value='{$r['hora_saida']}' required>
          <label>Motivo:</label>
          <textarea name='motivo' required>{$r['justificativa']}</textarea>
          <button class='btn btn-success'><i class='fas fa-save'></i> Salvar</button>
        </form>
      </td>
    </tr>";
}

echo "</tbody></table>";
