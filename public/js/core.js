/**
 * HarmonyCMS Core
 * @copyright Copyright (C) 2016 al3xable <al3xable@yandex.com>. All rights reserved.
 * @license https://opensource.org/licenses/GPL-3.0 GNU General Public License version 3
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>
 */

var app = {
		core: {}
	}, lang = {
		loadingLayerText: 'Loading... Please wait...',
        unknownError: 'Unknown error'
    };

!function($, app, lang) {
	/********
	 * AJAX *
	 ********/
	
	/**
     * AJAX POST Request
     * @param url Request address
     * @param data Request data
     * @param callback Request success callback
     */
    app.core.ajaxPost = function (url, data, callback) {
        app.core.loading(1);

        $.ajax({
            url: url,
            type: 'POST',
            data: data,
            dataType: 'json',
            success: callback,
            error: function (info) {
                app.core.alert.show('danger', lang.unknownError);
                console.error(info.responseText);
            },
            complete: function () {
                app.core.loading(0);
            }
        });
    };
    
    /**********
	 * ALERTS *
	 **********/
    app.core.alert = {};

    app.core.alert.id = 0;
    app.core.alert.liveTime = 5000;

    /**
     * Alert show
     * @param type Alert type
     * @param message Alert message
     * @param auto_close Auto close alert?
     */
    app.core.alert.show = function(type, message, auto_close) {
        var id = this.id,
            close = (auto_close == null) ? true : auto_close;

        this.id++;

        // SHOW ALERT
        $('<div class="cms-alert ' + type + '" id="alert-' + id + '">' + message + '</div>')
            .appendTo('#alerts')
            .hide()
            .fadeIn(300)
            .click(function() {
                app.core.alert.remove(id);
            });

        // CLOSE ALERT
        if (close)
            setTimeout(function () {
                app.core.alert.remove(id);
            }, this.liveTime);

        return id;
    };

    /**
     * Alert remove
     * @param id Alert ID
     */
    app.core.alert.remove = function(id) {
        $('#alert-' + id).fadeOut(300, function() {
            $('#alert-' + id).remove();
        });
    };
    
    /*****************
	 * LOADING LAYER *
	 *****************/
	app.core.loadingLayerId = '#loading-layer';
    lang.loadingLayerText = 'Loading... Please wait...';

    /**
     * Show loading
     * @param act Is show?
     */
    app.core.loading = function(act) {
        var id = this.loadingLayerId;

        if (act) {
            var left = ($(window).width() - $(id).width()) / 2,
                top = ($(window).height() - $(id).height()) / 2;

            $(id)
                .text(lang.loadingLayerText)
                .css({left: left + 'px', top: top + 'px', position: 'fixed', zIndex: 10000})
                .fadeIn(250);
        } else
            $(id).fadeOut(250);
    };
    
    /*****************
     * NOTIFICATIONS *
     *****************/
	app.core.notifications = {};

    app.core.notifications.get = function() {
        app.core.ajaxPost(app.core.path + "user/notifications/get", {}, function(data) {
            $("#core-notifications").html(data.tags["page-rows"]);
 
            if (data.tags["num"] > 0) {
                $("#core-notifications-new").addClass("text-primary");
            } else {
                $("#core-notifications-new").removeClass("text-primary");
            }
        });
    };

    app.core.notifications.clear = function() {
        app.core.ajaxPost(app.core.path + "user/notifications/clear", {}, function(data) {
            if (data.code == 0)
                app.core.notifications.get();
            else
                app.core.alert.show(data.type, data.message);
        });
    };

    app.core.notifications.remove = function(id) {
        app.core.ajaxPost(app.core.path + "user/notifications/remove", {
            id: id
        }, function(data) {
            if (data.code == 0)
                app.core.notifications.get();
            else
                app.core.alert.show(data.type, data.message);
        });
    };
	 
}(jQuery, app, lang);
