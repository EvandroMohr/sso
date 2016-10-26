<?php namespace Auth\Model\Services;

use Auth\Model\Services\IAuthenticationService;
use Auth\Model\Factory\AppFactory;
use Auth\Model\Services\TokenService;
use Auth\Model\DAO\IUsuarioDAO;
use Auth\Model\DAO\IPerfilDAO;

/**
 * Service facade for model access
 * @since 0.1.0
 * @author Evandro Mohr
 */
class AuthenticationService implements IAuthenticationService
{
    
    protected $tokenService,$userDAO,$perfilDAO;
    
    public function __construct(ITokenService $tokenService = null, IUsuarioDAO $usuarioDAO = null, IPerfilDAO $perfilDAO = null)
    {
        $this->tokenService = $tokenService ? : new TokenService();
        $this->userDAO = $usuarioDAO ? : AppFactory::getUsuarioDAO();
        $this->perfilDAO = $perfilDAO ? : AppFactory::getPerfilDAO();
    }
    
    /**
     * @see \Auth\Model\Services\IAuthenticationService::setUserDAO()
     */
    public function setUserDAO(IUsuarioDAO $userDAO)
    {
        $this->userDAO = $userDAO;
    }
    
    /**
     * @see \Auth\Model\Services\IAuthenticationService::authenticate()
     */
    public function authenticate($username, $passwd)
    {
        $user = $this->userDAO->getByUsernameAndPassword($username, $passwd);
        if (!isset($user)) {
            throw new \Exception("invalid user");
        }
        return $this->tokenService->generateToken($user);
    }
    
    /**
     * @see \Auth\Model\Services\IAuthenticationService::verify()
     */
    public function verify()
    {
        return $this->tokenService->isTokenValid();
    }
    
    /**
     * @see \Auth\Model\Services\IAuthenticationService::refreshToken()
     */
    public function refreshToken()
    {
        $usuario = $this->getUser();
        return $this->tokenService->generateToken($usuario);
    }
    
    /**
     * @see \Auth\Model\Services\IAuthenticationService::createUser()
     */
    public function createUser($userData)
    {
        $usuario = $this->userDAO->create();
        $usuario->storeFormValues($userData);
        $uid = $this->userDAO->insert($usuario);
        return $this->userDAO->getById($uid);
    }
    
    /**
     * @see \Auth\Model\Services\IAuthenticationService::getUser()
     */
    public function getUser()
    {
        $token = $this->tokenService->getToken();
        $user_token = $token->getClaim('token');
        $usuario = $this->userDAO->getByToken($user_token);
        if ($usuario != '' && $usuario != null) {
            unset($usuario->passwd);
            unset($usuario->uid);
            return $usuario;
        }
    }
    
    /**
     * @see \Auth\Model\Services\IAuthenticationService::getPrivileges()
     */
    public function getPrivileges($context = null)
    {
        $token = $this->tokenService->getToken();
        $user_token = $token->getClaim('token');
        $user = $this->userDAO->getByToken($user_token);
        $privileges = $this->perfilDAO->getPrivilegesByUser($user);
        if ($context == null) {
            return $privileges;
        }
        $result = array();
        foreach ($privileges as $privilege) {
            if (strtolower($privilege['contexto']) == strtolower($context)) {
                $result[] = $privilege;
            }
        }
        return $result;
    }
}
