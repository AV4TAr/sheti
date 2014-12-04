<?php
namespace BitbucketConnector;

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
           'id' => $data['local_id'],
           'title' => $data['title'], 'created_at' => $data['utc_created_on'],
           'updated_at' => $data['utc_last_updated'],
           'kind' => 'issue',
           'priority' => '',
           'status' => $data['status'],
        ];

        $exchangeArray['description'] = '';
        if(isset($data['content'])){
            $exchangeArray['description'] = $data['content'];
        }

        $exchangeArray['asignee'] = '';
        if(isset($data['responsible'])) {
            $exchangeArray['asignee'] = $data['responsible']['first_name'] . ' ' . $data['responsible']['last_name'];
        }


        $object->exchangeArray($exchangeArray);
    }
}
