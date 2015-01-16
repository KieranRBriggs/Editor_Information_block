<?php

defined('MOODLE_INTERNAL') || die();

/**
 * Displays user(s) profile information.
 *
 * @copyright  2014 Kieran Briggs
 * @author     Kieran Briggs <kieran.briggs@sheffcol.ac.uk>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_editorinfo extends block_base
{
    protected $helper;

    public function init()
    {
        global $CFG;
        
        include_once realpath(dirname(__FILE__)) . '/locallib.php';
        $this->helper = new editorinfo_helper;
        $this->title = $this->helper->get_string('pluginname');
    }

    /**
     * Display the content of a block
     * 
     * @global moodle_database $DB
     * @return object 
     */
    public function get_content()
    {
        global $DB;

        if ($this->content !== NULL) {
            return $this->content;
        }

        // Never useful unless you are logged in as real users
        if (!isloggedin() || isguestuser() || !isset($this->config->user) || !is_array($this->config->user)) {
            return '';
        }

        $this->content = new stdClass;
        $this->content->text = '<div class="editorinfoblock">';

        if ($this->config->message != '') {
            $this->content->text .= '<div class="desc">' . format_text($this->config->message, FORMAT_MOODLE) . '</div>';
        }

        $count = count($this->config->user) - 1;
        foreach ($this->config->user as $key => $username) {

            if ($username == '') {
                continue;
            }

            $islast = ($key == $count) ? true : false;
            $user = $DB->get_record('user', array('username' => $username));
            if ($user) {
                $this->content->text .= $this->render_user($user, $key, $islast);
            }
            unset($user);
        }
        $this->content->text .= '</div>';
        
        if ($this->config->extrainfo != '') {
            $this->content->text .= '<div class="extrainfo">' . format_text($this->config->extrainfo, FORMAT_MOODLE) . '</div>';
        }

        $this->content->footer = '';

        return $this->content;
    }

    /**
     * Render a user block details
     *
     * @global core_renderer $OUTPUT
     * @global object $USER
     * @param object $user
     * @param int $key
     * @param boolean $islast
     * @return string 
     */
    protected function render_user($user, $key, $islast = false)
    {
        global $OUTPUT, $USER, $CFG;

        $output = '<div class="editorinfo' . ($islast ? ' last' : '') . '" id="editorinfo-' . $user->id . '">';

        if ($this->can_display('picture', $key)) {
            $output .= '<div class="picture">';
            $output .= $OUTPUT->user_picture($user, array(
                        'courseid' => $this->page->course->id,
                        'size' => '100',
                        'class' => 'profilepicture'));
            $output .= '</div>';
        }

        if ($this->can_display('name', $key)) {
            $output .= '<div class="fullname">';
            if ($this->can_display('email', $key)) {
            	$output .= '<i class="fa fa-envelope-o"></i> <a href="mailto:' . $user->email .'><img src="' . $CFG->wwwroot . '/blocks/editorinfo/assets/mail.png" alt="' . $this->helper->get_string('emailme') . '" title="' . $this->helper->get_string('emailme') . '"/>' . fullname($user) . '</a>';
            } else {
	            $output .= fullname($user);
            }
            $output .= '</div>';
        }


        
        if ($this->can_display('isonline', $key)) {
           $timetoshowusers = 300;
           $timefrom = 100 * floor((time()-$timetoshowusers) / 100);
           if ($user->lastaccess > $timefrom) {
           	
           		// Checks to see if online icon can send a message
           		if ($this->can_display('sendmessage', $key)) {	
           			$output .= '<p class="online message"><a href=" ' . $CFG->wwwroot . '/message/index.php?id=' . $user->id . '"><img src="' . $CFG->wwwroot . '/blocks/editorinfo/assets/online.png" alt="' . $this->helper->get_string('online') . '" title="' . $this->helper->get_string('online') . '"/></a></p>';
           		} else {
           			$output .= '<p class="online"><img src="' . $CFG->wwwroot . '/blocks/editorinfo/assets/online.png" alt="' . $this->helper->get_string('online') . '" title="' . $this->helper->get_string('online') . '"/></p>';
           		}
           		
           } else {
           	$output .= '<p class="online"><img src="' . $CFG->wwwroot . '/blocks/editorinfo/assets/offline.png" alt="' . $this->helper->get_string('offline') . '" title="' . $this->helper->get_string('offline') . '"/></p>';
           }
         }
        
        $output .= '</div>';

        return $output;
    }

    /**
     * Checks if a configuration settings is enabled or not for a user
     * 
     * @param int $key
     * @param string $name 
     * @return boolean
     */
    protected function can_display($name, $key = 0)
    {
        if (!isset($this->config->{$name})) {
            return false;
        }

        $data = $this->config->{$name};
        if (!isset($data[$key]) || $data[$key] != 1) {
            return false;
        }

        return true;
    }

    /**
     * allow the block to have a configuration page
     *
     * @return boolean
     */
    public function has_config()
    {
        return false;
    }

    /**
     * allow more than one instance of the block on a page
     *
     * @return boolean
     */
    public function instance_allow_multiple()
    {
        return true;
    }

    /**
     * allow instances to have their own configuration
     *
     * @return boolean
     */
    public function instance_allow_config()
    {
        return true;
    }

    /**
     * instance specialisations (must have instance allow config true)
     *
     * @return void
     */
    public function specialization()
    {
        if (!empty($this->config->title)) {
            $this->title = strip_tags($this->config->title);
        }
    }

    /**
     * disable the displays of the instance configuration form (config_instance.html)
     *
     * @return boolean
     */
    public function instance_config_print()
    {
        return false;
    }

    /**
     * locations where block can be displayed
     *
     * @return array
     */
    public function applicable_formats()
    {
        return array('course' => true, 'mod' => true);
    }

    /**
     * post install configurations
     *
     */
    public function after_install()
    {
    }

    /**
     * post delete configurations
     *
     */
    public function before_delete()
    {
    }
}
