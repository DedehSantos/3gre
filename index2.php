<?php 
// Inclui o arquivo de conexão com o banco de dados
include_once "./conexao/conexao.php";
?>

<!doctype html>
<html lang="pt-BR">

<head>
  <title>Sistema de Frequência</title>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="icon" href="./img/nova_logo.png" type="image/png">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="./css/bootstrap.min.css">
  <link rel="stylesheet" href="./css/css_index.css">
</head>

<body class="bg-light">

  <!-- MENU PRINCIPAL -->
  <div class="container mt-4">
    <div class="btn-group">
      <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        MENU PRINCIPAL
      </button>
      <div class="dropdown-menu">
        <a class="dropdown-item" href="./entrada/gerar_planilhas.php" target="_blank">BAIXAR RELATÓRIO</a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="./entrada/quantidadeAlunos.php" target="_blank">QUANTIDADE DIÁRIA DE ALUNOS</a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="./entrada/faltasdiarias.php" target="_blank">FALTAS DO DIA</a>
        <div class="dropdown-divider"></div>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="./entrada/aulasperdidas.php" target="_blank">FALTAS PROPOCIONAIS</a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="botoes.php" target="_blank">TURMAS</a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="./entrada/escolherjustificativa.php" target="_blank">ATESTADOS/JUSTIFICATIVA</a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="./entrada/configuracoes.php" target="_blank">CONFIGURAÇÕES</a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="./entrada/login.php" target="_blank">LOGIN</a>
      </div>
    </div>
  </div>

  <!-- Logo -->
  <div class="text-center mt-4">
    <img class="logo_central img-fluid" src="./img/nova_logo.png" alt="Logo">
  </div>

  <!-- Formulário -->
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card shadow-lg">
          <div class="card-body">
            <h3 class="card-title text-center mb-4">Sistema de Frequência</h3>
            <form action="./entrada/aluno_que_vai_entrar.php" method="GET">
              <div class="form-group">
                <input id="busca" type="number" class="form-control" placeholder="Passe o Cartão" autofocus required name="busca">
              </div>
              <button type="submit" class="btn btn-primary btn-block">CONFIRMAR</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>


  <script>
  const input = document.getElementById('busca');

  // Coloca o foco no input assim que a página for carregada
  window.onload = function() {
    input.focus();
  };

  // Garante que o foco seja mantido sempre
  setInterval(function() {
    input.focus(); // Garante que o campo sempre tenha foco
  }, 100);  // Verifica a cada 100ms
</script>

  <!-- Mensagem e Feedback -->
  <h1 id="mensagem" style="display:none;" class="text-center mt-4"></h1> <!-- Título que será exibido -->
  
  <div id="feedback" class="alert alert-success text-center" style="display:none;"></div>

  <!-- Script para verificar o horário e fazer a requisição -->
  <script>
    var horarioInicio = "17:00:00"; // Horário para checar

    // Verifica se é um dia útil (segunda a sexta)
    function diaUtil() {
        var dataAtual = new Date();
        var diaSemana = dataAtual.getDay(); // 0 = Domingo, 1 = Segunda, ..., 6 = Sábado
        return diaSemana >= 1 && diaSemana <= 5;
    }

    // Verifica se há registros na data atual
    function verificarRegistros(callback) {
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "./entrada/verificar_registro_entrada.php", true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                var existeRegistro = xhr.responseText === "1"; // "1" indica que há registros
                callback(existeRegistro); // Executa o callback com o resultado
            }
        };
        xhr.send();
    }

    // Compara o horário e chama faltas.php se houver registros na data atual
    function verificarHorario() {
        if (!diaUtil()) {
            console.log("Hoje é fim de semana. Faltas não serão registradas.");
            return;
        }

        var dataAtual = new Date();
        var horas = dataAtual.getHours().toString().padStart(2, '0');
        var minutos = dataAtual.getMinutes().toString().padStart(2, '0');
        var segundos = dataAtual.getSeconds().toString().padStart(2, '0');
        var horarioAtual = horas + ":" + minutos + ":" + segundos;

        // Verifica o horário e se há registros
        if (horarioAtual >= horarioInicio) {
            verificarRegistros(function (existeRegistro) {
                if (existeRegistro) {
                    console.log("Horário atingido e registros encontrados. Chamando faltas.php...");

                    var xhr = new XMLHttpRequest();
                    xhr.open("GET", "./entrada/faltas.php", true);
                    xhr.onreadystatechange = function () {
                        if (xhr.readyState == 4 && xhr.status == 200) {
                            console.log("Requisição completada. Redirecionando para index.php...");
                            window.location.href = "index.php";
                        }
                    };
                    xhr.send();
                } else {
                    console.log("Nenhum registro encontrado para a data atual. Ação não realizada.");
                }
            });
        }
    }

    // Executa a função de verificação a cada 30 segundos
    setInterval(verificarHorario, 1000);
  </script>

  <!-- Scripts Bootstrap -->
  <script src="./js/jquery-3.3.1.slim.min.js" crossorigin="anonymous"></script>
  <script src="./js/popper.min.js" crossorigin="anonymous"></script>
  <script src="./js/bootstrap.min.js" crossorigin="anonymous"></script>

</body>

</html>