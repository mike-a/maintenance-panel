
For any package to easily plug in and plug out of the maintenance panel, there are certain things that the package must 
provide to ensure it's easily discoverable by the maintenance panel. <br>
Some of these things are: 
- provide its own installation command (normally this is the package name contact ':setup' i.e. `<package-name:setup>`
- This installation command must as conform to: the `PackageSetupInterface` which basically specifies (for now) that the
command must have the following functions: 
  ```php 
  /**
     * @return mixed
     * copy package assets to the core 
     */
    public function loadAssets();

    /**
     * @return mixed
     * remove the copied assets from the core
     */
    public function unloadAssets();

    /**
     * @return mixed
     * after the assets are copied over to the project
     * we want to now compile the project to have their effect affected
     */
    public function compile();

    /**
     * @return mixed
     * this is just but a short cut for (loadAssets() && compile())
     */
    public function fullCompilation();

    /**
     * @return mixed
     * this is simply a means of getting the package description from 
     * composer.json
     */
    public function packageInfo();
  ```
  
Not sure how to enforce this yet for now but will be tested upon installation into the maintenance panel where
the package setup command should accept the following arguments in its signature 
```php
 "package-name:setup {action : the type of action to execute i.e. load_assets, unload_assets, compile}"
```

And in your setup command handle method, use a switch/if statement to act upon each of the given actions e.g. 
```php 
$action = $this->arguments()['action'];

if ($action) {
    switch ($action) {
        case 'load_assets':
            return $this->loadAssets();
            break;
        case 'unload_assets':
            return $this->unloadAssets();
            break;
        case 'compile':
            return $this->compile();
            break;
        case 'compile-package':
            return $this->fullCompilation();
            break;
        case 'info':
            return $this->packageInfo();
            break;
        default:
            echo "unable to process given action";
    }

} else {
    Log::info("Action not provided");

    echo 'Please provide the necessary action';
}
```
