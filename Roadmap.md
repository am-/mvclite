# Introduction #

Here is a short overview about features that will be integrated in later versions. Additionally, the old planned features are listed too, to create something like a history.

# Details #

**Version 0.1.2**:
  * new directory-layout
  * Bootstrap-class
  * Ajax-view for Ajax results


**~~Version 0.1.1~~**:
  * security-features
    * use of protectors (MVCLite\_Security\_Protector) which protect the controller-actions
  * improvements for the controller
    * shutdown-hook
    * integration with the protectors (MVCLite\_Security\_Protectable)
    * implementation of action-helpers, which enable doing some operations independent from controllers
  * dispatcher improvements
    * implementation of plugins, which apply recurring operations on the specified controller
  * view additions
    * implementation of view-helpers, which encapsulate solutions of recurring problems
      * rewrite action, controller and arguments to a URL
      * prepend the base-url to some other links
  * new route for webserver unable to rewrite the urls
  * implementation of database-specific tables
    * very basic CRUD-methods
    * integration with the record-classes
  * value-object for records

**~~Version 0.1.0~~**:
  * minimal Controller-implementation (MVCLite\_Controller\_Abstract)
    * init-hooks
    * integration with different kinds of view (layout, empty, normal)
    * use of actions
  * minimal Model-implementation (MVCLite\_Model\_Abstract)
  * View-implementation
    * Layout-view (MVCLite\_View\_Layout)
    * normal view (MVCLite\_View)
    * empty view (MVCLite\_View\_Empty)
  * database-abstraction
    * using PDO as default adapter (MVCLite\_Db\_PDO)
    * interface defining most basic methods => easy integration for other databases or frameworks (MVCLite\_Db\_Adaptable)
    * global point for changing the database-adapter (MVCLite\_Db)
  * class-loader (MVCLite\_Loader)
    * loading of files/classes
    * semantic methods for loading controllers and models
  * clean request-class (MVCLite\_Request)
    * decoupling from superglobals (objects represent these variables) (MVCLite\_Request\_Global)
    * superglobals are synchronized after everything went okay (MVCLite\_Request\_Global\_Dispatcher)
    * enables a clean environment for testing
  * own dispatcher (MVCLite\_Request\_Dispatcher)
    * search-engine friendly (default) URL-schema (MVCLite\_Request\_Route\_Standard)
    * other schemas can be added easily (MVCLite\_Request\_Route)