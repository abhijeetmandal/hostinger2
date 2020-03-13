<?php
    if (!isset($forum))
        header('Location: /forum');

    $thread = $forum->getThread($thread->id);
    if (!$thread)
        header('Location: /forum');

    // Is post in thread owned by user?
    $post = $forum->getPost($_GET['edit'], $thread->id);
    if (!$post)
        header("Location: /forum/{$thread->slug}");

    $updated = null;
    if (isset($_POST['body'])) {
        $updated = $forum->editPost($post->post_id, $thread->id, $_POST['body']);
    }

    $section = $thread->section;

    $breadcrumb = $forum->getBreadcrumb($section, true) . "<a href='/forum/{$thread->slug}'>{$thread->title}</a>";

    $app->page->title = $thread->title . ' - Edit';

    require_once('../../files/header.php');
?>
                    <section class="row">
<?php
        include('../../files/elements/sidebar_forum.php');
?>    
                        <div class="col span_18 forum-main" data-thread-id="<?=$thread->id;?>" itemscope itemtype="http://schema.org/Article">
                            <h1 class='no-margin' itemprop="name"><?=$thread->title;?></h1>
                            <?=$breadcrumb;?><br/>

                            <form id="submit" class='forum-thread-reply' method="POST">
<?php
    if ($updated === false) {
        $app->utils->message($forum->getError(), 'error');
        $wysiwyg_text = $_POST['body'];
    } else if ($updated === true) {
        $app->utils->message("Post updated, <a href='/forum/{$thread->slug}'>view thread</a>", 'good');
        $wysiwyg_text = $_POST['body'];
    } else {
        $wysiwyg_text = $post->body;
    }
    include('../../files/elements/wysiwyg.php');
?>
                                <input type='submit' class='button' value='Submit'/>
                            </form>
                        </div>
                    </section>
<?php
    require('../../files/footer.php');
?>