##maintenance panel workings

Before we dive in, please note that this is still in its infancy stages and thus a lot of what is here may change 
drastically as time goes by but will try to keep the docs updated as much as I can. <br>
so how have this been constructed. to begin with following are what we need to achieve:
- Ability to install a package via the maintenance panel
- Ability to compile, composer dump and composer update the project from the maintenance panel.
- Once a package is installed, from the maintenance-panel we should be able to:
    * compile package (here basically load package assets first to the base project and then compile project assets)
    * need the ability to plug in and plug out the installed package cleanly.
    * Show package info whenever needed
    * finally test if a package is installed 


Lets get to the technical bits now, how exactly have the above been achieved. 

###### Initialization
To begin with, we know that this should be done via the browser. So first of we need some entry page where we can
make this actions available to the user. And this can be accessed by visiting `/maintenance-panel/setup`, there we 
return a simple page with the forms that make this actions possible: ![panel view image]('./images/panelView.png')

From above
You'll notice the four main project actions: 
- install a package
- compile the project
- dump 
- project update

Install a package: 

When we want to install a package, fill in the form specifying its source: (either a vcs or path). This will update
your composer.json to require this package and update the maintenance-panel config to accept this package, but hold 
it up first. Next up, you will need to install the given package into the project which is where the `package update` 
comes in, which basically  pulls in whatever is in the controller, next  up, its advisable that you run dump which in the
background will run `composer dumpautoload`
 
compile project

Here we are basically running npm run dev to compile assets to the public directory specifics (js and css);


Upon package installation, you can as well compile, plug in (park) and unplug the package.




