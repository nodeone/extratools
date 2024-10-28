<?php

namespace Piwik\Plugins\ExtraTools\Lib;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class Drop
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

        $checkDbExists = new Process\Process(
            [
                'mysql',
                "-u$db_user",
                "-p$db_pass",
                "-P$db_port",
                "-h$db_host",
                "--execute=SELECT SCHEMA_NAME FROM information_schema.SCHEMATA WHERE SCHEMA_NAME = '$db_name'"
            ]
        );

        $checkDbExists->run();

        if ($checkDbExists->isSuccessful() && strpos($checkDbExists->getOutput(), $db_name) !== false) {
            $drop = new Process\Process(
                [
                    "mysqladmin",
                    "-u$db_user",
                    "-h",
                    "$db_host",
                    "-P",
                    "$db_port",
                    "-p$db_pass",
                    "drop",
                    "$db_name",
                    "--force"
                ]
            );
            $drop->enableOutput();
            $drop->run();
            $message = $drop->getOutput();
            if (!$drop->isSuccessful()) {
                $message = $drop->getErrorOutput();
                $this->output->writeln("<error>$message</error>");
                throw new ProcessFailedException($drop);
            } else {
                if ($this->silent === true) {
                    return 0;
                } else {
                    $this->output->writeln("<info>$message</info>");
                    return 0;
                }
            }
        }



    }
}
