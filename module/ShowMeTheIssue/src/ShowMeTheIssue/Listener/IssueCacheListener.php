<?php
namespace ShowMeTheIssue\Listener;

use Zend\EventManager\EventInterface;
use Zend\EventManager\SharedListenerAggregateInterface;
use Zend\EventManager\SharedEventManagerInterface;

/**
 *
 * @author diego
 *
 */
class IssueCacheListener implements SharedListenerAggregateInterface
{

    /**
     *
     * @var \Zend\Stdlib\CallbackHandler[]
     */
    protected $listeners = array();

    protected $cache;
    protected $log;

    public function __construct($cache, $log)
    {
        $this->cache = $cache;
        $this->log = $log;
    }
    public function attachShared(SharedEventManagerInterface $events)
    {
        $this->listeners[] = $events->attach('ShowMeTheIssue\Controller\ShowController', 'ISSUES_GET.pre', [$this,'onIssueGetPre'], 500);
        $this->listeners[] = $events->attach('ShowMeTheIssue\Controller\ShowController', 'ISSUES_GET.post', [$this,'onIssueGetPost'], 500);
    }

    public function onIssueGetPre(EventInterface $e)
    {
        $config = $e->getParams();
        $key = 'issues-' . $config['account-name'] . '-' . $config['repo'] . '-' . implode('-', $config['issue-filters']);

        $item = $this->cache->getItem($key);
        if ($item) {
            $this->log->debug('ISSUES_GET.pre - cache hit');

            return $item;
        } else {
            $this->log->debug('ISSUES_GET.pre - cache miss');
        }
    }

    public function onIssueGetPost(EventInterface $e)
    {
        $this->log->debug('ISSUES_GET.post - store in cache');
        $config = $e->getParams();
        $key = 'issues-' . $config['account-name'] . '-' . $config['repo'] . '-' . implode('-', $config['issue-filters']);
        $this->cache->setItem($key, $config['issues']);
    }

    public function detachShared(SharedEventManagerInterface $events)
    {
        foreach ($this->listeners as $index => $callback) {
            if ($events->dettach($callback)) {
                unset($this->listeners[$index]);
            }
        }
    }
}
