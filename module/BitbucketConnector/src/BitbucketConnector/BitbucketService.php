<?php
namespace BitbucketConnector;

use ShowMeTheIssue\Repo\RepoInterface;
use Bitbucket\API\Repositories\Issues;
use Bitbucket\API\Http\Listener\OAuthListener;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use ShowMeTheIssue\Entity\Issue;

/**
 * Connects with Bitbucket
 * @author diego
 *
 */
class BitbucketService implements RepoInterface, ServiceLocatorAwareInterface
{
    use \Zend\ServiceManager\ServiceLocatorAwareTrait;

    protected $config;

    /**
     *
     * @var Issues
     */
    protected $issueConnector;

    /**
     * 
     * @param array $config
     * @throws \Exception
     */
    public function __construct(array $config)
    {
        if(!(isset($config['oauth']['oauth_consumer_key']) && $config['oauth']['oauth_consumer_key']!=NULL)){
            throw new \Exception('Bitbucket Connector need configuration');
        }
        if(!(isset($config['oauth']['oauth_consumer_secret']) && $config['oauth']['oauth_consumer_secret']!=NULL)){
            throw new \Exception('Bitbucket Connector need configuration');
        }
        
        $this->config = $config;
        $oauthListener = new OAuthListener($config['oauth']);
        $this->issueConnector = new Issues();
        $this->issueConnector->getClient()->addListener($oauthListener);
        
    }


    /**
     * (non-PHPdoc)
     * @see \ShowMeTheIssue\src\ShowMeTheIssue\Repo\RepoInterface::getIssues()
     * @return ShowMeTheIssue\Entity\Issue[]
     * @todo accept a configuration object.
     */
    public function getIssuesFromRepo($account = null, $repo = null, array $filter = null)
    {
        if($repo == null){
            throw new \InvalidArgumentException('No repo parameter specified.');
        }
        
        if($account == null){
            throw new \InvalidArgumentException('No account parameter specified for this repo: '.$repo);
        }
        $issues = json_decode($this->issueConnector->all($account, $repo, $filter)->getContent(), true);
        $issueList = [];
        $issueHydrator = new IssueHydrator();
        foreach($issues['issues'] as $issue){
           $issueObject = new Issue();
           $issueHydrator->hydrate($issue, $issueObject);
           $issueList[] = $issueObject;
        }
        return $issueList;
    }

}
