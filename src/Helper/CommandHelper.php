<?php

namespace App\Helper;

use Symfony\Component\Console\Output\OutputInterface;

trait CommandHelper
{
    /** @var float */
    public float $currentTime;

    /** @var OutputInterface */
    public OutputInterface $output;

    /**
     * @param string $message
     */
    protected function setOutput(string $message): void
    {
        if (empty($this->currentTime)) {
            $this->currentTime = microtime(true);
        } else {
            $time = microtime(true) - $this->currentTime;
            $this->currentTime = microtime(true);
            $message .= ' {' . sprintf("%01.4f", $time) . 's}';
        }

        if (!empty($this->output)) {
            $this->output->writeln($message);
        }
    }
}