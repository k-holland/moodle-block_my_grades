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
 * My Grades Block.
 *
 * @package   block_my_grades
 * @author    Karen Holland, Mei Jin, Jiajia Chen <kholland@lts.ie>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once("{$CFG->libdir}/gradelib.php");
require_once($CFG->dirroot . '/grade/report/lib.php');
require_once $CFG->dirroot.'/grade/report/overview/lib.php';
require_once $CFG->dirroot.'/grade/lib.php';

class block_my_grades extends block_base {
	public function init() {
		$this->title = get_string('my_grades', 'block_my_grades');
	}
	
	public function get_content() {
		global $DB, $USER, $COURSE;
	
		if ($this->content !== null) {
			return $this->content;
		}
 
		$this->content         =  new stdClass;

		/// return tracking object
		$gpr = new grade_plugin_return(array('type'=>'report', 'plugin'=>'overview', 'userid'=>$USER->id));
 
		// Create a report instance
		$context = get_context_instance(CONTEXT_COURSE, $COURSE->id);
		$report = new grade_report_overview($USER->id, $gpr, $context);

		$newdata=$this->grade_data($report);
		if (is_array($newdata))
		{
			if (count($newdata)>0)
			{
				$newtext="<table class=\"grades\"><tr><th>".get_string('gradetblheader_course', 'block_my_grades')."</th><th>".get_string('gradetblheader_grade', 'block_my_grades')."</th></tr>";
				foreach($newdata as $newgrade)
				{
					// need to put data into table for display here
					$newtext.="<tr><td>{$newgrade[0]}</td><td>{$newgrade[1]}</td></tr>";
				}
				$newtext.="</table>";
				$this->content->text.=$newtext;
			}
		}
		else
		{
			$this->content->text.=$newdata;
		}
		return $this->content;
	}
	
	public function specialization() {
		if (!empty($this->config->title)) {
			$this->title = $this->config->title;
		} else {
			$this->config->title = 'Default title ...';
		}   
	}

	public function instance_allow_multiple() {
		return false;
	}
	
	public function grade_data($report) {
		global $CFG, $DB, $OUTPUT;
		$data = array();
		
		if ($courses = enrol_get_users_courses($report->user->id, false, 'id, shortname, showgrades')) {
			$numusers = $report->get_numusers(false);

			foreach ($courses as $course) {
				if (!$course->showgrades) {
					continue;
				}

				$coursecontext = get_context_instance(CONTEXT_COURSE, $course->id);

				if (!$course->visible && !has_capability('moodle/course:viewhiddencourses', $coursecontext)) {
					// The course is hidden and the user isn't allowed to see it
					continue;
				}

				$courseshortname = format_string($course->shortname, true, array('context' => $coursecontext));
				$courselink = html_writer::link(new moodle_url('/grade/report/user/index.php', array('id' => $course->id, 'userid' => $report->user->id)), $courseshortname);
				$canviewhidden = has_capability('moodle/grade:viewhidden', $coursecontext);

				// Get course grade_item
				$course_item = grade_item::fetch_course_item($course->id);

				// Get the stored grade
				$course_grade = new grade_grade(array('itemid'=>$course_item->id, 'userid'=>$report->user->id));
				$course_grade->grade_item =& $course_item;
				$finalgrade = $course_grade->finalgrade;

				$data[] = array($courselink, grade_format_gradevalue($finalgrade, $course_item, true));
			}
			
			if (count($data)==0) {
				return $OUTPUT->notification(get_string('nocourses', 'grades'));
			} else {
				return $data;
			}
		} else {
			return $OUTPUT->notification(get_string('nocourses', 'grades'));
		}
	}
}

?>
