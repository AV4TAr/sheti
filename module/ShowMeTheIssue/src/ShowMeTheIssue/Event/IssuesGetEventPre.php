<?php
namespace ShowMeTheIssue\Event;

use Zend\EventManager\Event;
/**
 * Fired before gettgin the issues from the Connector
 * @author diego
 *
 */
class IssuesGetEventPre extends Event
{
    protected $name = 'ISSUES_GET.pre';
}
