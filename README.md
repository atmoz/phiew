# Phiew

Simple and fast MVC components for PHP 5

## Purpose and background

Phiew is just a couple of simple components I use in small projects where I have 
short deadlines. That means I don't want to use much time setting up advanced 
frameworks, but at the same time I don't want to make unorganized PHP files where
business/domain logic and presentation is mixed together like spaghetti.

At first I got inspired by Zend Framework's Zend_View class, so I made my own
little implementation and called it Phiew (short for "PHP view"). But then I wanted
to make some easy controller functions for handling form states as well. 
So I just stuck with the name and decided to expand the "project" to include 
the whole MVC concept. Besides, I like the name - it's short and fun. :-)

My goal is simple: to make some handy classes for use in a MVC setup, that's easy to use 
and not bloated with unnecessary additions, modifications, or complications.

## Features

As of now, this is what's on the menu:

*	Simple templating with alternative PHP syntax and plugins (view helpers).
	[See example](http://github.com/atmoz/phiew/blob/master/example/hello-world.php)
*	Easy handling of states between requests.
	[See example](http://github.com/atmoz/phiew/blob/master/example/controller-state.php)

## Installation and use

Overall, the classes can be used as is, just include them and use them. 
But the structure and naming of files makes it easy to use an autoloader,
and I recommend you do. [See the example autoloader][1] for hints.

[1]: http://github.com/atmoz/phiew/blob/master/example/autoload.php 

### Phiew_View

You can, if you will, set up a default folder where Phiew_View will look for
templates. Just define a `PHIEW_VIEW_TEMPLATE_FOLDER` global constant or use
`Phiew_View::setTemplateFolder()`.

You can also use absolute or relative path, removing the need to define a folder path.

### Phiew_Controller_StateAbstract

Extend your controller with this class, define your default state by overloading 
`_createDefaultState()` and make sure your GET and POST requests have the statekey 
included as an argument (you can use hidden fields and `_redirectState()`).
[See example implementation](http://github.com/atmoz/phiew/blob/master/example/controller-state.php).

## Examples

I try to show how I use the classes with simple examples.
You can find them in the [*example* folder](http://github.com/atmoz/phiew/tree/master/example/).
