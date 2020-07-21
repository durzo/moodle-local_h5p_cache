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
 * Command line utility to fill cache with h5p library files
 *
 * @package     local_h5p_cache
 * @copyright   2020 Jordan Tomkinson <jordan.tomkinson@ethinkeducation.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define('NO_OUTPUT_BUFFERING', true); // progress bar is used here
define('CLI_SCRIPT', true);
require_once(__DIR__ . '/../../../config.php');

global $CFG;

$where = "component IN ('core_h5p','mod_hvp') AND filearea = 'libraries' AND filesize!=0 AND filename != '.' ";
$records = $DB->get_records_sql("select min(id) AS id,contenthash from {files} where " . $where ." group by contenthash");

$total = sizeof($records);
$len = strlen($total);
$backspaces = str_repeat(chr(8), $len*2+1);
$i = 0;

echo "Total files according to Moodle database: $total\n";

//$resume = true;

echo "Filling cache with files - ". str_pad(0, $len, '0', STR_PAD_LEFT) ."/". $total;
$fs = get_file_storage();
$cache = \cache::make('local_h5p_cache', 'filecache');
$temp = make_request_directory();
$i = 0;

foreach ($records as $record) {
    if (!$cache->has($record->contenthash)) {
        $file = $fs->get_file_by_id($record->id);
        $file->copy_content_to($temp .'/'. $record->contenthash);
        $cache->set($record->contenthash, file_get_contents($temp .'/'. $record->contenthash));
    }
    global $i;
    $i++;
    echo $backspaces;
    echo str_pad($i, $len, '0', STR_PAD_LEFT) ."/". $total;
}

echo "\n\nDone.\n";
