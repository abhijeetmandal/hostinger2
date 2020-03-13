<?php
    $custom_css = array('articles.scss', 'highlight.css');
    $custom_js = array('articles.js', 'highlight.js');
    if (!defined("_SIDEBAR")) define("_SIDEBAR", false);
    if (!defined("PAGE_PUBLIC")) define('PAGE_PUBLIC', true);

    require_once('../../files/init.php');

    $limit = 5;
    $page = (isset($_GET['page']) && is_numeric($_GET['page']))?$_GET['page']:1;


    $app->page->desc = "Learn from the Body Building articles.";
    if (isset($_GET['slug'])) {
        $category = $app->articles->getCategory($_GET['slug']);
        if (!$category) {
            header('Location: /articles');
            die();
        }
        $articleList = $app->articles->getArticles($category->id, $limit, $page);
        $app->page->title = $category->title . ' Articles';
    } else {
        $category = null;
        $articleList = $app->articles->getArticles(null, $limit, $page);
        $app->page->title = 'Body Building Articles';
    }

    require_once('../../files/header.php');
?>
                    <section class="row">
<?php
        include('../../files/elements/sidebar_article.php');
?>    
                        <div class="col span_18 article-main">
<?php
    if ($category):
?>
                            <h1><?=$category->title;?> Articles [<?=$articleList['total'];?>]</h1>
<?php
    else:
?>
                            <h1>Body Building Articles</h1>
<?php
    endif;
    if (!isset($articleList) || !$articleList || $articleList['total'] == 0):
?>
                            <div class='msg msg-error'>
                                <i class='icon-error'></i>
                                No articles found
                            </div>
<?php
    else:

        if (!$category && $page == 1):
            $n = 0;
            $hot = $app->articles->getHotArticles();
            foreach($hot AS $article):
                if ($n++ == 3)
                    break;

                if ($n == 1):
?>
                            <div class="row fluid article-hot">
<?php           endif; ?>
                                <a href='<?=$article->slug;?>' class="col span_8 <?=isset($article->thumbnail) || isset($article->video)?'img':'';?> thumbnail" data-overlay="<?=$article->category;?>">
<?php               if (isset($article->thumbnail) && $article->thumbnail): ?>
                                    <img src="/images/200/4:3/<?=$article->thumbnail;?>">
<?php               elseif (isset($article->video)): ?>
                                    <img src="https://img.youtube.com/vi/<?=$article->video;?>/0.jpg">
<?php               endif; ?>
                                    <div class="caption">
                                        <h3><?=$article->title;?></h3>
<?php               if (!(isset($article->thumbnail) && $article->thumbnail) && !isset($article->video)): ?>
                                    <p><?=$app->parse($article->body, false);?></p>
<?php               endif; ?>
                                    </div>
                                </a>
<?php           if ($n == 3): ?>
                            </div>
<?php
               endif;
            endforeach;
        endif;
?>
                            <ul class='article-index plain'>
<?php
        foreach ($articleList['articles'] as $article):
            $article->title = $app->parse($article->title, false);
            $article->body = substr($app->parse($article->body, false), 0, 300) . '...';
?>
                                <li class="<?=isset($article->thumbnail) || isset($article->video)?'img':'';?>">
                                    <a href='<?=$article->uri;?>'>
<?php               if (isset($article->thumbnail) && $article->thumbnail): ?>
                                    <img src="/images/200/4:3/<?=$article->thumbnail;?>" class='mobile-hide'>
<?php               elseif (isset($article->video)): ?>
                                    <img src="https://img.youtube.com/vi/<?=$article->video;?>/0.jpg" class='mobile-hide'>
<?php               endif; ?>
                                    <h2><?=$article->title;?></h2></a>
                                    <a href='/articles/<?=$article->cat_slug;?>' class='category'><?=$article->cat_title;?></a><br/>
                                    <?php
                                        echo $article->body;
                                    ?>
                                    <a href='<?=$article->uri;?>'>continue reading</a>
                                </li>
<?php
        endforeach;
?>
                            </ul>
<?php
        if (ceil($articleList['total']/$limit) > 1) {
            $pagination = new stdClass();
            $pagination->current = $articleList['page'];
            $pagination->count = ceil($articleList['total']/$limit);
            $pagination->root = '?page=';
            include('../../files/elements/pagination.php');
        }
    endif;
?>
                        </div>
                    </section>
<?php
   require_once('../../files/footer.php');
?>