<?php
namespace ShowMeTheIssue\Entity;

/**
 *
 * @author diego
 *        
 */
class IssueAbstract
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
    
    public function __toString()
    {
        return $this->title;
    }
}
