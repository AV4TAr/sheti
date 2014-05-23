<?php
namespace GithubConnector;

use ShowMeTheIssue\Repo\RepoInterface;
use BitbucketConnector\IssueHydrator;
use ShowMeTheIssue\Entity\Issue;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Connects with Github
 * 
 * @author diego
 *        
 */
class GithubService implements RepoInterface
{
    use\Zend\ServiceManager\ServiceLocatorAwareTrait;

    protected $config;

    /**
     * 
     * @var \Github\HttpClient\CachedHttpClient
     */
    protected $client;
    
    /**
     * 
     * @param array $config
     * @param ServiceLocatorInterface $serviceLocator $serviceLocator
     * @throws \Exception
     */
    public function __construct(array $config, ServiceLocatorInterface $serviceLocator)
    {
        $this->config = $config;
        $this->setServiceLocator($serviceLocator);
        
        $client = new \Github\HttpClient\CachedHttpClient();
        $client->setCache(new \Github\HttpClient\Cache\FilesystemCache('./data/cache/github-api-cache'));
        
        if (! empty($config['user_connect']['username']) && ! empty($config['user_connect']['password'])) {
            $client->authenticate($config['user_connect']['username'], $config['user_connect']['password'], \Github\Client::AUTH_HTTP_PASSWORD);
        } elseif (! empty($config['oauth']['oauth_consumer_key']) && ! empty($config['oauth']['oauth_consumer_secret'])) {
            echo "no oauth";
            throw new \Exception('Oauth Not supported yet');
        } else {
            throw new \Exception('No configuration provided for Github connector');
        }
        try {
            $this->client = new \Github\Client($client);
            $this->getIssuesFromRepo();
        } catch (\Github\Exception\InvalidArgumentException $e) {
            die("Argumentos invalidos para la conexion");
        }
        
    }

    /**
     * (non-PHPdoc)
     * 
     * @see \ShowMeTheIssue\src\ShowMeTheIssue\Repo\RepoInterface::getIssues() 
     * @return ShowMeTheIssue\Entity\Issue[]
     * @todo inject hydrator
     * @todo inject IssueService
     */
    public function getIssuesFromRepo($repo = 'bim-analytics-backend', array $filter = ['state' => 'open'])
    {
        $issues = $this->client->api('issue')->all($this->config['account-name'], $repo, $filter);
        $issueList = [];
        $issueHydrator = new IssueHydrator();
        foreach ($issues as $issue) {
            $issueObject = new Issue();
            $issueHydrator->hydrate($issue, $issueObject);
            $issueList[] = $issueObject;
        }
        return $issueList;
    }
}