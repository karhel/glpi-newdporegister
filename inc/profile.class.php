<?php
/**
 * -------------------------------------------------------------------------
 * NewDpoRegister plugin for GLPI
 * Copyright (C) 2020 by the NewDpoRegister Development Team.
 *
 * https://github.com/pluginsGLPI/newdporegister
 * -------------------------------------------------------------------------
 *
 * LICENSE
 *
 * This file is part of NewDpoRegister.
 *
 * NewDpoRegister is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * NewDpoRegister is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with NewDpoRegister. If not, see <http://www.gnu.org/licenses/>.
 * --------------------------------------------------------------------------
 * 
 * @package   newdporegister
 * @author    Karhel Tmarr
 * @copyright Copyright (c) 2010-2013 Uninstall plugin team
 * @license   GPLv3+
 *            http://www.gnu.org/licenses/gpl.txt
 * @link      https://github.com/karhel/glpi-newdporegister
 * @since     2020
 * --------------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
    die("Sorry. You can't access this file directly");
}


class PluginNewdporegisterProfile extends Profile
{
    static $rightname = "profile";

    // --------------------------------------------------------------------
    //  PLUGIN MANAGEMENT - DATABASE INITIALISATION
    // --------------------------------------------------------------------
    
    /**
     * Install or update PluginNewdporegisterProfile
     *
     * @param Migration $migration Migration instance
     * @param string    $version   Plugin current version
     *
     * @return boolean
     */
    public static function install(Migration $migration, $version)
    {
        self::initProfile();
    }

    /**
     * Uninstall PluginNewdporegisterProfile
     *
     * @return boolean
     */
    public static function uninstall()
    {
        global $DB;
  
        // Delete rights associated with the plugin
        $query = "DELETE
                  FROM `glpi_profilerights`
                  WHERE `name` LIKE 'plugin_dporegister_%'";

        $DB->queryOrDie($query, $DB->error());

        // Remove rights in configuration
        $profileRight = new ProfileRight();
        foreach (self::getAllRights() as $right) {
            $profileRight->deleteByCriteria(['name' => $right['field']]);
        }

        // Remove rights in current session
        self::removeRightsFromSession();
    }

    // --------------------------------------------------------------------
    //  GLPI PLUGIN COMMON
    // --------------------------------------------------------------------

    //! @copydoc CommonGLPI::displayTabContentForItem($item, $tabnum, $withtemplate)
    static function displayTabContentForItem(CommonGLPI $item, $tabnum = 1, $withtemplate = 0)
    {
        if ($item->getType() == Profile::class) {

            // Get the profile ID
            $ID = $item->getID();

            $prof = new self();
            $prof->showForm($ID);
        }

        return true;
    }

    //! @copydoc CommonGLPI::getTabNameForItem($item, $withtemplate)
    public function getTabNameForItem(CommonGLPI $item, $withtemplate = 0)
    {
        if ($item->getType() == Profile::class) {

            if ($item->getField('id')
                && ($item->getField('interface') != 'helpdesk')) {
                
                return __('DPO Register', 'newdporegister');
            }
        }

        return '';
    }

    /**
     * Show the current (or new) object formulaire
     * 
     * @param Integer $ID
     * @param Array $options
     */
    public function showForm($ID, $options = [])
    {
        $profile = new Profile();

        if (($canedit = Session::haveRightsOr(self::$rightname, [CREATE, UPDATE, PURGE]))) {
            echo "<form method='post' action='" . $profile->getFormURL() . "'>";
        }

        $profile->getFromDB($ID);
        if ($profile->getField('interface') == 'central') {

            $rights = $this->getAllRights();
            $profile->displayRightsChoiceMatrix($rights, [
                'canedit' => $canedit,
                'default_class' => 'tab_bg_2',
                'title' => __('General')
            ]);
        }

        if ($canedit) {

            echo "<div class='center'>";
            echo Html::hidden('id', ['value' => $ID]);
            echo Html::submit(_sx('button', 'Save'), ['name' => 'update']);
            echo "</div>\n";
            Html::closeForm();
        }
    }

    // --------------------------------------------------------------------
    //  PROFILE OBJECT SPECIFICS
    // --------------------------------------------------------------------

    /**
     * Return all specifics rights for the current Plugin
     * 
     * @param Boolean $all
     * 
     * @return Array $rights
     */
    public static function getAllRights($all = false)
    {
        $rights = [
            [
                'itemtype' => PluginNewdporegisterProcessing::class,
                'label' => PluginNewdporegisterProcessing::getTypeName(2),
                'field' => PluginNewdporegisterProcessing::$rightname,
                'rights' => [
                    CREATE => __('Create'),
                    READ => __('Read'),
                    UPDATE => __('Update'),
                    DELETE => __('Delete'),
                    PURGE => __('Delete permanently'),
                    READNOTE => __('Read notes'),
                    UPDATENOTE => __('Update notes'),
                ]
            ],
        ];

        return $rights;
    }

    /**
     * Remove all rights from the session global variable for the 
     * active profile
     */
    public static function removeRightsFromSession()
    {
        foreach (self::getAllRights(true) as $right) {

            if (isset($_SESSION['glpiactiveprofile'][$right['field']])) {
                unset($_SESSION['glpiactiveprofile'][$right['field']]);
            }
        }
    }

    /**
     * Initialize rights on the active profile.
     * This method is called on the 'change_profile' hook
     */
    public static function initProfile()
    {
        global $DB;

        $profile = new self();
  
        //Add new rights in glpi_profilerights table
        foreach ($profile->getAllRights() as $data) {

            if (countElementsInTable(
                "glpi_profilerights",
                "`name` = '" . $data['field'] . "'"
            ) == 0) {

                ProfileRight::addProfileRights([$data['field']]);
            }
        }

        $profiles = $DB->request("SELECT *
            FROM `glpi_profilerights`
            WHERE `profiles_id`='" . $_SESSION['glpiactiveprofile']['id'] . "'
            AND `name` LIKE 'plugin_newdporegister_%'");

        foreach ($profiles as $prof) {

            $_SESSION['glpiactiveprofile'][$prof['name']] = $prof['rights'];
        }
    }
}