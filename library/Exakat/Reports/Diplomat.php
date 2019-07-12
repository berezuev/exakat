<?php
/*
 * Copyright 2012-2019 Damien Seguy – Exakat SAS <contact(at)exakat.io>
 * This file is part of Exakat.
 *
 * Exakat is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Exakat is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with Exakat.  If not, see <http://www.gnu.org/licenses/>.
 *
 * The latest code can be found at <http://exakat.io/>.
 *
*/

namespace Exakat\Reports;

use Exakat\Analyzer\Analyzer;
use Exakat\Data\Methods;
use Exakat\Config;
use Exakat\Exakat;
use Exakat\Phpexec;
use Exakat\Reports\Reports;

class Diplomat extends Ambassador {
    const FILE_FILENAME  = 'diplomat';
    const FILE_EXTENSION = '';
    const CONFIG_YAML    = 'Diplomat';

    const TOPLIMIT = 10;
    const LIMITGRAPHE = 40;

    const NOT_RUN      = 'Not Run';
    const YES          = 'Yes';
    const NO           = 'No';
    const INCOMPATIBLE = 'Incompatible';

    private $compatibilities = array();

    public function __construct(Config $config) {
        parent::__construct($config);

        foreach(Config::PHP_VERSIONS as $shortVersion) {
            $this->compatibilities[$shortVersion] = "Compatibility PHP $shortVersion[0].$shortVersion[1]";
        }

        $this->themesToShow = array('Top10');
    }

    public function dependsOnAnalysis() {
        return array('CompatibilityPHP53', 'CompatibilityPHP54', 'CompatibilityPHP55', 'CompatibilityPHP56',
                     'CompatibilityPHP70', 'CompatibilityPHP71', 'CompatibilityPHP72', 'CompatibilityPHP73', 'CompatibilityPHP74',
                     'Top10', 'Preferences',
                     'Appinfo',
                     );
    }

/*
    protected function getBasedPage($file) {
        static $baseHTML;

        if (empty($baseHTML)) {
            $baseHTML = file_get_contents($this->config->dir_root . '/media/devfaceted/datas/base.html');

            $baseHTML = $this->injectBloc($baseHTML, 'EXAKAT_VERSION', Exakat::VERSION);
            $baseHTML = $this->injectBloc($baseHTML, 'EXAKAT_BUILD', Exakat::BUILD);
            $baseHTML = $this->injectBloc($baseHTML, 'PROJECT', $this->config->project);
            $baseHTML = $this->injectBloc($baseHTML, 'PROJECT_LETTER', strtoupper($this->config->project{0}));

            $menu = <<<'MENU'
        <!-- Sidebar Menu -->
        <ul class="sidebar-menu">
          <li class="header">&nbsp;</li>
          <!-- Optionally, you can add icons to the links -->
          <li class="active"><a href="index.html"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
          <li><a href="issues.html"><i class="fa fa-flag"></i> <span>Issues</span></a></li>
          <li class="treeview">
            <a href="#"><i class="fa fa-certificate"></i> <span>Compatibility</span><i class="fa fa-angle-left pull-right"></i></a>
            <ul class="treeview-menu">
              <li><a href="compatibility_compilations.html"><i class="fa fa-circle-o"></i>Compilations</a></li>
              <li><a href="compatibility_version.html"><i class="fa fa-circle-o"></i>PHP Version</a></li>
              {{COMPATIBILITIES}}
              <li><a href="compatibility_issues.html"><i class="fa fa-circle-o"></i>Compatibility Violations</a></li>
            </ul>
          </li>
          <li class="treeview">
            <a href="#"><i class="fa fa-certificate"></i> <span>Favorites</span><i class="fa fa-angle-left pull-right"></i></a>
            <ul class="treeview-menu">
              <li><a href="favorites_dashboard.html"><i class="fa fa-circle-o"></i>Overview</a></li>
              <li><a href="favorites_issues.html"><i class="fa fa-circle-o"></i>Violations</a></li>
            </ul>
          </li>
          <li class="treeview">
            <a href="#"><i class="fa fa-sliders"></i> <span>Audit logs</span><i class="fa fa-angle-left pull-right"></i></a>
            <ul class="treeview-menu">
              <li><a href="appinfo.html"><i class="fa fa-circle-o"></i>Appinfo()</a></li>
              <li><a href="bugfixes.html"><i class="fa fa-circle-o"></i>Bugfixes</a></li>
              <li><a href="php_compilation.html"><i class="fa fa-circle-o"></i>PHP Compilation Directives</a></li>
              <li><a href="directive_list.html"><i class="fa fa-circle-o"></i>Directive List</a></li>
              <li><a href="extension_list.html"><i class="fa fa-circle-o"></i>Extensions usage</a></li>
            </ul>
          </li>
          <li><a href="files.html"><i class="fa fa-file-code-o"></i> <span>Files</span></a></li>
          <li><a href="analyzers.html"><i class="fa fa-line-chart"></i> <span>Analyzers</span></a></li>
          <li class="treeview">
            <a href="#"><i class="fa fa-sticky-note-o"></i> <span>Annexes</span><i class="fa fa-angle-left pull-right"></i></a>
            <ul class="treeview-menu">
              <li><a href="annex_settings.html"><i class="fa fa-circle-o"></i>Analyzer Settings</a></li>
              <li><a href="proc_analyzers.html"><i class="fa fa-circle-o"></i>Processed Analyzers</a></li>
              <li><a href="codes.html"><i class="fa fa-circle-o"></i>Codes</a></li>
              <li><a href="analyzers_doc.html"><i class="fa fa-circle-o"></i>Documentation</a></li>
              <li><a href="credits.html"><i class="fa fa-circle-o"></i>Credits</a></li>
            </ul>
          </li>
        </ul>
        <!-- /.sidebar-menu -->
MENU;

            $compatibilities = array();
            $res = $this->sqlite->query('SELECT DISTINCT SUBSTR(thema, -2) FROM themas WHERE thema LIKE "Compatibility%" ORDER BY thema DESC');
            while($row = $res->fetchArray(\SQLITE3_NUM)) {
                $compatibilities []= "              <li><a href=\"compatibility_php$row[0].html\"><i class=\"fa fa-circle-o\"></i>{$this->compatibilities[$row[0]]}</a></li>\n";
            }

            $baseHTML = $this->injectBloc($baseHTML, 'SIDEBARMENU', $menu);
            $baseHTML = $this->injectBloc($baseHTML, 'COMPATIBILITIES', implode(PHP_EOL, $compatibilities));
        }

        $subPageHTML = file_get_contents("{$this->config->dir_root}/media/devfaceted/datas/{$file}.html");
        $combinePageHTML = $this->injectBloc($baseHTML, 'BLOC-MAIN', $subPageHTML);

        return $combinePageHTML;
    }

    protected function generateIssues() {
        $this->generateIssuesEngine('issues',
                                    $this->getIssuesFaceted('Top10') );
    }
*/
}


?>