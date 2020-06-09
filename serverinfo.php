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
 * Local plugin "staticpage" - Settings: List of static pages
 *
 * @package    local_bbbadm
 * @copyright  2013 Alexander Bias, Ulm University <alexander.bias@uni-ulm.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Include config.php.
require(__DIR__ . '/../../config.php');

// Include adminlib.php.
require_once($CFG->libdir.'/adminlib.php');

// Include lib.php.
require_once(__DIR__ . '/lib.php');

global $CFG, $PAGE, $OUTPUT;

// Set up external admin page.
admin_externalpage_setup('local_bbbadm_serverinfo');


// Prepare page.
$title = get_string('serverinfopage', 'local_bbbadm');
$PAGE->set_title($title);
$PAGE->set_heading($title);

echo $OUTPUT->header();
echo $OUTPUT->heading($title);

echo bbbadm_get_servers_info();
echo bbb_rrd_monitor();
// Finish page.
echo $OUTPUT->footer();
