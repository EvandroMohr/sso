<?php
namespace Auth\Model\Services;

use Lcobucci\JWT\Token;
use Auth\Model\Entity\Usuario;

/**
 * Interface for token access and validation
 * @since 0.1.0
 * @author Evandro Mohr
 */
interface ITokenService{
	
	/**
	 * Generate a new and valid token for Granted Access
	 * @param Usuario $usuario generate token based on Usuario information
	 * @return Token a JWT object
	 */
	public function generateToken(Usuario $usuario);
	
	/**
	 * Verifies if a request - with Authorization Bearer header - is valid
	 * @return boolean True if it's still valid and signature matches
	 */
	public function isTokenValid();
	
	/**
	 * Grab Authorization Bearer header
	 * @return Token A JWT object
	 */
	public function getToken();
}