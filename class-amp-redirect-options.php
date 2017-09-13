<?php

class AMP_Redirect_Options
{
    private $themes;
    private $template;

    public function __construct()
    {
        $this->themes = wp_get_themes(['errors' => false]);
        $this->template = get_option(AMP_REDIRECT_OPTION);
    }

    public function init()
    {
        add_action('admin_menu', [$this, 'add_options_page']);
        add_action('admin_init', [$this, 'register_redirect_settings']);
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

    public function register_redirect_settings()
    {
        register_setting(AMP_REDIRECT_SETTINGS_GROUP, AMP_REDIRECT_OPTION);
    }

    public function options_page()
    {
        // check user capabilities
        if (!current_user_can('manage_options')) {
            return;
        }
        ob_start(); ?>
        <div class="wrap">
            <?php if (!empty($this->themes) && is_array($this->themes)) : ?>
                <h1 class="wp-heading-inline"><?php _e('Chose AMP Theme', 'wpampredirect'); ?></h1>
                <form method="post" action="options.php">
                    <?php settings_fields(AMP_REDIRECT_SETTINGS_GROUP); ?>
                    <?php do_settings_sections(AMP_REDIRECT_SETTINGS_GROUP); ?>
                    <table class="form-table">
                        <tbody>
                        <tr>
                            <th scope="row">
                                <label for="wp-amp-theme"><?php _e('AMP Theme', 'wpampredirect'); ?></label>
                            </th>
                            <td>
                                <select name="<?php echo AMP_REDIRECT_OPTION; ?>" id="wp-amp-theme">
                                    <option><?php _e('Chose AMP Theme', 'wpampredirect'); ?></option>
                                    <?php foreach ($this->themes as $theme) : ?>
                                        <?php if (!($theme instanceof WP_Theme)):
                                            continue;
                                        endif; ?>
                                        <option
                                            value="<?php echo $theme->template; ?>" <?php selected($this->template, $theme->template, true); ?>><?php echo $theme->name; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <!--                    <p class="submit"><input type="submit" name="submit"
                    id="submit" class="button button-primary"-->
                    <!--                                             value="Save Changes"></p>-->

                    <?php submit_button(); ?>
                </form>
            <?php else : ?>
                <div class="notice notice-info inline">
                    <p><?php _e('No themes found. Maybe your themes has some errors', 'wpampredirect'); ?></p></div>
            <?php endif; ?>
        </div>
        <?php
        $output .= ob_get_contents();
        ob_end_clean();
        echo $output;
    }
}
