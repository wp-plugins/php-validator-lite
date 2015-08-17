=== PHP Pseudo Compiler ===
Contributors: manojtd
Donate link: http://buy.thulasidas.com/php-validator
Tags: developer tool, php, compile, debug, test plugin
Requires at least: 2.5
Tested up to: 4.3
Stable tag: 2.02

PHP Pseudo Compiler is a validation tool for PHP to help developers (and quality checker for end users) to locate undefined functions and methods.

== Description ==

*PHP Pseudo Compiler* is a developer tool. It scans the file you specify and determines whether you have undefined functions or methods.

Why not just run the PHP code, you say? Well, PHP is not a compiled language. It looks for functions during runtime. So if you have a segment of code not covered by your normal testing, and you have an undefined function in there, you will never know of the undefined function until it hits production when the particular conditions activating that particular code segment are met.

= Features =

1. Modern Admin Interface: PHP Validator sports a modern and beautiful admin interface based on the twitter bootstrap framework.
2. Admin Interface Tour: A slick tour will take you around the admin page and familiarize you with its features.
3. Generous Help: Whenever you need help, the information and hint is only a click away in PHP Validator. (In fact, it is only a mouseover away.)
Robust Security: Unbreakable authentication (using hash and salt), impervious to SQL injection etc.
4. WordPress Integration: PHP Validator comes with built-in WordPress integration. It works as a WordPress plugin if uploaded to the wp-content/plugins folder of your blog. What's more, you can switch to the standalone mode from the WordPress plugin admin page of this application, while still using the WordPress authentication mechanism and database.

= Pro Version =

In addition to the fully functional Lite version, *PHP Pseudo Compiler*  also has a [Pro version](http://buy.thulasidas.com/php-validator "Pseudo-compiler plugin for PHP to find undefined functions and methods, $4.95") with many more features. These features are highlighted by a red icon in the menus of the lite version.

1. *Upload and Check PHP packages*: In the *Pro* version, you can upload a package as a zip file and check for missing function/method definitions.
2. *WordPress Support*: The *Pro* version can load and check any plugin on your server, and recognizes WordPress functions.
3. *Skinnable Admin Interface*: In the *Pro* version, you can select the color schemes of your admin pages from nine different skins.
4. *Advanced Options*: The Pro version lets you configure advanced options like suppressing duplicates, displaying all detected tokens, ability to do dynamic code analysis etc.
5. *Execution Time*: Ability to specify the maximum execution time for large compilation jobs.

== Upgrade Notice ==

Updating a screenshot, further minor fixes.

== Screenshots ==

1. PHP Pseudo Compiler admin page, with quick start, help and support info.
2. PHP Pseudo Compiler - how to launch it.
3. Options page.
4. PHP Pseudo Compiler output.
5. Advanced Options in the Pro version showing a dark theme.

== Installation ==

To install it as a WordPress plugin, please use the plugin installation interface.

1. Search for the plugin PHP Pseudo Compiler from your admin menu Plugins -> Add New.
2. Click on install.

It can also be installed from a downloaded zip archive.

1. Go to your admin menu Plugins -> Add New, and click on "Upload Plugin" near the top.
2. Browse for the zip file and click on upload.

Once uploaded and activated,

1. Visit the PHP Pseudo Compiler plugin admin page to configure it.
2. Take a tour of the plugin features from the PHP Pseudo Compiler admin menu Tour and Help.

If you would like to temporarily switch to the standalone mode of the plugin, click on the "Standalone Mode" button near the top right corner of PHP Pseudo Compiler screens. You can install it permanently in standalone mode (using its own database and authentication) by uploading the zip archive to your server.

1. Upload the contents of the archive `php-validator` to your server.
2. Browse to the location where your uploaded the package (`http://yourserver/php-validator`, for instance) using your web browser, and click on the green "Launch Installer" button.
3. Follow wizard to visit the admin page, login, configure basic options.

== Frequently Asked Questions ==

= What does this program do? =

*PHP Pseudo Compiler* is a developer tool. It scans the file you specify and determines whether you have undefined functions or methods.

= What do I enter in "List of Files"? =

You enter the full path names of the files you would like to validate. Note that *PHP Pseudo Compiler* runs on a server, and the files need to be accessible by your web server. Please specify the files relative to the installation directory, or by typing in their full path names. You can enter multiple file names separated by commas.

= What do I enter in "Folder Location"? =

*PHP Pseudo Compiler* can recursively load an entire folder on your server to validate the files therein. Specify a path relative to the current location (as shown in the help bubble), or as an absolute path.

= What about "Upload Application"? =

Using this file upload method, you can upload an entire PHP application (as a ZIP file) to your server and validate it by pseudo-compiling it. The uploaded ZIP file will be unpacked into a temporary folder and scanned for undefined functions and methods. Since the temporary locations have random names and cannot execute PHP files through external invocations, the security risk is believed to be non-existent.

= How do I use the "Select a Plugin" dropdown menu? =

Similar to the file upload method, you can validate any plugin installed on your WordPress server (both active and inactive ones) by pseudo-compiling it. Select a plugin and wait for the output.

= What is the purpose of the "Execute the Files" option? =

The uploaded files are parsed and examined statically by default. If you would like to do dynamic analysis by executing the files, please check here. Please note that executing uploaded files may have side effects, and it may be a security hole as well. For that reason, this Pro option self-disables after each execution.


== Change Log ==

* V2.02: Updating a screenshot, further minor fixes. [Aug 17, 2015]
* V2.01: Bug fix in the AJAX error handler. [Aug 17, 2015]
* V2.00: Major rewrite of the whole code base using the twitter bootstrap framework. Compatibility with WordPress V4.3. [Aug 15, 2015]
* V1.30: Minor fixes. Compatibility with WordPress V4.0. [Sep 8, 2014]
* V1.21: Minor refactoring changes. [Mar 25, 2014]
* V1.20: Compatibility checks for WordPress V3.8. Adding more help on admin page. [Dec 20, 2013]
* V1.10: Compatibility checks for WordPress V3.7. [Nov 11, 2013]
* V1.03: Bug fixes (Fatal error: Call-time pass-by-reference has been removed). [Jan 28, 2013]
* V1.02: Renaming the plugin to drop the word Lite. [May 12, 2012]
* V1.01: Minor code changes. [April 20, 2012]
* V1.00: Initial release. [April 3, 2012]
