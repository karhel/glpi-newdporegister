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

function plugin_newdporegister_classes()
{
    return [
        'Profile',
        'Processing',
    ];
}

/**
 * Plugin install process
 *
 * @return boolean
 */
function plugin_newdporegister_install() { 
   
   $migration = new Migration(PLUGIN_NEWDPOREGISTER_VERSION);
   $classesToInstall = plugin_newdporegister_classes();

   foreach ($classesToInstall as $className) {

       require_once('inc/' . strtolower($className) . '.class.php');

       $fullclassname = 'PluginNewdporegister' . $className;
       $fullclassname::install($migration, PLUGIN_NEWDPOREGISTER_VERSION);
   }

   return true;
}

/**
 * Plugin uninstall process
 *
 * @return boolean
 */
function plugin_newdporegister_uninstall() {

   $classesToUninstall = plugin_newdporegister_classes();

    foreach ($classesToUninstall as $className) {

        require_once('inc/' . strtolower($className) . '.class.php');

        $fullclassname = 'PluginNewdporegister' . $className;
        $fullclassname::uninstall();
    }

    return true;
}
