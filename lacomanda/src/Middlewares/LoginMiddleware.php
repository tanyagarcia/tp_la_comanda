<?php
namespace App\Middlewares;

use Slim\Psr7\Response as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use App\Models\User;

class LoginMiddleware
{
    
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $headers = $request->getParsedBody();
        $rta = new Response();
        if ((isset($headers['clave']) && $headers['clave']!= "") && (isset($headers['email']) && $headers['email']!= ""))
        {
            $clave =  strtolower($headers['clave']);
            $email =  strtolower($headers['email']);
            $userEncontrado = json_decode(User::where('clave', $clave)
            ->where('email', $email)
            ->get());
            
            if($userEncontrado != [])
            {
                $response = $handler->handle($request);
                $existingContent = (string) $response->getBody();
                $array = array(
                    "status" =>"success",
                    "message" => $existingContent
                );
                $rta->getBody()->write(json_encode($array));
            }else
            {
                $array = array(
                    "status" => "fail",
                    "message" => "No hay coincidencia con BD"
                );
                $rta->getBody()->write(json_encode($array));
            }
        }
        else
        {
            $array = array(
                "status" => "fail",
                "message" => "Faltan datos"
            );
            $rta->getBody()->write(json_encode($array));
        }

        return $rta->withHeader('Content-type', 'application/json');
    }

}