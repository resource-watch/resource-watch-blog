To translate strings from The Grid you should use .po/.mo files located in the-grid/langs/ folder.
You also need to setup wordpress your language if it's not already the case: https://make.wordpress.org/polyglots/handbook/

.po/.mo files must be named like this (prefixed by "tg-text-domain"):

	- tg-text-domain-fr_FR.po
	- tg-text-domain-fr_FR.mo

These files can be added directly in the grid folder : /the-grid/langs/
Or to preserve translation after an update directly in: /wp-content/languages/plugins/ (since Wordpress v3.7)

And you can easly edit these files with Poedit software: https://poedit.net/

You can aslo use a 3rd party plugin to easly translate strings like WPML plugin.The Grid is compatible with WPML.

Themeone Team