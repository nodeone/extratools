<?php

/**
 * The Extra Tools plugin for Matomo.
 *
 * Copyright (C) 2024 Digitalist Open Cloud <cloud@digitalist.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

namespace Piwik\Plugins\ExtraTools\Commands;

use Piwik\Plugin\ConsoleCommand;
use Piwik\Config;
use Symfony\Component\Yaml\Yaml;

/**
 * Get config for a section.
 */
class ConfigGet extends ConsoleCommand
{
    /**
     * This methods allows you to configure your command. Here you can define the name and description of your command
     * as well as all options and arguments you expect when executing it.
     */
    protected function configure()
    {

        $HelpText = 'The <info>%command.name%</info> command will backup your db.
<comment>Samples:</comment>
To run:
<info>%command.name%</info>
You could use options to override config or environment variables:
<info>%command.name% --db-backup-path=/tmp/foo</info>';
        $this->setHelp($HelpText);
        $this->setName('config:get');
        $this->setDescription('Get config in the file config/config.ini.php');
        $this->addOptionalValueOption(
            'section',
            's',
            'Section in ini file, like database',
            null
        );
        $this->addOptionalValueOption(
            'format',
            'f',
            'Output format (json, yaml, text)',
            'text'
        );
    }

    /**
     * Execute the command like: ./console backup:db"
     */
    protected function doExecute(): int
    {
        $input = $this->getInput();
        $output = $this->getOutput();
        $section = $input->getOption('section');
        $format = $input->getOption('format');

        $configs = Config::getInstance();
        $get_section = $configs->getFromLocalConfig("$section");
        if ($get_section == null) {
            $output->writeln("<info>Looks like section <comment>$section</comment> does not exist</info>");
            return self::FAILURE;
        } else {
            if ($format == 'json') {
                $this->json($get_section);
            }
            if ($format == 'yaml') {
                $this->yaml($get_section);
            }
            if ($format == 'text') {
                $this->text($get_section);
            }
        }
        return self::SUCCESS;
    }
    private function json($config)
    {
        $json = json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        print_r($json);
        echo "\n";
    }
    private function yaml($config)
    {
        $yaml = Yaml::dump($config, 2, 2);
        print_r($yaml);
    }


    private function text($config)
    {
        $output = $this->getOutput();

        foreach ($config as $key => $section) {
            if (is_array($section)) {
                foreach ($section as $key_1 => $section_1) {
                    $output->write("<info>$key: </info>");
                    $output->writeln("<info><comment>$section_1</comment></info>");
                }
            } else {
                $output->writeln("<info>$key: <comment>$section</comment></info>");
            }
        }
    }
}
