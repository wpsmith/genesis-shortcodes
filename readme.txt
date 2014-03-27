=== Genesis Shortcodes ===
Contributors: wpsmith
Plugin URI: http://www.wpsmith.net/genesis-shortcodes
Donate link: http://www.wpsmith.net/donation
Tags: shortcodes, genesis, genesiswp
Stable tag: trunk
Requires at least: 3.0
Tested up to: 3.3.1

Packages several commonly used shortcodes for the Genesis Framework.

== Description ==

This file creates all the shortcodes used throughout a site. While there is an array of shortcodes that are packaged with Genesis, this plugin provides a few more shortcodes that are used for the purpose of developing sites and migrating sites. It also enables code to execute shortcodes in the text widget. Contains the following shortcodes: [post_field], [child] or [child_url], [wpurl], [url], [site_url], [uploads], [genesis_comments], [genesis_email] or [email], [genesis_email_link] or [email_link], [genesis_modified_date] or [modified_date], [genesis_avatar] or [avatar], [plugin_info], & content columns shortcodes.
[post_field], [child_url], [wpurl], [url], [uploads], 

Plugin Info Shortcode:
 *   e.g., [plugin_info slug="wordpress-seo" data="banner" ]
 *   e.g., [plugin_info slug="genesis-shortcodes" data="tags" ]
 *   e.g., [plugin_info slug="genesis-shortcodes" data="author" ]
 *   e.g., [plugin_info slug="genesis-shortcodes" data="author_profile" ]
 *   e.g., [plugin_info slug="genesis-shortcodes" data="contributors" ]
 *   e.g., [plugin_info slug="genesis-shortcodes" data="requires" ]
 *   e.g., [plugin_info slug="genesis-shortcodes" data="tested" ]
 *   e.g., [plugin_info slug="genesis-shortcodes" data="rating" ]
 *   e.g., [plugin_info slug="genesis-shortcodes" data="num_ratings" ]
 *   e.g., [plugin_info slug="genesis-shortcodes" data="downloaded" ]
 *   e.g., [plugin_info slug="genesis-shortcodes" data="last_updated" ]
 *   e.g., [plugin_info slug="genesis-shortcodes" data="added" ]
 *   e.g., [plugin_info slug="genesis-shortcodes" data="homepage" ]
 *   e.g., [plugin_info slug="genesis-shortcodes" data="short_description" ]
 *   e.g., [plugin_info slug="genesis-shortcodes" data="download_link" ]
 *   e.g., [plugin_info slug="genesis-shortcodes" data="donate_link" ]
 *   e.g., [plugin_info slug="genesis-shortcodes" data="sections" ]
 *   e.g., [plugin_info slug="genesis-shortcodes" section="description" ]
 *   e.g., [plugin_info slug="genesis-shortcodes" section="installation" ]
 *   e.g., [plugin_info slug="genesis-shortcodes" section="screenshots" ]
 *   e.g., [plugin_info slug="genesis-shortcodes" section="changelog" ]
 *   e.g., [plugin_info slug="genesis-shortcodes" section="faq" ]

MultiSite Shortcode:

 *   e.g. [site_url]
 *   e.g. [site_url site="1"]
 *   e.g. [site_url site="1" scheme="admin"]
 *   e.g. [site_url site="1" path="/sample/"]
 *   e.g. [site_url site="{optional_ID}" path="/path/relative/to/site/url" scheme="http|login|login_post|admin"]
 
Content Columns Shortcodes

 *   e.g. [one_half_first]CONTENT[/one_half_first][one_half]CONTENT[/one_half]
 *   e.g. [one_third_first]CONTENT[/one_third_first][one_third]CONTENT[/one_third]
 *   e.g. [two_thirds_first]CONTENT[/two_thirds_first][two_thirds]CONTENT[/two_thirds]
 *   e.g. [one_fourth_first]CONTENT[/one_fourth_first][one_fourth]CONTENT[/one_fourth]
 *   e.g. [two_fourths_first]CONTENT[/two_fourths_first][two_fourths]CONTENT[/two_fourths]
 *   e.g. [three_fourths_first]CONTENT[/three_fourths_first][three_fourths]CONTENT[/three_fourths]
 *   e.g. [one_fifth_first]CONTENT[/one_fifth_first][one_fifth]CONTENT[/one_fifth]
 *   e.g. [two_fifths_first]CONTENT[/two_fifths_first][two_fifths]CONTENT[/two_fifths]
 *   e.g. [three_fifths_first]CONTENT[/three_fifths_first][three_fifths]CONTENT[/three_fifths]
 *   e.g. [four_fifths_first]CONTENT[/four_fifths_first][four_fifths]CONTENT[/four_fifths]
 *   e.g. [one_sixth_first]CONTENT[/one_sixth_first][one_sixth]CONTENT[/one_sixth]
 *   e.g. [two_sixths_first]CONTENT[/two_sixths_first][two_sixths]CONTENT[/two_sixths]
 *   e.g. [three_sixths_first]CONTENT[/three_sixths_first][three_sixths]CONTENT[/three_sixths]
 *   e.g. [four_sixths_first]CONTENT[/four_sixths_first][four_sixths]CONTENT[/four_sixths]
 *   e.g. [five_sixths_first]CONTENT[/five_sixths_first][five_sixths]CONTENT[/five_sixths]

IMPORTANT: 
**You must have [Genesis](http://wpsmith.net/get-genesis "Learn more about Genesis") installed. Click [here](http://wpsmith.net/get-genesis "Learn more about Genesis") to learn more about [Genesis](http://wpsmith.net/get-genesis "Learn more about Genesis")**


== Installation ==

1. Upload the entire `genesis-shortcodes` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Begin using!

== Frequently Asked Questions ==

= Do you have future plans for this plugin? =
*   If you have suggestions or want to add any shortcodes, please feel free to [contact me](http://wpsmith.net/contact/ "Contact Travis")

== Screenshots ==

None.

== Changelog ==

= 0.5 =
* Added email shortcode to protect emails from spam ([genesis_email email="my@email.com"]).
* Added modified date shortcode ([genesis_modified_date format="F j, Y"]).

= 0.4 =
*   Fixed [one_half_first] to appear with first instead of last, props [Joe Banks](http://wordpress.org/support/topic/plugin-genesis-shortcodes-one-half-last?replies=3#post-3047634)
*   Added last column shortcodes for the OCD though not needed

= 0.3 =
*   Added [site_url] for MS

= 0.2 =
*   Initial Release

= 0.1 =
*   Private Beta

== Special Thanks ==
I owe a huge debt of gratitude to all the folks at [StudioPress](http://wpsmith.net/get-genesis/ "StudioPress"), their [themes](http://wpsmith.net/get-genesis/ "StudioPress Themes") make life easier.

And thanks to the various individuals who helped me through the beta testing.