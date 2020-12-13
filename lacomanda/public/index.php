<?php


use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\ServerHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Factory\AppFactory;
use Config\Database;
use App\Controllers\UserController;
use App\Controllers\PedidoController;
use App\Controllers\MesaController;
use Slim\Routing\RouteCollectorProxy;
use App\Middlewares\JsonMiddleware;
use App\Middlewares\RegistrarUsuarioMiddleware;
use App\Middlewares\LoginMiddleware;
use App\Middlewares\AltaPedidoMiddleware;
use App\Middlewares\AltaMesaMiddleware;
use App\Middlewares\VerPedidosPendientesMiddleware;
use App\Middlewares\TomarPedidoMiddleware;
use App\Middlewares\CerrarPedidoMiddleware;
use App\Middlewares\VerPedidosListosMiddleware;
use App\Middlewares\ServirPedidoMiddleware;
use App\Middlewares\PagarPedidoMiddleware;
use App\Middlewares\CerrarMesaMiddleware;
use App\Middlewares\BuscarPedidoMiddleware;
use App\Middlewares\MostrarAllPedidosMiddleware;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

$app->setBasePath('/lacomanda/public');

new Database;

//punto 1
$app->post('/users', UserController::class. ":registrarUsuario")->add(new RegistrarUsuarioMiddleware())->add(new JsonMiddleware());

//punto 2
$app->post('/login', UserController::class. ":login")->add(new LoginMiddleware())->add(new JsonMiddleware());

//punto 3
$app->post('/altaMesa', MesaController::class. ":altaMesa")->add(new AltaMesaMiddleware())->add(new JsonMiddleware());

//punto 4
$app->post('/altaPedido', PedidoController::class. ":altaPedido")->add(new AltaPedidoMiddleware())->add(new JsonMiddleware());

//punto 5
$app->get('/pendientes', PedidoController::class. ":mostrarPedidosPendientes")->add(new VerPedidosPendientesMiddleware())->add(new JsonMiddleware());

//punto 6
$app->put('/pendientes/{idPedido}', PedidoController::class. ":tomarPedido")->add(new TomarPedidoMiddleware())->add(new JsonMiddleware());

//punto 7
$app->post('/pendientes/{idPedido}', PedidoController::class. ":cerrarPedido")->add(new CerrarPedidoMiddleware())->add(new JsonMiddleware());

//punto 8
$app->get('/listos', PedidoController::class. ":mostrarListosParaServir")->add(new VerPedidosListosMiddleware())->add(new JsonMiddleware());

//punto 9
$app->post('/listos/{idPedido}', PedidoController::class. ":servirPedido")->add(new ServirPedidoMiddleware())->add(new JsonMiddleware());

//punto 10
$app->post('/pagar/{idPedido}', PedidoController::class. ":pagarPedido")->add(new PagarPedidoMiddleware())->add(new JsonMiddleware());

//punto 11
$app->post('/cerrar/{idMesa}', MesaController::class. ":cerrarMesa")->add(new CerrarMesaMiddleware())->add(new JsonMiddleware());

//punto 12
$app->post('/buscar', PedidoController::class. ":buscarPedido")->add(new BuscarPedidoMiddleware())->add(new JsonMiddleware());

//punto 13
$app->get('/todos', PedidoController::class. ":mostrarAllPedidos")->add(new MostrarAllPedidosMiddleware())->add(new JsonMiddleware());




$app->addBodyParsingMiddleware();
$app->run();

?>