<?php

class AMP_Redirect_Options
{
    public function init()
    {
        add_action('admin_menu', [$this, 'add_options_page']);
    }

    public function add_options_page()
    {
        add_options_page(
            'AMP Redirects',
            'AMP Redirects',
            'manage_options',
            'wp-amp-redirect.php',
            [$this, 'options_page']
        );
    }

    public function options_page()
    {
        // check user capabilities
        if (!current_user_can('manage_options')) {
            return;
        }
    }
}
