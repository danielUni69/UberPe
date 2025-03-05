<?php
namespace App\Http\Controllers;

use App\Core\ListaPasajero;
use App\Core\ListaPersona;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PasajeroController extends Controller
{

    private ListaPasajero $listaPasajero;
    private ListaPersona $listaPersona;
    private $service; 
    public function __construct()
    {
        $this->listaPasajero = new ListaPasajero;
        $this->listaPersona = new ListaPersona;
    }

    public function solicitarServicio(Request $request): JsonResponse
    {
        try {
            $viaje = $this->listaPasajero->solicitarServicio(
                $request->origen,
                $request->destino,
                $request->metodo_pago,
                $request->tarifa
            );
            return response()->json([
                'mensaje' => 'Viaje solicitado con éxito.',
                'viaje' => $viaje
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], $e->getCode() ?: 400);
        }
    }
    public function cancelarViaje(){
        try {
            $viaje = $this->listaPersona->cancelarViaje();
            return response()->json([
                'mensaje' => 'Viaje cancelado correctamente.',
                'viaje' => $viaje
            ], 200);
        }catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], $e->getCode() ?: 400);
        }
    }
}
