<!DOCTYPE HTML>
<html dir="ltr" lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>title</title>
<link rel="apple-touch-icon" href="<?php bloginfo('template_url'); ?>/images/touch-icon.png" />
<link rel="shortcut icon" href="<?php bloginfo('template_url'); ?>/images/favicon.ico" />
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo('stylesheet_url'); ?>" />
<!--[if lt IE 9]>
  <meta http-equiv="Imagetoolbar" content="no" />
  <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
</head>
<body class="home">
<div id="wrap">
  <section id="description">
    <h1>description</h1>
  </section><!-- #description end -->
  <div id="container">
    <header id="header">
      <h1 id="site-id">
        <a href="#"><img src="<?php bloginfo('template_url'); ?>/images/header/site_id.png" alt="サイトID" /></a>
      </h1><!-- #site-id end -->
      <div id="utility-group">
        <nav id="utility-nav">
          <ul>
            <li><a href="#">utility-nav_1</a></li>
            <li><a href="#">utility-nav_2</a></li>
            <li><a href="#">utility-nav_3</a></li>
          </ul>
        </nav><!-- #utility-nav end -->
        <div id="header-widget-area">
          <aside class="widget_search">
            <form role="search" id="searchform">
              <div>
                <input type="text" id="s" />
                <input type="submit" id="searchsubmit"/>
              </div>
            </form><!-- #searchform end -->
          </aside><!-- .widget_search end -->
        </div><!-- #header-widget-area end -->
      </div><!-- #utility-group end -->
    </header><!-- #header end -->
    <nav id="global-nav">
      <ul id="menu-global">
        <li id="menu-item-home" class="menu-item current-menu-item"><a href="#">トップページ</a></li>
        <li id="menu-item-about" class="menu-item"><a href="#">会社概要</a></li>
        <li id="menu-item-mall" class="menu-item"><a href="#">モール開発実績</a>
          <ul class="sub-menu">
            <li class="menu-item"><a href="#">モール1</a></li>
            <li class="menu-item"><a href="#">モール2</a></li>
          </ul>
        </li>
        <li id="menu-item-column" class="menu-item"><a href="#">コラム</a></li>
        <li id="menu-item-inquiry" class="menu-item"><a href="#">お問い合わせ</a></li>
      </ul><!-- #menu-global end -->
    </nav><!-- #global-nav end -->
    <section id="branding">
      <img src="<?php bloginfo('template_url'); ?>/images/top/main_image.png" width="950" height="295" alt="" />
    </section><!-- #branding end -->
    <section id="contents-body">
      <section id="contents">
        <section id="malls-pickup">
          <div class="malls-group">
            <article>
              <h1><a href="#">mall-title_1</a></h1>
              <a href="#"><img width="302" height="123" src="<?php bloginfo('template_url'); ?>/images/top/mall_image.png"  alt="mall-title_1" /></a>
              <p>テキストテキストテキストテキストテキストテキストテキストテキスト</p>
              <div class="continue-button">
                <a href="#">詳しく見る</a>
              </div>
            </article>
            <article>
              <h1><a href="#">mall-title_2</a></h1>
              <a href="#"><img width="302" height="123" src="<?php bloginfo('template_url'); ?>/images/top/mall_image.png" alt="mall-title_2" /></a>
              <p>テキストテキストテキストテキストテキストテキストテキストテキスト</p>
              <div class="continue-button">
                <a href="#">詳しく見る</a>
              </div>
            </article>
          </div><!-- .malls-group end -->
        </section><!-- #malls-pickup end -->
        <section id="latest-columns">
          <h1 id="latest-columns-title">新着コラム</h1>
          <span class="link-text archive-link"><a href="#">コラム一覧</a></span>
          <div class="column-group head">
            <article class="column-article" >
              <h1 class="update-title"><a href="#">column-title_1</a></h1>
              <time class="entry-date" datetime="2012-01-01">entry-date</time>
              <a href="#"><img width="90" height="90" src="<?php bloginfo('template_url'); ?>/images/top/column_image.png" alt="マカロニ・スクータル" /></a>
              <p>テキストテキストテキストテキストテキストテキストテキストテキスト</p>
              <span class="link-text"><a href="#">続きを読む</a></span>
            </article>
            <article class="column-article" >
              <h1 class="update-title"><a href="#">column-title_2</a></h1>
              <time class="entry-date" datetime="2012-01-01">entry-date</time>
              <a href="#"><img width="90" height="90" src="<?php bloginfo('template_url'); ?>/images/top/column_image.png" alt="汐留モール夏祭りの花火大会" /></a>
              <p>テキストテキストテキストテキストテキストテキストテキストテキスト</p>
              <span class="link-text"><a href="#">続きを読む</a></span>
            </article>
          </div><!-- .column-group end -->
        </section><!-- #latest-columns end -->
      </section><!-- #contents end -->
      <section id="sidebar">
        <aside class="rss_link">
          <a href="#"><img src="<?php bloginfo('template_url'); ?>/images/btn_rss_feed.png" width="250" height="28" alt="RSS" /></a>
        </aside>
        <div id="primary" class="widget-area">
          <aside id="event-info" class="news-list">
            <h1>イベント開催情報</h1>
            <div class="info-wrap">
              <ul>
                <li>
                  <time class="entry-date" datetime="2012-01-01">entry-date</time>
                  <h2><a href="#">event-info_title_1</a></h2>
                  <a href="#"><img width="61" height="61" src="<?php bloginfo('template_url'); ?>/images/top/post_image.png" alt="「バンコクロイヤルガーデンフェア」開催のお知らせ" /></a>
                  <p>テキストテキストテキストテキストテキストテキストテキストテキスト</p>
                </li>
                <li>
                  <time class="entry-date" datetime="2012-01-01">entry-date</time>
                  <h2><a href="#">event-info_title_2</a></h2>
                  <a href="#"><img width="61" height="61" src="<?php bloginfo('template_url'); ?>/images/top/post_image.png" alt="「汐留 味紀行」開催のお知らせ" /></a>
                  <p>テキストテキストテキストテキストテキストテキストテキストテキスト</p>
                </li>
              </ul>
              <span class="link-text"><a href="#">イベント開催情報一覧</a></span>
            </div><!-- .info-wrap end -->
          </aside><!-- #event-info end -->
          <aside id="malls-info" class="news-list">
            <h1>新規モール出店情報</h1>
            <div class="info-wrap">
              <ul>
                <li>
                  <time class="entry-date" datetime="2012-01-01">entry-date</time>
                  <h2><a href="#">malls-info_title_1</a></h2>
                  <a href="#"><img width="61" height="61" src="<?php bloginfo('template_url'); ?>/images/top/post_image.png" alt="ホノルルに「カメハメハモール」オープン" /></a>
                  <p>テキストテキストテキストテキストテキストテキストテキストテキスト</p>
                </li>
                <li>
                  <time class="entry-date" datetime="2012-01-01">entry-date</time>
                  <h2><a href="#">malls-info_title_2</a></h2>
                  <a href="#"><img width="61" height="61" src="<?php bloginfo('template_url'); ?>/images/top/post_image.png" alt="サンフランシスコ・ノブヒル地区に「ゴールデンゲートモール」オープン" /></a>
                  <p>テキストテキストテキストテキストテキストテキストテキストテキスト</p>
                </li>
              </ul>
              <span class="link-text"><a href="#">モール出店情報一覧</a></span>
            </div><!-- #info-wrap end -->
          </aside><!-- #malls-info end -->
          <aside id="information-info" class="news-list">
            <h1>お知らせ</h1>
            <div class="info-wrap">
              <ul>
                <li>
                  <time class="entry-date" datetime="2012-01-01">entry-date</time>
                  <h2><a href="#">information-info_title_1</a></h2>
                  <a href="#"><img width="61" height="61" src="<?php bloginfo('template_url'); ?>/images/top/post_image.png" alt="人材募集のお知らせ" /></a>
                  <p>テキストテキストテキストテキストテキストテキストテキストテキスト</p>
                </li>
                <li>
                  <time class="entry-date" datetime="2012-01-01">entry-date</time>
                  <h2><a href="#">information-info_title_2</a></h2>
                  <a href="#"><img width="61" height="61" src="<?php bloginfo('template_url'); ?>/images/top/post_image.png"  alt="ホノルル支店を開設いたしました。" /></a>
                  <p>テキストテキストテキストテキストテキストテキストテキストテキスト</p>
                </li>
              </ul>
              <span class="link-text"><a href="#">お知らせ一覧</a></span>
            </div><!-- .info-wrap end -->
          </aside><!-- #information-info end -->
        </div><!-- #primary end -->
      </section><!-- #sidebar end -->
    </section><!-- #contents-body end -->
  </div><!-- #container end -->
  <div id="footer-container">
    <footer id="footer">
      <p id="copyright"><small>Copyright &copy; [ company's name ] All rights reserved.</small></p>
    </footer><!-- #footer end -->
  </div><!-- #footer-container end -->
</div><!-- #wrap end -->
</body>
</html>
