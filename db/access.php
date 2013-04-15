<?php
defined('MOODLE_INTERNAL') || die();
 
$capabilities = array(
 
    'block/editorinfo:addinstance' => array(
 
        'captype' => 'read',
        'contextlevel' => CONTEXT_COURSE,
        'legacy' => array(
            'guest' => CAP_PREVENT,
            'teacher' => CAP_ALLOW,
            'coursecreator' => CAP_ALLOW,
            'manager' => CAP_ALLOW
        )
    ),
    'block/editorinfo:myaddinstance' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => array(
            'user' => CAP_PREVENT
        ),

        'clonepermissionsfrom' => 'moodle/my:manageblocks'
    ),    
 );