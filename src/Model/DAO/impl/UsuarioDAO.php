<?php namespace Auth\Model\DAO\impl;

use PDO;

use Auth\Model\DAO\impl\GenericDAO;
use Auth\Model\DAO\IUsuarioDAO;
use Auth\Model\Entity\EntityBase;
use Auth\Model\Entity\Usuario;
use Auth\Model\Entity\Modulo;
use Auth\Model\Util\StringUtils;

/**
 * Database access for User management
 * 
 * @since 0.1.0
 * @author Evandro Mohr
 */
class UsuarioDAO extends GenericDAO implements IUsuarioDAO {
	
	public function __construct(){
		parent::__construct('Auth\Model\Entity\Usuario');
	}
	
	public function getByUsernameAndPassword($username, $password){
		$conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
		$get_user = "SELECT * FROM ". SCHEMA . ".usuario  WHERE cpf = :userName and passwd = :passwd";
		$st = $conn->prepare( $get_user );
		$st->setFetchMode(PDO::FETCH_CLASS, $this->type);
		$st->bindValue( ":userName", str_replace(array(".","-"), "", $username), PDO::PARAM_STR );
		$st->bindValue( ":passwd", strtoupper(sha1($password)), PDO::PARAM_STR );
		$st->execute();
		$user = $st->fetch();
		$conn = null;
		if ( $user ) return $user;
	}
	
	public function insert(EntityBase $usuario){
		$usuario->passwd = strtoupper(sha1($usuario->passwd));
		$usuario->token = StringUtils::generateRandomString();
		$usuario->creation = date('Y-m-d H:i:s');
		$usuario->cpf = str_replace(array(".","-"), '', $usuario->cpf);
		$usuario->ativo = true;
		return parent::insert($usuario);
	}
	
	public function update(EntityBase $usuario){
		$usuarioDB = parent::getById($usuario->uid);
		unset($usuario->token);// $usuarioDB->token;
		unset($usuario->creation);
		$usuario->update = date('Y-m-d H:i:s');
		$usuario->cpf = str_replace(array(".","-"), '', $usuario->cpf);
		if($usuario->passwd == '') $usuario->passwd = $usuarioDB->passwd;
		if($usuarioDB->passwd != $usuario->passwd){
			$usuario->passwd = strtoupper(sha1($usuario->passwd));
		}
		parent::update($usuario);
	}
	
	public function addNewRule(Usuario $usuario, Modulo $modulo){
		$connection = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD);
		$sql  = " INSERT INTO "  . SCHEMA . ".permissao (usuario, modulo, permissao) values (:usuario, :modulo, 0);";
		$st = $connection->prepare( $sql );
		$st->bindValue("usuario", $usuario->uid, PDO::PARAM_INT);		
		$st->bindValue("modulo", $modulo->uid, PDO::PARAM_INT);
		$st->execute();
		$connection = null;
	}
	
	public function addNewRole(Usuario $usuario, Perfil $perfil){
		$connection = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD);
		$sql  = " INSERT INTO "  . SCHEMA . ".role (usuario, perfil) values (:usuario, :perfil);";
		$st = $connection->prepare( $sql );
		$st->bindValue("usuario", $usuario->uid, PDO::PARAM_INT);
		$st->bindValue("perfil", $perfil->uid, PDO::PARAM_INT);
		$st->execute();
		$connection = null;
	}
	
	public function unsetRole(Usuario $usuario, Perfil $perfil){
		$connection = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD);
		$sql  = " DELETE FROM "  . SCHEMA . ".role where usuario = :usuario and perfil = :perfil;";
		$st = $connection->prepare( $sql );
		$st->bindValue("usuario", $usuario->uid, PDO::PARAM_INT);
		$st->bindValue("perfil", $perfil->uid, PDO::PARAM_INT);
		$st->execute();
		$connection = null;
	}
	
	public function updatePermissoes(Usuario $usuario, Modulo $modulo, $permissoes){
		$connection = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD);
		$sql  = " UPDATE "  . SCHEMA . ".permissao set permissao = :permissao where usuario = :usuario and modulo = :modulo;";
		$st = $connection->prepare( $sql );
		$st->bindValue("permissao", $permissoes, PDO::PARAM_INT);
		$st->bindValue("usuario", $usuario->uid, PDO::PARAM_INT);
		$st->bindValue("modulo", $modulo->uid, PDO::PARAM_INT);
		$st->execute();
		$connection = null;
	}
		
	public function getByToken($token){
		$conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
		$get_user = "SELECT * FROM ". SCHEMA . ".usuario  WHERE token = :token";
		$st = $conn->prepare( $get_user );
		$st->setFetchMode(PDO::FETCH_CLASS, $this->type);
		$st->bindValue( ":token", $token, PDO::PARAM_STR );
		$st->execute();
		$user = $st->fetch();
		$conn = null;
		if ( $user ) return $user;
	}
	
	
	public function getByCPF($cpf){
		$conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
		$get_user = "SELECT * FROM ". SCHEMA . ".usuario  WHERE cpf = :cpf";
		$st = $conn->prepare( $get_user );
		$st->setFetchMode(PDO::FETCH_CLASS, $this->type);
		$st->bindValue( ":cpf", $cpf, PDO::PARAM_STR );
		$st->execute();
		$user = $st->fetch();
		$conn = null;
		if ( $user ) return $user;
	}
	
	
}
