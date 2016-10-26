<?php

use \Auth\Controller\AuthController;
use Auth\Model\Services\TokenService;
use Auth\Model\Exception\InvalidTokenException;

/**
 * Verification middleware
 */
function verifyTokenBearer()
{
    header('Content-Type: application/json');
    $tokenService = new TokenService();
    if (!$tokenService->hasBearerToken()) {
        echo json_encode(array("error"=>"missing token"));
        die();
    }
    try {
        if (!$tokenService->isTokenValid()) {
            echo json_encode(array("error"=>"token has expired"));
            die();
        }
    } catch (InvalidTokenException $e) {
        echo json_encode(array("error"=>"invalid token"));
        die();
    }
}

$app->group('/api', function () use ($app) {
    $app->response->headers->set('Content-Type', 'application/json');
    $app->get('/', '\Auth\Controller\AuthController:index');
    $app->post('/authenticate', '\Auth\Controller\AuthController:authenticate');
    $app->get('/user', 'verifyTokenBearer', '\Auth\Controller\AuthController:getUser');
    $app->get('/verify', 'verifyTokenBearer', '\Auth\Controller\AuthController:verify');
    $app->get('/privileges', 'verifyTokenBearer', '\Auth\Controller\AuthController:getPrivileges');
    $app->get('/privileges/:context', 'verifyTokenBearer', '\Auth\Controller\AuthController:getPrivileges');
    $app->get('/status', '\Auth\Controller\AuthController:getStatus');
    $app->get('/refresh-token', 'verifyTokenBearer', '\Auth\Controller\AuthController:refreshToken');
    $app->post('/create-user', 'verifyTokenBearer', '\Auth\Controller\AuthController:createUser');
});
$app->notFound(function () use ($app) {
    $app->response->setStatus(404);
    echo json_encode(array('code' => 404, 'message' => "The requested resource doesn't exist"));
});
