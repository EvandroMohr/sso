<?php namespace Auth\Model\Entity;

use Auth\Model\Entity\EntityBase;

class Usuario extends EntityBase{
	
	public $nome;
	public $email;
	public $cpf;
	public $passwd;
	public $proxy;
	public $creation;
	public $update;
	public $token;
	public $ativo=FALSE;
	
}