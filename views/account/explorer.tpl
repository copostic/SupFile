<section class="content" id="fileExplorer">

    <div class="filemanager">
        <div class="explorer-header">
            <div class="breadcrumbs"><span class="folderName">My Drive</span>

                <div class="breadcrumb-rename">
                    <img src="/public/img/right-chevron.png">
                    <input type="text" title="Name of new folder">
                    <button id="validateRename"><i class="fa fa-check"></i></button>
                </div>
            </div>
            <div class="controls">
                <div class="actions">
                    <button title="Add a folder" id="add"><i class="fa fa-plus"></i></button>
                    <button title="Delete" id="delete"><i class="fa fa-trash"></i></button>
                    <button title="Rename" id="rename"><i class="fa fa-edit"></i></button>
                    <button title="Display" id="display"><i class="fa fa-eye"></i></button>
                    <button title="Download" id="download"><i class="fa fa-download"></i></button>
                    <button title="Upload" id="upload"><i class="fa fa-upload"></i></button>
                    {*                    <form method="POST" enctype="multipart/form-data" id="fileUploadForm">
                                            <label for="file"><i class="fa fa-upload"></i></label>
                                            <input id="file" type="file" name="file">
                                        </form>*}
                </div>
                <div class="search">
                    <i class="fa fa-search"></i>
                    <input placeholder="Find a file.." type="search">
                </div>
            </div>
        </div>

        <ul class="data"></ul>

        <div class="nothingfound">
            <div class="nofiles">
                <i class="fa fa-exclamation"></i>
            </div>
            <span>No files here.</span>
        </div>
        <div id="uploader"></div>
    </div>
</section>