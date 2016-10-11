<?php

namespace Auth\Model\Services;


use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\ValidationData;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Auth\Model\Entity\EntityBase;
use Auth\Model\Entity\Usuario;
use Auth\Model\Services\ITokenService;
use Auth\Model\Exception\InvalidTokenException;


/**
 * Service for token access and validation
 * @since 0.1.0
 * @author Evandro Mohr
 */
class TokenService implements ITokenService{
	
	//FIXME
	private $apiKey = "6BBB0DA1891646E58EB3E6A63AF3A6FC3C8EB5A0D44824CBA581D2E14A0450CF";
	
	/**
	 * Create a service for token access and validation
	 */
	public function __construct(){}

	/**
	 * @see \Auth\Model\Services\ITokenService::generateToken()
	 */
	public function generateToken(Usuario $usuario){
		$signer = new Sha256();
		
		$token = (new Builder())->setIssuer(TOKEN_ISSUER)
                        ->setAudience(TOKEN_AUDIENCE)
                        ->setIssuedAt(time())
                        ->setExpiration(time() + 8600)
                        ->set('nome', $usuario->nome)
                        ->set('token', $usuario->token)
                        ->sign( $signer, $this->apiKey)
                        ->getToken();
		return $token;
	}

	/**
	 * @see \Auth\Model\Services\ITokenService::isTokenValid()
	 */
	public function isTokenValid(){
		$token = $this->getToken();
		$signer = new Sha256();
		$data = new ValidationData();
		$data->setIssuer(TOKEN_ISSUER);
		$data->setAudience(TOKEN_AUDIENCE);
		return($token->validate($data) && $token->verify($signer, $this->apiKey));
	}
	
	/**
	 * @see \Auth\Model\Services\ITokenService::getToken()
	 */
	public function getToken(){
		try{
			$token = str_replace("Bearer ", "", $_SERVER['HTTP_AUTHORIZATION']);
			$token = (new Parser())->parse($token);
		} catch (\Exception $e){
			throw new InvalidTokenException("Token is not present, or doesn't have a valid format.");
		}
		return $token;
	}
	
	public function hasBearerToken(){
		return (isset($_SERVER['HTTP_AUTHORIZATION']) && (str_replace("Bearer ", "", $_SERVER['HTTP_AUTHORIZATION']) != ''));
	}
	
}

