<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitudes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #7ecb18;
        }
        .sidebar {
            width: 200px;
            height: 100vh;
            background-color: #000000;
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
            background-color: #609708;
            padding: 15px;
            width:180px;
            margin:auto;
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
        .sidebar a i {
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="text-center mb-3">
            <img src="https://cdn-icons-png.flaticon.com/512/149/149071.png" alt="Usuario">
            <p>Jose</p>
        </div>
        <a href="#"><i class="fa fa-user"></i> Perfil</a>
        <a href="#"><i class="fa fa-chart-bar"></i> Reporte</a>
        <a href="#"><i class="fa fa-money-bill"></i> Ingresos</a>
        <a href="#"><i class="fa fa-history"></i> Historial de viajes</a>
        <a href="#"><i class="fa fa-users"></i> Lista de pasajeros</a>
        <a href="#"><i class="fa fa-car"></i> Lista de Conductores</a>
        <a href="#"><i class="fa fa-tasks"></i> Lista de servicio</a>
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
                <button class="btn btn-success">Habilitar</button>
            </div>
            <div class="card-custom w-25">
                <h5>Header</h5>
                <p>Subhead</p>
                <h6>Title</h6>
                <p>Subtitle</p>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                <button class="btn btn-outline-dark">Enabled</button>
                <button class="btn btn-success">Habilitar</button>
            </div>
            <div class="card-custom w-25">
                <h5>Header</h5>
                <p>Subhead</p>
                <h6>Title</h6>
                <p>Subtitle</p>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                <button class="btn btn-outline-dark">Enabled</button>
                <button class="btn btn-success">Habilitar</button>
            </div>
        </div>
        <div class="footer mt-5">
            <h5>Estado</h5>
            <input type="radio" name="estado" id="activo" checked> <label for="activo">Activo</label>
            <input type="radio" name="estado" id="inactivo"> Inactivo</label>
        </div>
    </div>
</body>
</html>
