<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Plugin version and other meta-data are defined here.
 *
 * @package     local_task_tracker
 * @copyright   2025 Aleksei alexsuvorov2506@gmail.com
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');

$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/local/task_tracker/index.php'));
$PAGE->set_pagelayout('standard');
$PAGE->set_title($SITE->fullname);
$PAGE->set_heading(get_string('pluginname', 'local_task_tracker'));

$taskform = new \local_task_tracker\form\task_form();

if ($data = $taskform->get_data()) {
    $taskname = required_param('taskname', PARAM_TEXT);
    $enddate = required_param_array('enddate', PARAM_RAW);

    // Create new record in the table.
    if (!empty($taskname) && !empty($enddate)) {
        // IDK how to parse this date normally...
        $parsedate = $enddate['year'] . '-';
        if ($enddate['month'] < 10) {
            $parsedate = $parsedate . '0' . $enddate['month'];
        } else {
            $parsedate = $parsedate . $enddate['month'];
        }

        $parsedate = $parsedate . '-';

        if ($enddate['day'] < 10) {
            $parsedate = $parsedate . '0' . $enddate['day'];
        } else {
            $parsedate = $parsedate . $enddate['day'];
        }

        $date = strtotime($parsedate);

        $record = new stdClass;
        $record->taskname = $taskname;
        $record->enddate = $date;
        $record->userid = $USER->id;

        $DB->insert_record('local_task_tracker', $record);
    }
}

echo $OUTPUT->header();

$taskform->display();

echo html_writer::tag('h3', get_string('greeting', 'local_task_tracker'));

$userfields = \core_user\fields::for_name()->with_identity($context);
$userfieldssql = $userfields->get_sql('u');

$sql = "SELECT t.id, t.taskname, t.enddate, t.userid {$userfieldssql->selects}
        FROM {local_task_tracker} t
        LEFT JOIN {user} u ON u.id = t.userid
        ORDER BY enddate DESC";

$tasks = $DB->get_records_sql($sql);
echo $OUTPUT->box_start('card-columns');

foreach ($tasks as $t) {
    echo html_writer::start_tag('div', ['class' => 'card']);
    echo html_writer::start_tag('div', ['class' => 'card-body']);
    echo html_writer::tag('p', $t->taskname, ['class' => 'card-text']);
    echo html_writer::start_tag('p', ['class' => 'card-text']);
    echo html_writer::tag('small', userdate($t->enddate), ['class' => 'text-muted']);
    echo html_writer::end_tag('p');
    echo html_writer::end_tag('div');
    echo html_writer::end_tag('div');
}

echo $OUTPUT->box_end();

echo $OUTPUT->footer();
