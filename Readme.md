Basetheme Docs:

### Installation

####### Local development
To instal for local development, add the package service provider to your composer.json
under autoload-dev section and remember to add the service provider in the core `config/app.php`
under the providers list.

In composer:
```json
 "autoload-dev": {
        "psr-4": {
            "Vivinet\\EngineersConsole\\": "package-dev/vivinet/engineers-console/src"
        }
    },
```
Then in the core config add the package provider:
```
  \Vivinet\EngineersConsole\EngineersConsoleServiceProvider::class
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
            "url": "https://github.com/mike-a/engineers-console.git"
        }
    ],
```

##### Notes:
Kindly check under the `znotes directory` for more in detail notes or request access to 
scrivener notes
