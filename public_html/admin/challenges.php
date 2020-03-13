<?php
//echo "admin challenge";
    $custom_css = array('challenges.scss', 'admin.scss');
    $custom_js = array('admin.js');
    $page_title = 'Admin - Challenges';
    define("PAGE_PRIV", "admin_site");

    require_once('../../files/header.php');
//echo "admin challenge 2";
    if (!isset($_GET['edit'])):
?>

<a class='button right' href='?edit=new'>Add challenge</a>
<a class='button right' href='?edit-categories'>Edit categories</a>

<?php
    endif;
?>

<a href='challenges.php'><h1>Challenge editor</h1></a>

<?php
    if (isset($_GET['edit-categories'])) {
        include('../../files/elements/challenge_editor/edit_categories.php');
    }

    if (isset($_GET['edit'])) {
        if (isset($_GET['form']))
            include('../../files/elements/challenge_editor/edit_challenge_form.php');
        else
            include('../../files/elements/challenge_editor/edit_challenge.php');
    } else {
        include('../../files/elements/challenge_editor/challenge_list.php');
    }
?>


<?php
    require_once('../../files/footer.php');
?>