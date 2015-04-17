# Introduction #

Since there are many litte projects I had to code, I did not want to give up my useful habits, like using the MVC-pattern, a bootstrap file or unit-testing. When I have to code these little project it is a unproductive as it would be when I use a bloated framework (whose features I require only at a minimum) or nothing like a framework. Both solutions would me prevent from being efficient. Therefore I planned a projekt, which provides lightweight solution for my problems.

# Details #

Most of my habits, which I consider as useful, got in this project. In the following I will list most of these.

  * light-weight implementation of the MVC-pattern
  * easy to test
    * Use of  many abstractions allow it to create a clean test-environment
  * stable
    * each class is tested using the principle of unit-testing
  * good documentation
    * each class is documented
    * documentation describes nearly every feature of the subject
  * predicatability
    * due to the light-weigth design and documentation becomes the behaviour transparent and  therefore predicatable
  * good performance
    * reduction to really required features and possibly abstractions for later support  saves time
  * good interoperability
    * abstraction and transparency simplify the use in other applications
  * integratable in other framework
    * from a specified size it makes sense to use a more sophisticated framework
    * the integration in other frameworks should be simplified by use of bridges
    * the number of required adaptions should be minimal
    * refactoring should be only optimization for the new framework