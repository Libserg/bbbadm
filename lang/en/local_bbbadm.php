<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Local plugin "staticpage" - Language pack
 *
 * @package    local_staticpage
 * @copyright  2013 Alexander Bias, Ulm University <alexander.bias@uni-ulm.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'BBB administration';
$string['pagenotfound'] = 'Page not found';
$string['privacy:metadata'] = 'The static pages plugin provides extended functionality to Moodle admins, but does not store any personal data.';
$string['processcontent'] = 'Process content';
$string['serverinfopage'] = 'Server Status Information';
$string['forcelogin'] = 'forcelogin';
$string['forcelogin_desc'] = 'forcelogin_desc';
$string['noconfiguredservers'] = 'Empty list of servers.';
$string['serverconfighead'] = '{$a} settings';
$string['emptylist'] = 'No configured BBB-servers!';
$string['bbbdisable'] = 'Disable use of all BBB servers';
#$string['allowusing_desc'] = 'Disable create meeting on this server';
$string['denyusing'] = 'Deny a meetings creating';
$string['denyusing_desc'] = 'Disable create meeting on this server';
$string['autousing'] = 'Deny autoselect';
$string['autousing_desc'] = 'Exclude from automatic server selection';
$string['costusing'] = 'Server priority';
$string['costusing_desc'] = ''; #'Higher is worse';
$string['multserver'] = 'Server multiplier';
$string['multserver_desc'] = 'The server rating is calculated by the formula: (load rating) * (server multiplier)/100 + (server usage cost).<br>'.
	'The "load rating" is calculated by the BBB server itself.<br>'.
	'When creating a meeting, a server with a minimum load rating will be selected.';
$string['connlimit'] = 'Common limit of server connections';
$string['connlimit_desc'] = 'Must be < 300';
$string['range_error_min'] = 'The valid value must be in range {$a}';
$string['range_error_max'] = 'The valid value must be in range {$a}';
