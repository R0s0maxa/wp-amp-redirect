<?php

class AMP_Redirect_Rewrite
{
    public function __construct()
    {
        add_filter('query_vars', [$this, 'add_query_vars_filter']);
        add_filter('request', [$this, 'force_query_var_value']);
        add_rewrite_endpoint(AMP_REDIRECT_QUERY_VAR, EP_PERMALINK);
        add_post_type_support('page', AMP_REDIRECT_QUERY_VAR);
        add_post_type_support('post', AMP_REDIRECT_QUERY_VAR);
    }

    public function force_query_var_value($query_vars)
    {
        if (isset($query_vars[AMP_REDIRECT_QUERY_VAR]) && '' === $query_vars[AMP_REDIRECT_QUERY_VAR]) {
            $query_vars[AMP_REDIRECT_QUERY_VAR] = 1;
        }
        return $query_vars;
    }

    public function add_query_vars_filter($vars)
    {
        $vars[] = AMP_REDIRECT_QUERY_VAR;
        return $vars;
    }

    private function get_blog_page_name()
    {
        if (!$page_for_posts = get_option('page_for_posts')) return;
        $page_for_posts = get_option('page_for_posts');
        $post = get_post($page_for_posts);
        if ($post) {
            $slug = $post->post_name;
            return $slug;
        }
    }

    private function get_blog_page_id()
    {
        $page = "";
        $output = "";
        if ($this->get_blog_page_name()) {
            $page = get_page_by_path($this->get_blog_page_name());
            $output = $page->ID;
        }

        return $output;
    }

    public function init()
    {
        // For Homepage
        add_rewrite_rule(
            'amp/?$',
            'index.php?amp',
            'top'
        );
        // For Homepage with Pagination
        add_rewrite_rule(
            'amp/page/([0-9]{1,})/?$',
            'index.php?amp&paged=$matches[1]',
            'top'
        );
        // For /Blog page with Pagination
        add_rewrite_rule(
            $this->get_blog_page_name() . '/amp/page/([0-9]{1,})/?$',
            'index.php?amp&paged=$matches[1]&page_id=' . $this->get_blog_page_id(),
            'top'
        );

        // For Author pages
        add_rewrite_rule(
            'author\/([^/]+)\/amp\/?$',
            'index.php?amp&author_name=$matches[1]',
            'top'
        );

        add_rewrite_rule(
            'author\/([^/]+)\/amp\/page\/?([0-9]{1,})\/?$',
            'index.php?amp=1&author_name=$matches[1]&paged=$matches[2]',
            'top'
        );

        // For category pages
        $rewrite_category = get_option('category_base');
        if (!empty($rewrite_category)) {
            $rewrite_category = get_option('category_base');
        } else {
            $rewrite_category = 'category';
        }

        add_rewrite_rule(
            $rewrite_category . '\/(.+?)\/amp/?$',
            'index.php?amp&category_name=$matches[1]',
            'top'
        );
        // For category pages with Pagination
        add_rewrite_rule(
            $rewrite_category . '\/(.+?)\/amp\/page\/?([0-9]{1,})\/?$',
            'index.php?amp&category_name=$matches[1]&paged=$matches[2]',
            'top'
        );

        // For tag pages
        $rewrite_tag = get_option('tag_base');
        if (!empty($rewrite_tag)) {
            $rewrite_tag = get_option('tag_base');
        } else {
            $rewrite_tag = 'tag';
        }
        add_rewrite_rule(
            $rewrite_tag . '\/(.+?)\/amp/?$',
            'index.php?amp&tag=$matches[1]',
            'top'
        );
        // For tag pages with Pagination
        add_rewrite_rule(
            $rewrite_tag . '\/(.+?)\/amp\/page\/?([0-9]{1,})\/?$',
            'index.php?amp&tag=$matches[1]&paged=$matches[2]',
            'top'
        );

        //Rewrite rule for custom Taxonomies
        $args = [
            'public'   => true,
            '_builtin' => false
        ];
        $output = 'names'; // or objects
        $operator = 'and'; // 'and' or 'or'
        $taxonomies = get_taxonomies($args, $output, $operator);
        if ($taxonomies) {
            foreach ($taxonomies as $taxonomy) {
                add_rewrite_rule(
                    $taxonomy . '\/(.+?)\/amp/?$',
                    'index.php?amp&' . $taxonomy . '=$matches[1]',
                    'top'
                );
                // For Custom Taxonomies with pages
                add_rewrite_rule(
                    $taxonomy . '\/(.+?)\/amp\/page\/?([0-9]{1,})\/?$',
                    'index.php?amp&' . $taxonomy . '=$matches[1]&paged=$matches[2]',
                    'top'
                );
            }
        }
//        add_action('init', 'ampforwp_add_custom_rewrite_rules');
    }
}