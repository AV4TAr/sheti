<?php
namespace GithubConnector\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use GithubConnector\GithubService;

class GithubController extends AbstractActionController
{

    public function indexAction()
    {
        $config = [];
        $githubService = new GithubService($config);
        echo "si";
        die();
    }
}
