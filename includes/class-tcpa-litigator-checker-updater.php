<?php
class TCPA_Litigator_Checker_Updater
{
    public function __construct()
    {
        add_filter('pre_set_site_transient_update_plugins', [$this, 'check_for_update']);
    }

    public function check_for_update($transient)
    {
        if (empty($transient->checked)) return $transient;

        $plugin_slug = 'tcpa-litigator-checker';
        $github_api_url = 'https://api.github.com/repos/yourgithubusername/tcpa-litigator-checker/releases/latest';

        $response = wp_remote_get($github_api_url);
        if (is_wp_error($response)) return $transient;

        $release = json_decode(wp_remote_retrieve_body($response));
        if (!$release || version_compare(TCPA_LITIGATOR_CHECKER_VERSION, $release->tag_name, '>=')) return $transient;

        $transient->response["$plugin_slug/$plugin_slug.php"] = (object) [
            'new_version' => $release->tag_name,
            'package' => $release->zipball_url,
            'slug' => $plugin_slug,
        ];
        return $transient;
    }
}
new TCPA_Litigator_Checker_Updater();
