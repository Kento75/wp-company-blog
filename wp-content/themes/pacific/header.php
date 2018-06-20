<!DOCTYPE HTML>
<html dir="ltr" lang="ja">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title><?php bloginfo('name'); ?></title>
    <link rel="apple-touch-icon" href="<?php bloginfo('template_url'); ?>/images/touch-icon.png" />
    <link rel="shortcut icon" href="<?php bloginfo('template_url'); ?>/images/favicon.ico" />
    <link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo('stylesheet_url'); ?>" />
    <!--[if lt IE 9]>
    <meta http-equiv="Imagetoolbar" content="no" />
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
<?php wp_head(); ?>
</head>
<body class="home">
<div id="wrap">
    <section id="description">
        <h1><?php bloginfo('description'); ?></h1>
    </section><!-- #description end -->
    <div id="container">
        <header id="header">
            <h1 id="site-id">
                <a href="<?php echo home_url('/'); ?>"><img src="<?php bloginfo('template_url'); ?>/images/header/site_id.png" alt="<?php bloginfo('name'); ?>" /></a>
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
