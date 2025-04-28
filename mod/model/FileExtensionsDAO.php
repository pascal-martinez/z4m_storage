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
 * ZnetDK 4 Mobile Storage module DAO class
 *
 * File version: 1.1
 * Last update: 04/26/2025
 */
namespace z4m_storage\mod\model;

/**
 * File extension DAO
 */
class FileExtensionsDAO extends \DAO {

    /**
     * Initializes the DAO
     */
    protected function initDaoProperties() {     
        $this->table = 'z4m_documents'; // Required when the DAO is passed in parameter of Document::createModuleSqlTable()
        $this->query = "SELECT DISTINCT original_file_extension FROM z4m_documents";
    }

}
