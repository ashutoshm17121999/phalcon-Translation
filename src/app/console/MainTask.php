<?php

// declare(strict_types=1);

namespace App\Console;

use Phalcon\Cli\Task;

class MainTask extends Task
{
    public function mainAction()
    {
        echo 'This is the default task and default action' . PHP_EOL;
    }
}
