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
        $page->add(new admin_setting_configcheckbox('local_bbbadm/allowbbb',
                get_string('bbbdisable', 'local_bbbadm', 'all', true), null,
		#get_string('allowusing_desc', 'local_bbbadm', null, true),
	       	0));
	$page->add(new admin_setting_confightmleditor('local_bbbadm/allowbbbtxt',
       		new lang_string('optionalmaintenancemessage', 'admin'), '', ''));

        $page->add(new admin_setting_heading('local_bbbadm/serverconfighead',
                get_string('serverconfighead', 'local_bbbadm', null, true),
                ''));
	$srvlist = bbbadm_get_server_list();
	foreach($srvlist as $i => $s) {
	        $page->add(new admin_setting_configcheckbox('local_bbbadm/allowbbbserver_'.$i,
                get_string('allowusing', 'local_bbbadm', "'".$s[2]."'", true), null,
		#get_string('allowusing_desc', 'local_bbbadm', null, true), 
		0));
	}
if(0) {
        // Create document title source widget.
        $titlesource[STATICPAGE_TITLE_H1] = get_string('documenttitlesourceh1', 'local_bbbadm', null, false);
                // Don't use string lazy loading here because the string will be directly used and
                // would produce a PHP warning otherwise.
        $titlesource[STATICPAGE_TITLE_HEAD] = get_string('documenttitlesourcehead', 'local_bbbadm', null, true);
        $page->add(new admin_setting_configselect('local_bbbadm/documenttitlesource',
                get_string('documenttitlesource', 'local_bbbadm', null, true),
                get_string('documenttitlesource_desc', 'local_bbbadm', null, true),
                STATICPAGE_TITLE_H1,
                $titlesource));
        $page->add(new admin_setting_configselect('local_bbbadm/documentheadingsource',
                get_string('documentheadingsource', 'local_bbbadm', null, true),
                get_string('documentheadingsource_desc', 'local_bbbadm', null, true),
                STATICPAGE_TITLE_H1,
                $titlesource));
        $page->add(new admin_setting_configselect('local_bbbadm/documentnavbarsource',
                get_string('documentnavbarsource', 'local_bbbadm', null, true),
                get_string('documentnavbarsource_desc', 'local_bbbadm', null, true),
                STATICPAGE_TITLE_H1,
                $titlesource));

        // Apache rewrite.
        $page->add(new admin_setting_heading('local_bbbadm/apacherewriteheading',
                get_string('apacherewrite', 'local_bbbadm', null, true),
                ''));

        // Create apache rewrite control widget.
        $page->add(new admin_setting_configcheckbox('local_bbbadm/apacherewrite',
                get_string('apacherewrite', 'local_bbbadm', null, true),
                get_string('apacherewrite_desc', 'local_bbbadm', null, true),
                0));

        // Force login.
        $page->add(new admin_setting_heading('local_bbbadm/forceloginheading',
                get_string('forcelogin', 'local_bbbadm', null, true),
                ''));

        // Create force login widget.
        $forceloginmodes[0] = get_string('yes', 'core', null, true);
        $forceloginmodes[1] = get_string('no', 'core', null, true);
        $forceloginmodes[2] = get_string('forceloginglobal', 'local_bbbadm', null, false);
                // Don't use string lazy loading here because the string will be directly used and
                // would produce a PHP warning otherwise.
        $page->add(new admin_setting_configselect('local_bbbadm/forcelogin',
                get_string('forcelogin', 'local_bbbadm', null, true),
                get_string('forcelogin_desc', 'local_bbbadm', null, true),
                $forceloginmodes[2],
		$forceloginmodes));
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

    // Add pagelist page to navigation category.
}
