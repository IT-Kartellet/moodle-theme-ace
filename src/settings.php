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
 * Ace theme settings page
 *
 * @package    theme_ace
 * @copyright  2014 IT-Kartellet
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once($CFG->dirroot . '/theme/ace/lib.php');

$settings = null;

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {
    // Include custom menu select field
    require_once($CFG->dirroot . '/theme/ace/admin_setting_ace_menu.php');
    // Include custom font select field
    require_once($CFG->dirroot . '/theme/ace/admin_setting_ace_font.php');

	$ADMIN->add('themes', new admin_category('theme_ace', 'Ace'));

        // NOTE: error regarding style folder being writable are handled by admin_setting_ace_font.php

	$mainPage = new admin_settingpage('theme_ace_main',
			get_string('mainsettings', 'theme_ace'));

	// Logo file setting
	$setting = new admin_setting_configstoredfile('theme_ace/logo',
			get_string('logo','theme_ace'),
			get_string('logodesc', 'theme_ace'),
			'logo');
	$setting->set_updatedcallback('theme_reset_all_caches');
	$mainPage->add($setting);

	// Profile pic to be displayed in header
	$setting = new admin_setting_configcheckbox('theme_ace/profilepic',
		get_string('profilepic','theme_ace'),
	    get_string('profilepicdesc', 'theme_ace'),
	    1);
	$setting->set_updatedcallback('theme_reset_all_caches');
	$mainPage->add($setting);

	// Custom CSS textarea
	$setting = new admin_setting_configtextarea('theme_ace/csscustom',
		get_string('csscustom','theme_ace'),
	    get_string('csscustomdesc', 'theme_ace'),
	    '');
	$setting->set_updatedcallback('theme_reset_all_caches');
	$mainPage->add($setting);

	// Fluid or fixed layout
	$setting = new admin_setting_configselect('theme_ace/fluid',
			get_string('fluid','theme_ace'),
			get_string('fluiddesc', 'theme_ace'),
			'fluid',
		    array('fixed' => get_string('fluid_fixed','theme_ace'),
				 'fluid' => get_string('fluid_fluid','theme_ace')));
	$setting->set_updatedcallback('theme_reset_all_caches');
	$mainPage->add($setting);

	// List font combinations
	$fontCombinations = array("Fjalla One / Average",
		"Stint Ultra Expanded / Pontano Sans",
		"Rufina / Sintony",
		"Clicker Script / EB Garamond",
		"Oxygen / Source Sans Pro",
		"Dancing Script / Ledger",
		"Shadows Into Light Two / Roboto",
		"Open Sans / Gentium Basic",
		"Lustria / Lato",
		"Bitter / Raleway");

    $supportedFonts = array();
    foreach ($fontCombinations as $fontCombo) {
        foreach (preg_split("/\//", $fontCombo) as $font) {
            $supportedFonts []= trim($font);
        }
    }

	$setting = new admin_setting_ace_font('theme_ace/font',
			get_string('font','theme_ace'),
			get_string('fontdesc', 'theme_ace'),
			"Fjalla One / Average",
			array_combine($fontCombinations, $fontCombinations),
            $supportedFonts);
	$setting->set_updatedcallback('theme_reset_all_caches');
	$mainPage->add($setting);

	// Copyright message
	$setting = new admin_setting_configtext('theme_ace/copyright',
			get_string('copyright','theme_ace'),
			get_string('copyrightdesc', 'theme_ace'),
			'');
	$setting->set_updatedcallback('theme_reset_all_caches');
	$mainPage->add($setting);

	// Footer message - potentially disclaimer
	$setting = new admin_setting_confightmleditor('theme_ace/footer',
			get_string('footer','theme_ace'),
			get_string('footerdesc', 'theme_ace'),
			'');
	$setting->set_updatedcallback('theme_reset_all_caches');
	$mainPage->add($setting);

	// Layout selection - at this stage just one frame
	// Theme colour selector
	$setting = new admin_setting_configtext('theme_ace/colours',
			get_string('colours','theme_ace'),
			get_string('coloursdesc', 'theme_ace'),
			'');
    /* Was going to put "ace_theme_update_colours" function call here in callback function,
    however there is no way to get result so calling it above */
	$setting->set_updatedcallback('theme_reset_all_caches'); //ace_theme_update_colours
	$mainPage->add($setting);

	$ADMIN->add('theme_ace', $mainPage);

 	// Menu editor - tree design
	$mainPage = new admin_settingpage('theme_ace_menu',
			get_string('menusettings', 'theme_ace'));

	$setting = new admin_setting_ace_menu('theme_ace/menu',
			get_string('menu','theme_ace'),
			get_string('menudesc', 'theme_ace'),
			'');
	$setting->set_updatedcallback('theme_reset_all_caches');
	$mainPage->add($setting);

	$ADMIN->add('theme_ace', $mainPage);
}

?>
