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

namespace local_task_tracker\form;
defined('MOODLE_INTERNAL') || die();
require_once($CFG->libdir . '/formslib.php');

/**
 * Add button form.
 */
class add_button_form extends \moodleform {
    /**
     * Define form.
     */
    public function definition() {
        $mform = $this->_form;
        $buttonarray=array();
        $buttonarray[] = $mform->createElement('submit', 'submitbutton', get_string('add', 'local_task_tracker'));
        $buttonarray[] = $mform->createElement('reset', 'resetbutton', get_string('revert', 'local_task_tracker'));
        $mform->addGroup($buttonarray, 'buttonar', '', ' ', false);
    }
}
