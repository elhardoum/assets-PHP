<?php

function assets_setup() {
    global $Scripts, $Styles;

    if ( !class_exists('\Assets\Assets') )
        include ( '../Assets.php' );
    
    if ( !class_exists('\Assets\Scripts') )
        include ( '../Scripts.php' );
    
    if ( !class_exists('\Assets\Styles') )
        include ( '../Styles.php' );

    $Scripts = \Assets\Scripts::instance();
    $Styles = \Assets\Styles::instance();
}

function register_script() {
    global $Scripts;

    return call_user_func_array(array($Scripts, 'add'), func_get_args());
}

function enqueue_script() {
    global $Scripts;

    return call_user_func_array(array($Scripts, 'enqueue'), func_get_args());
}

function dequeue_script() {
    global $Scripts;

    return call_user_func_array(array($Scripts, 'dequeue'), func_get_args());
}

function remove_script() {
    global $Scripts;

    return call_user_func_array(array($Scripts, 'remove'), func_get_args());
}

function script_handler() {
    global $Scripts;

    return call_user_func_array(array($Scripts, 'handler'), func_get_args());
}

function is_script_active() {
    global $Scripts;

    return call_user_func_array(array($Scripts, 'isActive'), func_get_args());
}

function dequeue_children_script() {
    global $Scripts;

    return call_user_func_array(array($Scripts, 'dequeueChildren'), func_get_args());
}

function print_scripts() {
    global $Scripts;

    $Scripts->print();
}

function register_style() {
    global $Styles;

    return call_user_func_array(array($Styles, 'add'), func_get_args());
}

function enqueue_style() {
    global $Styles;

    return call_user_func_array(array($Styles, 'enqueue'), func_get_args());
}

function dequeue_style() {
    global $Styles;

    return call_user_func_array(array($Styles, 'dequeue'), func_get_args());
}

function remove_style() {
    global $Styles;

    return call_user_func_array(array($Styles, 'remove'), func_get_args());
}

function style_handler() {
    global $Styles;

    return call_user_func_array(array($Styles, 'handler'), func_get_args());
}

function is_style_active() {
    global $Styles;

    return call_user_func_array(array($Styles, 'isActive'), func_get_args());
}

function dequeue_children_style() {
    global $Styles;

    return call_user_func_array(array($Styles, 'dequeueChildren'), func_get_args());
}

function print_styles() {
    global $Styles;

    $Styles->print();
}

function get_buffer_file( $file ) {
    ob_start();
    include ( $file );
    return ob_get_clean();
}