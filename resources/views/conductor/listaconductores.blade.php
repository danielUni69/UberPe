<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Conductores</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #77A300;
            color: white;
        }
        .card-container {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 30px;
            border-radius: 15px;
        }
        .card {
            width: 300px;
            border-radius: 10px;
        }
        .header {
            display: flex;
            align-items: center;
            padding: 10px;
        }
        .avatar {
            width: 70px;
            height: 70px;
            background-color: #B599D5;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            margin-right: 10px;
        }
        .avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .text-start {
            text-align: left;
        }
        .btn-custom {
            background-color:#7e57c2;
            color: white;
            border: none;
            font-size: 12px; 
        }
        .btn-light{
            background-color: #a0fb0e;
            color: white;
            border: none;
            font-size: 12px; 
        }
        .btn-custom:hover {
            background-color: #5C3C72;
        }
        p {
            margin-bottom: 4px; 
            font-size: 12px; 
        }
        .card-body strong {
            margin-right: 4px; 
            
        }
    </style>
</head>
<body>
    <div class="container text-center">
        <h2 class="mt-4">Lista de conductores</h2>
        <div class="card-container">
            <div class="card">
                <div class="header">
                    <div class="avatar">
                        <img src="tu-imagen.jpg" alt="Avatar">
                    </div>
                    <div class="text-start">
                        <p><strong>Nombre:</strong> Pedro</p>
                        <p><strong>Apellido:</strong> Perez</p>
                    </div>
                </div>
                <img src="" class="card-img-top" alt="Carro">
                <div class="card-body text-start">
                    <p><strong>Ci:</strong>156165</p>
                    <p><strong>Teléfono:</strong>515846</p>
                    <p><strong>Email:</strong>pedro@gmail.com</p>
                    <p><strong>Licencia:</strong>5616546</p>
                    <p><strong>Vehículo:</strong>Automóvil</p>
                    <p><strong>Marca:</strong>Tesla</p>
                    <p><strong>Placa:</strong>3656PHP</p>
                    <p><strong>Color:</strong>Blanco</p>
                    <button class="btn btn-light">Habilitar</button>
                    <button class="btn btn-custom">Inhabilitar</button>
                </div>
            </div>
           <div class="card">
                <div class="header">
                    <div class="avatar">
                        <img src="tu-imagen.jpg" alt="Avatar">
                    </div>
                    <div class="text-start">
                        <p><strong>Nombre:</strong> Pedro</p>
                        <p><strong>Apellido:</strong> Perez</p>
                    </div>
                </div>
                <img src="https://via.placeholder.com/300x150" class="card-img-top" alt="Carro">
                <div class="card-body text-start">
                    <p><strong>Ci:</strong>156165</p>
                    <p><strong>Teléfono:</strong>515846</p>
                    <p><strong>Email:</strong>pedro@gmail.com</p>
                    <p><strong>Licencia:</strong>5616546</p>
                    <p><strong>Vehículo:</strong>Automóvil</p>
                    <p><strong>Marca:</strong>Tesla</p>
                    <p><strong>Placa:</strong>3656PHP</p>
                    <p><strong>Color:</strong>Blanco</p>
                    <button class="btn btn-light">Habilitar</button>
                    <button class="btn btn-custom">Inhabilitar</button>
                </div>
            </div>
            <div class="card">
                <div class="header">
                    <div class="avatar">
                        <img src="tu-imagen.jpg" alt="Avatar">
                    </div>
                    <div class="text-start">
                        <p><strong>Nombre:</strong> Pedro</p>
                        <p><strong>Apellido:</strong> Perez</p>
                    </div>
                </div>
                <img src="https://via.placeholder.com/300x150" class="card-img-top" alt="Carro">
                <div class="card-body text-start">
                    <p><strong>Ci:</strong>156165</p>
                    <p><strong>Teléfono:</strong>515846</p>
                    <p><strong>Email:</strong>pedro@gmail.com</p>
                    <p><strong>Licencia:</strong>5616546</p>
                    <p><strong>Vehículo:</strong>Automóvil</p>
                    <p><strong>Marca:</strong>Tesla</p>
                    <p><strong>Placa:</strong>3656PHP</p>
                    <p><strong>Color:</strong>Blanco</p>
                    <button class="btn btn-light">Habilitar</button>
                    <button class="btn btn-custom">Inhabilitar</button>
                </div>
            </div>
          </body>
</html>