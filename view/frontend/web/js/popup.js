define([
    'jquery',
    'Magento_Ui/js/modal/modal',
    'mage/cookies'
], function($, modal) {
    'use strict';

    $.widget('up.newsletterPopup', {
        options: {
            cookieName: 'up-newsletter-popup',
            popupModalClass: 'newsletter-popup-modal',
            popupSelector: '#up-newsletter-popup'
        },

        _create: function () {
            this._bind();
        },

        _bind: function() {
            var self = this,
                popupOptions = {
                  type: 'popup',
                  innerScroll: true,
                  title: this.options.title,
                  modalClass: this.options.popupModalClass,
                  buttons: ''
                };

            if (this._isCookieSet(this.options.cookieName) != true) {
                setTimeout(function () {
                    this._openModal(popupOptions, self.options.cookieName);
                }.bind(this), this._getDelay());
            }
        },

        _openModal: function (options, cookie) {
            var popup = modal(options, $(this.options.popupSelector));
            $(this.options.popupSelector).modal('openModal');
            this._setCookie(cookie);
        },

        _getDelay: function () {
            return this.options.delay * 1000;
        },

        _setCookie: function (cookie) {
            var expire = new Date();
            expire.setDate(expire.getDate() + 7);
            $.mage.cookies.set(cookie, 'yes', { expires: expire, path: '/' });
        },

        _isCookieSet: function (cookie) {
            if ($.mage.cookies.get(cookie) == 'yes') {
                return true;
            }
            return false;
        }
    });

    return $.up.newsletterPopup;
});
