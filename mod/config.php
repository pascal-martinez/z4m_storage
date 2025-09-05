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
 * Parameters of the ZnetDK 4 Mobile Storage module
 *
 * File version: 1.5
 * Last update: 09/04/2025
 */

/**
 * Maximum file size allowed on document upload. This value is only taken into
 * account if the php.ini directive 'upload_max_filesize' is set to a higher
 * file size.
 * @return int File size in bytes
 */
define('MOD_Z4M_STORAGE_MAX_UPLOAD_FILESIZE_IN_BYTES', 1024*1024*20 /* 20 MB */);

/**
 * Maximum disk space allowed to store the application database and files
 * uploaded by users.
 * @return int disk space in bytes
 */
define('MOD_Z4M_STORAGE_MAX_SPACE_IN_BYTES', 1024*1024*1024 /* 1 GB */);

/**
 * Maximum width of the photos once reduced.
 * @return int Width in pixels
 */
define('MOD_Z4M_STORAGE_MAX_PHOTO_WIDTH_IN_PIXELS', 800);

/**
 * Maximum width of the photo thumbnails displayed for preview.
 * @return int Width in pixels
 */
define('MOD_Z4M_STORAGE_MAX_PHOTO_THUMBNAIL_WIDTH_IN_PIXELS', 160);

/**
 * Views that the user must have access to in order to download, view and delete
 * files.
 * @return array | NULL An array of view names (without .php extension) or NULL
 * if document management is allowed to all users of the application.
 */
define('MOD_Z4M_STORAGE_DOCUMENT_MANAGEMENT_VIEWS_ALLOWED', NULL);

/**
 * Path of the SQL script to update the database schema
 * @var string Path of the SQL script
 */
define('MOD_Z4M_STORAGE_SQL_SCRIPT_PATH', ZNETDK_MOD_ROOT
        . DIRECTORY_SEPARATOR . 'z4m_storage' . DIRECTORY_SEPARATOR
        . 'mod' . DIRECTORY_SEPARATOR . 'sql' . DIRECTORY_SEPARATOR
        . 'z4m_storage.sql');

/**
 * Module version number
 * @return string Version
 */
define('MOD_Z4M_STORAGE_VERSION_NUMBER','1.6');
/**
 * Module version date
 * @return string Date in W3C format
 */
define('MOD_Z4M_STORAGE_VERSION_DATE','2025-09-04');