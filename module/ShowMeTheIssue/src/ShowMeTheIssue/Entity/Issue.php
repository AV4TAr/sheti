<?php
namespace ShowMeTheIssue\Entity;

use Zend\Stdlib\ArraySerializableInterface;
use Zend\Stdlib\JsonSerializable;

/**
 *
 * @author diego
 *
 */
class Issue implements ArraySerializableInterface, JsonSerializable
{

    public $id;

    public $title;

    public $description;

    public $asignee;

    public $created_at;

    public $updated_at;

    public $kind;

    public $priority;

    public $status;

    /*
     * (non-PHPdoc) @see \Zend\Stdlib\ArraySerializableInterface::exchangeArray()
     */
    public function exchangeArray(array $array)
    {
        $vars =  get_object_vars($this);
        foreach ($vars as $var => $v) {
            $this->{$var} = $array[$var];
        }
    }

    /*
     * (non-PHPdoc) @see \Zend\Stdlib\ArraySerializableInterface::getArrayCopy()
     */
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    public function jsonSerialize()
    {
        return $this->getArrayCopy();
    }

    public function __toString()
    {
        return $this->title;
    }
}
