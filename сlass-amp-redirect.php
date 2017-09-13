<?php

class AMP_Redirect
{
    public function init()
    {
        if (!empty($this->get_amp_template()) && $this->is_amp_url()) {
            add_filter('stylesheet', [$this, 'get_amp_template']);
            add_filter('template', [$this, 'get_amp_template']);
        }
    }

    public function get_amp_template()
    {
        return get_option(AMP_REDIRECT_OPTION);
    }

    private function is_amp_url()
    {
//        if (get_query_var(AMP_REDIRECT_QUERY_VAR)) {
//            return true;
//        }
        $urls_parts = explode('/', $_SERVER['REQUEST_URI']);

        if (is_array($urls_parts) && in_array(AMP_REDIRECT_QUERY_VAR, $urls_parts)) {
            add_filter('home_url', [$this, 'filter_amp_urls']);
            return true;
        }

        return false;
    }


    public function filter_amp_urls($url)
    {
        return $url . AMP_REDIRECT_QUERY_VAR . DIRECTORY_SEPARATOR;
    }
}