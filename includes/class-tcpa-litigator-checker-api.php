<?php

/**
 * Handles API requests for TCPA Litigator Checker.
 *
 * @since      1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Handles API calls to the TCPA Litigator Checker API.
 */
class TCPA_Litigator_Checker_API
{

    /**
     * Makes a request to the TCPA API and returns the response.
     *
     * @param string $phone_number The phone number to check.
     * @param array  $types The types of checks (e.g., tcpa, dnc).
     * @param string $contact_name The name associated with the number.
     * @return array API response.
     */
    public static function check_phone_number($phone_number, $types = ["tcpa", "dnc"], $contact_name = 'Unknown')
    {
        $api_username = get_option('tcpa_checker_api_url'); // API URL is used as username
        $api_password = get_option('tcpa_checker_api_key'); // API Key is used as password

        // Validate API credentials
        if (empty($api_username) || empty($api_password)) {
            return ['error' => 'API credentials are missing.'];
        }

        // API endpoint
        $api_endpoint = "https://api.tcpalitigatorlist.com/scrub/phone/";

        // Prepare POST data
        $post_data = [
            'type' => json_encode($types),
            'phone_number' => $phone_number,
            'contact_name' => $contact_name
        ];

        // Initialize cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api_endpoint);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
        curl_setopt($ch, CURLOPT_USERPWD, "{$api_username}:{$api_password}");
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10); // Set timeout
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/x-www-form-urlencoded"
        ]);

        // Execute request
        $output = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        // Decode JSON response
        $response_array = json_decode($output, true);

        // Handle API response errors
        if ($http_code !== 200 || empty($response_array)) {
            return ['error' => 'API request failed or returned empty response.'];
        }

        // Extract relevant data from API response
        return [
            'success' => true,
            'phone' => $response_array['results']['phone_number'] ?? $phone_number,
            'status' => $response_array['results']['status'] ?? 'Unknown',
            'is_bad_number' => $response_array['results']['is_bad_number'] ?? false,
            'phone_type' => $response_array['results']['phone_type'] ?? 'Unknown',
            'status_array' => $response_array['results']['status_array'] ?? [],
            'raw_response' => $response_array, // Keep for debugging
        ];
    }
}
