<?php

use App\Http\Controllers\WeatherController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Upgate\LaravelJsonRpc\Contract\ServerInterface as JsonRpcServerContract;

Route::post('/', function (Request $request) {
    // Create an instance of JsonRpcServer
    /**
     * 
     * @var \Upgate\LaravelJsonRpc\Server\Server $jsonRpcServer
     */
    $jsonRpcServer = app()->make(JsonRpcServerContract::class);
    // Set default controller namespace
    //$jsonRpcServer->setControllerNamespace($this->namespace);
    // Register middleware aliases configured for Laravel router
    //$jsonRpcServer->registerMiddlewareAliases($router->getMiddleware());
    $jsonRpcServer->router()->bindController('weather', WeatherController::class);
    // Run json-rpc server with $request passed to middlewares as a handle() method argument
    //Log::debug(print_r($jsonRpcServer, true));
    return $jsonRpcServer->run($request, $request->getContent());
});
