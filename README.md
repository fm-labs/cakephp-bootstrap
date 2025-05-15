# cakephp-bootstrap

Twitter Bootstrap for CakePHP


## Installation

    composer require fm-labs/cakephp-bootstrap


## Version Info

| Version   | CakePHP Version | Supported Bootstrap Versions | Default Bootstrap version |
|-----------|-----------------|------------------------------|---------------------------|
| \<= 0.6.x | CakePHP 3       | Bootstrap v3                 | v3                        |
| \^4       | CakePHP 4       | Bootstrap v3/v4/v5           | v5                        |
| \^5       | CakePHP 5       | Bootstrap v3/v4/v5           | v5                        |



## Usage

First, load and enable the plugin.
```php
// In Application' bootstrap method, add:
$this->addPlugin('Bootstrap')
```


Use the `\Bootstrap\View\Helper\BootstrapHelper` to load bootstrap assets:


### In templates

```php
// E.g. at the top of your (layout) template file:
$this->loadHelper('Bootstrap.Bootstrap');   // bootstrap 5.x (via CDN)
//$this->loadHelper('Bootstrap.Bootstrap4'); // bootstrap 4.x (via CDN)
//$this->loadHelper('Bootstrap.Bootstrap3'); // bootstrap 3.x (from plugin webroot)
```

### Application-wide

```php
// E.g. in your AppController

class AppController extends \Cake\Controller\Controller {

    public function initialize(){
        //...
        $this->viewBuilder()->loadHelper('Bootstrap.Bootstrap')
    }
}
```


## View Helpers

| Helper                 | Description                                                                  |
|------------------------|------------------------------------------------------------------------------|
| Bootstrap              | Alias for Bootstrap5 helper. Lazy-loading of other Bootstrap helpers.        |
| Bootstrap3             | Load bootstrap v3 plugin assets                                              |
| Bootstrap4             | Load bootstrap v4 plugin assets                                              |
| Bootstrap5             | Load bootstrap v5 plugin assets                                              |
| Badge                  | Render Badge components                                                      |
| Button                 | Render Button components                                                     |
| Dropdown               | Render Dropdown components                                                   |
| Icon                   | Render bootstrap icons                                                       |
| ~~Label~~              | Render Label compoments. *Deprecated: Use BadgeHelper instead*               |
| Navbar                 | Render Bavbar components                                                     |
| Nav                    | Render Nav components                                                        |
| Panel                  | Render Panel components (*custom*)                                           |
| Tabs                   | Render Tab components                                                        |
| ~~Ui~~                 | Wrapper for common UI components. *Deprecated: Use BootstrapHelper instead.* |
| Accordion              | @TODO                                                                        |
| Alerts                 | @TODO                                                                        |
| Breadcrumbs            | @TODO                                                                        |
| Card                   | @TODO                                                                        |
| Carousel               | @TODO                                                                        |
| Collapse               | @TODO                                                                        |
| Modal                  | @TODO                                                                        |
| Offcanvas              | @TODO                                                                        |
| Pagination             | @TODO                                                                        |
| Placeholders           | @TODO                                                                        |
| Popovers               | @TODO                                                                        |
| Progress               | @TODO                                                                        |
| Scrollspy              | @TODO                                                                        |
| Spinners               | @TODO                                                                        |
| Toasts                 | @TODO                                                                        |
| Tooltips               | @TODO                                                                        |
| Typography             | @TODO                                                                        |
| Tables                 | @TODO                                                                        |
| BS Helpers / Utilities | @TODO                                                                        |
| BS Layout              | @TODO Container/Grid                                                         |
|                        | @TODO                                                                        |
|                        |                                                                              |


## Form Widgets


| Widget   | Description           |
|----------|-----------------------|
| Button   | Submit button widget  |
| Datalist | Datalist widget       |
| Hidden   | Hidden input widget   |
| Textarea | Textarea input widget |
