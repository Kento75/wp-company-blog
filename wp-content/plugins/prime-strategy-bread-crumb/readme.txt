=== Prime Strategy Bread Crumb ===
Contributors: jim912
Tags: breadcrumb, topic path
Requires at least: 3.1
Tested up to: 4.0
Stable tag: 1.0.5

Adds the function to display breadcrumbs navigation.

== Description ==
This plugin adds the function to display breadcrumbs (topic path) navigation. You can use display styles, lots of parameters of styles and original plugin hooks of breadcrumbs navigation, and you can customize navigations flexibly.

= Examples =
**Default**<br />
Template Tag<br />
`
<?php if ( function_exists( 'bread_crumb' ) ) { bread_crumb(); } ?>
`
Output sample<br />
`
<ul class="bread_crumb">
	<li class="level-1 top"><a href="http://www.example.com/">Home</a></li>
	<li class="level-2 sub"><a href="http://www.example.com/?post_type=seminar">Seminar</a></li>
	<li class="level-3 sub"><a href="http://www.example.com/?area=tokyo">Tokyo</a></li>
	<li class="level-4 sub tail current">WordBench Tokyo</li>
</ul>
`

**String types**<br />
Template Tag<br />
`
<?php if ( function_exists( 'bread_crumb' ) ) bread_crumb( 'type=string' ); ?>
`
Output Sample<br />
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

1. Upload Prime Strategy Bread Cumb plugin folder you downloaded to the plugin directory.
2. Go to the plugin menu of Admin, and activate "Prime Strategy Bread Crumb" plugin.
3. Add a template tag "bread_crumb" of page navigation to the place where you would like to display breadcrumbs navigation in your theme. See below about parametes you can specify by template tags.

= Parameters =

**type**<br />
If you specify "string", output strings instead of list.
Default: list

**home_label**<br />
Texts displayed on front page.
Default: home

**search_label**<br />
Texts displayed on search results.
Default: Search Results of "%s" (%s : search strings)

**404_label**<br />
Texts displayed on 404 page.
Default: 404 Not Found

**category_label**<br />
Texts displayed on categories.
Default: %s (%s is a category label.)

**tag_label**<br />
Texts displayed on tags.
Default: %s (%s is a tag label)

**taxonomy_label**<br />
Texts displayed on taxonomies.
Default: %s (%s is a taxonomy label)

**author_label**<br />
Texts displayed on authors' page.
Default: %s (%s is author's name)

**attachment_label**<br />
Texts displayed on attachments.
Default: %s (%s is an attachment's name)

**year_label**<br />
Texts displayed on Yearly Archives.
Default: %s (%s is a year)

**month_label**<br />
Texts displayed on Monthly Archives. 
Default: %s (%s is monthly-display-type specified on date format)

**day_label**<br />
Texts displayed on Daily Archives.
Default: %s (%s is a day)

**post_type_label**<br />
Texts displayed on custom post type archives.
Default: %s  (%s is custom post type label)

**joint_string**<br />
If you specify "string" on type, strings between texts.
Default: " &amp;gt; " ( > )

**navi_element**<br />
Name of wrapper elements. You can select div or nav.
Default: none (no element)

**elm_class**<br />
Name of wrapper class. If no wrapper element and type is "list", name of "ul" class will be displayed.
Default: bread_crumb

**elm_id**<br />
Name of wrapper id. iF no wrapper element and type is "list", name of "ul" id will be displayed.
Default: none (no id)

**li_class**<br />
Name of class added to li if type is "list".
Default: none (no class)

**class_prefix**<br />
prefix added to each class.
Default: none (no prefix)

**current_class**<br />
Name of class added to breadcrumbs navigation on current page where you see.
Default: current

**indent**<br />
Number of tab indent. Default: 0

**echo**<br />
Output or not. Default: true (output).
If you specify 0 or false, return values as PHP.

== Changelog ==
= 1.0.4 =
* Compatible up to WordPress 3.4
* Add filter hook "bread_crumb_arr"

= 1.0.3 =
* Compatible up to WordPress 3.3

= 1.0.2 =
* refine ja.po, ja.mo

= 1.0.1 =
* Duplicated function "get_author_name" replace to "get_the_author_meta".

= 1.0.0 =
* Opening on WordPress Plugin Directory.
* Supported to display on archives of custom post types
* Added post_type_label parameter.

= 0.8.1 =
* Fixed the issue that true-values are not handled properly.
* Supported query parameter of "m" (month).

= 0.8.0 =
* Opening to the public.


== Screenshots ==
1. Output Sample of a breadcrumbs navigation

== Links ==
"[PS Taxonomy Expander](http://wordpress.org/extend/plugins/ps-taxonomy-expander/ "WordPress sitemap plugin")" makes categories, tags and custom taxonomies more useful.

"[PS Auto Sitemap](http://wordpress.org/extend/plugins/ps-auto-sitemap/ "WordPress sitemap plugin")" is a plugin automatically generates a site map page from your WordPress site. 
It is easy to install for beginners and easy to customize for experts.
It can change the settings of the display of the lists from administration page, several neat CSS skins for the site map tree are prepared.

"[PS Disable Auto Formatting](http://wordpress.org/extend/plugins/ps-disable-auto-formatting/ "WordPress formatting plugin")"
Stops the automatic forming and the HTML tag removal in the html mode of WordPress, and generates a natural paragraph and changing line.

"[CMS service with WordPress](http://www.prime-strategy.co.jp/ "CMS service with WordPress")" provides you service that uses WordPress as a CMS.
