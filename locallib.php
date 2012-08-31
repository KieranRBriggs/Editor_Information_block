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

include_once $CFG->libdir . '/form/text.php';

/**
 * Helper class for the block
 *
 * @copyright  2011 Mohamed Alsharaf
 * @author     Mohamed Alsharaf <mohamed.alsharaf@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class editorinfo_helper
{
    private $name = 'editorinfo';

    /**
     * Return string from the block language
     * 
     * @param string $name
     * @param object $a
     */
    public function get_string($name, $a = null)
    {
        return get_string($name, 'block_' . $this->name, $a);
    }

    /**
     * Return block unique name
     * 
     * @return string
     */
    public function get_name()
    {
        return $this->name;
    }

    /**
     * Return the directory path of the block
     * 
     * @global object $CFG
     * @return string
     */
    public function get_dirpath()
    {
        global $CFG;
        return $CFG->dirroot . '/blocks/' . $this->name . '/';
    }
}
