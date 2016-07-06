/**
 * Blog JS File
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

!function($, app) {
    app.blog = {};
    app.blog.rating = {};

    app.blog.rating.change = function(postId, type) {
        type = type ? "plus" : "minus";

        app.core.ajaxPost(app.core.path + 'blog/rating/' + type + '/' + postId, {}, function(data) {
            if (data.code != 0)
                app.core.alert.show(data.type, data.message);
            else {
                var id = '#blog-rating-' + postId;
                var rating = data.tags.num;
                $(id).html(rating);
            }
        });
    };
    
    app.blog.calendar = function(month, year) {
        app.core.ajaxPost(app.core.path + 'blog/calendar/' + month + '/' + year, {}, function(data) {
            if (data.code != 0)
                app.core.alert.show(data.type, data.message);
            else {            	
            	$('#calendar').fadeOut(500, function() {
            		$("#calendar").html(data.calendar).fadeIn(500)
            	});
            }
        });
    };

    app.blog.addComment = function(eForm) {
        var params = $(eForm).serialize();

        app.core.ajaxPost(
            app.core.path + 'blog/addcomment',
            params,
            function(data) {
                app.core.alert.show(data.add.type, data.add.message);

                if (data.add.code == 0) {
                    $('#blog-comments').html(data.comments);
                }
            });
    };

    app.blog.removeComment = function(eForm, page) {
        var params = $(eForm).serialize();

        app.core.ajaxPost(
            app.core.path + 'blog/removecomment/page/' + page,
            params,
            function(data) {
                app.core.alert.show(data.remove.type, data.remove.message);

                if (data.remove.code == 0) {
                    $('#blog-comments').html(data.comments);
                }
            });
    };

    app.blog.replyComment = function(id, user) {
        $('#blog-reply-id').val(id);
        $('#blog-reply-user').text(user);
        $('#blog-reply-remove').show();
    };

    app.blog.replyRemove = function() {
        $('#blog-reply-id').val(0);
        $('#blog-reply-user').text('');
        $('#blog-reply-remove').hide();
    };
}(jQuery, app);
