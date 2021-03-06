# Easy Debug Info

**Making collecting extensive and extendable debug info in WordPress finally easy.**

Easy Debug Info makes it incredibly easy to collect various debug infos and statistics from your WordPress powered site.

This includes, but is not limited to, various data points from your server environment, details about your WordPress installation and it's themes and plugins, database statistics, and much more. Extending the reports from your own plugins is easy, too!

After installing Easy Debug Info, just visit Tools > Easy Debug Info from your WordPress backend and hit Generate Report.

The plugin can be found in the [official Wordpress.org plugin repository](https://wordpress.org/plugins/easy-debug-info/).

## For End Users

If you just want to use Easy Debug Info to debug your own WordPress setup, there's not much you need to do:

**The short version**

1. Install the **Easy Debug Info** plugin.
2. Visit **Tools** &raquo; **Easy Debug Info** page.
3. Generate your report.

**Everything in detail**

1. Visit your WordPress Dashboard and navigate to **Plugins** &raquo; **Add New**.
2. Use the built in search functionality and search for **Easy Debug Info** by Jonas Döbertin.
3. Hit **Install Now** to add the plugin to your setup and **activate the plugin** afterwards.
4. Navigate to **Tools** &raquo; **Easy Debug Info** and hit **Generate Report**
5. The freshly generated report will be just below.

## For Developers

It's pretty easy to extend the reports generated by Easy Debug Info with additional debug data from your plugins or themes. You just write your custom **Reporter** and register it with the plugin.

**More details will be added soon!**

## Frequently Asked Questions

### Why does Easy Debug Info require PHP 5.3?

Yes, I am aware of the fact that WordPress itself requires only PHP 5.2. And yes, I am also aware that it's not a good practice to create a plugin that doesn't stick to this requirements. Easy Debug Info requires PHP 5.3 or above anyways. There are two main reasons for this decision:

* **Security**: PHP 5.2 was released back in November 2006 and was maintained and updated until January 2011. This means that it’s now considered an old release that’s no longer supported. Should a new vulnerability be discovered in PHP 5.2, it will remain unfixed.
* **Features**: PHP 5.3 adds a lot of new features and transforms PHP into a more modern language supporting closures, anonymous functions, namespace, etc. I don't want to miss these features while developing a plugin.
