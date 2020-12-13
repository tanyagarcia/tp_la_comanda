<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\User;
use App\Models\Mesa;
use App\Models\Pedido;
use \Firebase\JWT\JWT;

class PedidoController
{

    public function altaPedido(Request $request, Response $response, $args)
    {
        $body = $request->getParsedBody();
        $email = $body['emailCliente'];
        $usuarioABuscar = json_decode(User::where('email', $email)
        ->get());

        $codigoMesa = $body['codigoMesa'];
        $mesaABuscar = json_decode(Mesa::where('codigo_mesa', $codigoMesa)
        ->get());

        $menuPedido = $body['menu'];

        if($mesaABuscar != [] && $usuarioABuscar != [])
        {
            $pedido = new Pedido;
            $pedido->id_cliente = $usuarioABuscar[0]->id;
            $pedido->id_mesa =  $mesaABuscar[0]->id;
            $pedido->menu = $menuPedido;
            $pedido->estado = "Pendiente";
            $codigo = self::generateRandomString(5);
            $pedido->codigo_pedido = $codigo;
            $pedido->save();
            
            $registroOk = Mesa::where('codigo_mesa', $codigoMesa)
            ->update(['estado' => "Con cliente esperando pedido"]);

            $response->getBody()->write($codigo);

        }
        else{
            $array = array(
                "status" =>"fail",
                "message" => "No se pudo generar el pedido");
            $response->getBody()->write(json_encode($array));
        }

        $response->withHeader('Content-type', 'application/json');
        return $response;
    }

    private static function generateRandomString($length) 
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    
    }

    public function mostrarPedidosPendientes(Request $request, Response $response, $args)
    {
        $pendientes = json_decode(Pedido::where('estado', "Pendiente")
        ->get());
        $rta = json_encode(array("status" => "success",
        "Pendientes:" => $pendientes));
        $response->getBody()->write($rta);
        $response->withHeader('Content-type', 'application/json');
        return $response; 
    }


    public function tomarPedido(Request $request, Response $response, $args)
    {
        $headers = getallheaders();
        $token = $headers['token'];
        
        try
        {
            $decoded = JWT::decode($token, 'usuario', array('HS256'));
        }
        catch(\Throwable $th)
        {
            echo $th->getMessage();
        }
       
        $claveEmpleado = $decoded->clave;
        $empleado = User::where('clave', $claveEmpleado)->get();
        $respuesta = $request->getParsedBody();
        $duracion = $respuesta['duracion'];
        $idPedido = $args['idPedido'];
        $pedidoATomar = Pedido::where('id', $idPedido)
        ->where('estado', "Pendiente")
        ->first();

        if($pedidoATomar != [])
        {
            $pedidoATomar->estado = "En preparación";
            $pedidoATomar->tiempo_preparacion = $duracion;
            $pedidoATomar->id_empleado = $empleado[0]->id;
            $pedidoATomar->save();
            $array = array(
            "status" => "success",
            "message" => "Listo! Ya podés comenzar a preparar el pedido.");
        }
        else
        {
            $array = array(
            "status" => "fail",
            "mensaje" => "El pedido no está en estado Pendiente");
        }

        $response->getBody()->write(json_encode($array));
        $response->withHeader('Content-type', 'application/json');
        return $response;

    }

    public function cerrarPedido(Request $request, Response $response, $args)
    {
        $headers = getallheaders();
        $token = $headers['token'];
        $idPedido = $args['idPedido'];
        $pedidoACerrar = Pedido::where('id', $idPedido)
        ->where('estado', "En preparación")
        ->first();

        if($pedidoACerrar != [])
        {
            $pedidoACerrar->estado = "Listo para servir";
            $pedidoACerrar->tiempo_preparacion = 0;
            $pedidoACerrar->id_empleado = NULL;
            $pedidoACerrar->save();
            $array = array(
            "status" => "success",
            "message" => "Listo! Ya se puede servir el pedido.");
        }
        else
        {
            $array = array(
            "status" => "fail",
            "mensaje" => "El pedido no está en preparación");
        }

        $response->getBody()->write(json_encode($array));
        $response->withHeader('Content-type', 'application/json');
        return $response;

    }

    public function mostrarListosParaServir(Request $request, Response $response, $args)
    {
        $pendientes = json_decode(Pedido::where('estado', "Listo para servir")
        ->get());
        $rta = json_encode(array("status" => "success",
        "Listos para servir:" => $pendientes));
        $response->getBody()->write($rta);
        $response->withHeader('Content-type', 'application/json');
        return $response; 
    }

    public function servirPedido(Request $request, Response $response, $args)
    {
        $idPedido = $args['idPedido'];
        $pedidoAServir = Pedido::where('id', $idPedido)
        ->where('estado', "Listo para servir")
        ->first();

        $mesa = $pedidoAServir->id_mesa;

        $mesaAServir = Mesa::where('id', $mesa)
        ->where('estado', "Con cliente esperando pedido")
        ->first();

        if($mesaAServir != [])
        {
            $mesaAServir->estado = "Con cliente comiendo";
            $mesaAServir->save();
            $array = array(
            "status" => "success",
            "message" => "Listo! El cliente ya está comiendo.");
        }
        else
        {
            $array = array(
            "status" => "fail",
            "mensaje" => "Hubo un error al servir el pedido");
        }

        $response->getBody()->write(json_encode($array));
        $response->withHeader('Content-type', 'application/json');
        return $response;

    }

    public function pagarPedido(Request $request, Response $response, $args)
    {
        $idPedido = $args['idPedido'];
        $pedidoAPagar = Pedido::where('id', $idPedido)
        ->first();

        $mesa = $pedidoAPagar->id_mesa;

        $pagoMesa = Mesa::where('id', $mesa)
        ->where('estado', "Con cliente comiendo")
        ->first();

        if($pagoMesa != [])
        {
            $pagoMesa->estado = "Con cliente pagando";
            $pagoMesa->save();
            $array = array(
            "status" => "success",
            "message" => "El cliente se encuentra realizando el pago.");
        }
        else
        {
            $array = array(
            "status" => "fail",
            "mensaje" => "Hubo un error al pagar el pedido");
        }

        $response->getBody()->write(json_encode($array));
        $response->withHeader('Content-type', 'application/json');
        return $response;

    }

    public function buscarPedido(Request $request, Response $response, $args)
    {

        $respuesta = $request->getParsedBody();
        $codigoMesa = $respuesta['codigoMesa'];
        $codigoPedido = $respuesta['codigoPedido'];

        $mesaABuscar = Mesa::where('codigo_mesa', $codigoMesa)
        ->first();

        $idMesa = $mesaABuscar->id;

        $pedidoAMostrar = Pedido::where('codigo_pedido', $codigoPedido)
        ->where('id_mesa', $idMesa)
        ->first();

        $estadoDelPedido = $pedidoAMostrar->estado;

        if($estadoDelPedido != [])
        {
            $array = array(
            "status" => "success",
            "message" => "El estado del pedido es: ".$estadoDelPedido);
        }
        else
        {
            $array = array(
            "status" => "fail",
            "mensaje" => "Hubo un error al pagar el pedido");
        }

        $response->getBody()->write(json_encode($array));
        $response->withHeader('Content-type', 'application/json');
        return $response;

    }

    public function mostrarAllPedidos(Request $request, Response $response, $args)
    {
        $pedidos = json_decode(Pedido::get());
        $rta = json_encode(array("status" => "success",
        "Pedidos:" => $pedidos));
        $response->getBody()->write($rta);
        $response->withHeader('Content-type', 'application/json');
        return $response; 
    }

}
    
?>