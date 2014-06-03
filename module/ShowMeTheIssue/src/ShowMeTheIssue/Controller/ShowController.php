<?php
namespace ShowMeTheIssue\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Console\Request;
use HipChat\HipChat;
use Zend\Cache\StorageFactory;
use Zend\EventManager\Event;

class ShowController extends AbstractActionController
{

    private $cache;

    public function __construct()
    {
        $cache = StorageFactory::adapterFactory('filesystem');
        $cache->setOptions([
            'cache_dir' => 'data/cache'
        ]);
        $plugin = StorageFactory::pluginFactory('exception_handler', array(
            'throw_exceptions' => false
        ));
        $cache->addPlugin($plugin);
        
        
        $this->getEventManager()->attach('ISSUES_GET.pre', function (Event $e) use($cache)
        {
            $config = $e->getParams();
            $key = 'issues-' . $config['account-name'] . '-' . $config['repo'] . '-' . implode('-', $config['issue-filters']);
            
            $item = $cache->getItem($key);
            if ($item) {
                $e->stopPropagation();
                return unserialize($item);
            } 
        });
        
        $this->getEventManager()->attach('ISSUES_GET.post', function (Event $e) use($cache)
        {
            $config = $e->getParams();
            $key = 'issues-' . $config['account-name'] . '-' . $config['repo'] . '-' . implode('-', $config['issue-filters']);
            $cache->setItem($key, serialize($config['issues']));
        });
    }

    function processAction()
    {
        $request = $this->getRequest();
        
        if (! $request instanceof Request) {
            throw new \RuntimeException('You can only use this action from a console!');
        }
        
        $enableHipchat = $request->getParam('enable-hipchat');
        $addImage = $request->getParam('add-image', false);
        $defaultRoom = $request->getParam('hipchat-room');
        $verbose = $request->getParam('verbose');
        $filterRepo = $request->getParam('repo', false);
        
        $config = $this->getServiceLocator()->get('config')['show-me-the-issue'];
        
        foreach ($config['repo-mapping'] as $data) {
            try {
                if (isset($data['skip']) && $data['skip'] == true) {
                    continue;
                }
                if ($filterRepo !== false && $filterRepo !== $data['repo']) {
                    continue;
                }
                
                if ($this->getServiceLocator()->has($config['service-mapper'][$data['repo-type']])) {
                    $repoService = $this->getServiceLocator()->get($config['service-mapper'][$data['repo-type']]);
                } else {
                    if ($verbose) {
                        echo '***** No service declared for repository type: ' . $data['repo-type'] . PHP_EOL;
                    }
                    continue;
                }
                
                if ($verbose) {
                    echo '>>>> Getting issues from ' . ucfirst($data['repo-type']) . ' - ' . $data['repo'] . PHP_EOL;
                }
                
                // evento pre
                $result = $this->getEventManager()->trigger('ISSUES_GET.pre', $this, [
                    'account-name' => $data['account-name'],
                    'repo' => $data['repo'],
                    'issue-filters' => $data['issue-filters']
                ], function ($r)
                {
                    return is_array($r);
                });
                if ($result->stopped()) {
                    $issue_response = $result->last();
                } else {
                    $issue_response = $repoService->getIssuesFromRepo($data['account-name'], $data['repo'], $data['issue-filters']);
                }
                // evento post
                $result = $this->getEventManager()->trigger('ISSUES_GET.post', $this, [
                    'issues' => $issue_response,
                    'account-name' => $data['account-name'],
                    'repo' => $data['repo'],
                    'issue-filters' => $data['issue-filters']
                ]);
                
                $issue_msg = '<b>issue report from code repository: ' . $data['repo'] . '</b><br/>';
                if ($verbose) {
                    echo '   REPO: ' . $data['repo'] . PHP_EOL;
                }
                $issue_msg .= '<a href="' . $data['issue-tracker-link'] . '">Issue tracker</a><br/>';
                if (count($issue_response) == 0) {
                    $issue_msg .= '<b> NO ISSUES!!! Keep it up.</b>';
                    if ($addImage) {
                        $issue_msg .= '<br/><img src="' . $config['no-issue-images'][rand(0, count($config['no-issue-images']) - 1)] . '"/>';
                    }
                    if ($verbose) {
                        echo '       No issues' . PHP_EOL;
                    }
                } else {
                    $i = 1;
                    foreach ($issue_response as $issue) {
                        $issue_msg .= $issue->title . '<br/>';
                        if ($verbose) {
                            echo '       ' . $i . ' - ' . $issue . PHP_EOL;
                        }
                        $i ++;
                    }
                    if ($addImage) {
                        $issue_msg .= '<img src="' . $config['yes-issue-images'][rand(0, count($config['yes-issue-images']) - 1)] . '"/>';
                    }
                }
                
                if (isset($data['skip_if_no_issue']) && $data['skip_if_no_issue'] == true) {
                    if ($verbose) {
                        echo '       ** Skipping hipchat' . PHP_EOL;
                    }
                    continue;
                }
                
                // publish.pre event
                if ($enableHipchat) {
                    if ($verbose) {
                        echo '       ** Publishing to hipchat room: ' . $data['hipchat-room'] . PHP_EOL;
                    }
                    $hc = new HipChat($config['hipchat']['api-token']);
                    $hipchatRoom = $data['hipchat-room'];
                    if ($defaultRoom) {
                        $hipchatRoom = $defaultRoom;
                    }
                    if ($verbose) {
                        echo 'Publishing issues to bitbucket room - ' . $hipchatRoom . PHP_EOL;
                    }
                    $hc->message_room($hipchatRoom, 'Issues', $issue_msg);
                }
                // publish.post event
            } catch (\Exception $e) {
                throw $e;
                echo 'ERROR: ' . $e->getMessage() . PHP_EOL;
            }
        }
        
        return '--- DONE ---' . PHP_EOL;
    }
}
