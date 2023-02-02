<?php

/**
 * Plugin Name: templ-mods
 */

define('TEMPL_MODS_DIR_PATH', plugin_dir_path(__FILE__));
define('TEMPL_MODS_DIR_URL', plugin_dir_url(__FILE__));

require_once(TEMPL_MODS_DIR_PATH . '/vendor/autoload.php');
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

if (file_exists(TEMPL_MODS_DIR_PATH . '/dist/functions.php')) {
    require_once(TEMPL_MODS_DIR_PATH . '/dist/functions.php');
}

class TemplMods
{
    function __construct()
    {
        if (!isset($_GET['bs'])) {
            add_action('wp_enqueue_scripts', [$this, 'enqueue']);
        } else {
            add_action('wp_enqueue_scripts', [$this, 'enqueue_local']);
            add_action('wp_footer', [$this, 'browser_sync'], 9999);
        }
        add_filter('woocommerce_locate_template', [$this, 'intercept_wc_template'], 10, 3);
    }

    function enqueue()
    {
        $css_file = TEMPL_MODS_DIR_PATH . '/dist/style.css';
        if (file_exists($css_file) && filesize($css_file)) {
            wp_enqueue_style('templ-mods', TEMPL_MODS_DIR_URL . '/dist/style.css', [], filemtime($css_file));
        }
        $js_file = TEMPL_MODS_DIR_PATH . '/dist/main.js';
        if (file_exists($js_file) && filesize($js_file)) {
            wp_enqueue_script('templ-mods', TEMPL_MODS_DIR_URL . '/dist/main.js', [], filemtime($js_file), true);
        }
    }

    function enqueue_local()
    {
        $local_dir_url = 'https://localhost:' . $_ENV['PROXY_PORT'] . '/wp-content/plugins/templ-mods';
        wp_enqueue_style('templ-mods', $local_dir_url . '/dist/style.css', [], time());
        wp_enqueue_script('templ-mods', $local_dir_url . '/dist/main.js', [], time(), true);
    }

    function browser_sync()
    {
?>
        <script id="__bs_script__">
            //<![CDATA[
            document.write("<script async src='http://localhost:<?php echo $_ENV['PROXY_PORT'] ?>/browser-sync/browser-sync-client.js?v=2.27.11'><\/script>");
            //]]>
        </script>
        <script>
            document.querySelectorAll("a").forEach(link => {
                if( ! link.href.includes('<?php echo get_home_url(); ?>') ) return;
                let queryString = '?bs';
                link.href = link.href.includes("?") ? link.href + queryString.replace("?", "&") : link.href + queryString;
            });
        </script>
<?php
    }

    /**
     * Filter the cart template path to use cart.php in this plugin instead of the one in WooCommerce.
     *
     * @param string $template      Default template file path.
     * @param string $template_name Template file slug.
     * @param string $template_path Template file name.
     *
     * @return string The new Template file path.
     */
    function intercept_wc_template( $template, $template_name, $template_path ) {
        $template_directory = trailingslashit(TEMPL_MODS_DIR_PATH).'dist/templates/woocommerce/';
        $path = $template_directory . $template_name;
        return file_exists( $path ) ? $path : $template;
    }

}
new TemplMods();
