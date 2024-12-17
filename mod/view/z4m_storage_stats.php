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
 * ZnetDK 4 Mobile Storage module view
 *
 * File version: 1.0
 * Last update: 11/26/2024
 */

$storageStats = \z4m_storage\mod\StorageStats::getAll();
?>
<style>
    #z4m-storage-stats .progress-bar {
        overflow-wrap: normal;
        height: 22.8px;
        max-width: 100%;
    }
</style>
<div id="z4m-storage-stats" class="zdk-viewreload w3-content w3-section">
    <h3 class="w3-xlarge"><?php echo MOD_Z4M_STORAGE_USED_TOTAL_LABEL; ?>
        <span class="w3-tag w3-theme-dark w3-medium">
            <?php echo "{$storageStats['total']['space_display']} / {$storageStats['max_allowed']['space_display']}"; ?>
        </span>
    </h3>
    <div class="w3-theme-l3 w3-round-xlarge">
        <div class="progress-bar w3-container w3-theme w3-round-xlarge"
            role="progressbar" aria-valuenow="<?php echo $storageStats['total']['percent']; ?>" aria-valuemin="0"
            aria-valuetext="Total disk usage: <?php echo $storageStats['total']['percent']; ?>%" aria-valuemax="100" style="width:0%">0%</div>
    </div>
    <h3 class="w3-xlarge w3-padding-top-24"><?php echo MOD_Z4M_STORAGE_USED_DATABASE_LABEL; ?>
        <span class="w3-tag w3-theme-dark w3-medium">
            <?php echo $storageStats['database']['space_display']; ?>
        </span>
    </h3>
    <div class="w3-theme-l3 w3-round-xlarge">
        <div class="progress-bar w3-container w3-theme w3-round-xlarge"
            role="progressbar" aria-valuenow="<?php echo $storageStats['database']['percent']; ?>" aria-valuemin="0"
            aria-valuetext="Database disk usage: <?php echo $storageStats['database']['percent']; ?>%" aria-valuemax="100" style="width:0%">0%</div>
    </div>

    <h3 class="w3-xlarge w3-padding-top-24"><?php echo MOD_Z4M_STORAGE_USED_DOCUMENTS_LABEL; ?>
        <span class="w3-tag w3-theme-dark w3-medium">
            <?php echo $storageStats['documents']['space_display']; ?>
        </span>
    </h3>
    <div class="w3-theme-l3 w3-round-xlarge">
        <div class="progress-bar w3-container w3-theme w3-round-xlarge"
            role="progressbar" aria-valuenow="<?php echo $storageStats['documents']['percent']; ?>" aria-valuemin="0"
            aria-valuetext="Documents disk usage: <?php echo $storageStats['documents']['percent']; ?>%" aria-valuemax="100" style="width:0%">0%</div>
    </div>
<?php if (\MenuManager::getMenuItem('z4m_storage_documents') !== NULL 
        && \controller\Users::hasMenuItem('z4m_storage_documents') 
        && $storageStats['documents']['filecount'] > 0) : ?>
    <div class="w3-section w3-bar">
        <a href="javascript:void(0)" onclick="znetdkMobile.content.displayView('z4m_storage_documents');" class="w3-right"><?php echo General::getFilledMessage(MOD_Z4M_STORAGE_USED_SEE_DOCUMENTS_LABEL,$storageStats['documents']['filecount']); ?></a>
    </div>
<?php endif; ?>
</div>
<script>
    (function(){
        const delay = 3000;
        const dbProgressEl = $('#z4m-storage-stats .progress-bar');
        dbProgressEl.each(function(){
            $(this).animate({
                width: $(this).attr('aria-valuenow') + '%'
            },{duration: delay, step: function(now) {
                    $(this).text(Math.ceil(now) + '%');
                    if (now > 70) {
                        $(this).removeClass('w3-theme').addClass('w3-orange');
                    }
                    if (now > 90) {
                        $(this).removeClass('w3-orange').addClass('w3-red');
                    }
                }
            });
        });

    })();
</script>