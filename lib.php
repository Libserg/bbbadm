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

#function bbbadm_request_fast($url) {
#   if (extension_loaded('curl')) {
#        $ch = curl_init($url);
#        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: text/xml'));
#        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
#        curl_setopt($ch, CURLOPT_TIMEOUT , 3);
#        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT , 1);
#        curl_setopt($ch, CURLOPT_RETURNTRANSFER , true);
#        $ret = curl_exec($ch);
#        curl_close($ch);
#        return $ret;
#    } else {
#        return '<response><returncode>ERROR</returncode><meetings/>'.
#               '<message>Missing CURL extension.</message></response>';
#    }
#}
#
#function bbbadm_action_exec($api_name,$server,$params = null) {
#   $serverurl = $server[0];
#   if (substr($serverurl, -1) == '/') {
#       $serverurl = rtrim($serverurl, '/');
#   }
#   if (substr($serverurl, -4) == '/api') {
#       $serverurl = rtrim($serverurl, '/api');
#   }
#
#   $sh_sec = $server[1];
#   $action = $serverurl. '/api/' .$api_name;
#   return bbbadm_request_fast($action .'?'. $params . '&checksum=' . sha1($api_name . $params . $sh_sec));
#}
#
#function bbbadm_get_server_info($server) {
#
#	$res = bbbadm_action_exec('getMeetings',$server);
#
#	$previous = libxml_use_internal_errors(true);
#	$ret = '';
#        try {
#            $ret = simplexml_load_string($res,'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_NOBLANKS);
#        } catch (Exception $e) {
#            libxml_use_internal_errors($previous);
#            $error = 'Caught exception: ' . $e->getMessage();
#            debugging($error, DEBUG_DEVELOPER);
#            return array(0=>0,'MSG'=>'Bad xml response');
#        }
##	pre_print_r($ret);
#        if(!(isset($ret->returncode) && $ret->returncode == 'SUCCESS')) {
#            return array(0=>0,'MSG'=>isset($ret->message) ? $ret->message:'Error');
#        }
#        #print_r($ret);
#        $m_count = 0;
#        $m_list = 0;
#        $m_list_max = 0;
#        $m_video = 0;
#        $m_video_max = 0;
#        $trc = 0;
#        foreach ($ret->meetings as $m) {
#           foreach ($m->meeting as $i) {
#                #print_r($i);
#                $m_count++;
#                $m_c = intval($i->participantCount); # $i->videoCount + $i->moderatorCount + $i->listenerCount;
#                $m_v = intval($i->videoCount);
#                $m_list += $m_c;
#                $m_video += $m_v;
#                if($m_list_max < $m_c ) $m_list_max = $m_c;
#                if($m_video_max < $m_v) $m_video_max = $m_v;
#                $rc = $m_c + ($m_v + ($m_v > 4 ? $m_v/3 : 0)) * 10;
#                $trc += $rc;
#           }
#        }
#        #echo "OK|MC=$m_count LC=$m_list LM=$m_list_max VC=$m_video VM=$m_video_max\n";
#        return array(0=>1,'RC'=>$trc, 'MC'=>$m_count, 'LC'=>$m_list,
#                     'LM'=>$m_list_max, 'VC'=>$m_video, 'VM'=>$m_video_max);
#
#}

function bbbadm_get_server_list() {
	global $CFG;
	$servers = array();
	$last_server = 0;
	for($i=1; $i < 10; $i++) {
	   if(isset($CFG->bigbluebuttonbn['server_url'.$i]) &&
	      isset($CFG->bigbluebuttonbn['shared_secret'.$i]) &&
	      isset($CFG->bigbluebuttonbn['server_name'.$i])) {
		$last_server = $i;
		$servers[$i] = array($CFG->bigbluebuttonbn['server_url'.$i],
				     $CFG->bigbluebuttonbn['shared_secret'.$i],
				     $CFG->bigbluebuttonbn['server_name'.$i]);
	   }
	}
	if(!$last_server &&
	   isset($CFG->bigbluebuttonbn['server_url']) &&
	   isset($CFG->bigbluebuttonbn['shared_secret']) &&
	   isset($CFG->bigbluebuttonbn['server_name'])) {
		$last_server = 1;
		$servers[1]  = array($CFG->bigbluebuttonbn['server_url'],
				     $CFG->bigbluebuttonbn['shared_secret'],
				     $CFG->bigbluebuttonbn['server_name']);
	}
	return $last_server > 0 ? $servers : false;
}

function bbbadm_show_meetings(&$l,&$mi) {
	$htmltable = new html_table();
	$htmltable->head  = array('Start Time','Name', 'Moder.','Videos','Listeners');
        $htmltable->align = array('left'      ,'left', 'right', 'right','right');
        $htmltable->size  = array('9em'       ,'*',    '3em',   '3em', '3em');
	$mt = array(array());
	foreach($mi as $i=>$m) {
		$mt[] = new html_table_row(array(
			$m['StartTime'],
			$m['LMS'].' '.$m['Name'],
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
// Initialize HTML output.
$html = '';
// Fetch context.
$context = \context_system::instance();

$srvlist = bbbadm_get_server_list();
// If no file is found, quit with notification.

if($srvlist !== false) {
        $htmltable = new html_table();
        $htmltable->align = array('left', 'left', 'right', 'right', 'right', 'right');
	$htmltable->head = array('Name','URL','Load index','Rooms','Videos','View');
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

