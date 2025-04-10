<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cadastrar Usuário</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body {
      background-color: #f4f4f4;
      font-family: 'Segoe UI', sans-serif;
    }

    .topbar {
      background-color: #f44336;
      color: white;
      padding: 15px 30px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .topbar h4 {
      margin: 0;
    }

    .sidebar {
      width: 250px;
      background-color: #fff;
      border-right: 1px solid #ddd;
      height: 100vh;
      position: fixed;
      padding: 20px;
      overflow-y: auto;
    }

    .sidebar h5 {
      font-weight: bold;
      margin-bottom: 20px;
    }

    .sidebar .form-label {
      font-size: 0.85rem;
      font-weight: 500;
    }

    .content {
      margin-left: 250px;
      padding: 30px;
    }

    .card {
      border: none;
      border-radius: 8px;
    }

    .btn-primary {
      background-color: #2196F3;
      border: none;
    }

    .btn-primary:hover {
      background-color: #1976D2;
    }

    .form-control {
      border-radius: 6px;
    }
  </style>
</head>
<body>

  <div class="topbar">
    <h4>CADASTRAR USUARIOS</h4>
    <div>
      <span class="me-3"><i class="fa-solid fa-user"></i> Admin</span>
    </div>
  </div>

  <div class="sidebar">
    <h5>FILTROS</h5>
    <div class="mb-3">
      <label for="filtro1" class="form-label">Nome</label>
      <input type="text" class="form-control" id="filtro1" placeholder="Buscar por nome">
    </div>
    <div class="mb-3">
      <label for="filtro2" class="form-label">Setor</label>
      <select class="form-select" id="filtro2">
        <option selected>Todos</option>
        <option>RH</option>
        <option>TI</option>
        <option>Financeiro</option>
      </select>
    </div>
    <button class="btn btn-warning w-100 mb-2">Resetar</button>
    <button class="btn btn-info w-100">Filtrar</button>
  </div>

  <div class="content">
    <div class="container">
      <div class="card shadow-sm p-4">
        <h3 class="mb-4">Cadastrar Novo Usuário</h3>
        <form action="enviar_cadastro.php" method="POST">
          <div class="row mb-3">
            <div class="col-md-6">
              <label for="nome" class="form-label">Nome Completo</label>
              <input type="text" class="form-control" name="nome" id="nome" required placeholder="Digite seu nome completo" require>
            </div>
            <div class="col-md-6">
              <label for="matricula" class="form-label">Matrícula</label>
              <input type="number" class="form-control" name="matricula" id="matricula" required placeholder="Digite sua matrícula" require>
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-md-6">
              <label for="setor" class="form-label">Setor</label>
              <input type="text" class="form-control" name="setor" id="setor" required placeholder="Digite seu setor" require>
            </div>
            <div class="col-md-6">
              <label for="funcao" class="form-label">Função</label>
              <input type="text" class="form-control" name="funcao" id="funcao" required placeholder="Digite a Funçao">
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-md-4">
              <label for="inicio" class="form-label">Início da Jornada</label>
              <input type="time" class="form-control" name="inicio_jornada" id="inicio" required>
            </div>
            <div class="col-md-4">
              <label for="fim" class="form-label">Final da Jornada</label>
              <input type="time" class="form-control" name="fim_jornada" id="fim" required>
            </div>
            <div class="col-md-4">
              <label for="carga" class="form-label">Carga Horária</label>
              <input type="text" class="form-control" name="carga_horaria" id="carga" required placeholder="Ex: 8h">
            </div>
          </div>

          <div class="d-grid">
            <button type="submit" class="btn btn-primary"><i class="fa fa-save me-2"></i>Cadastrar</button>
          </div>
        </form>
      </div>
    </div>
  </div>

</body>
</html>
