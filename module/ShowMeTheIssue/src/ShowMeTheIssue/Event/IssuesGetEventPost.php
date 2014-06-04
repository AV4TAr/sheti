<?php
namespace ShowMeTheIssue\Event;

use Zend\EventManager\Event;
/**
 * Fired after issues where get from the connector
 * @author diego
 *
 */
class IssuesGetEventPost extends Event
{
    protected $name = 'ISSUES_GET.post';
}
