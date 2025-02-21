<?php

/**
 * The Extra Tools plugin for Matomo.
 *
 * Copyright (C) Digitalist Open Cloud <cloud@digitalist.com>
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
use Piwik\Db;
use Piwik\Common;

class DeleteLoggerMessages extends ConsoleCommand
{
    protected function configure()
    {
        $HelpText = 'The <info>%command.name%</info> will delete internal logger messages in the database.
<comment>Samples:</comment>
To run:
<info>%command.name%</info>';
        $this->setHelp($HelpText);
        $this->setName('logger:delete');
        $this->setDescription('Delete internal logger messages in database (monolog)');
        $this->addNoValueOption(
            'force',
            null,
            'force removing logs, without confirmation.',
            null
        );
    }

    /**
     * List users.
     */
    protected function doExecute(): int
    {
        $input = $this->getInput();
        $output = $this->getOutput();
        $force = $input->getOption('force');
        if ($force === false) {
            $question = $this->askForConfirmation('Are you really sure you would like to delete all logs? ', false);
            if (!$question) {
                $output->writeln("<info>Logs not deleted.</info>");
                return self::FAILURE;
            } else {
                $force = true;
            }
        }
        if ($force === true) {
            try {
                Db::query('TRUNCATE ' . Common::prefixTable('logger_message'));
                $output->writeln("<info>Logs deleted.</info>");
                return self::SUCCESS;
            } catch (\Exception $e) {
                return self::FAILURE;
            }
        }
        return self::SUCCESS;
    }
}
