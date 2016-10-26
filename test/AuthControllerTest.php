<?php

use Auth\Controller\AuthController;
use GuzzleHttp\Client;
use Auth\Model\Services\AuthenticationService;
use Lcobucci\JWT\Token;

class AuthControllerTest extends \PHPUnit_Framework_TestCase
{

    protected $client;
    protected $credentials;
    protected $authenticationService;
    
    public function setUp()
    {
        $this->client = new Client(['base_uri' => 'http://127.0.0.1/']);
        $this->credentials = ['user' => 'some_username', 'passwd' => '12345678'];
        $this->authenticationService = new AuthenticationService();
    }
    
    public function tearDown()
    {
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
        $this->assertTrue($this->authenticationService->authenticate('01481419196', '12345678') instanceof Token);
    }
}
