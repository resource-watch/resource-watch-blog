=== SVG Support ===
Contributors: Benbodhi
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=Z9R7JERS82EQQ
Tags: svg, vector, css, style, mime, mime type, embed, img, inline, animation, animate, js
Requires at least: 4.8
Tested up to: 4.9-alpha-41335
Stable tag: 2.3.11
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Allow SVG file uploads using the WordPress Media Library uploader plus the ability to inline SVG files for direct styling/animation of SVG elements using CSS/JS.

== Description ==

When using SVG images on your WordPress site, it can be hard to style elements within the SVG using CSS. **Now you can, easily!**

Scalable Vector Graphics (SVG) are becoming common place in modern web design, allowing you to embed images with small file sizes that are scalable to any visual size without loss of quality.

This plugin not only provides SVG Support like the name says, it also allows you to easily embed your full SVG file's code using a simple IMG tag.<br />
By adding the class `"style-svg"` to your IMG elements, this plugin dynamically replaces any IMG elements containing the `"style-svg"` class with your complete SVG.

The main purpose of this is to allow styling of SVG elements. Usually your styling options are restricted when using `embed`, `object` or `img` tags alone.

= Features =

* SVG Support for your media library
* Inline your SVG code
* Works with the new Image Widget (WordPress 4.8+)
* Style SVG elements directly using CSS
* Super easy settings page with instructions
* Restrict SVG upload ability to Administrators only
* Set custom css target class
* **Extremely Simple To Use**

== Usage ==

Firstly, install and activate SVG Support (this plugin).

Once activated, you can simply upload SVG images to your media library like any other file.

As an administrator, you can go to the admin settings page 'Settings' > 'SVG Support' and restrict SVG file uploads to administrators only and even define a custom CSS class to target if you wish.

If you only need to upload SVG files to use as images, you don't need to enable "Advanced Mode”. Leaving it disabled ensures the frontend script is not enqueued and the unnecessary settings stay hidden.

**For advanced users:** Enable the "Advanced Mode" under Settings > SVG Support.

With advanced mode enabled, you can embed your SVG images just like you would a standard image with the addition of adding (in text view) the class `"style-svg"` (or the custom class you defined) to your IMG tags that you want this plugin to swap out with your actual SVG code.

For example:

`<img class="style-svg" alt="alt-text" src="image-source.svg" />`

or

`<img class="your-custom-class" alt="alt-text" src="image-source.svg" />`

The whole IMG tag element will now be dynamically replaced by the actual code of your SVG, making the inner content targetable.<br />
This allows you to target elements within your SVG using CSS and JS.

You can remove all other attributes from the IMG tag as it will disappear anyway.

There’s a setting to automatically add your class to the IMG tag for you when you're inserting SVG’s in to a post or page, which also removes unnecessary tags.
Since 2.3.11, you can force all SVG files to be rendered inline with a single checkbox. Additionally, you can now choose whether to use the minified or expanded version of the JS file.

*Featured Images:* If a post/page is saved with your SVG as a featured image, a checkbox will display in the featured image meta box to allow you to render it inline (only if advanced mode is active).

Please Note: If your SVG isn’t showing, it’s likely that it is being displayed with 0 height and width. In this case, you will need to set your own height and width in your CSS for SVG files to display correctly.

*If you're having any issues, please use the support tab and I will try my best to get back to you quickly*

== Security ==

As with allowing uploads of any files, there is potential risks involved. Only allow users to upload SVG files if you trust them. You have the option to restrict SVG usage to Administrators only from the settings page. By default, anyone with Media Library access or upload_files capability will be able to upload SVG files (that is Administrators, Authors and Editors). Please note that SVG files are actually XML which would allow someone to inject malicious code if you're not careful with who has upload privileges.

== Feedback ==

* I'm open to your [suggestions and feedback](mailto:wp@benbodhi.com) - Thanks for using SVG Support!
* Tag me [@benbodhi](https://twitter.com/benbodhi) or [@GoWebben](https://twitter.com/gowebben) on Twitter
* Like & Follow [my Facebook page](https://www.facebook.com/gowebben)
* Or circle [+GoWebben](https://plus.google.com/+Gowebben/) on Google Plus ;-)

*Note:* This is my second plugin on the WordPress repository, I hope you like it. Please take a moment to rate it and click 'works' under compatibility with your version of WordPress.

== Translations ==

You can [contribute your translation here](https://translate.wordpress.org/projects/wp-plugins/svg-support).
New to Translating WordPress? Read through the [Translator Handbook](https://make.wordpress.org/polyglots/handbook/tools/glotpress-translate-wordpress-org/) to get started.

== Additional Info ==
**Idea Behind / Philosophy:** I needed an easy way to include SVG support in sites instead of having to copy and paste the code for each one. I also needed the ability to make odd shaped image links which SVG allows by embedding the links in the SVG file directly. I found a [really cool snippet](http://stackoverflow.com/questions/11978995/how-to-change-color-of-svg-image-using-css-jquery-svg-image-replacement) of jQuery written by Drew Baker a while ago and have used it (modified for WordPress) a few times until I was inspired to build it all into a plugin for ease of use and to share with others. Now styling SVG elements is super easy :)

Again, feel free to [shoot me suggestions](mailto:wp@benbodhi.com)

== Credits ==
Plugin by [Benbodhi](https://benbodhi.com/) [@benbodhi](https://twitter.com/benbodhi) from [GoWebben](http://gowebben.com/) [@GoWebben](https://twitter.com/gowebben)

Thanks to [ipokkel](https://wordpress.org/support/users/ipokkel/) for his suggestions and code contributions.
Thanks to [laurosello](https://wordpress.org/support/users/laurosollero) for his code contribution.
Logo By W3C, CC BY 2.5, [https://commons.wikimedia.org/w/index.php?curid=1895005](https://commons.wikimedia.org/w/index.php?curid=1895005).

== Frequently Asked Questions ==

= SVG not rendering inline since 2.3 update =

SVG Support 2.3 includes a new settings section called "Advanced Mode". Users that were inlining SVG files need to make sure this setting is checked. Go to your dashboard > Settings > SVG Support and check "Advanced Mode". All of your original settings should still be there.

= How do I disable the Javascript on the front end if I am not using inline SVG? =

If you go to `Settings > SVG Support` in your admin dashboard, you can choose to enable "Advanced Mode" or not. If you leave it disabled, the advanced functionality and extraneous script is removed.

= I'm trying to use SVG in the customizer but it's not working. =

To allow SVG to work in the customizer, you will need to modify/add some code in your child theme's function file. [Here is a great tutorial](https://thebrandid.com/using-svg-logos-wordpress-customizer/) on how to do that. The important part is
`
'flex-width'	=> true
'flex-height'	=> true
`

= How do I add animation to my SVG? =

You will need to edit your SVG file in a code editor so you can add CSS classes to each element you need to target within the SVG. Make sure that your IMG tag is being swapped out for your inline SVG and then you can use CSS or JS to apply animations to elements within your SVG file.

= Why is SVG Support not working in multisite? =

If you installed multisite prior to WordPress 3.5, then you will need to remove your ms-files. Here is a couple of resources to help you: [Dumping ms-files](http://halfelf.org/2012/dumping-ms-files/) [Removing ms-files after 3.5](https://www.yunomie.com/2298/removing-ms-files-php-after-upgrading-an-existing-multisite-installation-to-3-5/).

= Why is my SVG not working in Visual Composer? =

If you are using SVG Support with Visual Composer or any other page builders, you will need to make sure that you can add your own class to the image. The easiest way to do this is by using a simple text or code block in the builder to put your image code in to.

= How do I get this to work with the Media Library Assistant plugin? =

You need to add the mime type for svg and svgz to: "MLA Settings > Media Library Assistant > Uploads (tab)" and then it works.

== Screenshots ==

1. Basic Settings
2. Advanced Settings
3. Featured Image checkbox to render SVG inline
4. Inline SVG in the front end markup

== Installation ==

= via wp-admin =
1. Visit 'Plugins' > 'Add New'
2. Type "SVG Support" into the search field
3. Click 'Install Plugin' and confirm in the pop up
4. Click 'Activate Plugin'

or

1. Upload the compressed version `svg-support.zip` through 'Plugins' > 'Add New' > 'Upload'
2. Click 'Activate Plugin'

= via FTP =
1. Download plugin zip and extract it on your computer
2. Upload folder `svg-support` to your `/wp-content/plugins/` directory
3. Activate the plugin through the 'Plugins' menu in WordPress


== Changelog ==

= 2.3.11 =

* New: Feature to use expanded JS file rather than the minified/compressed version (useful for bundling and minifying using external caching plugins).
* New: Force Inline SVG option. This feature allows you to force all of your SVG files to be rendered inline regardless of classes applied. Addresses issues where you can't add your own class to an image for some reason. For example, some page builder image elements. Also addresses changing your target class in the settings and needing to change all of your already embedded media, allowing you to simply force render rather than update all of the classes.
* Modified the readme file and descriptions a bit.
* Refined some code in functions/featured-image.php line 69 to address a warning.
* Updated "Requires at least" tag to 4.8 (though it should still work in older versions, there was issues with core during the 4.7 phase and it's time for you to update anyway).

= 2.3.10 =

* Fixed missing links in settings page.

= 2.3.9 =

* Modified plugin action meta link for settings page.
* Changed some language throughout the plugin.
* Added recommendation for ShortPixel Image Optimization.
* Added conditional to check post type supports thumbnail before setting meta data.

= 2.3.8 =

* Added some CSS to make sure featured images show on WooCommerce products, Sensei Courses and Lessons.
* Fix: Auto insert class setting was stripping featured image HTML in some cases.

= 2.3.7 =

* Added WP version check to wrap mime fix function needed for WP v4.7.1 - v4.7.2.
* Moved mime fix into mime type file.
* Modified admin notice code to make it neater.
* Fix: attachment-modal.php issues with some servers and external SVG files (props to @abstractourist & @malthejorgensen for providing fixes, as I could not consistently reproduce the issue).
* Compatibility: Changed a line to provide wider compatibility, specifically for WordPress Bedrock on a LEMP stack.
* Compatibility: Added another snippet to the JS to support IE11 (apparently people still use IE).
* Added more FAQ's.


= 2.3.6 =

* New: Added polyfill to make svgs-inline.js work with older browsers.
* New: Section to leave reviews on settings page.
* Removed: Redundant one time upgrade activate code.
* Fix: Errors reported on activation and on the settings page - [Related Support Thread](https://wordpress.org/support/topic/error-on-plugin-settings-page/).

= 2.3.5 =

* Revision and modification of the thumbnail display code.

= 2.3.4 =

* Fix: Fatal error for some because a function wasn't prefixed.

= 2.3.3 =

* Fix: Missing arguments PHP warnings from new attribute control file.
* Update settings page text.

= 2.3.2 =

* Modified the attribute control code that auto inserts our class to only apply to SVG files.

= 2.3.1 =

* Fix: Fatal error in some cases due to admin notice.

= 2.3 =

* New Feature - Advanced Mode: allows you to turn off the advanced features and simply upload SVG files like normal images. This addition also enables users to turn off the script added on front end by leaving Advanced Mode unchecked.
* New Feature - Featured Image Support: If your featured image is SVG, once the post is saved you will see a checkbox to render the SVG inline (advanced mode only).
* Performance - Stop inlining JS from running if image source is not SVG.
* Added new stylesheet for settings page.
* Moved SCSS files to their own folder.
* Changed donate link so I can track it and properly thank you for your generous donations.
* Added a rating link to the settings and media pages.
* Cleaned up code formatting, added more comments.
* Added a plugin version check.
* Added notice so people are aware they may need to turn on the advanced mode.

= 2.2.5 =

* FIX: Display SVG thumbnails in attachment modals.

= 2.2.4 =

* FIX: Added function to temporarily fix an issue with uploading in WP 4.7.1

= 2.2.32 =

* Changed text domain to match plugin slug for localization.

= 2.2.31 =

* Attempt to fix ability to translate

= 2.2.3 =

* Modified code in svg-support/js/svg-inline.js and svg-support/js/min/svg-inline-min.js to allow JS control of the SVG elements and detect if they have been loaded (IMG tag swapped out). Thanks to [laurosello](https://wordpress.org/support/profile/laurosollero) for this suggestion and code contribution.
* Fixed SVG thumbnails not displaying correctly in list view of the media library.
* Cleaned up the code and comments a bit.
* Added translation for Spanish. Thanks to [Apasionados del Marketing](http://apasionados.es) for the translation.

= 2.2.2 =

* Changed another anonymous function in svg-support/functions/thumbnail-display.php that was causing errors for some.

= 2.2.1 =

* Changed anonymous function in svg-support/functions/thumbnail-display.php line 15 to prevent fatal error in older PHP versions.

= 2.2 =

* Added support to make SVG thumbnails visible in all media library screens.
* Added SVGZ to the mime types.
* Automatically removes the width and height attributes when inserting SVG files.
* Added ability to choose whether the target class is automatically inserted into img tags or not, stripping the default WordPress classes.
* Added ability to choose whether script is output in footer - true or false.
* Blocked direct access to PHP files.
* Added SCSS support using CodeKit - minified CSS + JS files.
* Updated spelling for incorrect function name.
* Changed comment formatting across all files for consistency.
* Added link to $25 Free credit at GoWebben on the settings page.
* Tested in WordPress 4.3.
* Updated Readme file.

= 2.1.7 =

* Tested in WordPress 4.0 and added plugin icons for the new interface.

= 2.1.6 =

* Added missing jQuery dependency in /functions/enqueue.php (pointed out by [walbach](http://wordpress.org/support/profile/waldbach)) - was loading SVG Support JS before jQuery.

= 2.1.5 =

* Added Serbian translation, submitted by Ogi Djuraskovic.

= 2.1.4 =

* Fixed plugin settings link (on plugins page)
* Added more links - Support & Donate
* Modified the settings page a little
* Cleaned up settings page with CSS
* Satisfied my OCD tendencies a little

= 2.1.3 =

* Added plugin_action_links file for custom menus on plugin page.

= 2.1.2 =

* Cleaned up trunk, tags and readme.txt to show correct changelog and update notice.

= 2.1.1 =

* Fixed JS file conditional - worked in local testing but not live.

= 2.1 =

* Updates to language files for localization.

= 2.0 =

* Added an admin settings page with instructions plus options for restricting to admin use only and setting a custom CSS target class.
* Whole plugin completely re-written and re-structured.
* Added option to restrict SVG uploads to administrators only.
* Added field for custom CSS target class.
* Added stylesheet to admin settings page.

= 1.0 =

* Initial Release.

== Upgrade Notice ==

= 2.3.11 =

* New Features and Fixes! Now you can force ALL of your SVG files (old and new) to be rendered inline in a single click with the new "Force Inline SVG" setting. You can also choose to use an expanded version of the inline JS if you want to minify it separately using a caching plugin or similar.

= 2.3.10 =

* Fixed missing links in settings page.

= 2.3.9 =

* Cleaned up some code and language, now stores less meta when not needed and added a plugin recommendation for Image Optimization.

= 2.3.8 =

* Adds better support for WooCommerce and Sensei. Fixes issue with featured images not showing up when auto insert class setting is on.

= 2.3.7 =

* Fixes issues with media library not loading for some, attachment-modal errors and adds some wider compatibility.

= 2.3.6 =

* Adds support for older browsers, fixes a couple of seemingly isolated errors reported, removes some redundant code.

= 2.3.5 =

* Modifications to thumbnail display code to prevent output buffer clash with another plugin.

= 2.3.4 =

* Fixes fatal error for some because a function wasn't prefixed.

= 2.3.3 =

* This update fixes some PHP warnings introduced in 2.3.2 and also has updated settings page text.

= 2.3.2 =

* Changes to the way the auto class insert works.

= 2.3.1 =

* Fixes fatal error in some cases due to admin notice in V2.3.

= 2.3 =

IMPORTANT, MAJOR CHANGES, BACKUP BEFORE UPDATING: Users that are inlining SVG will need to make sure "Advanced Mode" is active under "Settings > SVG Support". Your settings should all still be there. Make sure you run a backup before updating just in case!!!

= 2.2.5 =

* Fix to display SVG thumbnails in attachment modals. (NOTE: You can not edit SVG files like other images in WordPress)

= 2.2.4 =

* IMPORTANT: Fixes upload ability in WP 4.7.1

= 2.2.32 =

* Changed text domain to match plugin slug for localization.

= 2.2.31 =

* This release attempts to fix translation issues.

= 2.2.3 =

* Feature - Changed code to allow JS detection if SVG has loaded and ability to control SVG using JS.
* Fix - Thumbnail display in media library list view.
* Added Spanish translation and cleaned up code/comments a bit.

= 2.2.2 =

* Fix - Another change from anonymous function that was triggering errors for some.

= 2.2.1 =

* Minor change to remove anonymous function that triggered a fatal error in older PHP versions.

= 2.2 =

* Significant changes, added functionality, please BACKUP BEFORE UPDATING just in case.

= 2.1.7 =

* Tested in WordPress 4.0 and added plugin icons for the new interface.

= 2.1.6 =

* Important update! Added missing jQuery dependency in /functions/enqueue.php - was loading SVG Support JS before jQuery.

= 2.1.5 =

* Added Serbian translation, submitted by Ogi Djuraskovic.

= 2.1.4 =

* Some more re-arranging, added a few helpful links, updated language files, tended to my OCD a bit.

= 2.1.3 =

* Added a link on the plugins page to the plugin settings page for easy access after install.

= 2.1.2 =

* A little bit of house cleaning, updates to changelog and readme.txt for correct output with current version.

= 2.1.1 =

* Update to conditional in JS file.

= 2.1 =

* Updated language files for localization that were missed in version 2.0.

= 2.0 =

* SVG Support has been completely re-written and re-structured. It now includes an admin settings page with instructions, plus options for restricting to admin use only and setting a custom CSS target class.

= 1.0 =

* Initial Release.
