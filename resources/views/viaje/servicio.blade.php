<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Viaje</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font/css/materialdesignicons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background-color: #dcdcdc;
            font-family: 'Poppins', sans-serif;
        }
        .sidebar {
            width: 15%;
            height: 100vh;
            background-color: #263300;
            color: white;
            padding-top: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            padding: 10px;
            width: 100%;
        }
        .sidebar a i {
            margin-right: 10px;
        }
        .map-container {
            flex: 1;
            height: 80vh;
            background-color: #eaeaea;
        }
        .bottom-bar {
            background-color: #5a7600;
            color: white;
            padding: 15px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .btn-success {
            background-color: #98fb98;
            color: black;
            border: none;
            padding: 10px 20px;
        }
        .btn-danger {
            background-color: #dc143c;
            color: white;
            border: none;
            padding: 10px 20px;
        }
        .form-control {
            background-color: #caffb9;
            border: none;
            text-align: center;
        }
        .input-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .car-image {
            width: 80px;
            margin-bottom: 10px;
        }
        .payment-options {
            background-color: #4d6600;
            padding: 10px;
            border-radius: 10px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar">
            <i class="mdi mdi-account-circle mdi-48px"></i>
            <p>Jose</p>
            <a href="#"><i class="mdi mdi-account"></i> Perfil</a>
            <a href="#"><i class="mdi mdi-history"></i> Historias de viajes</a>
            <a href="#"><i class="mdi mdi-alert-circle"></i> Incidencias</a>
        </div>
        
        <!-- Main content -->
        <div class="d-flex flex-column flex-grow-1">
            <div class="map-container">
                <iframe width="100%" height="100%" frameborder="0" style="border:0" 
                    src="https://www.google.com/maps/embed/v1/place?key=TU_API_KEY&q=Potosi,Bolivia" allowfullscreen>
                </iframe>
            </div>
            
            <!-- Bottom controls -->
            <div class="bottom-bar">
                <img src="car.png" alt="Auto" class="car-image">
                <div class="payment-options text-white text-center">
                    <p class="mb-1">Tipo de pago</p>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="payment" id="qr" value="qr">
                        <label class="form-check-label" for="qr">QR</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="payment" id="efectivo" value="efectivo">
                        <label class="form-check-label" for="efectivo">Efectivo</label>
                    </div>
                </div>
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Ofrezca su tarifa">
                    <span class="input-group-text">Bs</span>
                </div>
                <div class="input-group mt-2">
                    <input type="text" class="form-control" placeholder="Ir a">
                    <button class="btn btn-success">Buscar Conductor</button>
                    <button class="btn btn-danger">Cancelar Viaje</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
