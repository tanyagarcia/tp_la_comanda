<?php
namespace App\Middlewares;

use Slim\Psr7\Response as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use App\Models\Pedido;
use \Firebase\JWT\JWT;

class VerPedidosPendientesMiddleware
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $headers = $request->getParsedBody();
        $token = getallheaders();
        $token = $token['token'] ?? '';
        $resp = new Response();

        if(isset($token) && $token !="")
        {
            try
            {
                $decoded = JWT::decode($token, 'usuario', array('HS256'));
                
                if($decoded->tipo == "bartender" || $decoded->tipo == "cocinero" || $decoded->tipo == "cervecero" 
                    || $decoded->tipo == "mozo" || $decoded->tipo == "socio")
                {
                    $response = $handler->handle($request);
                    $existingContent = (string) $response->getBody();
                    $resp->getBody()->write($existingContent);
                }
                else
                {
                    $array = array(
                    "status" =>"fail",
                    "message" => "Solo los empleados pueden ver los pendientes");
                    $resp->getBody()->write(json_encode($array));
                }  
            }
            catch(\Throwable $th)
            {
                echo $th->getMessage();
            }
        }

        return $resp->withHeader('Content-type', 'application/json');
    }



}