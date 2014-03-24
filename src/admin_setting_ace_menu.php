<?php

class admin_setting_ace_menu extends admin_setting
{
    /** @var mixed int means PARAM_XXX type, string is a allowed format in regex */
    public $paramtype;
    /** @var int default field size */
    public $size;
    public $settingsObj;

    /**
     * Constructor: uses parent::__construct
     *
     * @param string $name unique ascii name, either 'mysetting' for settings that in config, or 'myplugin/mysetting' for ones in config_plugins.
     * @param string $visiblename localised
     * @param string $description long localised info
     * @param array $defaultsetting array of selected
     * @param array $choices array of $value=>$label for each checkbox
     */
    public function __construct($name, $visiblename, $description, $defaultsetting, $choices = null) {
        $this->choices = $choices;
        parent::__construct($name, $visiblename, $description, $defaultsetting);
    }

    /**
     * Returns the current setting if it is set
     *
     * @return mixed null if null, else an array
     */
    public function get_setting() {
        $result = $this->config_read($this->name);

        if (is_null($result)) {
            return NULL;
        }
        if ($result === '') {
            return array();
        }

        return json_decode($result, true);
    }

    /**
     * This public function may be used in ancestors for lazy loading of choices
     *
     * @todo Check if this function is still required content commented out only returns true
     * @return bool true if loaded, false if error
     */
    public function load_choices() {
        $this->choices = $this->get_setting();

        return true;
    }

    /**
     * Is setting related to query text - used when searching
     *
     * @param string $query
     * @return bool true on related, false on not or failure
     */
    public function is_related($query) {
        if (!$this->load_choices() or empty($this->choices)) {
            return false;
        }
        if (parent::is_related($query)) {
            return true;
        }

        foreach ($this->choices as $desc) {
            if (strpos(core_text::strtolower($desc), $query) !== false) {
                return true;
            }
        }
        return false;
    }

    /**
     * Get asssociative array of blocks where key is block ID and value is translated name.
     * If no name found in translations use technical block name.
     *
     * @return array Block array
     */
    private function get_available_blocks($blankOption = true) {
        global $DB;

        $blocks = array();
        if ($blankOption) {
            $blocks = array('' => '- '.get_string('settings_block_none', 'theme_ace').' -');
        }
        $blocksRecords = $DB->get_records('block', array('visible' => 1));
        foreach ($blocksRecords as $blockID => $block) {
            // Get translation of block name
            $name = get_string('pluginname', 'block_'.$block->name);
            if ($name == "[[pluginname]]") {
                $name = $block->name;
            }

            $blocks [$blockID]= $name;
        }

        return $blocks;
    }

    /**
     * Get asssociative array of roles where key is role ID and value is translated name.
     * If no name found in translations use technical shortname.
     *
     * @return array Role names array
     */
    private function get_available_roles($blankOption = true) {
        global $DB;

        $roles = array();
        if ($blankOption) {
            $roles = array('' => '- '.get_string('settings_roles_all', 'theme_ace').' -');
        }
        foreach (get_all_roles() as $role) {
            if (get_string('archetype'.$role->archetype, 'role') == "[[archetype".$role->archetype."]]") {
                $name = ucwords(($role->archetype) ? $role->archetype : $role->shortname);
            } else {
                $name = str_replace("ARCHETYPE: ", '', get_string('archetype'.$role->archetype, 'role'));
            }

            $roles [$role->id]= $name;
        }

        return $roles;
    }

    /**
     * Saves the setting(s) provided in $data
     *
     * @param array $data An array of data, if not array returns empty str
     * @return mixed empty string on useless data or bool true=success, false=failed
     */
    public function write_setting($data) {
        if (!is_array($data)) {
            return ''; // ignore it
        }
        if (!$this->load_choices() or empty($this->choices)) {
            return '';
        }
        unset($data['xxxxx']);

        foreach ($data as $menu => $menuItem) {
            $name = $menuItem['name'];
            $url = $menuItem['url'];
            $block = $menuItem['block'];
            $role = $menuItem['role'];

            if (!$name) {
                return get_string('settings_name_invalid', 'theme_ace');
            }

            if ($url && $block) {
                return get_string('settings_url_block_error', 'theme_ace');
            }
        }

        return $this->config_write($this->name, json_encode($data)) ? '' : get_string('errorsetting', 'admin');
    }

    /**
     * Returns XHTML field(s) as required by choices
     *
     * Relies on data being an array should data ever be another valid vartype with
     * acceptable value this may cause a warning/error
     * if (!is_array($data)) would fix the problem
     *
     * @todo Add vartype handling to ensure $data is an array
     *
     * @param array $data An array of checked values
     * @param string $query
     * @return string XHTML field
     */
    public function output_html($data, $query='') {
        if (!$this->load_choices() or empty($this->choices)) {
            return '';
        }
        $default = $this->get_defaultsetting();
        if (is_null($default)) {
            $default = array();
        }
        if (is_null($data)) {
            $data = array();
        }
        $options = array();
        $defaults = array();

        $return = '<div class="form-acemenu">';
        // Something must be submitted even if nothing selected
        $return .= '<input type="hidden" name="'.$this->get_full_name().'[xxxxx]" value="1" />';
        if ($this->choices && sizeof($this->choices)) {
            $return .= '<ul>';

            foreach ($this->choices as $key=>$menuItem) {
                // Name field - no label
                $return .= '<li>'.
                    '<input type="text" id="'.$this->get_id().'_'.$key.'_name" name="'.
                        $this->get_full_name().'['.$key.'][name]" value="'.$menuItem['name'].'" />'.
                    '<ul>';

                // URL field
                $return .= '<li>'.
                    '<label>'.get_string('settings_url_label', 'theme_ace').'</label> '.
                    '<input type="text" id="'.$this->get_id().'_'.$key.'_url" name="'.
                    $this->get_full_name().'['.$key.'][url]" value="'.$menuItem['url'].'" />'.
                '</li>';


                // Block field
                $blockSelectFull = new admin_setting_configselect('blocks_'.$key.'_block', get_string('settings_block_label',
                    'theme_ace'), null, null, $this->get_available_blocks());
                $blockSelectFullDOM = new DOMDocument();
                $blockSelectFullDOM->loadHTML($blockSelectFull->output_html($menuItem['block']));

                $blockSelectHTML = '';
                foreach ($blockSelectFullDOM->getElementsByTagName("select") as $select) {
                    if (strstr($select->getAttribute("name"), "_".$key."_block") !== false) {
                        $select->setAttribute('id', $this->get_id().'_'.$key.'_block');
                        $select->setAttribute("name", $this->get_full_name().'['.$key.'][block]');

                        $blockSelectHTML .= $blockSelectFullDOM->saveHTML($select);

                        break;
                    }
                }

                if ($blockSelectHTML) {
                    $return .= '<li>'.
                        '<label>'.get_string('settings_block_label', 'theme_ace').'</label> '.
                        $blockSelectHTML.
                        '</li>';
                }

                // User role (who can see this menu item)
                $roleSelectFull = new admin_setting_configselect('blocks_'.$key.'_role',
                    get_string('settings_role_label', 'theme_ace'), null, false, $this->get_available_roles());
                $roleSelectFullDOM = new DOMDocument();
                $roleSelectFullDOM->loadHTML($roleSelectFull->output_html($menuItem['role']));

                $roleSelectHTML = '';
                foreach ($roleSelectFullDOM->getElementsByTagName("select") as $select) {
                    if (strstr($select->getAttribute("name"), "_".$key."_role") !== false) {
                        $select->setAttribute('id', $this->get_id().'_'.$key.'_role');
                        $select->setAttribute("name", $this->get_full_name().'['.$key.'][role]');

                        $roleSelectHTML .= $roleSelectFullDOM->saveHTML($select);

                        break;
                    }
                }

                if ($roleSelectHTML) {
                    $return .= '<li>'.
                        '<label>'.get_string('settings_role_label', 'theme_ace').'</label> '.
                        $roleSelectHTML.
                        '</li>';
                }

                $return .= '</ul></li>';
            }

            $return .= '</ul>';
        }
        $return .= '</div>';

        global $CFG;
        $wwwRoot = $CFG->wwwroot;
        $injectedJS = '<script type="text/javascript" src="'.$wwwRoot.'/theme/ace/js/zepto.min.js"></script>';
        $injectedJS .= '<script type="text/javascript" src="'.$wwwRoot.'/theme/ace/js/admin_settings_ace_menu.js"></script>';

        return format_admin_setting($this, $this->visiblename, $return.$injectedJS, $this->description, false, '', null, $query);

    }
}