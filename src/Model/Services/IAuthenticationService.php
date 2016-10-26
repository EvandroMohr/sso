<?php namespace Auth\Model\Services;

use Auth\Model\DAO\IUsuarioDAO;
use Auth\Model\Exception\InvalidTokenException;
use Lcobucci\JWT\Token;
use Auth\Model\Entity\Usuario;

/**
 * API façade
 * @since 0.1.0
 * @author Evandro Mohr
 */
interface IAuthenticationService
{
    
    /**
     * Dependency injection
     * @param IUsuarioDAO $userDAO
     */
    public function setUserDAO(IUsuarioDAO $userDAO);
    
    /**
     * Authenticate an user give username and password
     * @param string $username
     * @param string $passwd
     * @return Token on valid user
     */
    public function authenticate($username, $passwd);
    
    /**
     * Verifies if Bearer token is valid
     * @return boolean true if is valid format and still active
     * @throws InvalidTokenException if token is invalid
     */
    public function verify();
    
    /**
     * @return Token a new one if the previous is still valid
     * @throws InvalidTokenException if token is invalid
     */
    public function refreshToken();
    
    /**
     * Creates a new user in SSO
     * @param mixed $userData
     * @return Usuario object
     */
    public function createUser($userData);
    
    /**
     * Get user based on Bearer user token claims
     * @return Usuario
     */
    public function getUser();
    
    /**
     * Get privileges based on Bearer user token claims
     * @return mixed user privileges
     */
    public function getPrivileges();
}
