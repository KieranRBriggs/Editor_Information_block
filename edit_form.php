<?php

/**
 *
 * @package    block
 * @subpackage editorinfo
 * @copyright  2011 Mohamed Alsharaf
 * @author     Mohamed Alsharaf <mohamed.alsharaf@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

/**
 * Form for editing editorinfo block settings
 *
 * @package    block
 * @subpackage editorinfo
 * @copyright  2011 Mohamed Alsharaf
 * @author     Mohamed Alsharaf <mohamed.alsharaf@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_editorinfo_edit_form extends block_edit_form
{
    protected function specific_definition($mform)
    {
        global $CFG, $COURSE, $DB;

        include_once realpath(dirname(__FILE__)) . '/locallib.php';
        include_once ($CFG->dirroot .'/lib/accesslib.php');
        $context = get_context_instance(CONTEXT_COURSE, $COURSE->id);
        
        $helper = new editorinfo_helper();
        
        $users = get_users_by_capability($context, 'gradereport/grader:view', 'u.id, u.firstname, u.lastname, u.username', 'u.lastname ASC, u.firstname ASC');
    	$availabletutors = array();
        foreach ($users as $user) {
        	
        	$availabletutors[$user->username] = $user->firstname . ' ' .$user->lastname;
        	
        	
        } 
        $tutors = $availabletutors; 

        $mform->addElement('header', 'configheader', $helper->get_string('configheader_settings'));

        $mform->addElement('text', 'config_title', $helper->get_string('title'));
        $mform->setDefault('config_title', 'Course Tutor');
        $mform->setType('config_title', PARAM_MULTILANG);

        $mform->addElement('textarea', 'config_message', $helper->get_string('displaymessage'), array('rows' => '5', 'cols' => '60'));
        $mform->setDefault('config_message', 'Your tutor for this course is');
        $mform->addElement('textarea', 'config_extrainfo', $helper->get_string('extrainfo'), array('rows' => '5', 'cols' => '60'));

        $repeatarray = array(
            $repeatarray [] = $mform->createElement('header', 'headerconfiguser', $helper->get_string('configheader_user')),
            $repeatarray [] = $mform->createElement('select', 'config_user', $helper->get_string('username'), $tutors),
            $repeatarray [] = $mform->createElement('selectyesno', 'config_name', $helper->get_string('displayname')),
            $repeatarray [] = $mform->createElement('selectyesno', 'config_picture', $helper->get_string('displaypicture')),
            $repeatarray [] = $mform->createElement('selectyesno', 'config_email', $helper->get_string('displayemail')),
            $repeatarray [] = $mform->createElement('selectyesno', 'config_sendmessage', $helper->get_string('displaysendmessage')),
            $repeatarray [] = $mform->createElement('selectyesno', 'config_isonline', $helper->get_string('displayisonline')),
        );
        
        $repeatedoptions = array(
            'config_user' => array(
                'type' => PARAM_USERNAME
            ),

        );

        $repeatno = 1;
        if (isset($this->block->config->repeats)) {
            $repeatno = (int) $this->block->config->repeats;
        }
        $repeatno = $repeatno == 0 ? 1 : $repeatno;

        $this->repeat_elements($repeatarray, $repeatno, $repeatedoptions, 'config_repeats', 'config_add_fields', 1, $helper->get_string('addmoreusers'), false);
    }
}