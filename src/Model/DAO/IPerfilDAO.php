<?php namespace Auth\Model\DAO;

use Auth\Model\DAO\IGenericDAO;
use Auth\Model\Entity\Perfil;
use Auth\Model\Entity\Modulo;
use Auth\Model\Entity\Usuario;

/**
 * Interface for Database access for Permissions, Roles, Rules and Privileges
 * 
 * @since 0.1.0
 * @author Evandro Mohr
 */
interface IPerfilDAO extends IGenericDAO{
	
			
	/**
	 * Identifica os módulos que o perfil pode acessar com suas respectivas permissões de acesso.
	 * 
	 * @param $perfis Array de perfil.
	 * @return array Permissões que o perfil possui.
	 */
	public function getPrivilegesOfProfiles(Array $perfis);
			
	/**
	 * Cria uma regra de acesso sem permissões.
	 * 
	 * @param Perfil $perfil O perfil ao qual se deseja atribuir permissões.
	 * @param Modulo $modulo O módulo que se deseja permitir acesso pelo perfil.
	 */
	public function addNewRule(Perfil $perfil, Modulo $modulo);
	
	/**
	 * Atualiza os privilégios de um determinado perfil para um módulo específico.
	 * 
	 * @param Perfil $perfil O perfil que se deseja atribuir privilégios
	 * @param Modulo $modulo O módulo que será alvo dos privilégios
	 * @param numeric $privilegio O que o perfil pode realizar nesse módulo
	 */
	public function updatePrivilegios(Perfil $perfil, Modulo $modulo, $privilegio);
	
	public function getPrivilegesOfProfile(Perfil $perfil);
	
	public function getProfilesByUser(Usuario $user);
	
	public function getAvalilableList(Usuario $user);
	
	
	
}