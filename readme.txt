=== WP Hashed IDs ===
Contributors: palicao
Tags: encryption, permalinks, url shortening, permalink tag
Requires at least: 3.0
Tested up to: 4.5.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Stable tag: 2.0

Adds the ability to crypt post IDs in URLs (eg. http://www.example.com/ha5b9/my-slug instead of http://www.example.com/43/my-slug)

== Description ==

This plugin adds a new permalink tag %hashed_id% which represents the encrypted
version of your post ID.
Uses hashids library (see http://www.hashids.org/php/).

With this plugin you can change your permalink structure into something like

`/%hashed_id%/` 

to obtain links like

`http://www.example.com/B7j1rPk8`

or into something like

`/%year%/%monthnum%/%hashed_id%/`

to obtain URLs like

`http://www.example.com/2012/10/B7j1rPk8`

or

`/%hashed_id%/%postname%/`

to obtain

`http://www.example.com/B7j1rPk8/my-slug`

This plugin is useful when you want to obfuscate the number of posts or simply
if you want an unusual and concise url schema. This is also useful if you want to have
short URLs without using a third-party service.

== Installation ==

1. Upload `wp-hashed-id` directory to `/wp-content/plugins/`
2. Activate the plugin through the 'Plugins' menu in WordPress
3. If you have the `%post_id%` tag in your permalink structure it will be
   substituted by `%hashed_id%`. The opposite will happen upon deactivation.
