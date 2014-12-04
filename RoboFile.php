<?php

class RoboFile extends \Robo\Tasks
{

    public function watch()
    {
        $this->taskWatch()
            ->monitor('module', function () {
            $this->taskExec('./runtests.sh')
                ->run();
        })
            ->run();
    }
}
