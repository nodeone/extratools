<?php

namespace Piwik\Plugins\ExtraTools\Lib;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class Create
{
    protected $config;
    public bool $silent;

    /**
     * @var OutputInterface
     */
    private $output;

    public function __construct($config, OutputInterface $output, $silent = 0)
    {
        $this->config = $config;
        $this->output = $output;
        $this->silent = $silent;
    }

    public function execute()
    {
        $db_host = $this->config['db_host'];
        $db_port = $this->config['db_port'];
        $db_user = $this->config['db_user'];
        $db_pass = $this->config['db_pass'];
        $db_name = $this->config['db_name'];

        $createDatabaseCommand = "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci; CREATE DATABASE IF NOT EXISTS `$db_name` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;";
        $drop = new Process\Process(
            [
                'mysql',
                "-u$db_user",
                "-p$db_pass",
                "-P$db_port",
                "-h$db_host",
                "--execute=$createDatabaseCommand",
                "--force"
            ]
        );

        $drop->enableOutput();
        $drop->run();

        if (!$drop->isSuccessful()) {
            throw new ProcessFailedException($drop);
        } else {
            if ($this->silent === true) {
                return 0;
            } else {
                $text = 'Database "%s" created';
                $message = sprintf($text, $db_name);
                $this->output->writeln("<info>$message</info>");
            }
        }
    }
}
