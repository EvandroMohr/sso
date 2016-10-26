<?php namespace Auth\Model\Factory;

use Auth\Model\DAO;
use Auth\Model\DAO\impl\GenericDAO;
use Auth\Model\DAO\impl\UsuarioDAO;
use Auth\Model\DAO\impl\PerfilDAO;
use Auth\Model\Entity\EntityBase;
use Auth\Model\Entity\User;

/**
 * Simple factory for dependency injection
 *
 * @since 0.1.0
 * @author Evandro Mohr
 */
class AppFactory
{
    private static $_instance;

    public function __construct()
    {
    }

    /**
     * Set the factory instance
     * @param App_DaoFactory $f
     */
    public static function setFactory(AppDaoFactory $f)
    {
        self::$_instance = $f;
    }

    /**
     * Get a factory instance.
     * @return App_DaoFactory
     */
    public static function getFactory()
    {
        if (!self::$_instance) {
            self::$_instance = new self;
        }

        return self::$_instance;
    }
    
    public static function getRepository(EntityBase $objeto)
    {
        try {
            $class = get_class($objeto);
            $repository = $class.'DAO';
            if (!file_exists($repository)) {
                return new GenericDAO($class);
            }
            return new $repository;
        } catch (Exception $e) {
            die($e->getTrace());
        }
    }
    
    public static function getUsuarioDAO()
    {
        return new UsuarioDAO();
    }
    public static function getPerfilDAO()
    {
        return new PerfilDAO();
    }
}
