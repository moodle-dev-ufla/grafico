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
 * Report main page
 *
 * @package    report
 * @copyright  2019 Paulo Jr
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require __DIR__ . '/../../config.php';
require_once $CFG->libdir . '/adminlib.php';

admin_externalpage_setup('reportgrafico', '', null, '', array('pagelayout' => 'report'));

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('pluginname', 'report_grafico'));

$categories = $DB->get_records('course_categories', null, 'name');

$chart_labels = array();
$chart_values = array();

foreach ($categories as $cat) {
    $numofcourses = $DB->count_records(
        'course', array('visible' => '1', 'category' => $cat->id));

    $chart_labels[] = $cat->name;
    $chart_values[] = $numofcourses;
}

if (class_exists('core\chart_bar')) {
    $chart = new core\chart_bar();

    $serie = new core\chart_series(
        get_string('label_numofcourses', 'report_grafico'), $chart_values
    );

    $chart->add_series($serie);
    $chart->set_labels($chart_labels);

    echo $OUTPUT->render_chart($chart);
}

echo $OUTPUT->footer();
