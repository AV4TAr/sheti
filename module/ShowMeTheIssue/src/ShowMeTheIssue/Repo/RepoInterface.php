<?php
namespace ShowMeTheIssue\Repo;

/**
 *
 * @author diego
 *        
 */
interface RepoInterface
{
    public function getIssuesFromRepo($repo, array $filter);
}
