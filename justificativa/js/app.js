document.getElementById('campo-nome').addEventListener('keyup', function () {
    const nome = this.value;
  
    if (nome.length >= 2) {
      fetch(`buscar_faltas_atrasos.php?nome=${encodeURIComponent(nome)}`)
        .then(res => res.text())
        .then(html => {
          document.getElementById('resultados-container').innerHTML = html;
  
          document.querySelectorAll('.btn-justificar').forEach(botao => {
            botao.addEventListener('click', function () {
              this.closest('tr').nextElementSibling.style.display = 'table-row';
            });
          });
  
          document.querySelectorAll('.form-justificativa').forEach(form => {
            form.addEventListener('submit', function (e) {
              e.preventDefault();
              const dados = new FormData(this);
  
              fetch('atualizar_falta_atraso.php', {
                method: 'POST',
                body: dados
              })
                .then(resp => resp.text())
                .then(msg => {
                  alert(msg);
                  document.getElementById('campo-nome').dispatchEvent(new Event('keyup'));
                });
            });
          });
        });
    } else {
      document.getElementById('resultados-container').innerHTML = '';
    }
  });
  