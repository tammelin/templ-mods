<?php
/**
 * Plugin Name: templ-mods
 */

define('TEMPL_MODS_DIR_PATH', plugin_dir_path(__FILE__));
define('TEMPL_MODS_DIR_URL', plugin_dir_url(__FILE__));

require_once(TEMPL_MODS_DIR_PATH.'/vendor/autoload.php');
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

class TemplMods {
    function __construct()
    {
        if( ! isset($_GET['bs']) ) {
            add_action('wp_enqueue_scripts', [$this, 'enqueue']);
        } else {
            add_action('wp_enqueue_scripts', [$this, 'enqueue_local']);
        }
        add_action('wp_footer', [$this, 'browser_sync'], 9999);
    }

    function enqueue()
    {
        $style_date = date( "YmdHi", filemtime( TEMPL_MODS_DIR_PATH.'/assets/templ-mods.css' ) );
        if( filesize(TEMPL_MODS_DIR_PATH.'/assets/templ-mods.css') > 43 /* not 0 because of reference to .css.map */ ) {
            wp_enqueue_style('templ-mods', TEMPL_MODS_DIR_URL.'/assets/templ-mods.css', [], $style_date);
        }
        $js_date = date( "YmdHi", filemtime( TEMPL_MODS_DIR_PATH.'/assets/templ-mods.js' ) );
        if( filesize(TEMPL_MODS_DIR_PATH.'/assets/templ-mods.js') > 0 ) {
            wp_enqueue_script('templ-mods', TEMPL_MODS_DIR_URL.'/assets/templ-mods.js', [], $js_date, true);
        }
    }

    function enqueue_local()
    {
        $local_dir_url = 'https://localhost:'.$_ENV['PROXY_PORT'].'/wp-content/plugins/templ-mods';

        wp_enqueue_style('templ-mods', $local_dir_url.'/assets/templ-mods.css', [], time());
        wp_enqueue_script('templ-mods', $local_dir_url.'/assets/templ-mods.js', [], time(), true);
    }

    function browser_sync()
    {
        if( ! isset($_GET['bs']) ) {
            return;
        }
        ?>
            <script id="__bs_script__">//<![CDATA[
                document.write("<script async src='http://localhost:<?php echo $_ENV['PROXY_PORT'] ?>/browser-sync/browser-sync-client.js?v=2.27.11'><\/script>");
            //]]></script>
        <?php
    }
}
new TemplMods();