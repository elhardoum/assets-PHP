# PHP Assets

A set of functions to use to facilitate enqueuing stylesheets and scripts, inspired by WordPress API.

*more doc. to come.*

## Api

- **register_script**

```php
register_script($name, $path, $dependency=null);
```

Use to register a script to enqueue later on.

Example:

```php
// register a script for our navigation menu which depends on jquery
register_script('nav', '/path/to/nav.js', 'jquery');
```

- **enqueue_script**

```php
enqueue_script($name);
```

Enqueue a script that has already been registered.

Example:

```php
// enqueue the nav js which will also enqueue jquery since it depends on it
enqueue_script('nav');
```

*more doc. to come.*