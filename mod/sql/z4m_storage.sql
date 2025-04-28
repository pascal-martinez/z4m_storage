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
 * ZnetDK 4 Mobile Storage module SQL script
 *
 * File version: 1.1
 * Last update: 04/25/2025
 */
CREATE TABLE `z4m_documents` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Internal identifier',
  `original_basename` VARCHAR(100) NOT NULL COMMENT 'Original file name with extension',
  `original_file_extension` VARCHAR(20) NOT NULL COMMENT 'Original file extension',
  `stored_basename` VARCHAR(100) NOT NULL COMMENT 'Stored file name with extension',
  `subdirectory` VARCHAR(50) NULL COMMENT 'Subdirectory',
  `filesize` INT NOT NULL COMMENT 'File size',
  `upload_datetime` DATETIME NOT NULL COMMENT 'Upload date and time',
  `username` VARCHAR(50) NOT NULL COMMENT 'User who uploaded file',
  `business_id` int(11) NULL COMMENT 'Business identifier',
  PRIMARY KEY (`id`),
  KEY `business_id`(`business_id`, `subdirectory`),
  KEY `subdirectory`(`subdirectory`, `business_id`),
  KEY `upload_datetime`(`upload_datetime`),
  KEY `filesize`(`filesize`),
  KEY `original_file_extension`(`original_file_extension`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Document' AUTO_INCREMENT=1 ;