# Introduction #

Since one of the main-goals of MVCLite is to be easily understandable, this page explains how to get started. It is recommended to be familiar with the MVC-Pattern, although it is not stringently required.

# Details #

## Rewriting ##
The first thing is to get the URL rewritten. If you use Apache HTTPd you can apply following code to a .htaccess-file in your "www"-directory.

```
RewriteEngine on
RewriteRule !\.(js|ico|gif|jpg|png|css)$ index.php
```

If you use Lighttpd place this snippet to your config:

```
url.rewrite-once = ( ".*\.(js|ico|gif|jpg|png|css)$" => "$0", "" => "/index.php")
```

These snippets enable you to rewrite your URI and use the standard-route for MVCLite. I.e. that the URLs are rewritten for causes of transparency, elegance and optimization.

## Setting up controllers ##

```
/<controller>/<action>/<arg1>.<value1>_<argn>.<valuen>.html
// this is how a full URL can look like, an example follows
/foobar/bar/arg1.value1_arg2.value2.html
// will create the controller "FoobarController" and calls its method "barAction"
// the arguments can be accessed inside the controller-action
```

For a full understanding of the standard-route you should read the documentation of MVCLite\_Request\_Route\_Standard which gives you much information about that topic.

To create a controller "NameController" place a file "NameController.php" into your controller-directory (usually "/app/controllers/") and create a class named "NameController" which extends "MVCLite\_Controller\_Abstract". The base class should be loaded as well.

```
// content of "/app/controllers/NameController.php"
require_once 'MVCLite/Controller/Abstract.php';

class NameController extends MVCLite_Controller_Abstract
{
   public function indexAction ()
   {
      echo '/name/index or /name was requested';
   }
}
```

The "Name" can be replaced by every other name consisting of characters and digits. Please note that the first character is always capitalized while the rest is not capitalized.
When omitting the controller in the URL the "IndexController" is taken instead. If the action is omitted, the "indexAction" is taken instead.

## Setting up views ##

By default the controller tries to load a view fitting to the action.

```
/foobar/bar/arg1.value_arg2.value2.html
// will try to render "views/foobar/bar.phtml"
```

Creating a view is even simpler. You only have to place a file in "/view/

&lt;controller&gt;

/

&lt;action&gt;

.phtml". It is important that the controller-name is lowercase. The action is also lowercase. In the phtml-file you can place your HTML, which will be displayed after dispatching the request. Normally a layout is wraps the content of the file, but this can be changed by using method in MVCLite\_Controller\_Abstract.