=== SSL Insecure Content Fixer ===
Contributors: webaware
Plugin Name: SSL Insecure Content Fixer
Plugin URI: https://ssl.webaware.net.au/
Author URI: https://shop.webaware.com.au/
Donate link: https://shop.webaware.com.au/donations/?donation_for=SSL+Insecure+Content+Fixer
Tags: ssl, https, insecure content, partially encrypted, mixed content
Requires at least: 4.0
Tested up to: 4.9
Stable tag: 2.5.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Clean up WordPress website HTTPS insecure content

== Description ==

Clean up your WordPress website's HTTPS insecure content and mixed content warnings. Installing the SSL Insecure Content Fixer plugin will solve most insecure content warnings with little or no effort. The remainder can be diagnosed with a few simple tools.

When you install SSL Insecure Content Fixer, its default settings are activated and it will automatically perform some basic fixes on your website using the Simple fix level. You can select more comprehensive fix levels as needed by your website.

WordPress Multisite gets a network settings page. This can be used to set default settings for all sites within a network, so that network administrators only need to specify settings on sites that have requirements differing from the network defaults.

See the [SSL Insecure Content Fixer website](https://ssl.webaware.net.au/) for more details.

= Translations =

Many thanks to the generous efforts of our translators:

* Bulgarian (bg_BG) -- [the Bulgarian translation team](https://translate.wordpress.org/locale/bg/default/wp-plugins/ssl-insecure-content-fixer)
* Chinese simplified (zh_CN) -- [the Chinese translation team](https://translate.wordpress.org/locale/zh-cn/default/wp-plugins/ssl-insecure-content-fixer)
* English (en_CA) -- [the English (Canadian) translation team](https://translate.wordpress.org/locale/en-ca/default/wp-plugins/ssl-insecure-content-fixer)
* English (en_GB) -- [the English (British) translation team](https://translate.wordpress.org/locale/en-gb/default/wp-plugins/ssl-insecure-content-fixer)
* Dutch (nl_NL) -- [the Dutch translation team](https://translate.wordpress.org/locale/nl/default/wp-plugins/ssl-insecure-content-fixer)
* German (de_DE) -- [the German translation team](https://translate.wordpress.org/locale/de/default/wp-plugins/ssl-insecure-content-fixer)
* French (fr_FR) -- [the French translation team](https://translate.wordpress.org/locale/fr/default/wp-plugins/ssl-insecure-content-fixer)
* Italian (it_IT) -- [the Italian translation team](https://translate.wordpress.org/locale/it/default/wp-plugins/ssl-insecure-content-fixer)
* Japanese (ja) -- [the Japanese translation team](https://translate.wordpress.org/locale/ja/default/wp-plugins/ssl-insecure-content-fixer)
* Russian (ru_RU) -- [the Russian translation team](https://translate.wordpress.org/locale/ru/default/wp-plugins/ssl-insecure-content-fixer)
* Spanish (es_ES) -- [the Spanish translation team](https://translate.wordpress.org/locale/es/default/wp-plugins/ssl-insecure-content-fixer)

If you'd like to help out by translating this plugin, please [sign up for an account and dig in](https://translate.wordpress.org/projects/wp-plugins/ssl-insecure-content-fixer).

== Installation ==

1. Either install automatically through the WordPress admin, or download the .zip file, unzip to a folder, and upload the folder to your /wp-content/plugins/ directory. Read [Installing Plugins](https://codex.wordpress.org/Managing_Plugins#Installing_Plugins) in the WordPress Codex for details.
2. Activate the plugin through the 'Plugins' menu in WordPress.

If your browser still reports insecure/mixed content, have a read of the [Cleaning Up page](https://ssl.webaware.net.au/cleaning-up-content/).

== Frequently Asked Questions ==

= How do I tell what is causing the insecure content / mixed content warnings? =

Look in your web browser's error console.

* Google Chrome has a [JavaScript Console](https://developers.google.com/chrome-developer-tools/docs/console) in its developer tools
* FireFox has the [Web Console](https://developer.mozilla.org/en-US/docs/Tools/Web_Console) or [Firebug](http://getfirebug.com/)
* Internet Explorer has the [F12 Tools Console](https://msdn.microsoft.com/library/bg182326%28v=vs.85%29)
* Safari has the [Error Console](https://developer.apple.com/library/safari/documentation/AppleApplications/Conceptual/Safari_Developer_Guide/Introduction/Introduction.html)

NB: after you open your browser's console, refresh your page so that it tries to load the insecure content again and logs warnings to the error console.

[Why No Padlock?](https://www.whynopadlock.com/) has a really good online test tool for diagnosing HTTPS problems.

= I get "insecure content" warnings from some of my content =

You are probably loading content (such as images) with a URL that starts with "http:". Take that bit away, but leave the slashes, e.g. `//www.example.com/image.png`; your browser will load the content, using HTTPS when your page uses it. Better still, replace "http:" with "https:" so that it always uses https to load images, e.g. `https://www.example.com/image.png`.

If your page can be used outside a web browser, e.g. in emails or other non-web documents, then you should always use a protocol and it should probably be "https:" (since you have an SSL certificate). See [Cleaning up content](https://ssl.webaware.net.au/cleaning-up-content/) for more details.

= My website is behind a load balancer or reverse proxy =

If your website is behind a load balancer or other reverse proxy, and WordPress doesn't know when HTTPS is being used, you will need to select the appropriate [HTTPS detection settings](https://ssl.webaware.net.au/https-detection/). See my blog post, [WordPress is_ssl() doesn’t work behind some load balancers](https://snippets.webaware.com.au/snippets/wordpress-is_ssl-doesnt-work-behind-some-load-balancers/), for some details.

= I get warnings about basic WordPress scripts like jquery.js =

You are probably behind a reverse proxy -- see the FAQ above about load balancers / reverse proxies, and run the SSL Tests from the WordPress admin Tools menu.

= I changed the HTTPS Detection settings and now I can't login =

You probably have a conflict with another plugin that is also trying to fix HTTPS detection. Add this line to your wp-config.php file, above the lines about `ABSPATH`. You can then change this plugin back to default settings before proceeding.

`define('SSLFIX_PLUGIN_NO_HTTPS_DETECT', true);`

= I still get "insecure content" warnings on my secure page =

Post about it to [the support forum](https://wordpress.org/support/plugin/ssl-insecure-content-fixer), and be sure to include a link to the page. Posts without working links will probably be ignored.

= You listed my plugin, but I've fixed it =

Great! Tell me which plugin is yours and how to check for your new version, and I'll drop the "fix" from my next release.

== Contributions ==

* [Translate into your preferred language](https://translate.wordpress.org/projects/wp-plugins/ssl-insecure-content-fixer)
* [Fork me on GitHub](https://github.com/webaware/ssl-insecure-content-fixer)

== Upgrade Notice ==

= 2.5.0 =

support for https detection on KeyCDN; option to only fix content resource links for the current website; .htaccess rules file for non-WP test script now supports Apache v2.4

== Changelog ==

The full changelog can be found [on GitHub](https://github.com/webaware/ssl-insecure-content-fixer/blob/master/changelog.md). Recent entries:

### 2.5.0, 2017-11-23

* changed: .htaccess rules file for non-WP test script now supports Apache v2.4; thanks, [Andreas Schneider](https://github.com/cryptomilk)!
* added: option to only fix content resource links for the current website; thanks, [Luke Driscoll](https://github.com/ldriscoll)!
* added: support for KeyCDN https detection via the X-Forwarded-Scheme header
