<?php
    /**
     * This is a module for users to generate and send HTML mail newsletter.
     */

    //Add DB credentials and methods
    require_once '../database.php';
    session_start();

    //Add application specific variables
    require_once '../appvars.php';
    //Add application functions
    require_once '../functions.php';

    //If user isn't logged redirect to login page
    if (!isset($_SESSION['logged'])) {
        header('location: ../index.php');

        exit();
    }

    // VARIABLES
    $errors = @$_SESSION['errors'];
    $DBconnection = $_SESSION['DBConnection'];

    //Define page name for menu file
    $PAGE_NAME = 'Generator Newslettera';
    $ZAIKS = '';

    //Preprare SQL query and get songs list from DB
    //$query = "SELECT * FROM zaiks";
    //$entriesArray = $DBconnection->getFromDB($query);

?>




<!DOCTYPE HTML>
<html lang="pl">

<head>
    <?php require_once '../../head.php';
?>
    <!-- Inform robots to don't index this page-->
    <meta name="robots" content="noindex">
    <!-- Add CSS properties specific for this subpage. Main CSS file is included in file head.php!-->
    <link href="../style.css" rel="stylesheet" type="text/css" />
    <link href="../modal.css" rel="stylesheet" type="text/css" />

    <script src="https://cdn.tiny.cloud/1/vsmfu724ht3s7vzh7g6jg9h7m9iiq8r87cw0xk1uy1jpmb54/tinymce/5/tinymce.min.js"
        referrerpolicy="origin"></script>
    <script>
    tinymce.init({
        selector: '#mytextarea',
        plugins: 'image code link template',
        toolbar: 'undo redo | bold italic | alignleft aligncenter alignright alignjustify | link image | code | template',
        templates: [{
            title: 'Spacer',
            url: 'template-parts/spacer.html',
            description: 'Adds Simple Spacer Without Number'
        }],
        menubar: false,
        image_title: true,
        automatic_uploads: true,
        file_picker_types: 'image',
        file_picker_callback: function(cb, value, meta) {
            var input = document.createElement('input');
            input.setAttribute('type', 'file');
            input.setAttribute('accept', 'image/*');
            input.onchange = function() {
                var file = this.files[0];
                var reader = new FileReader();
                reader.onload = function() {
                    var id = 'blobid' + (new Date()).getTime();
                    var blobCache = tinymce.activeEditor.editorUpload.blobCache;
                    var base64 = reader.result.split(',')[1];
                    var blobInfo = blobCache.create(id, file, base64);
                    blobCache.add(blobInfo);
                    cb(blobInfo.blobUri(), {
                        title: file.name
                    });
                };
                reader.readAsDataURL(file);
            };
            input.click();
        },



    });
    </script>



</head>

<body>
    <!-- This div is used as container for whole page-->
    <div class="page-container">
        <?php require_once '../menu.php'; ?>
        <main>
            <div class="content">
                <div class="generator">
                    <div>
                        <form method="post">
                            <textarea id="mytextarea" name="mytextarea">
                                <?php echo file_get_contents('./newsletter.php'); ?>
                            </textarea>
                        </form>
                    </div>
                </div>
            </div>
        </main>
        <?php require_once '../../footer.php';
    showModal();
    ?>
    </div>
</body>


</html>