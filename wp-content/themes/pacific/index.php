<?php get_header(); ?>
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
<?php get_sidebar('top'); ?>
<?php get_footer(); ?>
