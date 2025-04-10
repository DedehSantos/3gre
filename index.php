<!-- // data da última atualização 19/10/2004 \\ -->

<!-- // mandando faltas do dia para o banco de dados \\ -->

<?php 
// Inclui o arquivo de conexão com o banco de dados
include_once "./conexao/conexao.php";
?>

<!doctype html>
<html lang="PT_BR">

<head>
  <title>Sistema de Frequência</title>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="./css/bootstrap.min.css">
  <link rel="stylesheet" href="./css/css_index.css">
</head>

<body>
   <div class="container">

   
  <div class="btn-group ">
    <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      MENU PRINCIPAL
    </button>
    <div class="dropdown-menu">
      <a class="dropdown-item" href="./entrada/gerar_planilha.php" target="_blank">BAIXAR RELATÓRIO</a>
      <div class="dropdown-divider"></div>
      <a class="dropdown-item" href="./entrada/quantidade.php" target="_blank">QUANTIDADE NA GRE HOJE</a>
      <div class="dropdown-divider"></div>
      <a class="dropdown-item" href="./entrada/no_local.php" target="_blank">PRESENTES NA 3GRE</a>
      <div class="dropdown-divider"></div>
      <a class="dropdown-item" href="./entrada/cadastrar_usuario.php" target="_blank">CADASTRAR USUARIO</a>
      <div class="dropdown-divider"></div>
      <a class="dropdown-item" href="./entrada/quantidade_de_funcionarios.php" target="_blank"> RELATORIO EM TEMPO REAL </a>
      <div class="dropdown-divider"></div>
      <a class="dropdown-item" href="botoes.php" target="_blank">SETORES</a>
      <div class="dropdown-divider"></div>
      <a class="dropdown-item" href="./justificativa" target="_blank">ATESTADOS/JUSTIFICATIVA</a>
      <div class="dropdown-divider"></div>
      <a class="dropdown-item" href="./entrada/configuracoes.php" target="_blank">CONFIGURAÇÕES</a>
      <div class="dropdown-divider"></div>
    </div>
  </div>

  <img class="logo_central" src="./img/logo_nova.png" alt="">

  <div class="geral">
    <div class="botoes">
      <form action="./entrada/entrada_de_funcionarios.php" method="GET">
        <div class="form-group">
          <input id="busca" type="number" class="form-control" placeholder="Passe o Cartão" autofocus required name="busca">
        </div>
        <button type="submit" class="btn btn-primary">CONFIRMAR</button>
      </form>
    </div>
  </div>

  </div>

  <script>
  const input = document.getElementById('busca');

  // Foco no campo ao carregar a página
  window.onload = function () {
    input.focus();
  };

  // Mantém o foco constante no campo
  setInterval(function () {
    input.focus();
  }, 100);
</script>

<!-- Mensagens de feedback -->
<h1 id="mensagem" style="display:none;" class="text-center mt-4"></h1>
<div id="feedback" class="alert alert-success text-center" style="display:none;"></div>

<!-- Script de execução de faltas.php ao atingir o horário definido -->
<script>
  const horarioInicio = "17:00:00"; // Defina o horário desejado aqui
  let faltasChamadas = false; // Garante que só será chamado uma vez

  // Verifica se é um dia útil
  function diaUtil() {
    const hoje = new Date().getDay(); // 0 = Domingo, 6 = Sábado
    return hoje >= 1 && hoje <= 5;
  }

  function verificarHorario() {
    if (!diaUtil() || faltasChamadas) return;

    const agora = new Date();
    const horarioAtual = agora.toTimeString().slice(0, 8); // HH:MM:SS

    if (horarioAtual >= horarioInicio) {
      console.log("Horário atingido! Chamando faltas.php...");

      // Faz a chamada para faltas.php
      const xhr = new XMLHttpRequest();
      xhr.open("GET", "./entrada/faltas.php", true);
      xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
          console.log("Faltas registradas com sucesso.");
          window.location.href = "index.php";
        }
      };
      xhr.send();

      faltasChamadas = true; // Evita múltiplas chamadas
    }
  }

  // Verifica o horário a cada segundo
  setInterval(verificarHorario, 1000);
</script>












</body>

<script src="./js/jquery-3.3.1.slim.min.js" crossorigin="anonymous"></script>
<script src="./js/popper.min.js" crossorigin="anonymous"></script>
<script src="./js/bootstrap.min.js" crossorigin="anonymous"></script>

</html>
