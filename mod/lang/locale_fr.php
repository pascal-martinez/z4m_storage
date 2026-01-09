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
 * ZnetDK 4 Mobile Storage Module French translations
 *
 * File version: 1.3
 * Last update: 01/09/2026
 */
define('MOD_Z4M_STORAGE_MENU_LABEL', 'Stockage');
define('MOD_Z4M_STORAGE_USED_MENU_LABEL', 'Conso. disque');
define('MOD_Z4M_STORAGE_DOCUMENTS_MENU_LABEL', 'Documents');

define('MOD_Z4M_STORAGE_FILE_SIZE_UNITS', ['o', 'Ko', 'Mo', 'Go']);
define('MOD_Z4M_STORAGE_USED_TOTAL_LABEL', 'TOTAL');
define('MOD_Z4M_STORAGE_USED_DATABASE_LABEL', 'Base de données');
define('MOD_Z4M_STORAGE_USED_DOCUMENTS_LABEL', 'Documents');
define('MOD_Z4M_STORAGE_USED_SEE_DOCUMENTS_LABEL', 'Voir les %1 documents');

define('MOD_Z4M_STORAGE_DOCUMENTS_UPLOAD_BUTTON', 'Ajouter des documents...');
define('MOD_Z4M_STORAGE_PHOTOS_UPLOAD_BUTTON', 'Ajouter des photos...');
define('MOD_Z4M_STORAGE_DOCUMENTS_REMOVE_BUTTON', 'Suppression...');
define('MOD_Z4M_STORAGE_DOCUMENTS_DOWNLOAD_LINK', 'Téléchargement...');
define('MOD_Z4M_STORAGE_DOCUMENTS_DISPLAY_LINK', 'Afficher...');
define('MOD_Z4M_STORAGE_DOCUMENTS_REMOVE_QUESTION', 'Supprimer <b>%filename%</b> ?');
define('MOD_Z4M_STORAGE_DOCUMENTS_FILENAME_LABEL', 'Fichier');
define('MOD_Z4M_STORAGE_DOCUMENTS_UPLOAD_DATE_LABEL', 'Chargé le');
define('MOD_Z4M_STORAGE_DOCUMENTS_USER_LABEL', 'Chargé par');
define('MOD_Z4M_STORAGE_DOCUMENTS_SUBDIRECTORY_LABEL', 'Sous-dossier');
define('MOD_Z4M_STORAGE_DOCUMENTS_FILESIZE_LABEL', 'Taille');
define('MOD_Z4M_STORAGE_DOCUMENTS_EMPTY', 'Aucun document.');
define('MOD_Z4M_STORAGE_PHOTOS_EMPTY', 'Aucune photo.');

define('MOD_Z4M_STORAGE_LIST_FILTER_PERIOD_START', 'Période du');
define('MOD_Z4M_STORAGE_LIST_FILTER_PERIOD_END', 'Au');
define('MOD_Z4M_STORAGE_LIST_FILTER_OPTION_CHOOSE_LABEL', 'Choisissez une valeur...');
define('MOD_Z4M_STORAGE_LIST_FILTER_SUBDIRECTORY_LABEL', 'Sous-dossier');
define('MOD_Z4M_STORAGE_LIST_FILTER_FILE_EXTENSION_LABEL', 'Type de fichier');
define('MOD_Z4M_STORAGE_LIST_FILTER_FILE_SIZE_LABEL', 'Taille');
define('MOD_Z4M_STORAGE_PURGE_BUTTON_LABEL', 'Purger...');
define('MOD_Z4M_STORAGE_PURGE_CONFIRMATION_TEXT', 'Confirmez-vous la purge des documents ?');
define('MOD_Z4M_STORAGE_PURGE_SUCCESS', 'Purge des documents réussie.');
define('MOD_Z4M_STORAGE_DOWNLOAD_BUTTON_LABEL', 'Télécharger...');
define('MOD_Z4M_STORAGE_DOWNLOAD_CONFIRMATION_TEXT', 'Confirmez-vous le téléchargement de %1 documents ?');
define('MOD_Z4M_STORAGE_DOWNLOAD_ZIP_FILENAME', 'documents.zip');

define('MOD_Z4M_STORAGE_DOCUMENTS_ERROR_DOWNLOAD_NOT_EXISTS', "Le fichier n'existe plus.");
define('MOD_Z4M_STORAGE_DOCUMENTS_SUCCESS_UPLOAD', '%count% fichiers ajoutés.');
define('MOD_Z4M_STORAGE_DOCUMENTS_ERROR_UPLOAD_FILESIZE_EXCEEDED', 'La taille maximale de <b>%max_filesize%</b> dépassée pour le fichier <b>%filename%</b>.');
define('MOD_Z4M_STORAGE_DOCUMENTS_ERROR_UPLOAD_SELECTION_FILESIZE_EXCEEDED', 'La taille maximale de <b>%max_selectionfilesize%</b> dépassée pour les <b>%file_count% fichiers</b> sélectionnés.');
define('MOD_Z4M_STORAGE_DOCUMENTS_ERROR_UPLOAD_DISKSPACE_EXCEEDED', 'Espace disque maximum de <b>%max_diskspace%</b> dépassé pour stocker le fichier <b>%filename%</b>.');
define('MOD_Z4M_STORAGE_DOCUMENTS_ERROR_UPLOAD_OTHER', "L'ajout des fichiers a échoué.");
define('MOD_Z4M_STORAGE_DOCUMENTS_ERROR_FETCH', "Echec de lecture des fichiers.");
define('MOD_Z4M_STORAGE_DOCUMENTS_SUCCESS_REMOVE', "'%filename%' supprimé.");
define('MOD_Z4M_STORAGE_DOCUMENTS_ERROR_REMOVE', "Echec de suppression du fichier.");