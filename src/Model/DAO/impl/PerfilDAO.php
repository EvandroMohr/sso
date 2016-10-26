<?php namespace Auth\Model\DAO\impl;

use Auth\Model\DAO\IGenericDAO;
use Auth\Model\DAO\IPerfilDAO;
use Auth\Model\Entity\Perfil;
use Auth\Model\Entity\Modulo;
use Auth\Model\Entity\Usuario;

use PDO;

/**
 * Database access for Permissions, Roles, Rules and Privileges
 *
 * @since 0.1.0
 * @author Evandro Mohr
 *
 */
class PerfilDAO extends GenericDAO implements IPerfilDAO
{
    
    
    public function __construct()
    {
        parent::__construct('uth\Model\Entity\Perfil');
    }
    
    public function getPrivilegesOfProfiles(Array $perfis)
    {
        if (!isset($perfis) || $perfis == null || $perfis == '') {
            return null;
        }
        $ids;
        foreach ($perfis as $perfil) {
            $ids[] = $perfil['uid'];
        }
        $ids = implode(',', $ids);
        $connection = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $get_privileges = "SELECT mod.descricao as modulo,																																																																																																																																																																																																																																																																																						
								sis.nome as contexto,
	        					permissao as perms 
	        				FROM " . SCHEMA . ".privilegio pri
	        				join " . SCHEMA . ".modulo mod ON pri.modulo = mod.uid
	        				join " . SCHEMA . ".perfil per on pri.perfil = per.uid
	        				join " . SCHEMA . ".sistema sis on per.sistema = sis.uid
	
							WHERE per.uid in (".($ids).")" ;
        $st = $connection->prepare($get_privileges);
        $st->execute();
        $st->setFetchMode(PDO::FETCH_ASSOC);
        
        $list = array();
        while ($row = $st->fetch()) {
            $list[] = $row;
        }
        $connection = null;
        if ($list) {
            return $list;
        }
    }
    
    public function getPrivilegesByUser(Usuario $user)
    {
        $perfisDoUsuario = $this->getProfilesByUser($user);
        return $this->getPrivilegesOfProfiles($perfisDoUsuario);
    }
    
    public function getPrivilegesOfProfile(Perfil $perfil)
    {
        $connection = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $get_privileges = "SELECT mod.descricao as modulo,
								sis.nome as contexto,
	        					permissao as perms
	        				FROM " . SCHEMA . ".privilegio pri
	        				join " . SCHEMA . ".modulo mod ON pri.modulo = mod.uid
	        				join " . SCHEMA . ".perfil per on pri.perfil = per.uid
	        				join " . SCHEMA . ".sistema sis on per.sistema = sis.uid
	
							WHERE per.uid = :perfil" ;
        $st = $connection->prepare($get_privileges);
        $st->bindValue("perfil", $perfil->uid, PDO::PARAM_INT);
        $st->execute();
        $st->setFetchMode(PDO::FETCH_ASSOC);
    
        $list = array();
        while ($row = $st->fetch()) {
            $list[] = $row;
        }
        if ($list) {
            return $list;
        }
    }
    
    public function getProfilesByUser(Usuario $user)
    {
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $query = "select per.* from ".SCHEMA.".role role
						join ".SCHEMA.".perfil per on role.perfil = per.uid
						 where usuario = :usuario";
        $st = $conn->prepare($query);
        $st->setFetchMode(PDO::FETCH_CLASS, $this->type);
        $st->bindValue(":usuario", $user->uid, PDO::PARAM_INT);
        $st->execute();
        $list = $st->fetchAll();
        $connection = null;
        return $list;
    }
    
    public function getAvalilableList(Usuario $user)
    {
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $query = "select * from ".SCHEMA.".perfil where uid not in(select per.uid from ".SCHEMA.".role role
						join ".SCHEMA.".perfil per on role.perfil = per.uid
						 where usuario = :usuario)";
        $st = $conn->prepare($query);
        $st->setFetchMode(PDO::FETCH_CLASS, $this->type);
        $st->bindValue(":usuario", $user->uid, PDO::PARAM_INT);
        $st->execute();
        $list = $st->fetchAll();
        $connection = null;
        return $list;
    }
    
    
    public function addNewRule(Perfil $perfil, Modulo $modulo)
    {
        $connection = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql  = " INSERT INTO "  . SCHEMA . ".privilegio(perfil, modulo, permissao) values (:perfil, :modulo, 0);";
        $st = $connection->prepare($sql);
        $st->bindValue("perfil", $perfil->uid, PDO::PARAM_INT);
        $st->bindValue("modulo", $modulo->uid, PDO::PARAM_INT);
        $st->execute();
        $connection = null;
    }
    
    public function updatePrivilegios(Perfil $perfil, Modulo $modulo, $privilegio)
    {
        $connection = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql  = " UPDATE "  . SCHEMA . ".privilegio set permissao = :privilegio where perfil = :perfil and modulo = :modulo;";
        $st = $connection->prepare($sql);
        $st->bindValue("privilegio", $privilegio, PDO::PARAM_STR);
        $st->bindValue("perfil", $perfil->uid, PDO::PARAM_INT);
        $st->bindValue("modulo", $modulo->uid, PDO::PARAM_INT);
        $st->execute();
        $connection = null;
    }
}
