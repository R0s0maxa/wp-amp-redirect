<?php

class AMP_Redirect
{
    private $template;

    public function __construct()
    {
        $this->template = get_option(AMP_REDIRECT_OPTION);
    }

    public function init()
    {
        if (!empty($this->template)) {
            add_filter('stylesheet', $this->template);
            add_filter('template', $this->template);
        }
    }
}