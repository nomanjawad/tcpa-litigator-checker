<?php
class TCPA_Litigator_Checker_Updater
{
    private $github_api_url = 'https://api.github.com/repos/nomanjawad/tcpa-litigator-checker/releases/latest';

    public function __construct()
    {
        add_filter('pre_set_site_transient_update_plugins', [$this, 'check_for_update']);
    }

    public function check_for_update($transient)
    {
        if (empty($transient->checked)) return $transient;

        $plugin_slug = plugin_basename(__FILE__); // Dynamically get plugin path

        // Fetch latest GitHub release
        $response = wp_remote_get($this->github_api_url, [
            'headers' => ['User-Agent' => 'WordPress']
        ]);

        if (is_wp_error($response)) return $transient;

        $release = json_decode(wp_remote_retrieve_body($response), true);
        if (!$release || !isset($release['tag_name'])) return $transient;

        // Compare installed vs. latest GitHub version
        if (version_compare(TCPA_LITIGATOR_CHECKER_VERSION, $release['tag_name'], '>=')) return $transient;

        // Add update details
        $transient->response[$plugin_slug] = (object) [
            'new_version' => $release['tag_name'],
            'package' => $release['zipball_url'],
            'slug' => $plugin_slug,
        ];

        return $transient;
    }
}
new TCPA_Litigator_Checker_Updater();
