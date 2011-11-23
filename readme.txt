=== Plugin Name ===
Contributors: crmtech
Donate link: http://www.2wconsulting.com/products-2/free-plugins/web2lead-sugarcrm-free/
Tags: sugarcrm, leads, lead capture, crm
Requires at least: 3.2.1
Tested up to: 3.2.1
Stable tag: 1.0.5

Web2Lead enables SugarCRM lead record generation from a Wordpress-based website.

== Description ==

Web2Lead enables SugarCRM lead record generation from a Wordpress-based website. This version contains a basic contact form and creates a SugarCRM lead record assigned to a specified user.

This plugin was developed on Worpress v3.2.1, SugarCRM CE v6.2.4 and PHP v5.3.8. PHP 5.2.6 or later is required due to the internal PHP SOAP libraries used.

It has been tested against the following stacks:
Wordpress 3.2.1 and SugarCRM CE 6.2.4 on IIS 7 running PHP 5.3.8 (Windows 7)
Wordpress 3.2.1 and SugarCRM CE 6.2.4 on Apache running PHP 5.3.3-7+squeeze3 (Debian GNU/Linux 6.0)
Wordpress 3.2.1 and SugarCRM CE 5.5.0 on Linux.

== Installation ==

Installation is simple - just go to Plugins > Add New. Then upload the zip file. Wordpress takes care of the rest. Remember to activate it!


== Frequently Asked Questions ==

= What is my (or How should I enter the) SugarCRM SOAP URL? =

The url of the SugarCRM SOAP service is usually the root of the SugarCRM url with the page 'soap.php' specified. For instance, if you run Sugar locally at http://localhost/sugar/, then the SOAP url would be http://localhost/sugar/soap.php.

= Which version(s) of Worpress, SugarCRM and/or PHP is this compatible with? =

It should work with almost any recent version of Wordpress and SugarCRM. The earliest version of SugarCRM tested is 5.5. 

It has been tested against the following stacks:
Wordpress 3.2.1 and SugarCRM CE 6.2.4 on IIS 7 running PHP 5.3.8 (Windows 7)
Wordpress 3.2.1 and SugarCRM CE 6.2.4 on Apache running PHP 5.3.3-7+squeeze3 (Debian GNU/Linux 6.0)
Wordpress 3.2.1 and SugarCRM CE 5.5.0 on Linux.

= Which fields are on the form? =

Currently, the standard fields are First Name, Last Name, Company, Phone, Email and Comments.

= Where are the fields stored in SugarCRM? =

On the lead record that is created, the fields are mapped as follows: First Name => first_name, Last Name => last_name, Company => account_name, Phone => phone_work, Email => email1, Comments => description.

Additionally, the following values are also set on the lead record: Status = "New", Lead Source = "Web Site".

= How can I change/add fields to the form? =

You'll have to know a little bit about PHP and Wordpress Plugin programming to do it, but it's pretty easy: Fields and their mappings are stored as arrays within 2 WordPress options (1 for fields, 1 for the mappings).

If you do not wish to learn all that, I have a [Pro version](http://www.2wconsulting.com/products-2/premium-plugins/web2lead-sugarcrm-pro/) of the plugin which includes a nice interface to managing form fields and how they map into SugarCRM.

= What anti-spam measures are taken in the form? =

This version uses the "honeypot" approach by placing an empty hidden field in the form. Bots usually fill in all fields, so if this hidden field has a value, the data does not get imported into SugarCRM (The plugin returns a blank form).

== Changelog ==

= 1.0.0 =
* Original version.

= 1.0.1 =
* Added test form on the main plugin option page.
* Transformed sugarlead.php script into a structured class object with exception handling.
* Added notification email to Worpress admin when form is submitted.

= 1.0.2 =
* Default contact form fields and mappings into SugarCRM are stored as arrays in Wordpress options.

= 1.0.3 =
* Required field support (First Name, Last Name and Email are required). If any required fields are missing, the form is re-displayed with a message indicating which fields are missing (entered values are retained).
* Minor form markup updates.
* Added a little more error checking during SugarCRM login function.

= 1.0.4 =
* Wrapped form building in a function.
* Updated shortcode function to properly output html.
* Added email validation function.

= 1.0.5 =
* Added honeypot-style anti-spam on the form.