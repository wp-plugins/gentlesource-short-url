<?php
/*
Plugin Name: GentleSource Short URL
Plugin URI: http://www.gentlesource.com/plugins/short-url/
Description: Automatically shortens the blog post URL.
Version: 1.2.0
Author: GentleSource
Author URI: http://www.gentlesource.com/
*/

define('DEFAULT_API_URL', 'http://unrelo.com/?api=test&u=%s');

//load_plugin_textdomain('gentlesource_shorturl', '/wp-content/plugins/gentlesource_shorturl/language/');

class GentleSource_Short_URL
{
    /**
     * List of short URL website API URLs
     */
    function api_urls()
    {
        return array(
            array(
                'name' => '',
                'url'  => '',
                ),
            array(
                'name' => 'melt.li',
                'url'  => 'http://melt.li/?api=1&u=%s',
                ),
            array(
                'name' => 'unrelo.com',
                'url'  => 'http://unrelo.com/?api=1&u=%s',
                ),
            array(
                'name' => 'lin.io',
                'url'  => 'http://lin.io/?api=1&u=%s',
                ),
            array(
                'name' => 'bit.ly',
                'url'  => 'http://bit.ly/api?url=%s',
                ),
            array(
                'name' => 'u.nu',
                'url'  => 'http://u.nu/unu-api-simple?url=%s',
                ),
            array(
                'name' => 'tinyurl.com',
                'url'  => 'http://tinyurl.com/api-create.php?url=%s',
                ),
            array(
                'name' => 'a.gd',
                'url'  => 'http://a.gd/?module=ShortURL&file=Add&mode=API&url=%s',
                ),
            array(
                'name' => 'fwd4.me',
                'url'  => 'http://api.fwd4.me/?url=%s',
                ),
            array(
                'name' => 'su.pr',
                'url'  => 'http://su.pr/api?url=%s',
                ),
            array(
                'name' => 'redir.ec',
                'url'  => 'http://redir.ec/_api/rest/redirec/create?url=%s',
                ),
            array(
                'name' => 'href.be',
                'url'  => 'http://href.be/api?url=%s',
                ),
            array(
                'name' => 'zip.li',
                'url'  => 'http://zip.li/api?longUrl=%s',
                ),
            array(
                'name' => 'mug.im',
                'url'  => 'http://mug.im/create?url=%s&format=text',
                ),
            );
    }

    /**
     * Create short URL based on post URL
     */
    function create($post_id)
    {
        if (!$apiURL = get_option('gentlesourceShortUrlApiUrl')) {
            $apiURL = DEFAULT_API_URL;
        }

        // For some reason the post_name changes to /{id}-autosave/ when a post is autosaved
        $post = get_post($post_id);
        $pos = strpos($post->post_name, 'autosave');
        if ($pos !== false) {
            return false;
        }
        $pos = strpos($post->post_name, 'revision');
        if ($pos !== false) {
            return false;
        }

        $apiURL = str_replace('%s', urlencode(get_permalink($post_id)), $apiURL);

        $result = false;

        if (ini_get('allow_url_fopen')) {
            if ($handle = @fopen($apiURL, 'r')) {
                $result = fread($handle, 4096);
                fclose($handle);
            }
        } elseif (function_exists('curl_init')) {
            $ch = curl_init($apiURL);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            $result = @curl_exec($ch);
            curl_close($ch);
        }

        if ($result !== false) {
            delete_post_meta($post_id, 'GentleSourceShortURL');
            $res = add_post_meta($post_id, 'GentleSourceShortURL', $result, true);
            return true;
        }
    }

    /**
     * Option list (default settings)
     */
    function options()
    {
        return array(
           'ApiUrl'         => DEFAULT_API_URL,
           'Display'        => 'Y',
           'TwitterLink'    => 'Y',
           );
    }

    /**
     * Plugin settings
     *
     */
    function settings()
    {
        $apiUrls = $this->api_urls();
        $options = $this->options();
        $opt = array();

        if (!empty($_POST)) {
            foreach ($options AS $key => $val)
            {
                if (!isset($_POST[$key])) {
                    continue;
                }
                update_option('gentlesourceShortUrl' . $key, $_POST[$key]);
            }
        }
        foreach ($options AS $key => $val)
        {
            $opt[$key] = get_option('gentlesourceShortUrl' . $key);
        }
        include '../wp-content/plugins/gentlesource-short-url/template/settings.tpl.php';
    }

    /**
     *
     */
    function admin_menu()
    {
        add_options_page('GentleSource Short URL', 'Short URL', 10, 'gentlesource_shorturl-settings', array(&$this, 'settings'));
    }

    /**
     * Display the short URL
     */
    function display($content)
    {

        global $post;

        if ($post->ID <= 0) {
            return $content;
        }

        $options = $this->options();

        foreach ($options AS $key => $val)
        {
            $opt[$key] = get_option('gentlesourceShortUrl' . $key);
        }

        $shortUrl = get_post_meta($post->ID, 'GentleSourceShortURL', true);

        if (empty($shortUrl)) {
            return $content;
        }

        $shortUrlEncoded = urlencode($shortUrl);

        ob_start();
        include './wp-content/plugins/gentlesource-short-url/template/public.tpl.php';
        $content .= ob_get_contents();
        ob_end_clean();

        return $content;
    }
}

$gssu = new GentleSource_Short_URL;

if (is_admin()) {
    add_action('edit_post', array(&$gssu, 'create'));
    add_action('save_post', array(&$gssu, 'create'));
    add_action('publish_post', array(&$gssu, 'create'));
    add_action('admin_menu', array(&$gssu, 'admin_menu'));
} else {
    add_filter('the_content', array(&$gssu, 'display'));
}