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
 * Settings.php
 * @package userstatus_timechecker
 * @copyright 2016/17 N Herrmann
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// Included in admin/tool/cleanupusers/classes/plugininfo/userstatus.php therefore need to include global variable.
global $CFG, $PAGE;

if ($hassiteconfig) {
    $url = $CFG->wwwroot . '/' . $CFG->admin . '/tool/cleanupusers/timechecker/index.php';
    $settings->add(new admin_setting_heading('timechecker_heading', get_string(
        'settingsinformation',
        'userstatus_timechecker'
    ), get_string('introsettingstext', 'userstatus_timechecker')));
    $settings->add(new admin_setting_configtext(
        'userstatus_timechecker/suspendtime',
        get_string('suspendtime', 'userstatus_timechecker'),
        get_string('timechecker_time_to_archive', 'userstatus_timechecker'),
        90,
        PARAM_INT
    ));
    $settings->add(new admin_setting_configtext(
        'userstatus_timechecker/deletetime',
        get_string('deletetime', 'userstatus_timechecker'),
        get_string('timechecker_time_to_delete', 'userstatus_timechecker'),
        365,
        PARAM_INT
    ));
    // get installed auth types for choices
    $is_disabled = '('.get_string('disabled', 'userstatus_timechecker').')';
    $plugins = core_plugin_manager::instance()->get_plugins_of_type('auth');
    $defaults = [];
    $enabled = [];
    $disabled = [];
    // sort the plugins in the same order as in the admin settings
    foreach ($plugins as $plugin) {
        if ($plugin->name == 'manual' || $plugin->name == 'nologin') {
            $defaults[$plugin->name] = $plugin->displayname;
        } elseif ($plugin->is_enabled()) {
            $enabled[$plugin->name] = $plugin->displayname;
        } else {
            $disabled[$plugin->name] = $plugin->displayname.' '.$is_disabled;
        }
        $auths = [];
        $auths = array_merge($defaults, $enabled, $disabled);
    }
    $settings->add(new admin_setting_configmulticheckbox(
        'userstatus_timechecker/enabledauth',
        get_string('enabledauth', 'userstatus_timechecker'),
        get_string('timechecker_enableddauth', 'userstatus_timechecker'),
        [],
        $auths
    ));
}
