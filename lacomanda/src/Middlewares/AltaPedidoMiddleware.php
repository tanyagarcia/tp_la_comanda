<?php
namespace App\Middlewares;

use Slim\Psr7\Response as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use App\Models\Pedido;
use \Firebase\JWT\JWT;

class AltaPedidoMiddleware
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
                
                if($decoded->tipo == "mozo")
                {
                    if ((isset($headers['codigoMesa']) && $headers['codigoMesa']!="") 
                    && (isset($headers['menu']) && $headers['menu']!="")
                    && (isset($headers['emailCliente']) && $headers['emailCliente']!=""))
                    {
                        
                        $response = $handler->handle($request);
                        $existingContent = (string) $response->getBody();
                        $array = array(
                            "status" =>"success",
                            "message" => $existingContent
                        );
                        $resp->getBody()->write(json_encode($array));
                        
                    }
                    else
                    {
                        $array = array(
                        "status" =>"fail",
                        "message" => "Datos inválidos");
                        $resp->getBody()->write(json_encode($array));
                    }  
                }
                else
                {
                    $array = array(
                    "status" =>"fail",
                    "message" => "Solo el mozo puede generar un pedido");
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