<?php
    $page_title = 'Admin - Sitemap';
    define("PAGE_PRIV", "admin_site");

    require_once('../../files/header.php');

    if (!isset($_GET['generate'])):
?>
    <a href='?generate' class='button'>Generate Sitemap</a>
<?php
    else:
        $base = 'https://' . $app->config['domain'];

        $pages = array();
        array_push($pages, array('loc'=>'', 'freq'=>'monthly', 'priority'=>'1.0'));

        // Articles
        array_push($pages, array('loc'=>'/articles', 'freq'=>'daily', 'priority'=>'0.9'));
        $categories = $app->articles->getCategories(null, false);
        foreach($categories AS $category) {
            array_push($pages, array('loc'=>'/articles/'.$category->slug, 'freq'=>'daily', 'priority'=>'0.9'));
        }

        $articles = $app->articles->getArticles(null, null);
        foreach($articles['articles'] AS $article) {
            array_push($pages, array('loc'=>$article->uri, 'freq'=>'monthly', 'priority'=>'0.9', 'lastmod'=>date('Y-m-d\TH:i:sP', strtotime($article->submitted))));
        }

        // News
        array_push($pages, array('loc'=>'/news', 'freq'=>'daily', 'priority'=>'0.6'));
        $articles = $app->articles->getArticles(0, null);
        foreach($articles['articles'] AS $article) {
            array_push($pages, array('loc'=>$article->uri, 'freq'=>'monthly', 'priority'=>'0.7', 'lastmod'=>date('Y-m-d\TH:i:sP', strtotime($article->submitted))));
        }

        // Forum
        array_push($pages, array('loc'=>'/forum', 'freq'=>'daily', 'priority'=>'0.8'));

        $forum = $app->forum->getThreads(null, 1, false, false, false, null);
        foreach($forum->threads AS $thread) {
            array_push($pages, array('loc'=>'/forum/'.$thread->slug, 'freq'=>'daily', 'priority'=>'0.8', 'lastmod'=>date('Y-m-d\TH:i:sP', strtotime($thread->latest))));
        }

        /* create a dom document with encoding utf8 */
        $domtree = new DOMDocument('1.0', 'UTF-8');
        $domtree->preserveWhiteSpace = false;
        $domtree->formatOutput = true;

        /* create the root element of the xml tree */
        $xmlRoot = $domtree->createElement("urlset");
        $domAttribute = $domtree->createAttribute('xmlns');
        $domAttribute->value = 'http://www.sitemaps.org/schemas/sitemap/0.9';
        $xmlRoot->appendChild($domAttribute);

        /* append it to the document created */
        $xmlRoot = $domtree->appendChild($xmlRoot);

        foreach($pages AS $page) {
            $currentTrack = $domtree->createElement("url");
            $currentTrack = $xmlRoot->appendChild($currentTrack);

            /* you should enclose the following two lines in a cicle */
            $currentTrack->appendChild($domtree->createElement('loc', $base.$page['loc'].'/'));
            if (isset($page['lastmod']))
                $currentTrack->appendChild($domtree->createElement('lastmod', $page['lastmod']));
            if (isset($page['freq']))
                $currentTrack->appendChild($domtree->createElement('changefreq', $page['freq']));
            if (isset($page['priority']))
                $currentTrack->appendChild($domtree->createElement('priority', $page['priority']));
        }       

        /* get the xml printed */
        $sitemap = $domtree->saveXML();
        file_put_contents('../sitemap.xml', $sitemap);

        $app->utils->message('Sitemap generated', 'good');
    endif;

    require('../../files/footer.php');
?>
