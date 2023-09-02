<?php

namespace Lcarr\CommandRunner\Commands;

use GuzzleHttp\Client;
use Lcarr\CommandRunner\Modules\Test;

class TestCommand extends Command
{
    private string $name = 'test';

    public function __construct(private Client $client, private Test $test)
    {}

    public function handle(): void
    {
        $names = [];

        while (true) {
            $name = $this->read('Please enter a name (type exit to submit): ');

            if (strtolower(trim($name)) === 'exit') {
                break;
            }

            if (empty($name)) {
                $this->output('Please enter a name.');
                continue;
            }

            $this->output(sprintf('You entered %s', $name));
            $names[] = $name;
        }

        $this->logSuccess($this->test->getName());
        $this->output(sprintf('The names you entered: %s', implode(', ', $names)));
    }
}