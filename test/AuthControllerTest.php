<?php

use Auth\Model\Services\AuthenticationService;
use Lcobucci\JWT\Token;

class AuthControllerTest extends \PHPUnit_Framework_TestCase
{

    protected $client;
    protected $credentials;
    protected $authenticationService;
    
    public function setUp()
    {
        $this->authenticationService = new AuthenticationService();
    }

    /**
     * @test
     */
    public function aValidUserCanAuthenticateAndGetAToken()
    {
        $user = $this->getMock('Auth\Model\Entity\Usuario');
        $user->nome = 'John Doe';
        $user->token = 'TokenFake';

        $userDAO = $this->getMock('Auth\Model\DAO\IUsuarioDAO');
        $userDAO->expects($this->any())->method('getByUsernameAndPassword')->with('some_username', '12345678')->willReturn($user);

        $this->authenticationService->setUserDAO($userDAO);
        $this->assertTrue($this->authenticationService->authenticate('some_username', '12345678') instanceof Token);
    }
}
