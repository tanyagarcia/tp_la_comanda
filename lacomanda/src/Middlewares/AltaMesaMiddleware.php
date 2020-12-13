<?php
namespace App\Middlewares;

use Slim\Psr7\Response as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use App\Models\Mesa;
use \Firebase\JWT\JWT;

class AltaMesaMiddleware
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
                
                if($decoded->tipo == "socio")
                {
                    if (isset($headers['codigoMesa']) && $headers['codigoMesa']!="")
                    {
                        $codigoMesa = $headers['codigoMesa'];
                        $mesaEncontrada = json_decode(Mesa::where('codigo_mesa', $codigoMesa)
                        ->get());
            
                        if($mesaEncontrada != [])
                        {
                            $response = $handler->handle($request);
                            $existingContent = (string) $response->getBody();
                            $resp->getBody()->write($existingContent);
                        }
                    }
                    else{
                        $array = array(
                            "status" =>"fail",
                            "message" => "El código de mesa no es válido o ya fue dada de alta");
                            $resp->getBody()->write(json_encode($array));
                    }
                }
                else
                {
                    $array = array(
                    "status" =>"fail",
                    "message" => "Sólo un socio puede dar de alta una mesa");
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