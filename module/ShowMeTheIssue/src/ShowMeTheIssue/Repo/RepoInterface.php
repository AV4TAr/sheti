<?php
namespace ShowMeTheIssue\Repo;

/**
 *
 * @author diego
 *        
 */
interface RepoInterface
{
    public function getIssuesFromRepo($account = null, $repo = null, array $filter = []);
}
