<?php

/* 
 * ZnetDK, Starter Web Application for rapid & easy development
 * See official website https://www.znetdk.fr
 * Copyright (C) 2025 Pascal MARTINEZ (contact@znetdk.fr)
 * License GNU GPL http://www.gnu.org/licenses/gpl-3.0.html GNU GPL
 * --------------------------------------------------------------------
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 * --------------------------------------------------------------------
 * ZnetDK 4 Mobile Storage module view fragment
 *
 * File version: 1.0
 * Last update: 05/18/2025
 */
$storageStats = \z4m_storage\mod\StorageStats::getAll();
?>        
        <div class="w3-card w3-padding-16 w3-section w3-container w3-border">
            <div class="w3-left">
                <i class="fa fa-database w3-xxlarge"></i>
                <div class="w3-leftbar w3-padding-small w3-stretch <?php echo $color['nav_menu_bar_select']; ?> w3-margin-top"><b><?php echo MOD_Z4M_STORAGE_MENU_LABEL; ?></b></div>
            </div>
            <div class="w3-right">
                <div class="w3-xxlarge w3-center"><?php echo $storageStats['total']['percent']; ?>%</div>
                <div class="w3-small"><?php echo "{$storageStats['total']['space_display']} / {$storageStats['max_allowed']['space_display']}"; ?></div>
            </div>
        </div>
