/**
 * Created by Михаил on 15.03.16.
 */

var layout = null;
var buttons = null;
var finder = new Finder();
var buttonsContainer;
var leftButtonContainer;
var rightButtonContainer;
var contentContainer;
var titleContainer;
var pathContainer;
var lastLoadedUrl;

function init() {
    $.getJSON('/admin/layout.json', function (data) {
        layout = data;
        finder.setLayout(layout);
        $.getJSON('/admin/buttons.json', function (data) {
            buttons = data;

            var urlAfterRoot = getUrlAfterRoot();

            if (urlAfterRoot == null) {
                reload();
            } else {
                finder.setFolderWithUrl(urlAfterRoot);
                reload();
            }
        });
    });
}

function reload() {
    buildInterface(finder.getCurrentMenu(), finder.getFoldersArray());
}

function buildInterface(menu, path) {
    var title = titleContainer;
    title.fadeTo(130, 0.3, function () {
        title.html(menu.title);
        title.fadeTo(130, 1);
    });
    document.title = menu.title;

    pathContainer.html('');
    var i;
    var pathItemClass = 'pathItem';
    for (i = 0; i < path.length; i++) {
        if (i == path.length - 1) pathItemClass += ' bold';

        var pathItem = $('<a/>', {
            'text': path[i],
            'onclick': 'setDepth(' + i + ')',
            'class': pathItemClass
        });

        pathContainer.append(pathItem);
        pathContainer.append('/');
    }

    buttonsContainer.html('');
    if (menu.menu != undefined) {
        buttonsContainer.hide();
        for (i = 0; i < menu.menu.length; i++) {
            var button = $('<a/>', {
                'class': 'button-primary-2 inline',
                'onclick': 'goDeeper(' + i + ')',
                'text': menu.menu[i].title
            });

            buttonsContainer.append(button);
        }
        //buttonsContainer.show();
        //buttonsContainer.show(130);
        buttonsContainer.fadeTo(60, 1);
        //buttonsContainer.slideDown(200);
    }

    rightButtonContainer.html(getButtonWIthType(menu.rightButton).addClass('right'));
    leftButtonContainer.html(getButtonWIthType(menu.leftButton).addClass('left'));

    window.history.pushState(null, null, menu.url);

    contentContainer.html('');
    if (menu.hasContent) {
        loadContent(menu.url);
    }
}

function loadContent(url) {
    lastLoadedUrl = url;
    if (url != undefined) {
        $.ajax({
            url: url,
            type: "GET"
        }).done(function (data) {
            contentContainer.html(data);
        });
    }
}

function getButtonWIthType(type) {
    for (var i = 0; i < buttons.length; i++) {
        if (type == buttons[i].type) {
            return $('<a/>', {
                'class': buttons[i].class,
                'text': buttons[i].text,
                'onclick': buttons[i].onclick
            });
        }
    }

    return $('<a/>', {
        'class': 'empty'
    })
}

function getUrlAfterRoot() {
    var rootUrlEndIndex = window.location.href.indexOf(finder.getCurrentMenu().url);
    rootUrlEndIndex += finder.getCurrentMenu().url.length + 1;

    if (rootUrlEndIndex == window.location.href.length) {
        return null;
    } else {
        var nextUrl = '';
        var currentFullUrl = decodeURIComponent(window.location.href);

        for (var i = rootUrlEndIndex; i < currentFullUrl.length; i++) {
            nextUrl += currentFullUrl[i];
        }

        return '/admin/' + nextUrl;
    }
}

$(document).ready(function () {
    buttonsContainer = $('#buttonsContainer');
    leftButtonContainer = $('#leftButtonContainer');
    rightButtonContainer = $('#rightButtonContainer');
    contentContainer = $('#content');
    titleContainer = $('#title');
    pathContainer = $('#pathContainer');

    init();
});
