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
                
                
                if($this->getServiceLocator()->has($config['service-mapper'][$data['repo-type']])){
                    $repoService = $this->getServiceLocator()->get($config['service-mapper'][$data['repo-type']]);
                } else {
                    if($verbose){ echo '***** No service declared for repository type: '.$data['repo-type'].PHP_EOL; }
                    continue;
                }
                
                if($verbose){ echo '>>>> Getting issues from '.ucfirst($data['repo-type']).' - '.$data['repo'].PHP_EOL; }
                
                $issue_response = $repoService->getIssuesFromRepo($data['repo']);
                
                $issue_msg = '<b>issue report from code repository: '.$data['repo'].'</b><br/>';
                if($verbose){ echo '   REPO: '.$data['repo'].PHP_EOL; }
                $issue_msg .= '<a href="' . $data['issue-tracker-link'] . '">Issue tracker</a><br/>';
                if (count($issue_response) == 0) {
                    $issue_msg .= '<b> NO ISSUES!!! Keep it up.</b>';
                    if($addImage){
                        $issue_msg .= '<br/><img src="' . $config['no-issue-images'][rand(0, count($config['no-issue-images']) - 1)] . '"/>';
                    }
                    if($verbose){ echo '       No issues'.PHP_EOL; }
                } else {
                    $i = 1;
                    foreach ($issue_response as $issue) {
                        $issue_msg .= $issue->title . '<br/>';
                        if($verbose){ echo '       '.$i.' - '.$issue.PHP_EOL; }
                        $i++;
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
