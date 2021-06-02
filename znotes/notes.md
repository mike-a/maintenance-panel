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
