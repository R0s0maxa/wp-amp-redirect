<?php

class AMP_Redirect_Options
{
    private $themes;

    /**
     * AMP_Redirect_Options constructor.
     * @param $themes array
     */
    public function __construct($themes)
    {
        $this->themes = $themes;
    }

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
        $value = null;
        ob_start(); ?>
        <div class="wrap">
            <?php if (!empty($this->themes) && is_array($this->themes)) : ?>-
                <h1 class="wp-heading-inline"><?php _e('Chose AMP Theme', 'wpampredirect'); ?></h1>
                <form method="post" action="options.php" novalidate="novalidate">
                    <table class="form-table">
                        <tbody>
                        <tr>
                            <th scope="row">
                                <label for="wp-amp-theme"><?php _e('AMP Theme', 'wpampredirect'); ?></label>
                            </th>
                            <td>
                                <select name="wp-amp-theme" id="wp-amp-theme">
                                    <option><?php _e('Chose AMP Theme', 'wpampredirect'); ?></option>
                                    <?php foreach ($this->themes as $theme) : ?>
                                        <?php if (!($theme instanceof WP_Theme)):
                                            continue;
                                        endif; ?>
                                        <option
                                            value="<?php echo $theme->template; ?>" <?php selected($value, $theme->template, true); ?>><?php echo $theme->name; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary"
                                             value="Save Changes"></p>
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
