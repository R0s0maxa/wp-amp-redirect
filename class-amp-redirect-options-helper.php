<?php

/**
 * Provides some helpers functions for admin options page
 * Class AMP_Redirect_Options_Helper
 */
class AMP_Redirect_Options_Helper
{
    public static function get_existing_themes()
    {
        return wp_get_themes(['errors' => false]);
    }
}