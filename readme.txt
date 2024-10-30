=== KC Tools ===
Contributors: krumch
Donate link: http://krumch.com/kctools_wp.html
Tags: system info, environment info, hardware info, DB access, DB, SSH, developers tools, tool, PHP info
Requires at least: 3.0
Tested up to: 5.2.4
Stable tag: 2016.08.08

Brain surgery for WEB-sites (System info, DB access, SSH over HTTP, formatted debug output to mail, file and URL, debug shortcode and site wide debug email, all over HTTP).

== Description ==

<p>KC Tools are a Wordpress plugin with several tools for "in deep" development and troubleshooting. It allows reading of environment info, direct database access and terminal-like interface with server. It is locked by password to your browser only, so you can use it on life sites securely. It do nothing else - no output on pages, no shortcodes, no widgets, nothing, only a new row in "Settings" in Admin area.<br>
<br>
Sometimes, when develop something new or try to find problem somewhere, needs to read "raw" info. Thus, this plugin is the right tool. But it is too powerfull tool, so it is not for everyone.<br>
<br>
<strong>Blogers, self-webmasters, average users</strong>: This is not right tools for you. <a href="http://krumch.com/contacts.html">Contact me</a>, I can create anything what you will need, but leave this plugin away. It can (and will) destroy your site within seconds. No joke...<br>
<br>
<strong>Developers</strong>: Generally not for you too, but if you are brave enough, and want to learn - you are welcome. Sorry to repeat, but it is dangerous, because it is so powerfull. Imagine your plugin is a car - <a href="http://en.wikipedia.org/wiki/Nissan_Leaf" target="_blank">shiny and power</a> (80&nbsp;kW, 110&nbsp;hp). So Wordpress itself is a <a href="http://en.wikipedia.org/wiki/Truck" target="_blank">truck</a> (450 kW, 600 hp). This plugin is not a truck. It is not <a href="http://en.wikipedia.org/wiki/Locomotive" target="_blank">locomotive</a> (1,500&nbsp;kW, 2000 hp) too. Nor <a href="http://en.wikipedia.org/wiki/Haul_truck" target="_blank">haul truck</a> (2,610&nbsp;kW, 3,500&nbsp;hp). It is a <a href="http://en.wikipedia.org/wiki/Emma_M%C3%A6rsk" target="_blank">ship</a> (81&nbsp;MW, 109,000 hp), but it is not <a href="http://en.wikipedia.org/wiki/Falcon_Heavy" target="_blank">rocket</a>. Be warned...<br>
<br>

<strong>Gurus</strong>: It countain 3 tools. First allows to see environment variables and PHP init settings. Second allows direct communication with database, think about "mysql" command line tool. It is very light phpMyAdmin. Third is terminal-like tool, allowing command row, almost like SSH, but restricted to HTTPD user rights and no way to run continuous tasks like "top". <a href="http://krumch.com/kctools.html">There</a> exists a commercial WP version and non-WP versions too, with more functions.</p>
<p>Feel free to review all my <a href="http://krumch.com/category/wp_plugins/" target="_blank">other plugins</a>.</p>

== Installation ==

0. Create MD5 of your password
1. Upload the archive by "Add new"-&gt;"Upload" on "Plugins" page
2. Click "Edit" under plugin name at "Plugins" page. Put MD5-ed password in $thepassword variable at first code row in kstools.php
3. Activate the plugin through the "Plugins" menu in WordPress
4. Click "KC Tools" row in "Settings" within "Admin dashboard". First time you will be asked about password.

== Frequently Asked Questions ==

No questions, so far. Ask me, I will answer. Did you have some suggestions? What else you would like to have as tools?

== Screenshots ==

1. ENV tab
2. DB tab
3. SHH tab

== Changelog ==

= 2016.08.08 =
New version schema
Merged with Pro version, new functions added
- formatted debug output to mail, file and URL
- debug shortcode
- site wide debug email
Tested with WP 4.5.3

= 20150428 =
Tested with WP 4.2.1

= 20140127 =
Tested with WP 3.8.1

= 20121014 =
Annoying warnings fixed (we need fun warnings only)

= 20120921 =
* Testing with last WP version.

= 20120613 =
* Improved visual appearance.

= 20120512 =
* Fixed pictures in Admin panel, when PHP info is shown.
* Removed environmental info at top, it stay at bottom in the tables.
* Icon added.

= 20110601 =
* The very first versions.

== Upgrade Notice ==

= 2016.08.08 =
New version schema
Merged with Pro version, new functions added
- formatted debug output to mail, file and URL
- debug shortcode
- site wide debug email
Tested with WP 4.5.3

= 20150428 =
Tested with WP 4.2.1

= 20121014 =
Annoying warnings fixed (we need fun warnings only)

= 20120921 =
Testing with last WP version.

= 20120613 =
Improved visual appearance.

= 20120512 =
Fixed pictures in Admin panel, when PHP info is shown.
Removed environmental info at top, it stay at bottom in the tables.
Icon added.

= 20120418 =
Minor fixings

= 20110601 =
Upgrade from scratch to initial version :-)


