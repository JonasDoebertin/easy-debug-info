<?php
namespace jdpowered\EasyDebugInfo;

class Factory {

    /**
     * Create a plugin instance
     *
     * Since this is an admin only plugin, we'll only perform operations
     * if the user is actually visiting a backend page.
     *
     * @since 1.0.0
     *
     * @return jdpowered\EasyDebugInfo\Plugin
     */
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
