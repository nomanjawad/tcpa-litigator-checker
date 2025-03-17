<?php
class TCPA_Litigator_Checker_Updater
{
    private $github_api_url = 'https://api.github.com/repos/nomanjawad/tcpa-litigator-checker/releases/latest';

    public function __construct()
    {
        add_filter('pre_set_site_transient_update_plugins', [$this, 'check_for_update']);
        add_action('admin_init', [$this, 'force_check_for_updates']); // Force update check
    }

    public function check_for_update($transient)
    {
        if (empty($transient->checked)) return $transient;

        // Ensure the correct plugin slug is used
        $plugin_slug = 'tcpa-litigator-checker/tcpa-litigator-checker.php';
        $plugin_path = WP_PLUGIN_DIR . '/' . $plugin_slug;

        // Get installed version dynamically
        $plugin_data = get_file_data($plugin_path, ['Version' => 'Version']);
        $installed_version = $plugin_data['Version'];

        // Fetch latest GitHub release
        $response = wp_remote_get($this->github_api_url, [
            'headers' => ['User-Agent' => 'WordPress']
        ]);

        if (is_wp_error($response)) return $transient;

        $release = json_decode(wp_remote_retrieve_body($response), true);
        if (!$release || !isset($release['tag_name']) || !isset($release['zipball_url'])) return $transient;

        // Compare installed vs. latest GitHub version
        if (version_compare($installed_version, $release['tag_name'], '>=')) return $transient;

        // Add update details
        $transient->response[$plugin_slug] = (object) [
            'new_version' => $release['tag_name'],
            'package' => $release['zipball_url'], // Ensure the package URL exists
            'slug' => 'tcpa-litigator-checker',
            'url' => 'https://github.com/nomanjawad/tcpa-litigator-checker/releases/latest',
        ];

        return $transient;
    }

    // Force check updates when the admin panel is loaded
    public function force_check_for_updates()
    {
        delete_site_transient('update_plugins');
        delete_transient('tcpa_litigator_checker_latest_version'); // Force version refresh
    }
}
new TCPA_Litigator_Checker_Updater();
