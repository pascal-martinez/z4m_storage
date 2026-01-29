/* 
 * ZnetDK, Starter Web Application for rapid & easy development
 * See official website https://www.znetdk.fr
 * Copyright (C) 2026 Pascal MARTINEZ (contact@znetdk.fr)
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
 * ZnetDK 4 Mobile Sorage module JS library
 * 
 * File version: 1.0
 * Last update: 01/25/2026
 */

/**
 * Photo viewer to display photos in an inner modal dialog.
 * The modal is loaded only once even if this class is instantiated multiple
 * times.
 */
export class Z4M_StoragePhotoViewer {
    #modalViewName = 'z4m_storage_image_modal'
    #modalSelector = '#z4m-storage-image-modal'
    #containerEl
    #downloadURL
    #photos
    constructor(containerEl, downloadURL) {
        this.#containerEl = containerEl;
        this.#downloadURL = downloadURL;
        this.#scanPhotosInContainer();
    }
    #getPrevButton() {
        return $(this.#modalSelector + ' a.prev');
    }
    #getNextButton() {
        return $(this.#modalSelector + ' a.next');
    }
    #handleEvents() {
        const $this = this, evt = 'click.Z4M_StoragePhotoViewer';
        this.#getPrevButton().off(evt).on(evt, function(){
            $this.#showPrevious();
        });
        this.#getNextButton().off(evt).on(evt, function(){
            $this.#showNext();
        });
    }
    #scanPhotosInContainer() {
        this.#photos = [];
        if (this.#containerEl === undefined || $(this.#containerEl).length < 1
                || typeof this.#downloadURL !== 'string') {
            return;
        }
        const $this = this;
        this.#containerEl.find('img').each(function(){
            const alt = $(this).attr('alt'),
                photoId = $(this).closest('.file').data('id'),
                src = $this.#downloadURL + '&doc_id=' + encodeURIComponent(photoId);
            $this.addPhoto(src, alt);
        });
    }
    addPhoto(src, alt) {
        this.#photos.push({src: src, alt: alt});
    }
    #getPhoto(pos) {
        if (pos > 0 && pos <= this.#photos.length) {
            return this.#photos[pos-1];
        }
    }
    #getPhotoPosition(src, alt) {
        let pos = 1;
        for (const photo of this.#photos) {
            if (photo.src === src && photo.alt === alt) {
                return pos;
            }
            pos++;
        }
        return 0;
    }
    #toggleNavButtons(src, alt) {
        this.#getPrevButton().addClass('w3-hide');
        this.#getNextButton().addClass('w3-hide');
        const pos = this.#getPhotoPosition(src, alt);
        if (pos > 1) {
            this.#getPrevButton().removeClass('w3-hide');
        }
        if (pos > 0 && pos < this.#photos.length) {
            this.#getNextButton().removeClass('w3-hide');
        }
    }
    #loadModal() {
        const $this = this;
        return new Promise(function(resolve) {
            z4m.modal.make($this.#modalSelector, $this.#modalViewName, function(){
                $this.#handleEvents();                
                resolve(this);// Z4M Modal object returned
            });
        });
    }
    #getVisiblePhotoPos() {
        const curPhoto = $(this.#modalSelector + ' img.photo');
        return this.#getPhotoPosition(curPhoto.attr('src'), curPhoto.attr('alt'));
    }
    #showPrevious() {
        const pos = this.#getVisiblePhotoPos();
        if (pos > 1) {
            this.#removePhotoInModal();
            z4m.ajax.toggleLoader(true);
            const prev = this.#getPhoto(pos-1);
            this.#insertPhotoInModal(prev.src, prev.alt);
        }
    }
    #showNext() {
        const pos = this.#getVisiblePhotoPos();
        if (pos > 0 && pos < this.#photos.length) {
            this.#removePhotoInModal();
            z4m.ajax.toggleLoader(true);
            const next = this.#getPhoto(pos+1);
            this.#insertPhotoInModal(next.src, next.alt);
        }
    }
    #removePhotoInModal() {
        $(this.#modalSelector + ' img.photo').remove();
    }
    #insertPhotoInModal(src, alt) {
        const $this = this, imgTpl = $(this.#modalSelector + ' template.image-tpl'),
            img = imgTpl.contents().filter('img').clone();
        const loading = new Promise(function(resolve){
            img[0].onload = function() {
                z4m.ajax.toggleLoader(false);
                $this.#toggleNavButtons(src, alt);
                resolve(true);
            };
            img[0].onerror = function() {
                z4m.ajax.toggleLoader(false);
                z4m.messages.notify(imgTpl.data('error-title'), imgTpl.data('error-msg'));
                resolve(false);
            };
        });        
        $(this.#modalSelector + ' header').before(img);
        img.addClass('photo');
        img.attr('alt', alt);
        img.attr('src', src);
        return loading;
    }
    async show(src, alt) {
        z4m.ajax.toggleLoader(true);
        const $this = this, modal = await this.#loadModal();
        if (await this.#insertPhotoInModal(src, alt)) {
            modal.open(null, function(){
                $this.#removePhotoInModal();
            });
        }
    }    
}
