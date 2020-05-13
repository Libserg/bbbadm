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
 * Local plugin "staticpage" - Library
 *
 * @package    local_bbbadm
 * @copyright  2013 Alexander Bias, Ulm University <alexander.bias@uni-ulm.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once(__DIR__ . '/../../config.php');
require_once($CFG->libdir .'/adminlib.php');
#require_once(__DIR__ .'/../../mod/bigbluebuttonbn/locallib.php');

function bbbadm_show_meetings(&$l,&$mi) {
	$htmltable = new html_table();
	$htmltable->head  = array('Start Time','Name', 'Moder.','Videos','Listeners');
        $htmltable->align = array('left'      ,'left', 'right', 'right','right');
        $htmltable->size  = array('9em'       ,'*',    '3em',   '3em', '3em');
	$mt = array(array());
	foreach($mi as $i=>$m) {
		$mt[] = new html_table_row(array(
			$m['StartTime'],
			'<b>'.$m['LMS'].'</b> '.$m['Course'].' '.$m['Name'],
			$m['Moderators'],
			$m['Videos'],
			$m['Users']
		));
	}
	$htmltable->data = $mt;
	$text = html_writer::table($htmltable);
	$t = new html_table_cell($text.' ');
	$t->colspan = 5;
	$l[] = new html_table_row(array('&nbsp;',$t));
}

function bbbadm_get_servers_info() {
global $CFG;
// Initialize HTML output.
$html = '';
// Fetch context.
$context = \context_system::instance();

$srvlist = \mod_bigbluebuttonbn\locallib\config::server_list();
// If no file is found, quit with notification.

if($srvlist !== false) {
        $htmltable = new html_table();
        $htmltable->align = array('left', 'left', 'right', 'right', 'right', 'right');
	$htmltable->head = array('Name','URL','Load ratio','Rooms','Videos','View');
        $htmltable->size = array('8em', '*', '3em','3em','3em','3em');
	$l = array(array());
	foreach($srvlist as $i=>$s) {
	    $si = bbb_get_server_info($i);
	    if($si[0] > 0) {
		$l[] = new html_table_row(array($s[2],$s[0], $si['RC'], $si['MC'], $si['VC'], $si['LC']));
		if(isset($si['info']))
			bbbadm_show_meetings($l,$si['info']);
	    } else {
		$m = new html_table_cell($si['MSG']);
		$m->colspan = 4; $m->style = 'text-align:center;';
		$l[] = new html_table_row(array($s[2],$s[0], $m));
	    }
	}
        $htmltable->data = $l;
        $html = html_writer::table($htmltable);
} else {

	$html = get_string('noconfiguredservers', 'local_bbbadm',
                    rtrim($CFG->wwwroot, '/').'/admin/settings.php?section=local_bbbadm_serverinfo');
}
return $html;
}

class admin_setting_configint_range extends admin_setting_configtext {

    /** @var int maximum number of chars allowed. */
    public $minval;
    public $maxval;

    public function __construct($name, $visiblename, $description, $defaultsetting, $paramtype=PARAM_RAW,
                                $size=null, $minval = false, $maxval=false) {
        $this->minval = $minval;
        $this->maxval = $maxval;
        parent::__construct($name, $visiblename, $description, $defaultsetting, PARAM_RAW, $size);
    }

    /**
     * Validate data before storage
     *
     * @param string $data data
     * @return mixed true if ok string if error found
     */
    public function validate($data) {
        $parentvalidation = parent::validate($data);
	if ($parentvalidation !== true) 
		return $parentvalidation;
	if($data == '') $data = $this->get_defaultsetting();
        if ($this->minval != false && intval($data) < $this->minval)
            return get_string('range_error_min', 'local_bbbadm', $this->minval . ' - '. $this->maxval);
        if ($this->maxval != false && intval($data) > $this->maxval)
            return get_string('range_error_max', 'local_bbbadm', $this->minval . ' - '. $this->maxval);
        return true; // No max length check needed.
    }
}

