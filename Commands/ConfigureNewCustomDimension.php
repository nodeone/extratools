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
use Piwik\Plugins\CustomDimensions\API as CustomDimensionsAPI;

class ConfigureNewCustomDimension extends ConsoleCommand
{
    /**
     * This methods allows you to configure your command. Here you can define the name and description of your command
     * as well as all options and arguments you expect when executing it.
     */
    protected function configure()
    {
        $HelpText = 'The <info>%command.name%</info> command will configure a new CustomDimension.
<comment>Samples:</comment>
To run:
<info>%command.name%</info>';
        $this->setHelp($HelpText);
        $this->setName('customdimensions:configure-new-dimension');
        $this->setDescription('Configure new Custom Dimension');
        $this->addRequiredValueOption(
            'id',
            null,
            'Site id'
        );
        $this->addRequiredValueOption(
            'name',
            null,
            'Name of the custom dimension'
        );
        $this->addRequiredValueOption(
            'scope',
            null,
            'Scope - visit or action'
        );
        $this->addNoValueOption(
            'active',
            null,
            'If provided, the custom dimension is marked as active'
        );
    }

    /**
     * Execute the command like.
     */
    protected function doExecute(): int
    {
        $input = $this->getInput();
        $output = $this->getOutput();
        $idsite = $input->getOption('id');
        $name = $input->getOption('name');
        $scope = $input->getOption('scope');
        $active = $input->getOption('active') ? true : false;
        if (!isset($name)) {
            $output->writeln('<error>A name is required</error>');
            return self::FAILURE;
        }
        if (!isset($idsite)) {
            $output->writeln('<error>A site id (id) is required</error>');
            return self::FAILURE;
        }
        if (!isset($scope)) {
            $output->writeln('<error>A scope (visit/action) is required</error>');
            return self::FAILURE;
        }

        $configure = $this->configureCustomDimension($idsite, $name, $scope, $active);
        $output->writeln('<info>Adding a new custom dimension.</info>');
        return self::SUCCESS;
    }
    public function configureCustomDimension($idsite, $name, $scope, $active)
    {
        $configureNew = new CustomDimensionsAPI();
        try {
            $add = $configureNew->configureNewCustomDimension($idsite, $name, $scope, $active);
            return self::SUCCESS;
        } catch (\Exception $e) {
            echo $e->getMessage();
            return self::FAILURE;
        }
    }
}
