=== Dinner Reservations Calendar (with Couverts) ===
Contributors: peter.eussen
Tags: forms,couverts,reservation,api,shortcode
Requires at least: 4.5
Tested up to: 4.5.3
Stable tag: 1.1
Licence: GPLv3
Author URI: http://harperjones.nl
Plugin URL: https://github.com/HarperJones/wp-couverts
License URI: http://www.gnu.org/copyleft/gpl.html

Allows a more embedded way of using the Couverts reservation system

== Description ==

= Introduction =
The couverts plugin offers an alternative way of embedding the [Couverts](https://www.couverts.nl/)
reservation system on your website. The templates are based on Bootstrap 4.

= Prerequisits =
To use it, you will have to request an API key from Couverts. Once you obtained
a valid key, you need to define these in your wp-config.php as follows:

`
define('COUVERTS_API_KEY','Your-API-Key');
define('COUVERTS_RESTAURANT_CODE','Your-Restaurant-Code');

// Set this if you are ready to go live. Otherwise it will use the
// Test API URL
define('COUVERTS_API_URL','https://api.couverts.nl/');

// Optionally you need to define this as either Dutch or English
// define('COUVERTS_LANGUAGE','Dutch');
`

Alternatively you can also define these variables as enviroment variables
in a .env file in your project (if you are using a bit more custom setup
as for example roots/bedrock). 

= Customization =
You may want to adjust the code that is generated. To do so you simply
copy the templates directory from the plugin into your theme. 

You can then adjust them at will.

= Usage =
You can embed the reservation in two ways:

1. Use the [couverts] shortcode somewhere in your content
2. Call the couverts_reservation() function from somewhere in your templates.

== Changelog ==

= 0.1 =

* Initial release.

= 0.1.1 =

Better versioning & deployment (hopefully)

= 0.1.2 =

* Added small fix to properly remove the loading class from the buttons after loading is complete

= 0.1.3 =

* Added caching for the Basic Info API requests to avoid long loading times on all page requests

= 0.1.4 =

 * Fixed an issue with defining the COUVERTS_API_URL, which blocked sites from using the production URL

= 0.2.0 =

 * Added calls to the getdateconfig API endpoint to take closing days into consideration
   This also changes the way the template is built, so please be aware of this when you
   made the templates custom

= 0.2.1 =

 * A better way to handle caching of getDateConfig calls.

= 0.2.2 =

 * Fixed an issue where the current day would not show up in the selection list

= 1.0.0 =

 * Added support for day configurations (minimum and maximum party size). 
   Please be aware that if you upgrade to this version and you pulled the templates locally, 
   you will not notice the changes. To make this work, I had to change the javascript and
   the form slightly.

 * Also changed the default layout of the form to form-horizontal
 * This release now includes all major parts of the Couverts API, except handling of larger
   groups. So you should be able to use this Plugin for "normal" group sizes now.
  


= 1.0.1 =

  * Fixed an issue when no times are available


= 1.1 =

  * Added an admin options screen to configure the plugin without defines
  * Disable the time selects in case Couverts takes a bit too long to respond
  

