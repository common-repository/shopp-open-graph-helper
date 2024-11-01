=== Plugin Name ===
Contributors: tysonlt
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=BJQ6ZNN4JVAZL
Tags: shopp,open-graph,facebook,social,like
Requires at least: 3.0.0
Tested up to: 3.0.1
Stable tag: 1.5

Prints a Facebook 'Like' button and adds Open Graph meta tags to each Shopp product page. 

== Description ==

When you add a Facebook 'Like' button to a Shopp page, usually it will say 'You like Shop' when you click it. 
It is difficult to get it to use the right data, so this plugin writes og: meta headers to the product page. 
This shows up on Facebook as 'Bob likes Awesome Cool Product at Your Site' instead of 'Bob likes Shop at www.yoursite.com'.

This plugin can now optionally print the 'Like' button code at the bottom of each product page, so it is a total solution for 
all your Facebook liking needs! 

== Installation ==

1. Upload the contents of this zip file to your `/wp-content/plugins/' folder, or use the built-in Plugin upload tool.
1. Activate the plugin through the 'Plugins' menu in WordPress.  
1. Visit the settings page and set your preferences. You may want to set the 'Type' setting to 'product'. You can also tell the plugin to print the 'Like' button here.

== Frequently Asked Questions ==

= What does it set the og:type field to? =

By default this is blank. Can be set using the admin screen under the Settings menu. I would probably set it to 'product'. 

= How do install a facebook like button, without using another plugin? =

Since 1.4, this plugin can now print the 'Like' button code at the bottom of the product page for you. Just enable it in the settings page and you are done!
You can also change the code used to print the like button. There is an example in the default code to use the 'Comments' plugin instead of the 'Like' plugin. 

= I want to add my own Like button code to my template. Can I do this? =

Sure! Just disable the automatic printing in the admin screen, and add your own like button code to product.php or wherever you want to put it.

= Why do you print fb:app_id and fb:admins on every page, not just the product pages? =

This allows Facebook Insights to check your page. It needs these tags in the homepage in order to validate your domain.
With this enabled, you can see all the likes you have received for every product in your whole shop. You even
get a pretty graph! 

== Upgrade Notice ==

In version 1.3 and lower, the 'Like' button code had to be added to the product.php Shopp template. This is no longer required, so please remove that code from the product template.
If you have made modifications to the 'Like' button code, just paste your code into the settings field to keep your changes.
If you want to keep your code in the product template, just disable the automatic output option in the settings page.

== Screenshots ==

1. The admin screen.
2. The products page. To see it live, go to edieandbup.com.au. Press 'Like' to see it in action!

== Changelog ==

= 1.5 =
* Escape double quotes and HTML tags in product title and description. Existing HTML entities will be left as-is. (Thanks masshoff on Shopp forums for finding this).

= 1.4 =
* Option to print the 'Like' button code automatically. (NOTE: if you changed your product.php template, please remove the 'Like' button code before enabling the automatic option. Otherwise you would get two like buttons!)
* Added fb:admins option
* Description is now printed in og:description meta tag 
* Added dropdown for product-related 'Type' values
* fb:app_id and fb:admins are now printed in the header of every page if set, not just the product pages 

= 1.3 = 
* Developer committed the wrong version in 1.2.

= 1.2 =
* Added admin screen to change 'Object Type' parameter.

= 1.1 =
* Documentation update.

= 1.0 =
* Initial release.

