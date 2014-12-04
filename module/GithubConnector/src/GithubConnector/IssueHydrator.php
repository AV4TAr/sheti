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
        $exchangeArray = [
            'id' => $data['id'],
            'title' => $data['title'],
            'created_at' => $data['created_at'],
            'updated_at' => $data['updated_at'],
            'kind' => 'issue',
            'priority' => '',
            'status' => $data['state'],
        ];

        $exchangeArray['description'] = '';
        if(isset($data['content'])){
            $exchangeArray['description'] = $data['description'];
        }
        $data['asignee'] = '';
        if(isset($data['asignee'])){
            $exchangeArray['asignee'] = $data['asignee'];
        }
        $object->exchangeArray($exchangeArray);
    }
}
