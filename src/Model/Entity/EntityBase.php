<?php
namespace Auth\Model\Entity;

/**
 * Entity base for generic persistence
 * @since 0.1.0
 * @author Evandro Mohr
 */
class EntityBase
{
    public $uid;

    public function storeFormValues($params)
    {
        foreach ($params as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = htmlspecialchars($value);
            }
        }
    }
}
