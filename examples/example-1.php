<?php

// include functions
include ( '../functions.php' );

// initialize classes and globals
assets_setup();

// register jQuery handler
register_handler('jquery', '/path/to/jquery.js');

// register the main stylesheet
register_style('style', '/path/to/style.css');

// enqueue stylesheet
enqueue_style('style');

// register navigation JS
register_script('nav', '/path/to/nav.js', 'jquery');

// enqueue nav js
// since it is jquery dependent, jquery will also be enqueued
enqueue_script('nav');

// more to come..