<?php
    $custom_css = array('articles.scss', 'highlight.css', 'faq.scss');
    $custom_js = array('articles.js', 'highlight.js');
    define("_SIDEBAR", false);
    define("PAGE_PUBLIC", true);

    require_once('init.php');
    $app->page->title = 'Refund Policy';
    $app->page->canonical = 'http://www.crushit.fit/policy';
    require_once('header.php');
?>

<section class="row">
    
    <div class="col span_24 article-main">
        <article class='bbcode body' itemscope itemtype="http://schema.org/Article">
            <header class='clearfix'>
                <div class='width-center'>
                    <h1 itemprop="name">Cancellation & Refund Policy</h1>
                    <div class='meta'>
                        <i class="icon-clock"></i> May 28, 2019
                        <i class="icon-user"></i> <a rel='author' itemprop='author' href='/user/crushit'>crushit</a>
                    </div>
                </div>
            </header>
            <div itemprop='articleBody' class='articleBody width-center'>
                <p>
                    Once we receive your email on hello@crushit.fit for cancellation and refund within 7 day's of it's activation we will refund your payments. Refund will be processed within 10 working days, we will scrutinize and evaluate the transaction. After approval refund will initiate.
                    <br/><br/>
                    If the standard time-frame as mentioned above has passed and you have still not received the refund, please contact your credit or debit card issuer or your bank for more information.
                    <br/><br/>
                    If you have any questions about our Cancellation and Refunds Policy, please contact us:
By email: hello@crushit.fit or call us on +91-7892382172
                </p>
            </div>
        </article>
    </div>
</section>
<?php  
    require_once('footer.php');
?>
