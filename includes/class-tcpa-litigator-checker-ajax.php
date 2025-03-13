<?php

/**
 * Handles AJAX requests for the TCPA Litigator Checker.
 *
 * @since      1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Processes the AJAX request for checking phone numbers.
 */
class TCPA_Litigator_Checker_AJAX
{

    public function __construct()
    {
        add_action('wp_ajax_tcpa_checker_ajax', [$this, 'process_ajax_request']);
        add_action('wp_ajax_nopriv_tcpa_checker_ajax', [$this, 'process_ajax_request']);
    }

    public function process_ajax_request()
    {
        check_ajax_referer('tcpa_checker_action', 'security');

        $phone_number = sanitize_text_field($_POST['phone']);
        $type = sanitize_text_field($_POST['type']);

        if (empty($phone_number)) {
            wp_send_json_error(['message' => 'Phone number is required.']);
        }

        $response = TCPA_Litigator_Checker_API::check_phone_number($phone_number, [$type]);

        if (isset($response['error'])) {
            wp_send_json_error(['message' => $response['error']]);
        } else {
            wp_send_json_success([
                'phone' => $response['phone'],
                'status' => $response['status'],
                'is_bad_number' => $response['is_bad_number'],
                'phone_type' => $response['phone_type'],
                'status_array' => $response['status_array'],
                'raw_response' => $response['raw_response'],
            ]);
        }
    }
}



// Initialize AJAX handler
new TCPA_Litigator_Checker_AJAX();
