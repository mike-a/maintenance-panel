Basetheme Docs:

### Installation

###### Local development
To instal for local development, add the package link to your composer.json
under autoload-dev section and remember to add the service provider in the core `config/app.php`
under the providers list.

In composer:
```json
 "autoload-dev": {
        "psr-4": {
            "Vivinet\\MaintenancePanel\\": "package-dev/vivinet/maintenance-panel/src"
        }
    },
```
Then in the core config add the package provider:
```
  \Vivinet\MaintenancePanel\MaintenancePanelServiceProvider::class
```

###### Production environment
When working with this on a production environment, you most definitely want to publish
the package to the vendor folder and thus since the package will be accessed from a
version control (github). You will add the repository link under `repositories` group in
your `composer.json` as shown below:
```json
  "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/mike-a/maintenance-panel.git"
        }
    ],
```

Finally: <br/>
Run `composer update` to plugin the given package then ensure you have `nodejs` and `npm`  installed in your machine 
before continuing. Once that is done, run `npm install && npm run dev` to install

<br/>

##### Notes:
Kindly check under the `znotes directory` for more in detail notes or request access to 
scrivener notes. <br/>
Also do take note of the Core usage across the documentation which refers to the laravel application to 
which you've installed the maintenance-panel
