var $mainSection = $('section.content#fileExplorer'),
    $fileExplorer = $('div.filemanager', $mainSection),
    breadcrumbs = $('div.breadcrumbs', $mainSection),
    $fileList = $fileExplorer.find('ul.data');

var data = false;
getList();
var response = [data],
    currentPath = '',
    breadcrumbsUrls = [];

var folders = [],
    files = [];

if (window.location.hash && window.location.hash === '#_=_') {
    if (window.history && history.pushState) {
        window.history.pushState("", document.title, window.location.pathname);
    } else {
        var scroll = {
            top: document.body.scrollTop,
            left: document.body.scrollLeft
        };
        window.location.hash = '';
        document.body.scrollTop = scroll.top;
        document.body.scrollLeft = scroll.left;
    }
}

// This event listener monitors changes on the URL. We use it to
// capture back/forward navigation in the browser.

$(window).on('hashchange', function() {

    goToHashPath(window.location.hash);
    $('div#uploader').html('');

    // We are triggering the event. This will execute
    // this function on page load, so that we show the correct folder:

}).trigger('hashchange');


// Hiding and showing the search box

/*    $fileExplorer.find('.search').on('click', function() {

        var search = $(this);

        search.find('span').hide();
        search.find('input[type=search]').show().on('focus');

    });*/


// Listening for keyboard input on the search field.
// We are using the "input" event which detects cut and paste
// in addition to keyboard input.

$('div.explorer-header > div.controls > div.search > input', $fileExplorer).on('input', function() {

    folders = [];
    files = [];

    var value = this.value.trim();

    if (value.length) {

        $fileExplorer.addClass('searching');

        // Update the hash on every key stroke
        window.location.hash = 'search=' + value.trim();

    }

    else {

        $fileExplorer.removeClass('searching');
        window.location.hash = encodeURIComponent(currentPath);

    }

}).on('keyup', function(e) {

    // Clicking 'ESC' button triggers focusout and cancels the search

    var searchBar = $(this);

    if (e.keyCode === 27) {

        searchBar.trigger('focusout');

    }

}).on('focusout', function() {

    // Cancel the search

    var searchBar = $(this);

    if (!searchBar.val().trim().length) {

        window.location.hash = encodeURIComponent(currentPath);
        //search.hide();
        searchBar.parent().find('span').show();

    }


});


// Clicking on folders

$fileList.on('click', 'li.folders', function(e) {
    e.preventDefault();

    if ($(this).hasClass('active')) {
        var nextDir = $(this).find('a.folders').attr('href');
        if ($fileExplorer.hasClass('searching')) {
            // Building the breadcrumbs
            breadcrumbsUrls = generateBreadcrumbs(nextDir);

            $fileExplorer.removeClass('searching');
            $fileExplorer.find('input[type=search]').val('');
            $fileExplorer.find('span').show();
        }
        else {
            breadcrumbsUrls.push(nextDir);
        }
        window.location.hash = encodeURIComponent(nextDir);
        currentPath = nextDir;
    } else {
        $('li', $fileList).removeClass('active');
        $(this).addClass('active');
    }
});

$fileExplorer.on('click', function(e) {
    if (!$(e.target).is('a, span')) {
        $('li', $fileList).removeClass('active');
    }
});

//Clicking on file
$fileList.on('click', 'li.files', function(e) {
    e.preventDefault();
    $('li', $fileList).removeClass('active');
    $(this).addClass('active');
});


// Clicking on breadcrumbs
breadcrumbs.on('click', 'a', function(e) {
    e.preventDefault();

    var index = breadcrumbs.find('a').index($(this)),
        nextDir = breadcrumbsUrls[index];

    breadcrumbsUrls.length = Number(index);

    window.location.hash = encodeURIComponent(nextDir);

});

$('div.actions > button', $mainSection).on('click', function() {
    var $file = $fileList.find('li.active'),
        action = $(this).attr('id'),
        folderName = '';
    if (action === 'add') {
        $('div.breadcrumb-rename', breadcrumbs).first().css('display', 'inline-block');
        $('button#validateRename').on('click', function() {
            folderName = $('div.breadcrumb-rename > input', breadcrumbs).val();
            if (folderName !== '') {
                $.post('http://supfile.tk/explorer', {
                    action: action,
                    path: currentPath,
                    folderName: folderName
                }, function() {
                    getList();
                });
            } else {
                $("#toast").html('Please type a file name.').fadeIn().delay(1500).fadeOut();
                $('div.breadcrumb-rename', breadcrumbs).first().hide();
            }
        });
    } else if (action === 'upload') {
        if (!$('div#uploader').html().length) {
            var uploader = new qq.FineUploader({
                debug: true,
                request: {
                    endpoint: '/uploader',
                    params: {
                        path: decodeURIComponent(window.location.hash).slice(1).split('=')[0]
                    }
                },
                retry: {
                    enableAuto: true
                },
                element: document.getElementById("uploader"),
                callbacks: {
                    onComplete: function() {
                        getList();
                    }
                }
            });
        }

    }

    else if ($file.length) {
        var path = $file.find('a').attr('href'),
            fileName = $file.find('span.name').text(),
            fileExtension = $file.find('a').data('extension'),
            isFile = $file.hasClass('files'),
            validFileName = new RegExp(/^[\w\-]*[\.][a-z]*$/i),
            validFolderName = new RegExp(/^[\w\-]*$/i),
            resultMessage = '';
        if (action === 'rename') {
            var $divRename = $file.find('div.rename');
            $divRename.show();
            $('div.right-buttons > button').on('click', function() {
                if ($(this).attr('id') === 'cancelRename')
                    $divRename.hide();
                else {
                    var newName = $divRename.find('input').val();
                    if (isFile && newName.length) {
                        newName += '.' + fileExtension;
                        if (validFileName.test(newName)) {
                            $.post('http://supfile.tk/explorer', {
                                action: action,
                                path: path,
                                newName: newName
                            }, function(data) {
                                getList(data);
                            });
                            resultMessage = 'Your file has been renamed.';
                        } else {
                            resultMessage = 'Your file name is not valid.';
                        }
                    } else {
                        if (validFolderName.test(newName)) {
                            $.post('http://supfile.tk/explorer', {
                                action: action,
                                path: path,
                                newName: newName
                            }, getList());
                            resultMessage = 'Your folder has been renamed.';

                        } else {
                            resultMessage = 'Your folder name is not valid.';
                        }
                    }
                }
                $("#toast").html(resultMessage).fadeIn().delay(1500).fadeOut();
            });

        } else if (action === 'download') {
            $.ajax({
                url: 'http://supfile.tk/explorer',
                type: 'POST',
                data: {action: action, path: path},
                cache: false,
                xhr: function() {
                    var xhr = new XMLHttpRequest();
                    xhr.responseType = 'blob';
                    return xhr;
                },
                success: function(data) {
                    var url = window.URL || window.webkitURL;
                    saveFile(fileName, data);
                },
                error: function() {

                }
            });
        } else {
            $.post('http://supfile.tk/explorer', {action: action, path: path}, function(data) {
                getList(data);
            });
        }
    } else {
        $("#toast").html('Please select a file').fadeIn().delay(1500).fadeOut();
    }

    /*getList();
    goToHashPath(encodeURIComponent(currentPath));*/
});

// Navigates to the given hash (path)

function goToHashPath(hash) {

    hash = decodeURIComponent(hash).slice(1).split('=');

    if (hash.length) {
        var rendered = '';

        // if hash has search in it

        if (hash[0] === 'search') {

            $fileExplorer.addClass('searching');
            rendered = searchData(response, hash[1].toLowerCase());

            if (rendered.length) {
                currentPath = hash[0];
                render(rendered);
            }
            else {
                render(rendered);
            }

        }

        // if hash is some path

        else if (hash[0].trim().length) {

            rendered = searchByPath(hash[0]);

            if (rendered.length) {

                currentPath = hash[0];
                breadcrumbsUrls = generateBreadcrumbs(hash[0]);
                render(rendered);

            }
            else {
                currentPath = hash[0];
                breadcrumbsUrls = generateBreadcrumbs(hash[0]);
                render(rendered);
            }

        }

        // if there is no hash

        else {
            currentPath = data.path;
            breadcrumbsUrls.push(data.path);
            render(searchByPath(data.path));
        }
    }
}

// Splits a file path and turns it into clickable breadcrumbs

function generateBreadcrumbs(nextDir) {
    var path = nextDir.split('/').slice(0);
    for (var i = 1; i < path.length; i++) {
        path[i] = path[i - 1] + '/' + path[i];
    }
    if (path[0] === 'files') {
        path.shift();
    }
    return path;
}


// Locates a file by path

function searchByPath(dir) {
    var path = dir.split('/'),
        demo = response,
        flag = 0;

    for (var i = 0; i < path.length; i++) {
        for (var j = 0; j < demo.length; j++) {
            if (demo[j].name === path[i]) {
                flag = 1;
                demo = demo[j].items;
                break;
            }
        }
    }

    demo = flag ? demo : [];
    return demo;
}


// Recursively search through the file tree

function searchData(data, searchTerms) {

    data.forEach(function(d) {
        if (d.type === 'folder') {

            searchData(d.items, searchTerms);

            if (d.name.toLowerCase().match(searchTerms)) {
                folders.push(d);
            }
        }
        else if (d.type === 'file') {
            if (d.name.toLowerCase().match(searchTerms)) {
                files.push(d);
            }
        }
    });
    return {folders: folders, files: files};
}


// Render the HTML for the file manager

function render(data) {

    var scannedFolders = [],
        scannedFiles = [];

    if (Array.isArray(data)) {

        data.forEach(function(d) {

            if (d.type === 'folder') {
                scannedFolders.push(d);
            }
            else if (d.type === 'file') {
                scannedFiles.push(d);
            }

        });

    }
    else if (typeof data === 'object') {

        scannedFolders = data.folders;
        scannedFiles = data.files;

    }


    // Empty the old result and make the new one

    $fileList.empty().hide();

    if (!scannedFolders.length && !scannedFiles.length) {
        $fileExplorer.find('div.nothingfound').show();
    }
    else {
        $fileExplorer.find('div.nothingfound').hide();
    }

    if (scannedFolders.length) {

        scannedFolders.forEach(function(f) {

            var itemsLength = f.items.length,
                name = escapeHTML(f.name),
                icon = '<span class="icon folder"></span>';

            if (itemsLength) {
                icon = '<span class="icon folder full"></span>';
            }

            if (itemsLength === 1) {
                itemsLength += ' item';
            }
            else if (itemsLength > 1) {
                itemsLength += ' items';
            }
            else {
                itemsLength = 'Empty';
            }

            var folder = $('<li class="folders">' +
                '<a href="' + f.path + '" title="' + f.path + '" class="folders">' + icon +
                '<span class="name">' + name + '</span> ' +
                '<span class="details">' + itemsLength + '</span>' +
                '<div class="rename">' +
                '<input type="text"> ' +
                '<div class="right-buttons">' +
                '<button id="cancelRename"><i class="fa fa-close"></i></button>' +
                '<button id="validateRename"><i class="fa fa-check"></i></button>' +
                '</div>' +
                '</div> ' +
                '</a>' +
                '</li>');
            folder.appendTo($fileList);
        });

    }

    if (scannedFiles.length) {

        scannedFiles.forEach(function(f) {

            var fileSize = bytesToSize(f.size),
                name = escapeHTML(f.name),
                fileType = name.split('.'),
                icon = '<span class="icon file"></span>';

            fileType = fileType[fileType.length - 1];

            icon = '<span class="icon file f-' + fileType + '">.' + fileType + '</span>';

            var file = $('<li class="files">' +
                '<a href="' + f.path + '" title="' + f.path + '" class="files" data-extension="' + fileType + '">' + icon +
                '<span class="name">' + name + '</span> ' +
                '<div class="rename">' +
                '<input type="text"> ' +
                '<div class="right-buttons">' +
                '<button id="cancelRename"><i class="fa fa-close"></i></button>' +
                '<button id="validateRename"><i class="fa fa-check"></i></button>' +
                '</div>' +
                '</div> ' +
                '<span class="details">' + fileSize + '</span>' +
                '</a>' +
                '</li>');
            file.appendTo($fileList);
        });

    }


    // Generate the breadcrumbs

    var url = '';
    var renameFolder = '<div class="breadcrumb-rename">\n' +
        '                    <img src="/public/img/right-chevron.png">\n' +
        '                    <input type="text" title="Name of new folder">\n' +
        '                    <button id="validateRename">\n' +
        '                        <i class="fa fa-check"></i></button>\n' +
        '                </div>';
    if ($fileExplorer.hasClass('searching')) {

        url = '<span>Search results: </span>';
        $fileList.removeClass('animated');

    }
    else {

        $fileList.addClass('animated');

        breadcrumbsUrls.forEach(function(u, i) {

            var name = u.split('/');
            if (name[name.length - 1] === uuid)
                name[name.length - 1] = 'My Drive';

            if (i !== breadcrumbsUrls.length - 1) {
                url += '<a href="' + u + '"><span class="folderName">' + name[name.length - 1] + '</span></a> <span class="arrow"><img src="/public/img/right-chevron.png"> </span> ';
            }
            else {
                url += '<span class="folderName">' + name[name.length - 1] + '</span>';
            }
        });
    }


    breadcrumbs.text('').append(url).append(renameFolder);


    // Show the generated elements

    $fileList.show().animate({'display': 'inline-block'});

}

function escapeHTML(text) {
    return text.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
}


function bytesToSize(fileSize, precision) {
    if (fileSize === 0) return "0 Bytes";
    var floatPrecision = precision || 2,
        sizes = ["Bytes", "KB", "MB", "GB", "TB", "PB", "EB", "ZB", "YB"],
        f = Math.floor(Math.log(fileSize) / Math.log(1024));
    return parseFloat((fileSize / Math.pow(1024, f)).toFixed(floatPrecision)) + " " + sizes[f]
}

function showPreview(path) {

}


function getList(files) {
    if (files) {

        data = files;
        response = [files];
        breadcrumbsUrls = '';
        goToHashPath(window.location.hash);
    } else {

        $.ajax({
            type: 'POST',
            url: 'http://supfile.tk/explorer',
            success: function(files) {
                data = files;
                response = [files];
                breadcrumbsUrls = '';
                goToHashPath(window.location.hash);
            },
            async: false
        });
    }
}

function saveFile(name, data) {
    if (data != null && navigator.msSaveBlob)
        return navigator.msSaveBlob(new Blob([data], {type: data.type}), name);
    var a = $("<a style='display: none;'/>");
    var url = window.URL.createObjectURL(new Blob([data], {type: data.type}));
    a.attr("href", url);
    a.attr("download", name);
    $("body").append(a);
    a[0].click();
    window.URL.revokeObjectURL(url);
    a.remove();
}