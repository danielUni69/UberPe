<!doctype html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Historial de Viajes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
      body {
          background-color: #f8f9fa;
      }
      .container {
          max-width: 900px;
          background: white;
          padding: 20px;
          border-radius: 10px;
          box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      }
      h2 {
          color: #343a40;
      }
      .table thead {
          background-color: #343a40;
          color: white;
      }
      .btn-primary {
          background-color: #007bff;
          border: none;
      }
      .btn-primary:hover {
          background-color: #0056b3;
      }
      .pagination .page-link {
          color: #007bff;
      }
      .pagination .page-item.active .page-link {
          background-color: #007bff;
          border-color: #007bff;
      }
    </style>
  </head>
  <body>
    <div class="container mt-5">
      <h2 class="mb-4 text-center">Historial de Viajes</h2>
      <table class="table-striped table-bordered table">
        <thead>
          <tr>
            <th>#</th>
            <th>Origen</th>
            <th>Destino</th>
            <th>Fecha</th>
            <th>Estado</th>
            <th>Tarifa</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          @foreach($viajes as $viaje)
          <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $viaje->origen }}</td>
            <td>{{ $viaje->destino }}</td>
            <td>{{ $viaje->fecha }}</td>
            <td><span class="badge bg-{{ $viaje->estado == 'Completado' ? 'success' : ($viaje->estado == 'Cancelado' ? 'danger' : 'warning') }}"> {{ $viaje->estado }}</span></td>
            <td>${{ number_format($viaje->tarifa, 2) }}</td>
            <td>
              <a href="{{ route('viajes.show', $viaje->id_viaje) }}" class="btn btn-primary btn-sm">Ver Detalles</a>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
      <div class="d-flex justify-content-center">{{ $viajes->links() }}</div>
    </div>
  </body>
</html>
