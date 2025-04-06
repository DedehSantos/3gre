<?php
$mysqli = new mysqli("localhost", "root", "", "3gre");

// Função: excluir
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    $mysqli->query("DELETE FROM usuarios_3gre WHERE id = $id");
    header("Location: listar_usuarios.php");
    exit;
}

// Função: inativar/ativar
if (isset($_GET['toggle'])) {
    $id = (int) $_GET['toggle'];
    $res = $mysqli->query("SELECT ativo FROM usuarios_3gre WHERE id = $id");
    if ($res && $row = $res->fetch_assoc()) {
        $status_atual = isset($row['ativo']) ? (int)$row['ativo'] : 1;
        $novo_status = $status_atual === 1 ? 0 : 1;
        $mysqli->query("UPDATE usuarios_3gre SET ativo = $novo_status WHERE id = $id");
    }
    header("Location: listar_usuarios.php");
    exit;
}

// Função: editar
if (isset($_POST['editar'])) {
    $id = (int) $_POST['id'];
    $matricula = $_POST['matricula'];
    $nome = $_POST['nome'];
    $setor = $_POST['setor'];
    $email = $_POST['email'];
    $inicio = $_POST['inicio'];
    $fim = $_POST['fim'];
    $carga = $_POST['carga'];

    $stmt = $mysqli->prepare("UPDATE usuarios_3gre SET 
    matricula=?, nome=?, setor=?, email=?, inicio_da_jornada=?, final_da_jornada=?, carga_horaria=? 
    WHERE id = ?");
    $stmt->bind_param("ssssssii", $matricula, $nome, $setor, $email, $inicio, $fim, $carga, $id);
    $stmt->execute();
    $stmt->close();

    header("Location: listar_usuarios.php");
    exit;
}

// Lista todos os usuários
$resultado = $mysqli->query("SELECT * FROM usuarios_3gre");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Admin - Usuários</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <style>
    body { background: #f5f5f5; }
    .sidebar {
      width: 250px;
      background-color: #343a40;
      height: 100vh;
      position: fixed;
      color: #fff;
    }
    .sidebar .nav-link { color: #adb5bd; }
    .sidebar .nav-link.active { background-color: #495057; color: #fff; }
    .topbar {
      margin-left: 250px;
      background-color: #dc3545;
      padding: 1rem;
      color: white;
    }
    .content {
      margin-left: 250px;
      padding: 20px;
    }
    .table td, .table th { vertical-align: middle; }
    .btn i { margin-right: 4px; }
  </style>
  <script>
    function confirmarToggle(id, statusAtual) {
      let msg = statusAtual === 'Ativo' ? 'Deseja inativar este usuário?' : 'Deseja ativar este usuário?';
      if (confirm(msg)) {
        window.location.href = '?toggle=' + id;
      }
    }
  </script>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar p-3">
  <h4 class="text-center">CRUD Admin Panel</h4>
  <hr>
  <ul class="nav nav-pills flex-column">
    <li><a href="#" class="nav-link active"><i class="fa fa-user"></i> Usuários</a></li>
    <li><a href="#" class="nav-link"><i class="fa fa-clock"></i> Pontos</a></li>
  </ul>
</div>

<!-- Topbar -->
<div class="topbar d-flex justify-content-between">
  <h5>Painel Administrativo</h5>
  <div><i class="fa fa-user-circle"></i> Admin</div>
</div>

<!-- Conteúdo principal -->
<div class="content">
  <h2 class="mb-4">Lista de Usuários</h2>

  <?php if (isset($_GET['edit'])): 
    $id = (int) $_GET['edit'];
    $usuario = $mysqli->query("SELECT * FROM usuarios_3gre WHERE id = $id")->fetch_assoc();
  ?>
    <form method="POST" class="card p-4 mb-4 shadow-sm bg-white">
      <input type="hidden" name="id" value="<?= $usuario['id'] ?>">
      <h5>Editar Usuário</h5>
      <div class="row g-3">
        <div class="col-md-4">
          <input class="form-control" name="matricula" value="<?= $usuario['matricula'] ?>" required>
        </div>
        <div class="col-md-4">
          <input class="form-control" name="nome" value="<?= $usuario['nome'] ?>" required>
        </div>
        <div class="col-md-4">
          <input class="form-control" name="setor" value="<?= $usuario['setor'] ?>">
        </div>
        <div class="col-md-6">
          <input type="email" class="form-control" name="email" value="<?= $usuario['email'] ?>" required>
        </div>
        <div class="col-md-3">
          <input type="time" class="form-control" name="inicio" value="<?= $usuario['inicio_da_jornada'] ?>">
        </div>
        <div class="col-md-3">
          <input type="time" class="form-control" name="fim" value="<?= $usuario['final_da_jornada'] ?>">
        </div>
        <div class="col-md-3">
          <input type="number" class="form-control" name="carga" value="<?= $usuario['carga_horaria'] ?>">
        </div>
        <div class="col-12 d-flex justify-content-end">
          <button type="submit" class="btn btn-success" name="editar"><i class="fa fa-save"></i> Salvar</button>
        </div>
      </div>
    </form>
  <?php endif; ?>

  <table class="table table-bordered table-hover shadow-sm bg-white">
    <thead class="table-dark">
      <tr>
        <th>Ações</th>
        <th>ID</th>
        <th>Matrícula</th>
        <th>Nome</th>
        <th>Setor</th>
        <th>Email</th>
        <th>Início</th>
        <th>Fim</th>
        <th>Carga</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($usuario = $resultado->fetch_assoc()): ?>
        <?php
          $ativo = isset($usuario['ativo']) ? (int)$usuario['ativo'] : 1;
          $status = $ativo === 1 ? 'Ativo' : 'Inativo';
          $badge = $ativo === 1 ? 'bg-success' : 'bg-secondary';
          $icone = $ativo === 1 ? 'fa-check-circle text-success' : 'fa-times-circle text-secondary';
          $btnText = $ativo === 1 ? 'Inativar' : 'Ativar';
          $btnIcon = $ativo === 1 ? 'fa-toggle-off' : 'fa-toggle-on';
          $btnColor = $ativo === 1 ? 'btn-secondary' : 'btn-success';
        ?>
        <tr>
          <td>
            <a href="?edit=<?= $usuario['id'] ?>" class="btn btn-sm btn-warning"><i class="fa fa-pen"></i></a>
            <a href="?delete=<?= $usuario['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Confirmar exclusão?');"><i class="fa fa-trash"></i></a>
            <a href="#" class="btn btn-sm <?= $btnColor ?>" onclick="confirmarToggle(<?= $usuario['id'] ?>, '<?= $status ?>')">
              <i class="fa <?= $btnIcon ?>"></i> <?= $btnText ?>
            </a>
          </td>
          <td><?= $usuario['id'] ?></td>
          <td><?= $usuario['matricula'] ?></td>
          <td><?= $usuario['nome'] ?></td>
          <td><?= $usuario['setor'] ?></td>
          <td><?= $usuario['email'] ?></td>
          <td><?= $usuario['inicio_da_jornada'] ?></td>
          <td><?= $usuario['final_da_jornada'] ?></td>
          <td><?= $usuario['carga_horaria'] ?>h</td>
          <td>
            <span class="badge <?= $badge ?>">
              <i class="fa <?= $icone ?>"></i> <?= $status ?>
            </span>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>

</body>
</html>
