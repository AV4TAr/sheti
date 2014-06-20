<?php
namespace GithubConnector;

use ShowMeTheIssue\Entity\IssueAbstract;

/**
 *
 * @author diego
 *
 */
class IssueHydrator
{
    /**
     * (non-PHPdoc)
     * @see \Zend\Stdlib\Hydrator\ArraySerializable::extract()
     * @return array
     */
    public function extract($object)
    {
        $vars =  get_object_vars($object);

        return $object->getArrayCopy();

    }

    /**
     *  (non-PHPdoc)
     * @see \Zend\Stdlib\Hydrator\ArraySerializable::hydrate()
     * @param IssueAbstract $object
     */
    public function hydrate(array $data, $object)
    {
        $object->exchangeArray($data);
    }

}
