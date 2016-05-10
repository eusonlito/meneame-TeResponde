jQuery(function($) {
    'use strict';

    var postsCache = [];

    $('#main-search input').selectize({
        valueField: 'link',
        labelField: 'title',
        searchField: 'title',
        renderCache: true,
        create: false,
        sortField: 'id DESC',
        render: {
            option: function(item, escape) {
                return '<div>'
                    + '<span class="title">' + escape(item.title) + '</span>'
                    + '<span class="description">Enviado por <strong>' + escape(item.user) + '</strong> el ' + escape(item.date) + '</span>'
                + '</div>';
            }
        },
        onItemAdd: function(value) {
            this.clear();
            this.close();

            window.location = WWW + '/' + value;
        },
        load: function(query, callback) {
            if (!query.length) {
                return callback();
            }

            if (postsCache.length) {
                return callback(postsCache);
            }

            $.ajax({
                url: WWW + '/storage/cache/posts.json',
                dataType: 'json',
                cache: false,
                error: function() {
                    callback();
                },
                success: function(res) {
                    callback(postsCache = res);
                }
            });
        }
    });
});