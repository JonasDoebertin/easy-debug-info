<?php
namespace jdpowered\EasyDebugInfo;

class Factory {

    public static function make()
    {

        /*
            This is a backend only plugin.
            Let's be nice guys and shutdown ourselves
            when a frontend page has been called.
         */
        if( ! is_admin())
        {
            return null;
        }

        /*
            Otherwise, let's create an instance of
            the plugin class and return it.
         */
        return new Plugin;
    }

}
