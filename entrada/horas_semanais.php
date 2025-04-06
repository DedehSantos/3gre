<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jornada de Trabalho</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        .trabalho { background-color: red; color: white; padding: 10px; border-radius: 10px; text-align: center; }
        .folga { background-color: green; color: white; padding: 10px; border-radius: 10px; text-align: center; }
        .container { text-align: center; }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-success">Jornada de Trabalho de 40h Semanais</h2>
        <h4>Escalas 5x2 e 6x1 - 200h Mensais</h4>
        
        <h5 class="mt-4">De Segunda a Sexta (5x2)</h5>
        <div class="row justify-content-center">
            <div class="col-md-2 trabalho">Segunda<br>08:00 - 17:00<br>1h - Pausa</div>
            <div class="col-md-2 trabalho">Terça<br>08:00 - 17:00<br>1h - Pausa</div>
            <div class="col-md-2 trabalho">Quarta<br>08:00 - 17:00<br>1h - Pausa</div>
            <div class="col-md-2 trabalho">Quinta<br>08:00 - 17:00<br>1h - Pausa</div>
            <div class="col-md-2 trabalho">Sexta<br>08:00 - 17:00<br>1h - Pausa</div>
            <div class="col-md-2 folga">Sábado<br>Folga</div>
            <div class="col-md-2 folga">Domingo<br>Folga</div>
        </div>
        
        <h5 class="mt-5">De Segunda a Sábado (6x1)</h5>
        <div class="row justify-content-center">
            <div class="col-md-2 trabalho">Segunda<br>08:00 - 16:00<br>1h - Pausa</div>
            <div class="col-md-2 trabalho">Terça<br>08:00 - 16:00<br>1h - Pausa</div>
            <div class="col-md-2 trabalho">Quarta<br>08:00 - 16:00<br>1h - Pausa</div>
            <div class="col-md-2 trabalho">Quinta<br>08:00 - 16:00<br>1h - Pausa</div>
            <div class="col-md-2 trabalho">Sexta<br>08:00 - 16:00<br>1h - Pausa</div>
            <div class="col-md-2 trabalho">Sábado<br>08:00 - 14:00<br>1h - Pausa</div>
            <div class="col-md-2 folga">Domingo<br>Folga</div>
        </div>
    </div>
</body>
</html>
