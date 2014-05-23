<?php
namespace ShowMeTheIssue\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Console\Request;
use HipChat\HipChat;

class ShowController extends AbstractActionController
{

    public function processAction()
    {
        $request = $this->getRequest();
        
        if (! $request instanceof Request) {
            throw new \RuntimeException('You can only use this action from a console!');
        }
        
        $enableHipchat  = $request->getParam('enable-hipchat');
        $addImage       = $request->getParam('add-image', false);
        $defaultRoom    = $request->getParam('hipchat-room');
        $verbose        = $request->getParam('verbose');
        $filterRepo     = $request->getParam('repo',false);

        $config = $this->getServiceLocator()->get('config')['show-me-the-issue'];

        
        
        foreach ($config['repo-mapping'] as $data) {
            try {
                if (isset($data['skip']) && $data['skip'] == true) {
                    continue;
                }
                if($filterRepo !== false && $filterRepo !== $data['repo']){
                    continue;
                }
                
                if($verbose){ echo '>>>> Getting issues from Bitbucket - '.$data['repo'].PHP_EOL; } 
                
//                $bitbucketService = $this->getServiceLocator()->get('BitbucketService');
                $bitbucketService = $this->getServiceLocator()->get($config['service-mapper'][$data['repo']]);
                $issue_response = $bitbucketService->getIssuesFromRepo($data['repo']);
                
                $issue_msg = '<b>issue report from code repository: '.$data['repo'].'</b><br/>';
                if($verbose){ echo '***** REPO: '.$data['repo'].PHP_EOL; }
                $issue_msg .= '<a href="' . $data['issue-tracker-link'] . '">Issue tracker</a><br/>';
                if (count($issue_response) == 0) {
                    $issue_msg .= '<b> NO ISSUES!!! Keep it up.</b>';
                    if($addImage){
                        $issue_msg .= '<br/><img src="' . $config['no-issue-images'][rand(0, count($config['no-issue-images']) - 1)] . '"/>';
                    }
                    if($verbose){ echo '    No issues'.PHP_EOL; }
                } else {
                    foreach ($issue_response as $issue) {
                        $issue_msg .= $issue->title . '<br/>';
                        if($verbose){ echo '    '.$issue.PHP_EOL; }
                    }
                    if($addImage){
                        $issue_msg .= '<img src="' . $config['yes-issue-images'][rand(0, count($config['yes-issue-images']) - 1)] . '"/>';
                    }
                }
                
                if ($enableHipchat) {
                    $hc = new HipChat($config['hipchat']['api-token']);
                    $hipchatRoom = $data['hipchat-room'];
                    if ($defaultRoom) {
                        $hipchatRoom = $defaultRoom;
                    }
                    if($verbose){ echo 'Publishing issues to bitbucket room - '.$hipchatRoom.PHP_EOL; }
                    $hc->message_room($hipchatRoom, 'Issues', $issue_msg);
                } 
    
                //echo nl2br($issue_msg);
                //echo "<br>-------------------------------------------------\r";
            } catch (\Exception $e){
                echo $e->getMessage();
            }
        }
        
        return '--- DONE ---'.PHP_EOL;
    }
}
