<?php
//echo "admin level";
    $custom_css = array('levels.scss', 'admin.scss');
    $custom_js = array('admin.js');
    $page_title = 'Admin - Levels';
    define("PAGE_PRIV", "admin_site");

    require_once('../../files/header.php');
//echo "admin level 2";
    if (!isset($_GET['edit'])):
?>

<a class='button right' href='?edit=new'>Add level</a>
<a class='button right' href='?edit-categories'>Edit categories</a>

<?php
    endif;
?>

<a href='levels.php'><h1>Level editor</h1></a>

<?php
    if (isset($_GET['edit-categories'])) {
        include('../../files/elements/trainer_editor/edit_categories.php');
    }

    if (isset($_GET['edit'])) {
        if (isset($_GET['form']))
            include('../../files/elements/trainer_editor/edit_level_form.php');
        else
            include('../../files/elements/trainer_editor/edit_level.php');
    } else {
        include('../../files/elements/trainer_editor/level_list.php');
    }
?>


<?php
    require_once('../../files/footer.php');
?>