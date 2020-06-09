<?php

// disable moodle specific debug messages and any errors in output
define('NO_DEBUG_DISPLAY', true);

require_once('../../config.php');
require_once('../../lib/filelib.php');

$relativepath  = get_file_argument();

// relative path must start with '/', because of backup/restore!!!
if (!$relativepath) {
    print_error('invalidargorconf');
} else if ($relativepath{0} != '/') {
    print_error('pathdoesnotstartslash');
}

// extract relative path components
$args = explode('/', ltrim($relativepath, '/'));

if (count($args) != 1) { // always at least courseid, may search for index.html in course root
    print_error('invalidarguments');
}

$relativepath = implode('/', $args);

$fullpath = $CFG->dataroot. "/bbbcache/bbb_rrd/$relativepath";

#        send_file_not_found();
\core\session\manager::write_close(); // Unlock session during file serving.

if(!file_exists($fullpath))
	        send_file_not_found();
send_file($fullpath,basename($fullpath),0);

