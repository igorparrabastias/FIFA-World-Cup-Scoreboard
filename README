=== FIFA World Cup South Africa Scoreboard ===
Contributors: Nomikos
Donate link: https://www.paypal.com/cl/cgi-bin/webscr?cmd=_flow&SESSION=2cOKD07f2uX8_d16CO3H8aY1aypcNYD3MjXQYvACoYwuGHuHXWMyzYI1JAK&dispatch=5885d80a13c0db1f22d2300ef60a6759516e590e949da361fd1b680561e9552a
Tags: FIFA, World Cup, South Africa, FIFA 2010, football, soccer, Copa del Mundo 2010, Mundial de fútbol
Requires at least: 2.8
Tested up to: 2.9.2
Stable tag: 2.1

== Description ==

Get the latest results of 2010 FIFA World Cup South Africa™ in your Wordpress blog. Put a shortcode: wp_fifa_world_cup_scoreboard into a post or page and/or use the sidebar widget. Automatically updated after each match!

**CAVEAT:** This plugin has a scraper included that fetch data from the [FIFA official website](http://www.fifa.com/worldcup/matches/index.html "2010 FIFA World Cup South Africa™"). All outbound links go to their website.

**What's new**

* The plugin is brand new.
* Check changelog please.

**Other features**

* Code should be updated frequently... As long as my team keep winning. LOL. Please at least, check for updates, before the second fase of the World Cup.
* The scraper is deployed only if necessary (when someone visits a page or post with the shortcode [wp_fifa_world_cup_scoreboard] included or the sidebar's widget is executed).
* When this happens, the plugin checks the last time of scraping. If there is new match unparsed will be deployed, otherwise uses data from an internal cache.

== Installation ==

1. You need to load/activate the curl extension. If you do't know what this means, just go on. Most servers have curl installed!
1. Download the plugin and extract its contents.
1. Upload the `fifa-world-cup-south-africa-scoreboard` folder to the `/wp-content/plugins/` directory.
1. Activate **FIFA World Cup South Africa scoreboard** plugin through the 'Plugins' menu in WordPress.
1. In your admin console, go to Appeareance > Widgets, drag the FIFA World Cup South Africa Scoreboard widget to wherever you want it to be, configure it and click on Save.
1. More options in Settings > FIFA World Cup South Africa Scoreboard.
1. (optional) Use [wp_fifa_world_cup_scoreboard] into a post or a page.
1. Any malfunction please drop me a line in http://nomikos.info/2010/06/10/fifa-world-cup-south-africa-scoreboard-wp-plugin.html

That's it!

= Problems fetching data? Easy... = 

* This would be very rare, but if the scraper seems to fail, check following permissions in folder fifa-world-cup-south-africa-scoreboard/
* Verifies that you have curl installed (mandatory).
* chmod 757 php/tmp && chmod 646 php/tmp/cookies.txt (mandatory).
* chmod 757 log && chmod 646 log.txt (optional for logging).

= Template Tags =

*nomikos_fifa_world_cup_scoreboard_widget_manual*

Due to the fact that some themes are not widget-ready, or that some blog users don't like widgets at all, there's another choice: the *nomikos_fifa_world_cup_scoreboard_widget_manual* template tag. With it, you can embed the scoreboard on your site's sidebar without using a widget. This function also accepts parameters (optional) so you can customize the look and feel of the listing.

**Usage:**

*Without any parameters:*

`<?php
if (function_exists('nomikos_fifa_world_cup_scoreboard_widget_manual'))
    nomikos_fifa_world_cup_scoreboard_widget_manual();
?>`

* It will show random groups with date and one row by match.

*Using parameters:*

`<?php
if (function_exists('nomikos_fifa_world_cup_scoreboard_widget_manual'))
    nomikos_fifa_world_cup_scoreboard_widget_manual("date=1&one_row=1&a=1&d=1");
?>`

* It will show random groups if you do not pass at least one group.
* date (0|1): show date of match.
* one_row (0|1): reduce the output. useful in small sidebars.
* a, b, c, d, e, f, g, h: groups of teams.

== Frequently Asked Questions ==

* How are you?
* Fine, thanks! o/ \o high five!

== Screenshots ==

1. Page and Widget view.
2. Widgets Control Panel.
3. Static bar on top. 
4. Options panel.

== Changelog ==

= 2.1 =
* Javascript totally revamped.

= 2.0 =
* Stage 2 updated.
* Countdown added to each match.

= 1.7.2 =
* Safe mode on compatibility.

= 1.7 =
* Scraper updated.
* Function to switch to local time added to page && sidebar.
* Sidebar widget now shows one group at a time.
* Top bar shows "Last result", "Next macth" and "Now playing" (if there is a match in progress).
* Fade effect added to top bar.
* Top bar now fix better in 1024px wide screens.
* Schedule image in sidebar added.
* Top bar & sidebar schedule can be removed from Options panel.

= 1.6 =
* Scraper partial fixed. Next version will be complete.

= 1.5.5 =
* Scraper partial fixes. Next version will be complete.

= 1.5 =
* Directory problem fixed!!!

= 1.4 =
* Directory problem fixed.

= 1.3 =
* General improvements.
* Method init run only once. Set data for widget and page first time is called.
* New options panel in admin panel.
* Refresh button added to top bar. Force scraping.
* Top bar updated. Now say "Now playing..." or "Last result..."

= 1.2 =
* Sync with the official schedule for deploying the scraper once by match.
* Static bar at the top added. Opened only in time of match.

= 1.1  =
* readme.txt updated

= 1.0 =
* Public release

== Upgrade Notice ==

* Deactivate plugin.
* Overwrite directory.
* Activate plugin.

== To do ==

* Add last news feeds.
* Maybe some images/videos into a jquery-generated tooltip. (Too much?)
* Any idea?
* Work for money!!!