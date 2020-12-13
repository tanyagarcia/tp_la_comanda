<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\Mesa;
use \Firebase\JWT\JWT;

class MesaController
{
    public function altaMesa(Request $request, Response $response, $args)
    {
        $body = $request->getParsedBody();
        $codigoMesa = $body['codigoMesa'];
        $mesa = new Mesa;
        $mesa->estado = "Disponible";
        $mesa->codigo_mesa = $codigoMesa;
        $mesa->save();
        $rta = json_encode(array("status" => "success",
        "message" => "Mesa dada de alta"));
        $response->getBody()->write($rta);
        $response->withHeader('Content-type', 'application/json');
        return $response;
        
    }

    public function cerrarMesa(Request $request, Response $response, $args)
    {
        $idMesa = $args['idMesa'];
        $mesaACerrar = Mesa::where('id', $idMesa)
        ->where('estado', "Con cliente pagando")
        ->first();

        if($mesaACerrar != [])
        {
            $mesaACerrar->estado = "Disponible";
            $mesaACerrar->save();
            $array = array(
            "status" => "success",
            "message" => "La mesa fue cerrada.");
        }
        else
        {
            $array = array(
            "status" => "fail",
            "mensaje" => "Hubo un error al cerrar la mesa");
        }

        $response->getBody()->write(json_encode($array));
        $response->withHeader('Content-type', 'application/json');
        return $response;

    }

}

?>