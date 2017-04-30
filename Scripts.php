<?php

namespace Assets;

class Scripts extends Assets
{
    public static function instance() {
        static $instance = null;
        
        if ( null === $instance ) {
            $instance = new Scripts;
        }

        return $instance;
    }

    function printItemWithPath($script)
    {
        printf (
            '<script type="text/javascript" id="%s">%s</script>%s',
            $script['id'],
            get_buffer_file($script['path']),
            PHP_EOL
        );
    }

    function printItemWithSrc($script)
    {
        printf (
            '<script type="text/javascript" id="%s" src="%s"></script>%s',
            $script['id'],
            $script['src'],
            PHP_EOL
        );
    }

    function isInline($src)
    {
        return preg_match( '/<script/si', $src );
    }
}