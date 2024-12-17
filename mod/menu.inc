<?php
/**
 * ZnetDK, Starter Web Application for rapid & easy development
 * See official website https://mobile.znetdk.fr
 * Copyright (C) 2024 Pascal MARTINEZ (contact@znetdk.fr)
 * License GNU GPL https://www.gnu.org/licenses/gpl-3.0.html GNU GPL
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
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 * --------------------------------------------------------------------
 * Menu definition of the ZnetDK 4 Mobile Storage module
 *
 * File version: 1.0
 * Last update: 05/24/2024
 */
\MenuManager::addMenuItem(NULL, '_storage', MOD_Z4M_STORAGE_MENU_LABEL, 'fa-database');
\MenuManager::addMenuItem('_storage', 'z4m_storage_stats', MOD_Z4M_STORAGE_USED_MENU_LABEL, 'fa-tachometer');
\MenuManager::addMenuItem('_storage', 'z4m_storage_documents', MOD_Z4M_STORAGE_DOCUMENTS_MENU_LABEL, 'fa-file-o');

