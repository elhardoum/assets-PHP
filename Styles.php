<?php

namespace Assets;

class Styles extends Assets
{
    public $dequeue_children_on_missing = false;

    public static function instance() {
        static $instance = null;
        
        if ( null === $instance ) {
            $instance = new Styles;
        }

        return $instance;
    }

    function printItemWithPath($script)
    {
        printf (
            '<style type="text/css" id="%s">%s</style>%s',
            $script['id'],
            get_buffer_file($script['path']),
            PHP_EOL
        );
    }

    function printItemWithSrc($script)
    {
        printf (
            '<link rel="stylesheet" type="text/css" id="%s" href="%s">%s',
            $script['id'],
            $script['src'],
            PHP_EOL
        );
    }

    function isInline($src)
    {
        return preg_match( '/<style/si', $src );
    }
}