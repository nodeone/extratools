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

namespace Piwik\Plugins\ExtraTools\Lib;

use Piwik\Access;
use Piwik\Plugins\SitesManager\API as APISitesManager;

class Site
{
    protected $site;

    public function __construct($site)
    {
        $this->site = $site;
    }

    public function add()
    {
        $site = $this->site;

        $result = Access::doAsSuperUser(
            function () use ($site) {
                $siteName = false;
                $urls = null;
                $ecommerce = null;
                $siteSearch = null;
                $searchKeywordParameters = null;
                $searchCategoryParameters = null;
                $excludedIps = null;
                $excludedQueryParameters = null;
                $timezone = null;
                $currency = null;
                $group = null;
                $startDate = null;
                $excludedUserAgents = null;
                $keepURLFragments = null;
                $type = null;
                $settingValues = null;
                $excludeUnknownUrls = null;
                $site = $this->site;
                $extract = extract($site);
                return APISitesManager::getInstance()->addSite(
                    $siteName,
                    $urls,
                    $ecommerce,
                    $siteSearch,
                    $searchKeywordParameters,
                    $searchCategoryParameters,
                    $excludedIps,
                    $excludedQueryParameters,
                    $timezone,
                    $currency,
                    $group,
                    $startDate,
                    $excludedUserAgents,
                    $keepURLFragments,
                    $type,
                    $settingValues,
                    $excludeUnknownUrls
                );
            }
        );
        return $result;
    }

    public function exists(): bool
    {
        $sites = Access::doAsSuperUser(
            function (): array {
                $siteName = false;
                $site = $this->site;
                extract($site);

                return APISitesManager::getInstance()->getPatternMatchSites($siteName, 1);
            }
        );

        return !empty($sites);
    }

    public function list()
    {
        $site_name = [];
        $list = APISitesManager::getInstance()->getAllSitesId();
        foreach ($list as $id) {
            $site_name[] = APISitesManager::getInstance()->getSiteFromId($id);
        }
        return $site_name;
    }

    public function record()
    {
        try {
            $result = APISitesManager::getInstance()->getSiteFromId($this->site);
            return $result['name'];
        } catch (\Exception $e) {
            return false;
        }
    }

    public function delete()
    {
        try {
            $delete = APISitesManager::getInstance()->deleteSite($this->site);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
    public function addURL($id, $urls)
    {
        try {
            $add_url = APISitesManager::getInstance()->addSiteAliasUrls($id, $urls);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function totalSites()
    {
        $all = APISitesManager::getInstance()->getAllSites();
        return count($all);
    }
}
