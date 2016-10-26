<?php

namespace Auth\Controller;

use Auth\Model\DAO\IUsuarioDAO;
use Auth\Model\Services\ITokenService;
use Auth\Model\Services\TokenService;
use Auth\Model\Factory\AppFactory;
use Auth\Model\Entity\User;
use Auth\Model\Services\AuthenticationService;

/**
 * Controller for API access
 * @since 0.1.0
 * @author Evandro Mohr
 */
class AuthController
{

    protected $service;
    protected $userDAO;
    protected $authenticationService;

    public function __construct(
        ITokenService $tokenService = null,
        IUsuarioDAO $usuarioDAO = null
    ) {
        $this->authenticationService = new AuthenticationService();
        $this->service = $tokenService ? : new TokenService();
        $this->userDAO = $usuarioDAO ? : AppFactory::getUsuarioDAO();
    }

    public function setUserDAO(IUsuarioDAO $userDAO)
    {
        $this->userDAO = $userDAO;
    }

    public function index()
    {
        echo json_encode("welcome to the new API for auth");
    }

    public function authenticate()
    {
        if (!isset($_POST['user']) || !isset($_POST['passwd'])) {
            echo json_encode(array("error"=>"missing credentials"));
            return;
        }

        $username = filter_var($_POST['user'], FILTER_SANITIZE_STRING);
        $passwd = filter_var($_POST['passwd'], FILTER_SANITIZE_STRING);

        try {
            $token =  array("token"=>(string) $this->authenticationService->authenticate($username, $passwd));
        } catch (\Exception $e) {
            echo json_encode(array("error"=>"invalid user"));
            return;
        }

        echo json_encode($token);
    }

    public function verify()
    {
        echo json_encode(array("valid"=>"true"));
    }

    public function refreshToken()
    {
        echo json_encode(array("token"=>(string) @$this->authenticationService->refreshToken()));
    }

    public function createUser()
    {
        $notIssetPostVars = !isset($_POST['nome'])
            || !isset($_POST['email'])
            || !isset($_POST['cpf'])
            || !isset($_POST['passwd'])
            || !isset($_POST['proxy']);

        if ($notIssetPostVars) {
            echo json_encode(array("error"=>"missing basic information"));
            return;
        }

        echo json_encode($this->authenticationService->createUser($_POST));
    }

    public function getUser()
    {
        echo json_encode(($this->authenticationService->getUser()));
    }

    public function getPrivileges($context = null)
    {
        echo json_encode($this->authenticationService->getPrivileges($context));
    }

    public function getStatus()
    {
        echo "ok";
    }
}
