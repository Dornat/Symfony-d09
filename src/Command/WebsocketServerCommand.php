<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class WebsocketServerCommand extends Command
{
    protected static $defaultName = 'websocket:server';

    protected function configure()
    {
        $this
            ->setDescription('Launches websocket server on port 8080.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $process = new Process([
            './mercure',
            '--jwt-key=aVerySecretKey',
            '--addr=127.0.0.1:8080',
            '--allow-anonymous',
            '--cors-allowed-origins=*'
        ]);

        $process->setTimeout(0);

        $process->run(function ($type, $buffer) {
            echo $buffer;
        });
//
//        try {
//            $process->mustRun();
//
//            echo $process->getOutput();
//        } catch (ProcessFailedException $exception) {
//            echo $exception->getMessage();
//        }
//        echo $process->getOutput();
        return 0;
    }
}
