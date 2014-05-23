<?php
namespace BitbucketConnector;

use Zend\Stdlib\Hydrator\ArraySerializable;
use ShowMeTheIssue\src\ShowMeTheIssue\Entity\Issue;
/**
 *
 * @author diego
 *        
 */
class IssueHydrator extends ArraySerializable
{
	/* (non-PHPdoc)
     * @see \Zend\Stdlib\Hydrator\ArraySerializable::extract()
     */
    public function extract($object)
    {
        $vars =  get_object_vars($object);
        return $vars;
        
    }

	/* (non-PHPdoc)
     * @see \Zend\Stdlib\Hydrator\ArraySerializable::hydrate()
     */
    public function hydrate(array $data, $object)
    {
        $object->title = $data['title'];
        $object->description = $data['content'];
        $object->asignee = $data['responsible']['display_name'];
        $object->created_at = $data['utc_created_on'];
        $object->updated_at = $data['utc_last_updated'];
        $object->status = $data['status'];
        $object->kind = $data['metadata']['kind'];
        $object->priority = $data['priority'];
    }

}
