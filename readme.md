# PHP Assets

A set of functions to use to facilitate enqueuing stylesheets and scripts, inspired by WordPress API.

more doc. to come.

## Api

```php
register_script($name, $path, $dependency=null)
```

Use to register a script to enqueue later on.

Example:

```php
register_script('nav', '/path/to/nav.js', 'jquery');
```