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

    // Include custom font select field
    require_once($CFG->dirroot . '/theme/ace/admin_setting_ace_font.php');
    require_once($CFG->dirroot . '/theme/ace/admin_setting_ace_slider.php');

    $ADMIN->add('themes', new admin_category('theme_ace', 'Ace'));

    $page = new admin_settingpage('theme_ace_main',
        get_string('mainsettings', 'theme_ace'));

    // NOTE: error regarding style folder being writable are handled by admin_setting_ace_font.php
	// Logo file setting
	$setting = new admin_setting_configstoredfile('theme_ace/logo',
			get_string('logo','theme_ace'),
			get_string('logodesc', 'theme_ace'),
			'logo');
	$setting->set_updatedcallback('theme_reset_all_caches');
	$page->add($setting);

	// Profile pic to be displayed in header
	/*$setting = new admin_setting_configcheckbox('theme_ace/profilepic',
		get_string('profilepic','theme_ace'),
	    get_string('profilepicdesc', 'theme_ace'),
	    1);
	$setting->set_updatedcallback('theme_reset_all_caches');
	$page->add($setting);*/

	// Custom CSS textarea
	$setting = new admin_setting_configtextarea('theme_ace/csscustom',
		get_string('csscustom','theme_ace'),
	    get_string('csscustomdesc', 'theme_ace'),
	    '');
	$setting->set_updatedcallback('theme_reset_all_caches');
	$page->add($setting);

	// Fluid or fixed layout
	/*$setting = new admin_setting_configselect('theme_ace/fluid',
			get_string('fluid','theme_ace'),
			get_string('fluiddesc', 'theme_ace'),
			'fluid',
		    array('fixed' => get_string('fluid_fixed','theme_ace'),
				 'fluid' => get_string('fluid_fluid','theme_ace')));
	$setting->set_updatedcallback('theme_reset_all_caches');
	$page->add($setting);*/

	// List font combinations
	$fontCombinations = array("Droid Serif / serif",
        "Fjalla One / Average",
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
			"Droid Serif / serif",
			array_combine($fontCombinations, $fontCombinations),
            $supportedFonts);
	$setting->set_updatedcallback('theme_reset_all_caches');
	$page->add($setting);

	// Footer message - potentially disclaimer
	$setting = new admin_setting_confightmleditor('theme_ace/footer',
			get_string('footer','theme_ace'),
			get_string('footerdesc', 'theme_ace'),
			'');
	$setting->set_updatedcallback('theme_reset_all_caches');
	$page->add($setting);

    $ADMIN->add('theme_ace', $page);

    $sliderPage = new admin_settingpage('theme_ace_menu',
        get_string('slidersettings', 'theme_ace'));

    // Image slider selector
    $setting = new admin_setting_ace_slider('theme_ace/slider2',
            get_string('slider','theme_ace'),
            get_string('sliderdesc', 'theme_ace'),
            'slider');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $sliderPage->add($setting);

    $ADMIN->add('theme_ace', $sliderPage);


?>
