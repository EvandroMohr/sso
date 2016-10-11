<?php namespace Auth\Model\DAO;

use Auth\Model\Entity\EntityBase;

/**
 * Generic persistence interface. The entity must extends EntityBase.
 * 
 * @since 0.1.0
 * @author Evandro Mohr
 * @see EntityBase
 */
interface IGenericDAO {
	
	
	/**
	 * Get an object based on its UID
	 * @param int object UID
	 * @return EntityBase object
	 */
	public function getById($uid);
	
	/**
	 * Get a Collection of objects
	 * 
	 * @param string $sortColumn (optional) The column to sort.
	 * @param string $sortOrder (optional) ASC|DESC sort direction.
	 * @param number $limit (optional) Max number of returned objects.
	 * @param number $offset (optional) Start after this point.
	 * 
	 * @return mixed List of EntityBase
	 */
	public function getList($sortColumn=null, $sortOrder = 'ASC', $limit = 1000000, $offset = 0);
	
	/**
	 * Remove object from database
	 * @param int object UID
	 */
	public function delete($uid);
	
	/**
	 * Insert object into database
	 * @param EntityBase 
	 */
	public function insert(EntityBase $entidade);
	
	/**
	 * Update an object in the database
	 * @param EntityBase 
	 */
	public function update(EntityBase $entidade);
	
	
}