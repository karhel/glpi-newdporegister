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

define('PLUGIN_NEWDPOREGISTER_VERSION', '2.0.0');

/**
 * Init hooks of the plugin.
 * REQUIRED
 *
 * @return void
 */
function plugin_init_newdporegister() {
   global $PLUGIN_HOOKS;

   $PLUGIN_HOOKS['csrf_compliant']['newdporegister'] = true;
}

/**
 * Get the name and the version of the plugin
 * REQUIRED
 *
 * @return array
 */
function plugin_version_newdporegister() {
   return [
      'name'           => 'NewDpoRegister',
      'version'        => PLUGIN_NEWDPOREGISTER_VERSION,
      'author'         => '<a href="http://www.teclib.com">Teclib\'</a>',
      'license'        => '',
      'homepage'       => '',
      'requirements'   => [
         'glpi' => [
            'min' => '9.2',
         ]
      ]
   ];
}

/**
 * Check pre-requisites before install
 * OPTIONNAL, but recommanded
 *
 * @return boolean
 */
function plugin_newdporegister_check_prerequisites() {

   //Version check is not done by core in GLPI < 9.2 but has to be delegated to core in GLPI >= 9.2.
   $version = preg_replace('/^((\d+\.?)+).*$/', '$1', GLPI_VERSION);
   if (version_compare($version, '9.2', '<')) {
      echo "This plugin requires GLPI >= 9.2";
      return false;
   }
   return true;
}

/**
 * Check configuration process
 *
 * @param boolean $verbose Whether to display message on failure. Defaults to false
 *
 * @return boolean
 */
function plugin_newdporegister_check_config($verbose = false) {
   if (true) { // Your configuration check
      return true;
   }

   if ($verbose) {
      echo __('Installed / not configured', 'newdporegister');
   }
   return false;
}
