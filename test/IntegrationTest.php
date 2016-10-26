<?php

use GuzzleHttp\Client;

/**
 * API Integration test suite
 *
 * @since 0.1.0
 * @author Evandro Mohr
 *
 */
class IntegrationTest extends \PHPUnit_Framework_TestCase
{
    
    protected $client;
    protected $credentials;
    
    public function setUp()
    {
        $this->client = new Client(['base_uri' => 'http://192.168.0.125/']);
        $this->credentials = ['user' => '01481419196', 'passwd' => '12345678'];
    }
    
    public function tearDown()
    {
    }
    
    
    /**
     * @test
     */
    public function anAuthenticatedUserCanCreateANewUser()
    {
        $usuario=[
                "nome"=>"Jose João",
                "email"=>"jose@joao.com.br",
                "cpf"=>"12345678911",
                "passwd"=>time(),
                "proxy"=>"jose.joao"
        ];
        $data = array("form_params"=>$this->credentials);
        $response = $this->client->request('POST', 'auth/api/authenticate', ($data));
        $data = json_decode($response->getBody(true));
        $data1 = array("form_params"=>$usuario,'headers'=>array('Authorization' => "Bearer ".$data->token));
        $response1 = $this->client->request('POST', 'auth/api/create-user', ($data1));
        $data1 = json_decode($response1->getBody(true));
        $this->assertObjectHasAttribute('token', $data1);
        $this->assertNotNull($data1->token);
    }
    
    
    /**
     * @test
     */
    public function validUserCanIssueANewToken()
    {
        $data = array("form_params"=>$this->credentials);
        $response = $this->client->request('POST', 'auth/api/authenticate', ($data));
        $data = json_decode($response->getBody(true));
        $response = $this->client->request('GET', 'auth/api/refresh-token', array('headers'=>array('Authorization' => "Bearer ".$data->token)));
        $data1 = (array) json_decode($response->getBody(true));
        $this->assertArrayHasKey('token', $data1);
    }
    
    
    /**
     * @test
     */
    public function shouldReturnPrivilegesOnValidToken()
    {
        $data = array("form_params"=>$this->credentials);
        $response = $this->client->request('POST', 'auth/api/authenticate', ($data));
        $data = json_decode($response->getBody(true));
        $response1 = $this->client->request('GET', 'auth/api/privileges', array('headers'=>array('Authorization' => "Bearer ".$data->token)));
        $data1 =  json_decode($response1->getBody(true));
        $this->assertObjectHasAttribute('contexto', $data1[0]);
        $this->assertObjectHasAttribute('modulo', $data1[0]);
        $this->assertObjectHasAttribute('perms', $data1[0]);
    }
    
    /**
     * @test
     */
    public function shouldReturnAnUserOnValidToken()
    {
        $data = array("form_params"=>$this->credentials);
        $response = $this->client->request('POST', 'auth/api/authenticate', ($data));
        $data = json_decode($response->getBody(true));
        $response = $this->client->request('GET', 'auth/api/user', array('headers'=>array('Authorization' => "Bearer ".$data->token)));
        $data1 = (array) json_decode($response->getBody(true));
        $this->assertArrayHasKey('nome', $data1);
        $this->assertEquals('EVANDRO ROQUE MOHR', $data1['nome']);
    }
    
    /**
     * @test
     */
    public function userCanAuthenticateAndIssueAToken()
    {
        $data = array("form_params"=>$this->credentials);
        $response = $this->client->request('POST', 'auth/api/authenticate', ($data));
        $data = (array) json_decode($response->getBody(true));
        $this->assertArrayHasKey('token', $data);
    }
    
    /**
     * @test
     */
    public function shouldReturnErrorWhenMissingCredentials()
    {
        $response = $this->client->request('POST', 'auth/api/authenticate');
        $data = (array) json_decode($response->getBody(true));
        $this->assertArrayHasKey('error', $data);
    }
    
    /**
     * @test
     */
    public function shouldNotReturnTokenOnInvalidUser()
    {
        $data = array(
                "form_params"=>array('user' => 'c',
                                     'passwd' => 'y'));
        $response = $this->client->request('POST', 'auth/api/authenticate', ($data));
        $data = (array) json_decode($response->getBody(true));
        $this->assertArrayNotHasKey('token', $data);
    }
    
    /**
     * @test
     */
    public function canVerifyAValidToken()
    {
        $data = array("form_params"=>$this->credentials);
        $response = $this->client->request('POST', 'auth/api/authenticate', ($data));
        $data = json_decode($response->getBody(true));
        $response = $this->client->request('GET', 'auth/api/verify', array('headers'=>array('Authorization' => "Bearer ".$data->token)));
                $data1 = (array) json_decode($response->getBody(true));
        $this->assertArrayHasKey('valid', $data1);
        $this->assertTrue((boolean) $data1['valid']);
    }
    
    /**
     * @test
     */
    public function shouldDenyRequestForInvalidToken()
    {
        $response = $this->client->request('GET', 'auth/api/verify', array('headers'=>array('Authorization' => "Bearer X")));
        $data1 = (array) json_decode($response->getBody(true));
        $this->assertArrayHasKey('error', $data1);
        $this->assertEquals('invalid token', $data1['error'], "Must be invalid");
    }
    
    /**
     * @test
     */
    public function throwErrorOnMissingToken()
    {
        $response = $this->client->request('GET', 'auth/api/verify');
        $data1 = (array) json_decode($response->getBody(true));
        $this->assertArrayHasKey('error', $data1);
    }
    
    /**
     * @test
     */
    public function apiShouldBeReacheable()
    {
        $response = $this->client->request('GET', 'auth/api/');
        $this->assertEquals(200, $response->getStatusCode(), "Server response shoud be 200");
    }
}
