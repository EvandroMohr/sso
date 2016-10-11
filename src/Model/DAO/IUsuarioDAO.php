<?php namespace Auth\Model\DAO;

use Auth\Model\DAO\IGenericDAO;
use Auth\Model\Entity\Modulo;
use Auth\Model\Entity\Usuario;

/**
 * Database access for User management
 * 
 * @since 0.1.0
 * @author Evandro Mohr
 */
interface IUsuarioDAO extends IGenericDAO{
	
	/**
	 * Obtém um usuário por meio de seu nome de usuário e sua senha.
	 * Útil para realizar autenticação.
	 * 
	 * @param string $username
	 * @param string $password
	 * @return Usuario retorna um usuário caso exista um com as caracteristicas passadas.
	 */
	public function getByUsernameAndPassword($username, $password);
	
	/**
	 * Cria uma regra de permissão especifica de acesso para um determinado usuário.
	 * Por padrão a regra é criada em branco. As devidas permissões devem ser configuradas posteriormente
	 * utilizando o método específico.
	 * 
	 * @see IUsuarioDAO::updatePermissoes();
	 * 
	 * @param Usuario $usuario
	 * @param Modulo $modulo
	 */
	public function addNewRule(Usuario $usuario, Modulo $modulo);
	
	/**
	 * Atualiza as permissoes do usuário. As permissões servem para garantir acesso específico por usuário
	 * 
	 * @param Usuario $usuario O usuário que irá possuir a permissão.
	 * @param Modulo $modulo Em qual módulo ela se aplicará.
	 * @param numeric $permissao as permissões concedidas sobre o módulo para o usuário.
	 */
	public function updatePermissoes(Usuario $usuario, Modulo $modulo, $permissoes);
	
	
	/**
	 * Return an Usuario object give its token
	 * @param string $token A user token
	 * @return Usuario
	 */
	public function getByToken($token);
	
	
	
}