=== Easy Debug Info ===
Contributors: JonasDoebertin
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=V8PNBN3D3MRYU
Tags: Debug, Debugging, Statistics, Constants, Database, Plugins, Themes
Requires at least: 3.7.0
Tested up to: 4.0
Stable tag: 1.2.0
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Making collecting extensive and extendable debug info finally easy.

== Description ==

Easy Debug Info makes it incredibly easy to collect various debug infos and statistics from your WordPress powered site.

This includes, but is not limited to, various data points from your server environment, details about your WordPress installation and it's themes and plugins, database statistics, scheduled events and much more. Extending the reports from your own plugins is easy, too!

After installing Easy Debug Info, just visit Tools > Easy Debug Info from your WordPress backend and hit **Generate Report**.

The full source is available on [GitHub](https://github.com/JonasDoebertin/easy-debug-info/). Contributions are welcome!

== Installation ==

1. Upload the entire `easy-debug-info` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress

You will find 'Easy Debug Info' within the Tools menu in your WordPress admin panel.

== Frequently Asked Questions ==

= Why does Easy Debug Info require PHP 5.3? =

Yes, I am aware of the fact that WordPress itself requires only PHP 5.2. And yes, I am also aware that it's not a good practice to create a plugin that doesn't stick to this requirements. Easy Debug Info requires PHP 5.3 or above anyways. There are two main reasons for this decision:

* **Security**: PHP 5.2 was released back in November 2006 and was maintained and updated until January 2011. This means that it’s now considered an old release that’s no longer supported. Should a new vulnerability be discovered in PHP 5.2, it will remain unfixed.
* **Features**: PHP 5.3 adds a lot of new features and transforms PHP into a more modern language supporting closures, anonymous functions, namespace, etc. I don't want to miss these features while developing a plugin.

== Screenshots ==

1. Visiting the "Easy Debug Info" backend page for the first time.
2. Showing a previously generated report.

== Changelog ==

= 1.2.0 =

**Enhancements**

* Added Scheduled Events reports
* Developers may register their own reporters

** Further Notes**

* Updated language files: en_US & de_DE

= 1.1.0 =

**Enhancements**

* Added Environment & Database reports
* Extended General report  to include WordPress specific constants

= 1.0.0 =
This is the first stable release for internal use.

**Remarks:**

* Not listed in the wp.org plugin directory, yet
* Limited data collection possibilities
* Made for internal use

== Upgrade Notice ==

= 1.1.0 =
Added additional debug info (scheduled events) to the report

= 1.1.0 =
Added additional debug info (constants, server environment & database statistics) to the report

= 1.0.0 =
This is the first stable release for internal use.
