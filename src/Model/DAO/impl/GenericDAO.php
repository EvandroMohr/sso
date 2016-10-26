<?php namespace Auth\Model\DAO\impl;

use PDO;
use PDOStatement;
use ReflectionClass;

use Auth\Model\DAO\IGenericDAO;
use Auth\Model\Entity\EntityBase;

/**
 * Postgres persistence implementation for IGenericDAO
 * @since 0.1.0
 * @author Evandro Mohr
 */
class GenericDAO implements IGenericDAO
{

    protected $type;

    /**
     * @param EntityBase object
     */
    public function __construct($type)
    {
        $this->type = $type;
    }

    /**
     * Creates a new object
     * @return Object an EntityBase child
     */
    public function create()
    {
        return new $this->type();
    }

    /**
     * @see \Auth\Model\DAO\IGenericDAO::getById()
     */
    public function getById($uid)
    {
        $reflect = new ReflectionClass($this->type);
        $connection = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION  ));
        $sql  = " SELECT * FROM "  . SCHEMA . '.' . strtolower($reflect->getShortName()) ." where uid = :uid";
        $st = $connection->prepare($sql);
        $st->bindValue(":uid", $uid, PDO::PARAM_INT);
        $st->setFetchMode(PDO::FETCH_CLASS, $this->type);
        $st->execute();
        $object = $st->fetch();
        $connection = null;
        return $object;
    }

    /**
     * @see IGenericDAO::getList()
     */
    public function getList($sortColumn = null, $sortOrder = 'ASC', $limit = 1000000, $offset = 0)
    {
        $reflect = new ReflectionClass($this->type);
        $connection = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION  ));
        $sql  = " SELECT * FROM "  . SCHEMA . '.' . strtolower($reflect->getShortName()) . ($sortColumn != null ? " ORDER BY $sortColumn $sortOrder " : '') . " LIMIT :limit OFFSET :offset; ";
        $st = $connection->prepare($sql);
        $st->bindValue(":limit", $limit, PDO::PARAM_INT);
        $st->bindValue(":offset", $offset, PDO::PARAM_INT);
        $st->setFetchMode(PDO::FETCH_CLASS, $this->type);
        $st->execute();
        $list = $st->fetchAll();
        $connection = null;
        return $list;
    }

    /**
     * @see IGenericDAO::insert();
     */
    public function insert(EntityBase $object)
    {
        $connection = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD, array( PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION  ));
        $attributos = (array) $object;
        $parametros = array();
        $reflect = new ReflectionClass($this->type);
        foreach ($attributos as $k => $v) {
            if ($k != 'uid' && ($v != '')) {
                $parametros[] = ":$k";
            } else {
                unset($attributos[$k]);
            }
        }
        $queryParams = "(".implode(", ", array_keys($attributos)).") VALUES(". implode(', ', $parametros) ." )";
        try {
            $sql  = " INSERT INTO "  . SCHEMA . '.' . strtolower($reflect->getShortName()) . "$queryParams RETURNING uid;";
            $st = $connection->prepare($sql);
            $this->bindArrayValue($st, $attributos);
            $st->execute();
            $result = $st->fetch(PDO::FETCH_ASSOC);
            $uid = $result['uid'];
            //var_dump($uid);
            $connection = null;
        } catch (Exception $e) {
            // FIXME tratar a exceção adequadamente
            die(print_r($e));
            throw new RelacionamentoException("Existem relacionamentos vinculados à esse objeto. Impossível excluir.");
            $connection = null;
        }
        return $uid;
    }


    /**
     * @see \Auth\Model\DAO\IGenericDAO::update()
     */
    public function update(EntityBase $object)
    {
        $reflect = new ReflectionClass($this->type);
        $connection = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD, array( PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION ));
        $attributos = (array) $object;
        $parametros = array();
        foreach ($attributos as $k => $v) {
            if ($k != 'uid' && ($v != '')) {
                $parametros[] = "$k = :$k";
            } else {
                unset($attributos[$k]);
            }
        }
        $queryParams = implode(', ', $parametros);

        try {
            $sql = "UPDATE " . SCHEMA . '.' . strtolower($reflect->getShortName()) . " SET " . $queryParams . ' WHERE uid = :uid;';
            $st = $connection->prepare($sql);
            $st->bindValue(":uid", $object->uid, PDO::PARAM_INT);
            $this->bindArrayValue($st, $attributos);
            $st->execute();
            $connection = null;
        } catch (Exception $e) {
            // FIXME tratar a exceção adequadamente
            die(print_r($e));
            throw new RelacionamentoException("Existem relacionamentos vinculados à esse objeto. Impossível excluir.");
            $connection = null;
        }
    }

    /**
     * @see \Auth\Model\DAO\IGenericDAO::delete()
     */
    public function delete($uid)
    {
        $reflect = new ReflectionClass($this->type);
        if (is_null($uid)) {
            throw new Exception("Erro ao remover registro.");
        }
        try {
            $connection = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION ));
            $sql  = " DELETE FROM "  . SCHEMA . '.' . strtolower($reflect->getShortName()) . " where uid = :uid";
            $st = $connection->prepare($sql);
            $st->bindValue(":uid", $uid, PDO::PARAM_INT);
            $st->execute();
            $connection = null;
        } catch (Exception $e) {
            // FIXME trataR a exceção adequadamente
            die(print_r($e));
            throw new RelacionamentoException("Existem relacionamentos vinculados à esse objeto. Impossível excluir.");
            $connection = null;
        }
    }


    /**
     * @param string $req : the query on which link the values
     * @param array $array : associative array containing the values ​​to bind
     * @param array $typeArray : associative array with the desired value for its corresponding key in $array
     */
    private function bindArrayValue($query, $array, $typeArray = false)
    {
        if (is_object($query) && ($query instanceof PDOStatement)) {
            foreach ($array as $key => $value) {
                if ($typeArray) {
                    $query->bindValue(":$key", $value, $typeArray[$key]);
                } else {
                    $valor = $value;
                    if (is_int($valor)) {
                        $param = PDO::PARAM_INT;
                    } elseif (is_bool($valor))
                        $param = PDO::PARAM_BOOL;
                    elseif (is_null($valor))
                        $param = PDO::PARAM_NULL;
                    elseif (is_string($valor))
                        $param = PDO::PARAM_STR;
                    else {
                        $param = false;
                    }

                    if ($param) {
                        $query->bindValue(":$key", $valor, $param);
                    }
                }
            }
        }
    }
}
