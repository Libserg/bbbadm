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
 * Local plugin "staticpage" - Settings
 *
 * @package    local_bbbadm
 * @copyright  2013 Alexander Bias, Ulm University <alexander.bias@uni-ulm.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// Include lib.php.
require_once(__DIR__ . '/lib.php');

global $CFG, $PAGE;

if ($hassiteconfig) {
    // Add new category to site admin navigation tree.
    $ADMIN->add('root', new admin_category('local_bbbadm',
            get_string('pluginname', 'local_bbbadm', null, true)));


    // Create new settings page.
    $page = new admin_settingpage('local_bbbadm_settings',
            get_string('settings', 'core', null, true));

    if ($ADMIN->fulltree) {
        $page->add(new admin_setting_configcheckbox('local_bbbadm/stopbbb',
                get_string('bbbdisable', 'local_bbbadm', 'all', true), null,
	       	0));
	$page->add(new admin_setting_confightmleditor('local_bbbadm/denybbbtxt',
       		new lang_string('optionalmaintenancemessage', 'admin'), '', ''));

	$srvlist = \mod_bigbluebuttonbn\locallib\config::server_list();
	if(!$srvlist ) {
    		$page = new admin_settingpage('local_bbbadm_settings',
		            get_string('emptylist', 'local_bbbadm', null, true));
	} else
	    foreach($srvlist as $i => $s) {
	        $page->add(new admin_setting_heading('local_bbbadm/serverconfighead_'.$i,
        	        get_string('serverconfighead', 'local_bbbadm',"'".$s[2]."'" , true),
                	''));
	        $page->add(new admin_setting_configcheckbox('local_bbbadm/denybbbserver_'.$i,
                	get_string('denyusing', 'local_bbbadm', null , true),
			get_string('denyusing_desc', 'local_bbbadm', null, true), 
			0));
	        $page->add(new admin_setting_configcheckbox('local_bbbadm/autobbbserver_'.$i,
                	get_string('autousing', 'local_bbbadm', null , true),
			get_string('autousing_desc', 'local_bbbadm', null, true), 
			0));
	        $page->add(new admin_setting_configint_range('local_bbbadm/connlimitserver_'.$i,
			get_string('connlimit', 'local_bbbadm', null , true), 
			'( 2 - 300 )', 200, PARAM_INT, 3 , 1, 300));
	        $page->add(new admin_setting_configtext('local_bbbadm/costbbbserver_'.$i,
			get_string('costusing', 'local_bbbadm', null , true), 
			get_string('costusing_desc', 'local_bbbadm', null, true), 
			'0', PARAM_INT, 3 ));
	        $page->add(new admin_setting_configtext('local_bbbadm/multbbbserver_'.$i,
			get_string('multserver', 'local_bbbadm', null , true), 
			get_string('multserver_desc', 'local_bbbadm', null, true), 
			'100', PARAM_INT, 3 ));
	    }
    }

    // Add settings page to navigation category.
    $ADMIN->add('local_bbbadm', $page);

    // Create new external pagelist page.
    $page = new admin_externalpage('local_bbbadm_serverinfo',
            get_string('serverinfopage', 'local_bbbadm', null, true),
            new moodle_url('/local/bbbadm/serverinfo.php'),
	    'moodle/site:config');
    $ADMIN->add('local_bbbadm', $page);
}
