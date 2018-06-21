=== Prime Strategy Bread Crumb ===
Contributors: jim912
Tags: breadcrumb, topic path
Requires at least: 3.1
Tested up to: 4.0
Stable tag: 1.0.5

パンくずナビ（トピックパス）表示機能を追加します。

== Description ==
パンくずナビ（トピックパス）表示機能を追加します。表示タイプ、スタイルに関する多数のパラメータやパンくずナビ独自のプラグインフックが用意されており、フレキシブルなカスタマイズが可能です。

= Examples =
**デフォルト**<br />
テンプレートタグ<br />
`
<?php if ( function_exists( 'bread_crumb' ) ) { bread_crumb(); } ?>
`
出力サンプル<br />
`
<ul class="bread_crumb">
	<li class="level-1 top"><a href="http://www.example.com/">Home</a></li>
	<li class="level-2 sub"><a href="http://www.example.com/?post_type=seminar">Seminar</a></li>
	<li class="level-3 sub"><a href="http://www.example.com/?area=tokyo">Tokyo</a></li>
	<li class="level-4 sub tail current">WordBench Tokyo</li>
</ul>
`

**文字列タイプ**<br />
テンプレートタグ<br />
`
<?php if ( function_exists( 'bread_crumb' ) ) bread_crumb( 'type=string' ); ?>
`
出力サンプル<br />
`
<a href="http://www.example.com/">Home</a> &amp;gt; <a href="http://www.example.com/?post_type=seminar">Seminar</a> &amp;gt; <a href="http://www.example.com/?area=tokyo">Tokyo</a> &amp;gt; <strong class="current">WordBench Tokyo</strong>
`

**CSS Sample**
`
.bread_crumb {
	margin:0;
	border-bottom:1px solid #eee;
	padding:0.8em 0 0.5em;
	clear: both;
	height: 20px;
	background: #f8f8f8;
}
.bread_crumb li {
	font-size:12px;
	color:rgb(180,180,180);
	float:left;
	/*margin-right:1em;*/
	list-style: none outside none;
}
.bread_crumb li:after {
	content : '>';
	padding-left:10px;
	margin-right:10px;
}
.bread_crumb li:last-child:after {
	content : '';
}
.bread_crumb li a {
	color:rgb(120,120,120);
}
.bread_crumb li.current {
}
`

= Special Thanks =
English Translation:[Odyssey](http://www.odysseygate.com/ "Translation")

== Installation ==

1. pluginsフォルダに、ダウンロードした Prime Strategy Bread Crumb のフォルダをアップロードしてください。
2. プラグインページで "Prime Strategy Bread Crumb" を有効化して下さい。
3. 利用しているテーマのパンくずナビを表示したい箇所にページナビのテンプレートタグ "bread_crumb" を追加してください。テンプレートタグで指定できるパラメータについては、下記の Parameters を参照してください。

= Parameters =

**type**<br />
stringを指定すると、リストではなく文字列として出力します。デフォルトはlist

**home_label**<br />
トップページの表示テキスト。デフォルトは「トップページ」

**search_label**<br />
検索結果の表示テキスト。デフォルトは「『%s』の検索結果」（%sが検索文字列）

**404_label**<br />
404ページの表示テキスト。デフォルトは「404 Not Found」

**category_label**<br />
カテゴリーの表示テキスト。デフォルトは「%s」（%sがカテゴリー名）

**tag_label**<br />
投稿タグの表示テキスト。デフォルトは「%s」（%sが投稿タグ名）

**taxonomy_label**<br />
カスタムタクソノミーの表示テキスト。デフォルトは「%s」（%sがタクソノミー名）

**author_label**<br />
寄稿者の表示テキスト。デフォルトは「%s」（%sが寄稿者名）

**attachment_label**<br />
アタッチメントの表示テキスト。デフォルトは「%s」（%sがアタッチメント名）

**year_label**<br />
年の表示テキスト。デフォルトは「%s年」（%sが年の数字）

**month_label**<br />
月の表示テキスト。デフォルトは「%s」（%sは日付フォーマットで指定した月の表示設定）

**day_label**<br />
日の表示テキスト。デフォルトは「%s日」（%sが日の数字）

**post_type_label**<br />
カスタム投稿タイプアーカイブの表示テキスト。デフォルトは「%s」（%sがカスタム投稿タイプ名）

**joint_string**<br />
typeでstringを指定した場合の結合文字列。デフォルトは「 &amp;gt; 」（ > ）

**navi_element**<br />
ラッパー要素名。divまたはnavを選択可能。デフォルトは空（要素無し）

**elm_class**<br />
ラッパー要素のクラス名。ラッパー要素がなくタイプがリストの場合は、ulのクラス名となる。デフォルトは、「bread_crumb」

**elm_id**<br />
ラッパー要素のid名。ラッパー要素がなくタイプがリストの場合は、ulのid名となる。デフォルトは空。（idなし）

**li_class**<br />
タイプがリストの場合のliに付くクラス名。デフォルトは空（なし）

**class_prefix**<br />
各クラスに付く接頭辞。デフォルトは空（なし）

**current_class**<br />
表示中のページのパンくずナビに付与されるクラス名。デフォルトは「current」

**indent**<br />
タブでのインデント数。デフォルトは０。

**echo**<br />
出力を行うか。デフォルトはtrue（出力する）。0またはfalseの指定でPHPの値としてreturnする。 

== Changelog ==
= 1.0.4 =
* WordPress 3.4対応
* フィルターフック bread_crumb_arr の追加

= 1.0.2 =
* 日本語訳の修正

= 1.0.1 =
* 非推奨関数 get_author_name を get_the_author_meta に修正

= 1.0.0 =
* 公式ディレクトリ公開
* カスタム投稿アーカイブ表示への対応
* パラメータにpost_type_labelを追加

= 0.8.1 =
* 真偽値のパラメータが正しく反映されない問題を修正
* mクエリーパラメータへの対応

= 0.8.0 =
* 一般公開


== Screenshots ==
1. パンくずナビ出力サンプル

== Links ==
"[PS Taxonomy Expander](http://wordpress.org/extend/plugins/ps-taxonomy-expander/ "WordPress sitemap plugin")" makes categories, tags and custom taxonomies more useful.

"[PS Auto Sitemap](http://wordpress.org/extend/plugins/ps-auto-sitemap/ "WordPress sitemap plugin")" is a plugin automatically generates a site map page from your WordPress site. 
It is easy to install for beginners and easy to customize for experts.
It can change the settings of the display of the lists from administration page, several neat CSS skins for the site map tree are prepared.

"[PS Disable Auto Formatting](http://wordpress.org/extend/plugins/ps-disable-auto-formatting/ "WordPress formatting plugin")"
Stops the automatic forming and the HTML tag removal in the html mode of WordPress, and generates a natural paragraph and changing line.

"[CMS service with WordPress](http://www.prime-strategy.co.jp/ "CMS service with WordPress")" provides you service that uses WordPress as a CMS.
