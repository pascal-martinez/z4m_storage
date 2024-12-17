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
 * ZnetDK 4 Mobile Storage Module Spanish translations
 *
 * File version: 1.0
 * Last update: 12/04/2024
 */
define('MOD_Z4M_STORAGE_MENU_LABEL', 'Almacenamiento');
define('MOD_Z4M_STORAGE_USED_MENU_LABEL', 'Disco utilizado');
define('MOD_Z4M_STORAGE_DOCUMENTS_MENU_LABEL', 'Documentos');

define('MOD_Z4M_STORAGE_FILE_SIZE_UNITS', ['B', 'KB', 'MB', 'GB']);
define('MOD_Z4M_STORAGE_USED_TOTAL_LABEL', 'TOTAL');
define('MOD_Z4M_STORAGE_USED_DATABASE_LABEL', 'Base de datos');
define('MOD_Z4M_STORAGE_USED_DOCUMENTS_LABEL', 'Documentos');
define('MOD_Z4M_STORAGE_USED_SEE_DOCUMENTS_LABEL', 'Ver los %1 documentos');

define('MOD_Z4M_STORAGE_DOCUMENTS_UPLOAD_BUTTON', 'Subir documentos...');
define('MOD_Z4M_STORAGE_PHOTOS_UPLOAD_BUTTON', 'Subir fotos...');
define('MOD_Z4M_STORAGE_DOCUMENTS_REMOVE_BUTTON', 'Eliminar...');
define('MOD_Z4M_STORAGE_DOCUMENTS_DOWNLOAD_LINK', 'Descargar...');
define('MOD_Z4M_STORAGE_DOCUMENTS_REMOVE_QUESTION', '¿Eliminar el documento <b>%filename%</b>?');
define('MOD_Z4M_STORAGE_PHOTOS_REMOVE_QUESTION', '¿Eliminar la foto <b>%filename%</b>?');
define('MOD_Z4M_STORAGE_DOCUMENTS_FILENAME_LABEL', 'Archivo');
define('MOD_Z4M_STORAGE_DOCUMENTS_UPLOAD_DATE_LABEL', 'Fecha de subida');
define('MOD_Z4M_STORAGE_DOCUMENTS_USER_LABEL', 'Usuario');
define('MOD_Z4M_STORAGE_DOCUMENTS_SUBDIRECTORY_LABEL', 'Subdirectorio');
define('MOD_Z4M_STORAGE_DOCUMENTS_FILESIZE_LABEL', 'Tamaño');
define('MOD_Z4M_STORAGE_DOCUMENTS_EMPTY', 'Sin documentos.');
define('MOD_Z4M_STORAGE_PHOTOS_EMPTY', 'Sin fotos.');

define('MOD_Z4M_STORAGE_LIST_FILTER_PERIOD_START', 'Período del');
define('MOD_Z4M_STORAGE_LIST_FILTER_PERIOD_END', 'Al');
define('MOD_Z4M_STORAGE_LIST_FILTER_OPTION_CHOOSE_LABEL', 'Elige un valor...');
define('MOD_Z4M_STORAGE_LIST_FILTER_SUBDIRECTORY_LABEL', 'Subdirectorio');
define('MOD_Z4M_STORAGE_LIST_FILTER_FILE_EXTENSION_LABEL', 'Typo de archivo');
define('MOD_Z4M_STORAGE_LIST_FILTER_FILE_SIZE_LABEL', 'Tamaño');
define('MOD_Z4M_STORAGE_PURGE_BUTTON_LABEL', 'Purgar...');
define('MOD_Z4M_STORAGE_PURGE_CONFIRMATION_TEXT', '¿Confirmas la purga de los documentos?');
define('MOD_Z4M_STORAGE_PURGE_SUCCESS', 'Purga de los documentos exitosa.');

define('MOD_Z4M_STORAGE_DOCUMENTS_ERROR_DOWNLOAD_NOT_EXISTS', 'El documento ya no existe.');
define('MOD_Z4M_STORAGE_DOCUMENTS_SUCCESS_UPLOAD', '%count% documentos cargados.');
define('MOD_Z4M_STORAGE_DOCUMENTS_ERROR_UPLOAD_FILESIZE_EXCEEDED', 'Se excedió el tamaño máximo permitido de <b>%max_filesize%</b> para el archivo <b>%filename%</b>.');
define('MOD_Z4M_STORAGE_DOCUMENTS_ERROR_UPLOAD_SELECTION_FILESIZE_EXCEEDED', 'Se excedió el tamaño máximo permitido de <b>%max_selectionfilesize%</b> para los <b>%file_count% archivos</b> seleccionados.');
define('MOD_Z4M_STORAGE_DOCUMENTS_ERROR_UPLOAD_DISKSPACE_EXCEEDED', 'Se superó el espacio máximo en disco de <b>%max_diskspace%</b> para almacenar el archivo <b>%filename%</b>.');
define('MOD_Z4M_STORAGE_DOCUMENTS_ERROR_UPLOAD_OTHER', 'La carga de los documentos ha fallado.');
define('MOD_Z4M_STORAGE_DOCUMENTS_ERROR_FETCH', "No se pueden obtener los documentos.");
define('MOD_Z4M_STORAGE_DOCUMENTS_SUCCESS_REMOVE', "'%filename%' eliminado.");
define('MOD_Z4M_STORAGE_DOCUMENTS_ERROR_REMOVE', "No se pueden eliminar el documento.");