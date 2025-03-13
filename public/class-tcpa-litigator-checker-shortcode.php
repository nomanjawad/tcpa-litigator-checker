<?php

/**
 * Handles the shortcode for TCPA Litigator Checker.
 *
 * @since      1.0.0
 * @package    TCPA_Litigator_Checker
 * @subpackage TCPA_Litigator_Checker/public
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Defines the shortcode for checking phone numbers via API.
 *
 * @since      1.0.0
 */
class TCPA_Litigator_Checker_Shortcode
{
    /**
     * Registers the shortcode with WordPress.
     */
    public function __construct()
    {
        add_shortcode('tcpa-checker-form', [$this, 'render_shortcode']);
    }

    /**
     * Renders the shortcode form.
     *
     * @param array $atts Shortcode attributes.
     * @return string HTML form.
     */
    public function render_shortcode($atts)
    {
        $atts = shortcode_atts(['class' => ''], $atts, 'tcpa-checker-form');

        // Default style settings
        $button_color = get_option('tcpa_checker_button_color', '#007bff');
        $button_font_color = get_option('tcpa_checker_button_font_color', '#fff');
        $label_font_size = get_option('tcpa_checker_label_font_size', '14');

        ob_start(); ?>
        <style>
            .tcpa-checker-form label {
                font-size: <?php echo esc_attr($label_font_size); ?>px;
            }

            .tcpa-checker-form .input-group-append button {
                background-color: <?php echo esc_attr($button_color); ?>;
                color: <?php echo esc_attr($button_font_color); ?>;
            }
        </style>
        <div class="tcpa-checker-wrapper <?php echo esc_attr($atts['class']); ?>">
            <form id="tcpa-checker-form" class="tcpa-checker-form">
                <?php wp_nonce_field('tcpa_checker_action', 'tcpa_checker_nonce'); ?>
                <div class="input-group">
                    <!-- Phone Number Field -->
                    <label for="phone">Phone Number</label>
                    <input type="text" name="phone" class="form-control phone" placeholder="Enter Phone Number" required>
                </div>
                <div class="input-group">
                    <!-- Type Dropdown -->
                    <label for="phone">Select Status</label>
                    <select name="type" class="form-control type">
                        <option value="all">All Statuses</option>
                        <option value="tcpa">TCPA</option>
                        <option value="dnc_fed">Federal DNC</option>
                        <option value="dnc">DNC Complainers</option>
                    </select>

                </div>
                <div class="input-group-append">
                    <button type="submit" class="btn btn-primary">Check</button>
                </div>

            </form>
            <div id="tcpa-result"></div>
        </div>
<?php
        return ob_get_clean();
    }
}

// Initialize the shortcode
new TCPA_Litigator_Checker_Shortcode();
