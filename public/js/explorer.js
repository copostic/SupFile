var $mainSection = $('section.content#fileExplorer'),
    $filemanager = $('div.filemanager', $mainSection),
    breadcrumbs = $('div.breadcrumbs', $mainSection),
    fileList = $filemanager.find('ul.data');

// Start by fetching the file data from scan.php with an AJAX request

$.post('http://supfile.tk/explorer', function(data) {
    var response = [data],
        currentPath = '',
        breadcrumbsUrls = [];

    var folders = [],
        files = [];

    //Disable right click on file explorer
    $mainSection.on('contextmenu', function(e) {
        //e.preventDefault();
    });

    // This event listener monitors changes on the URL. We use it to
    // capture back/forward navigation in the browser.

    $(window).on('hashchange', function() {

        goto(window.location.hash);

        // We are triggering the event. This will execute
        // this function on page load, so that we show the correct folder:

    }).trigger('hashchange');


    // Hiding and showing the search box

/*    $filemanager.find('.search').on('click', function() {

        var search = $(this);

        search.find('span').hide();
        search.find('input[type=search]').show().on('focus');

    });*/


    // Listening for keyboard input on the search field.
    // We are using the "input" event which detects cut and paste
    // in addition to keyboard input.

    $filemanager.find('input').on('input', function() {

        folders = [];
        files = [];

        var value = this.value.trim();

        if (value.length) {

            $filemanager.addClass('searching');

            // Update the hash on every key stroke
            window.location.hash = 'search=' + value.trim();

        }

        else {

            $filemanager.removeClass('searching');
            window.location.hash = encodeURIComponent(currentPath);

        }

    }).on('keyup', function(e) {

        // Clicking 'ESC' button triggers focusout and cancels the search

        var search = $(this);

        if (e.keyCode === 27) {

            search.trigger('focusout');

        }

    }).on('focusout', function() {

        // Cancel the search

        var search = $(this);

        if (!search.val().trim().length) {

            window.location.hash = encodeURIComponent(currentPath);
            //search.hide();
            search.parent().find('span').show();

        }

    });


    // Clicking on folders

    fileList.on('click', 'li.folders', function(e) {
        e.preventDefault();

        if ($(this).hasClass('active')) {
            var nextDir = $(this).find('a.folders').attr('href');
            if ($filemanager.hasClass('searching')) {
                // Building the breadcrumbs
                breadcrumbsUrls = generateBreadcrumbs(nextDir);

                $filemanager.removeClass('searching');
                $filemanager.find('input[type=search]').val('');
                $filemanager.find('span').show();
            }
            else {
                breadcrumbsUrls.push(nextDir);
            }
            window.location.hash = encodeURIComponent(nextDir);
            currentPath = nextDir;
        } else {
            $('li', fileList).removeClass('active');
            $(this).addClass('active');
        }
    });

    $filemanager.on('click', function(e) {
        if (!$(e.target).is('a, span')) {
            $('li', fileList).removeClass('active');
        }
    });

    //Clicking on file
    fileList.on('click', 'li.files', function(e) {
        e.preventDefault();
        $('li', fileList).removeClass('active');
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


    // Navigates to the given hash (path)

    function goto(hash) {

        hash = decodeURIComponent(hash).slice(1).split('=');

        if (hash.length) {
            var rendered = '';

            // if hash has search in it

            if (hash[0] === 'search') {

                $filemanager.addClass('searching');
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

        fileList.empty().hide();

        if (!scannedFolders.length && !scannedFiles.length) {
            $filemanager.find('div.nothingfound').show();
        }
        else {
            $filemanager.find('div.nothingfound').hide();
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

                var folder = $('<li class="folders"><a href="' + f.path + '" title="' + f.path + '" class="folders">' + icon + '<span class="name">' + name + '</span> <span class="details">' + itemsLength + '</span></a></li>');
                folder.appendTo(fileList);
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

                var file = $('<li class="files"><a href="' + f.path + '" title="' + f.path + '" class="files">' + icon + '<span class="name">' + name + '</span> <span class="details">' + fileSize + '</span></a></li>');
                file.appendTo(fileList);
            });

        }


        // Generate the breadcrumbs

        var url = '';

        if ($filemanager.hasClass('searching')) {

            url = '<span>Search results: </span>';
            fileList.removeClass('animated');

        }
        else {

            fileList.addClass('animated');

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

        breadcrumbs.text('').append(url);


        // Show the generated elements

        fileList.show().animate({'display': 'inline-block'});

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

});