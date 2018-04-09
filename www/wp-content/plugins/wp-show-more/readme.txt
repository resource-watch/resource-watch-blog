=== WP show more ===
Contributors: JAMOS Web Service
Donate link:  http://www.jamos.ch
Tags: show more, more, less, read more, read less
Requires at least: 3.0.1
Tested up to: 4.7.5
Stable tag: 1.0.7
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Hide the Text between a simple Shortcode.

== Description ==

This plugin hides the text that is defined between the simple Shortcode. By clicking on the user-defined link, the hidden text becomes visible. It works via a Shortcode and can be placed everywhere!

<p>
Usage: <strong>[show_more more="TEXT" less="TEXT"] Content [/show_more]</strong><br /><br />
You can use some parameters like:<br />
- more="show more"<br>
- less="show more"<br>
- color="#0066CC"<br />
- list="»"<br />
- align="left" | "center" | "right"<br>
- size="120"<br />
</p>

<p>
Feel free to try it <a href="http://www.jamos.ch/plugins/wp-show-more" target="_blank">HERE</a>
</p>

== Installation ==

All you have to do is upload the plugin within your Wordpress Plugins menu and activate it. 
Then, you can add the shortcode everywhere you want (pages, posts, widgets etc): 

<strong>[show_more more="show more" less="show less"] Your hidden Content [/show_more]</strong> 

Of course, you can customize the text "show more".
In case that you have any support inquiries, feel free to contact me.

== Frequently Asked Questions ==

= Does the plugin works with any version of WordPress? =
<strong>Yes</strong> it does.

= How can I change the Link color? =
You can define the color with the attribute <strong>[color=“#ffcc00”]</strong> in the shortcode.

= How to make it center? =
Please use the parameter <strong>[align=“center”]</strong> in the shortcode.

= Can I put a specific character in front of the link? =
Yes you can. Define the character  with the attribute <strong>[list=“-”]</strong> in the shortcode.

= How can I change the link text? =
Define the «read more» and «read less» text with the attribute <strong>[more=“your text”]</strong> and <strong>[less=“your text”]</strong> in the shortcode.

= Which attributes are possible? =
* more="show more"
* less="show less"
* color="#0066CC"
* list="»"
* align="left" | "center" | "right"
* size="120"

= Is it possible to include other shortcodes as part of the content? =
Yes, since the version 1.0.7 it's possible (A special thanks to Ben Kirkland)

= How can I change the Font Size? =
Set the parameter <strong>[size=“120”]</strong> (for example 120 = 120%) 

== Screenshots ==

1. show more deactivated
2. show more activated (after click)

== Upgrade Notice ==

You can upgrade your Plugin manually or automatically.

== Changelog ==

= 1.0.7 =
Allows shortcodes to be used within the hidden content.

= 1.0.6 =
New parameter «size» to change the Font Size in (%)

= 1.0.5 =
Add new parameter «align» to set the link as «left», «center» or «right»

= 1.0.4 =
- Fix the message «headers already sent»
- Add the JavaScript code to the footer

= 1.0.3 =
New functions, which allows you to use the shortcodes "more" and "less"

= 1.0.2 =
The Shortcode with a new attribute [list=“-“] extended

= 1.0.1 =
The Shortcode with a new attribute [color=“#0066cc“] extended

= 1.0 =
Initial release.