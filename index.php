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
  <div class="btn-group">
    <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      MENU PRINCIPAL
    </button>
    <div class="dropdown-menu">
      <a class="dropdown-item" href="./entrada/planilha_entrada.php" target="_blank">BAIXAR RELATÓRIO</a>
      <div class="dropdown-divider"></div>
      <a class="dropdown-item" href="./entrada/no_local.php" target="_blank">PRESENTES NA 3GRE</a>
      <div class="dropdown-divider"></div>
      <div class="dropdown-divider"></div>
      <a class="dropdown-item" href="./entrada/cadastrar_usuario.php" target="_blank">CADASTRAR USUARIO</a>
      <div class="dropdown-divider"></div>
      <a class="dropdown-item" href="./entrada/faltasdiarias.php" target="_blank">FALTAS</a>
      <div class="dropdown-divider"></div>
      <a class="dropdown-item" href="botoes.php" target="_blank">SETORES</a>
      <div class="dropdown-divider"></div>
      <a class="dropdown-item" href="entrada/atestadoejustificativa.php" target="_blank">ATESTADOS/JUSTIFICATIVA</a>
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

  <h1 id="mensagem" style="display:none;"></h1> <!-- Título que será exibido -->

  <!-- Script para verificar o horário e fazer a requisição -->
  <script>
    // Define o horário em que a ação deve ocorrer (exemplo: 15:00:00)
    var horarioInicio = "17:00:00";

    // Função para comparar o horário atual com o horário definido e chamar faltas.php
    function verificarHorario() {
        var dataAtual = new Date(); // Obtém a data e hora atuais
        var horas = dataAtual.getHours().toString().padStart(2, '0'); // Formata horas para 2 dígitos
        var minutos = dataAtual.getMinutes().toString().padStart(2, '0'); // Formata minutos para 2 dígitos
        var segundos = dataAtual.getSeconds().toString().padStart(2, '0'); // Formata segundos para 2 dígitos
        var horarioAtual = horas + ":" + minutos + ":" + segundos; // Cria o horário atual no formato HH:MM:SS

   

    // Chama a função em um intervalo de 1 segundo (1000 ms)
    setInterval(verificarHorario, 30000);
</script>

</body>

<script src="./js/jquery-3.3.1.slim.min.js" crossorigin="anonymous"></script>
<script src="./js/popper.min.js" crossorigin="anonymous"></script>
<script src="./js/bootstrap.min.js" crossorigin="anonymous"></script>

</html>
