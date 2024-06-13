<?php

declare(strict_types=1);

use App\Controllers\Players;
use App\Controllers\TeamIndex;
use App\Controllers\PlayerIndex;
use App\Controllers\Teams;
use App\Middleware\GetPlayer;
use App\Middleware\GetTeam;
use Slim\Factory\AppFactory;
use DI\ContainerBuilder;
use Slim\Handlers\Strategies\RequestResponseArgs;
use App\Middleware\AddJsonResponseHeader;
use Slim\Routing\RouteCollectorProxy;

define('APP_ROOT', dirname(__DIR__));

require APP_ROOT . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));

$dotenv->load();

$builder = new ContainerBuilder;

$container = $builder->addDefinitions(APP_ROOT . '/config/definitions.php')
                     ->build();

AppFactory::setContainer($container);

$app = AppFactory::create();

$collector = $app->getRouteCollector();

$collector->setDefaultInvocationStrategy(new RequestResponseArgs);

$app->addBodyParsingMiddleware();

$error_middleware = $app->addErrorMiddleware(true, true, true);

$error_handler = $error_middleware->getDefaultErrorHandler();

$error_handler->forceContentType('application/json');

$app->add(new AddJsonResponseHeader);

$app->group('/api', function (RouteCollectorProxy $group) {

    //Get all teams registered
    $group->get('/equipos', TeamIndex::class);
    //Add a new team 
    $group->post('/equipos', [Teams::class, 'create']);
    //get, update and delete team by id
    $group->group('', function (RouteCollectorProxy $group) {

        $group->get('/equipos/{id:[0-9]+}', Teams::class . ':show');

        $group->patch('/equipos/{id:[0-9]+}', Teams::class . ':update');

        $group->delete('/equipos/{id:[0-9]+}', Teams::class . ':delete');

    })->add(GetTeam::class);


    $group->get('/jugadores', PlayerIndex::class);
    $group->post('/jugadores', [Players::class, 'create']);
    $group->group('', function (RouteCollectorProxy $group) {

        $group->get('/jugadores/{id:[0-9]+}', Players::class . ':show');

    })->add(GetPlayer::class);

});

$app->run();