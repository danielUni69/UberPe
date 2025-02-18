<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitudes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #7ecb18;
        }
        .sidebar {
            width: 200px;
            height: 100vh;
            background-color: #1c2508;
            color: white;
            position: fixed;
            padding: 20px;
        }
      .sidebar img {
            width: 80px;
            height: 80px;
            margin-bottom: 20px;
        }
        .sidebar a {
            color: white;
            display: block;
            text-decoration: none;
            padding: 10px;
        }
        .sidebar a:hover {
            background-color: #4b5d1e;
            border-radius: 5px;
        }
        .main-content {
            margin-left: 220px;
            padding: 20px;
        }
        .card-custom {
            background-color: #d7ffb2;
            border-radius: 15px;
            padding: 15px;
        }
        .footer {
            background-color: #4b5d1e;
            padding: 10px;
            text-align: center;
            color: white;
            border-radius: 10px;
            margin-top: 20px;
        }
      .success-message {
            color: green;
            margin-bottom: 15px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="text-center mb-3">
            <img src="https://cdn-icons-png.flaticon.com/512/149/149071.png" alt="Usuario">
            <p>Jose</p>
        </div>
        <a href="#">Perfil</a>
        <a href="#">Reporte</a>
        <a href="#">Ingresos</a>
        <a href="#">Historial de viajes</a>
        <a href="#" class="text-warning">Lista de pasajeros</a>
        <a href="#" class="text-warning">Lista de Conductores</a>
        <a href="#" class="text-warning">Lista de servicio</a>
    </div>
    <div class="main-content">
        <h2 class="text-center fw-bold">Solicitudes</h2>
        <div class="d-flex justify-content-around">
            <div class="card-custom w-25">
                <h5>Header</h5>
                <p>Subhead</p>
                <h6>Title</h6>
                <p>Subtitle</p>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                <button class="btn btn-outline-dark">Enabled</button>
                <button class="btn btn-success">Enabled</button>
            </div>
            <div class="card-custom w-25">
                <h5>Header</h5>
                <p>Subhead</p>
                <h6>Title</h6>
                <p>Subtitle</p>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                <button class="btn btn-outline-dark">Enabled</button>
                <button class="btn btn-success">Enabled</button>
            </div>
            <div class="card-custom w-25">
                <h5>Header</h5>
                <p>Subhead</p>
                <h6>Title</h6>
                <p>Subtitle</p>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                <button class="btn btn-outline-dark">Enabled</button>
                <button class="btn btn-success">Enabled</button>
            </div>
        </div>
        <div class="footer mt-5">
            <h5>Estado</h5>
            <input type="radio" name="estado" id="activo" checked> <label for="activo">Activo</label>
            <input type="radio" name="estado" id="inactivo"> <label for="inactivo">Inactivo</label>
        </div>
    </div>
</body>
</html>
