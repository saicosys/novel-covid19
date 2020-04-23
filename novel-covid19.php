<?php
/**
 * Plugin Name: Novel COVID-19 Live Statistics
 * Description: Live statistics tracking the number of confirmed cases, recovered and deaths by country or global due to Novel COVID-19.
 * Plugin URI: https://saicosysla.com/novelcovid19
 * Version: 1.0.0
 * Author: Sandeep Kadyan, Amit Shokeen
 * Author URI: https://saicosysla.com/
 * Requires at least: 4.3.X
 * Tested up to: 5.4.0
 * License:
 * Text Domain: novel-covid19
 * Domain Path: /languages/
 **/

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('NovelCovid19')) {
    /**
     * NovelCovid19 Class
     *
     * Novel COVID-19 Live Statistics
     * Live statistics tracking the number of confirmed cases, recovered and deaths by country or global with the help of Novel COVID-19 Plugin.
     */
    class NovelCovid19
    {

        /**
         * Use this method to add common initialization code like loading following:
         * Stable Version v1.0.0
         * Create Cron Jobs
         * Register Shortcode
         */
        function __construct()
        {
            define('NOVEL_COVID19_VER', '1.0.0');

            if (!defined('NOVEL_COVID19_URL')) {
                define('NOVEL_COVID19_URL', plugin_dir_url(__FILE__));
            }

            if (!defined('NOVEL_COVID19_PATH')) {
                define('NOVEL_COVID19_PATH', plugin_dir_path(__FILE__));
            }

            add_action('init', array($this, 'load_textdomain'));
            add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_assets'));
            add_action('admin_menu', array($this, 'register_custom_menu_page'));
            $this->createJob();
            $this->make_sure_data_loaded();

            add_action('init', array($this, 'register_assets'));
            add_shortcode('novel-covid19-adminview', array($this, 'shortcode_adminview'));
            add_shortcode('novel-covid19', array($this, 'shortcode'));
            add_shortcode('novel-covid19-table', array($this, 'shortcode_table'));
            add_shortcode('novel-covid19-map', array($this, 'shortcode_map'));
            add_shortcode('novel-covid19-newsline', array($this, 'shortcode_newsline'));
            add_shortcode('novel-covid19-chart', array($this, 'shortcode_chart'));
        }

        /**
         * Register a custom menu page.
         *
         * @return (string) The resulting page's hook_suffix.
         */
        function register_custom_menu_page()
        {
            add_menu_page(
                esc_attr__('Live-statistics', 'novel-covid19'),
                esc_attr__('Novel Covid-19', 'novel-covid19'),
                'manage_options',
                'novel-covid19',
                array($this, 'custom_menu_page'),
                'dashicons-admin-site',
                81
            );
        }

        /**
         * Display a custom menu page
         *
         * * @return (string) The resulting page's hook_suffix.
         */
        function custom_menu_page()
        {
            include_once(NOVEL_COVID19_PATH . 'templates/admin.php');
        }

        /**
         * Register custom assets for the Front-end.
         *
         * @return void
         */
        function register_assets()
        {
            $getOptionAll = get_option('novel_covid19_all');
            $getOptionCountries = get_option('novel_covid19_countries');
            $getOptionHistory = get_option('novel_covid19_history');
            $translation_array = array(
                'all' => $getOptionAll,
                'countries' => $getOptionCountries,
                'history' => $getOptionHistory
            );

            wp_enqueue_style('novelcovid', NOVEL_COVID19_URL . 'assets/css/novelcovid.css',  null, NOVEL_COVID19_VER);
            wp_enqueue_style('datatables', NOVEL_COVID19_URL . 'assets/css/datatables.bundle.css', null, NOVEL_COVID19_VER);
            wp_register_script('jquery.datatables', 'https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js', array('jquery'), NOVEL_COVID19_VER, true);
            wp_register_script('chartjs', 'https://cdn.jsdelivr.net/npm/chart.js@2.8.0', array('jquery'), NOVEL_COVID19_VER, true);
            wp_register_script('novel-covid19', NOVEL_COVID19_URL . 'assets/js/frontend.js', array('jquery'), NOVEL_COVID19_VER, true);
            wp_localize_script('novel-covid19', 'novel_covid19', $translation_array);
        }

        /**
         * Register admin js.
         *
         * @return void
         */
        public function admin_enqueue_assets()
        {
            wp_enqueue_script('novel-covid19-admin', NOVEL_COVID19_URL . 'assets/js/admin.js', array('jquery'), NOVEL_COVID19_VER, true);
        }

        /**
         * Create Cron Jobs to get the latest update of Novel Covid 19 cases.
         *
         * @return void
         */
        function createJob()
        {
            add_filter('cron_schedules', array($this, 'add_wp_cron_schedule'));
            if (!wp_next_scheduled('covid_job')) {
                $next_timestamp = wp_next_scheduled('covid_job');
                if ($next_timestamp) {
                    wp_unschedule_event($next_timestamp, 'covid_job');
                }
                wp_schedule_event(time(), 'every_15minute', 'covid_job');
            }
            add_action('covid_job', array($this, 'getDatafromAPI'));
        }

        /**
         * Set cron job schedule. Nomally it will update data in every 10 minutes.
         *
         * @param $schedules (Every 10 minutes)
         *
         * @return $schedules
         */
        function add_wp_cron_schedule($schedules)
        {
            $schedules['every_15minute'] = array(
                'interval' => 10 * 60,
                'display'  => esc_attr__('Every 10 minutes', 'novel-covid19'),
            );
            return $schedules;
        }

        /**
         * Get data from the Covid 19 Api call
         *
         * @return void
         */
        function getDatafromAPI()
        {
            $all = $this->getData(false);
            $countries = $this->getData(true);
            $history = $this->getData(false, true);
            $getOptionAll = get_option('novel_covid19_all');
            $getOptionCountries = get_option('novel_covid19_countries');
            $getOptionHistory = get_option('novel_covid19_history');

            if ($getOptionAll) {
                update_option('novel_covid19_all', $all);
            } else {
                add_option('novel_covid19_all', $all);
            }
            if ($getOptionCountries) {
                update_option('novel_covid19_countries', $countries);
            } else {
                add_option('novel_covid19_countries', $countries);
            }
            if ($getOptionHistory) {
                update_option('novel_covid19_history', $history);
            } else {
                add_option('novel_covid19_history', $history);
            }
        }

        /**
         * Get data from the Covid 19 Api call
         *
         * @return void
         */
        function make_sure_data_loaded()
        {
            $getOptionAll = get_option('novel_covid19_all');
            $getOptionCountries = get_option('novel_covid19_countries');
            $getOptionHistory = get_option('novel_covid19_history');
            if (!$getOptionCountries) {
                $countries = $this->getData(true);
                update_option('novel_covid19_countries', $countries);
            }
            if (!$getOptionAll) {
                $all = $this->getData(false);
                update_option('novel_covid19_all', $all);
            }
            if (!$getOptionHistory) {
                $history = $this->getData(false, true);
                update_option('novel_covid19_history', $history);
            }
        }

        /**
         * Load text domain
         */
        function load_textdomain()
        {
            load_plugin_textdomain('novel-covid19', false, dirname(plugin_basename(__FILE__)) . '/languages');
        }

        /**
         * Load data from the NovelCovid/API
         *
         * @param $countryCode default false
         * @param $history default false
         *
         * @return $data
         */
        function getData($countryCode = false, $history = false)
        {
            $endPoint     = 'https://corona.lmao.ninja/';
            $methodPath = 'v2/all';

            if ($history) {
                $methodPath = 'v2/historical/all';
            }

            if ($countryCode && !$history) {
                $methodPath = 'v2/countries/?sort=cases';
            } else if ($history && $countryCode) {
                $methodPath = 'v2/historical/' . $countryCode;
            }

            $endPoint = $endPoint . $methodPath;
            $args = array(
                'timeout' => 120
            );
            $request = wp_remote_get($endPoint, $args);
            $body = wp_remote_retrieve_body($request);
            $data = json_decode($body);

            $current_time = current_time('timestamp');
            if (get_option('novel_covid19_last_updated')) {
                update_option('novel_covid19_last_updated', $current_time);
            } else {
                add_option('novel_covid19_last_updated', $current_time);
            }

            return $data;
        }

        /**
         * Shortcode for the admin view
         *
         * @param $atts
         *
         * @return string Get current buffer contents and delete current output buffer
         */
        function shortcode_adminview($atts)
        {
            $params = shortcode_atts(array(
                'heading' => esc_attr__('Novel COVID-19 Coronavirus Tracker by', 'novel-covid19'),
                'heading_by_country' => esc_attr__('Cases By Country', 'novel-covid19'),
                'title' => esc_attr__('Corona cases', 'novel-covid19'),
                'country' => null,
                'label_confirmed' => esc_attr__('Total Confirmed Cases', 'novel-covid19'),
                'label_confirmedtoday' => esc_attr__('Confirmed Today', 'novel-covid19'),
                'label_deaths' => esc_attr__('Deaths', 'novel-covid19'),
                'label_deathstoday' => esc_attr__('Deaths Today', 'novel-covid19'),
                'label_recovered' => esc_attr__('Recovered', 'novel-covid19'),
                'label_active' => esc_attr__('Active', 'novel-covid19'),
                'label_critical' => esc_attr__('Critical', 'novel-covid19'),
                'label_tests' => esc_attr__('Tests Done', 'novel-covid19'),
                'label_testsPerOneMillion' => esc_attr__('Tests Per 1 Million', 'novel-covid19'),
                'label_casesPerOneMillion' => esc_attr__('Cases Per 1 Million', 'novel-covid19'),
                'label_deathsPerOneMillion' => esc_attr__('Deaths Per 1 Million', 'novel-covid19'),
                'label_total' => esc_attr__('Total Confirmed Cases', 'novel-covid19'),
                'label_recovery_ratio' => esc_attr__('Recovery Ratio', 'novel-covid19'),
                'label_deaths_ratio' => esc_attr__('Deaths Ratio', 'novel-covid19'),
                'label_updated' => esc_attr__('Last updated: ', 'novel-covid19'),
                'label_country' => esc_attr__('Country', 'novel-covid19'),
                'style' => 'default',
                'show_detail' => 'yes',
                'lang_url' => "",
            ), $atts);

            if ($params['show_detail'] === 'yes') {
                $params['show_detail'] = true;
            } else {
                $params['show_detail'] = false;
            }

            if ($params['country']) {
                $data = get_option('novel_covid19_countries');
                if ($params['country'] && $params['style'] !== 'list') {
                    $new_array = array_filter($data, function ($obj) use ($params) {
                        if ($obj->country === $params['country']) {
                            return true;
                        }
                        return false;
                    });
                    if ($new_array) {
                        $data = reset($new_array);
                    }
                }
            }

            ob_start();
            echo $this->render_adminview($params, $data);
            return ob_get_clean();
        }

        /**
         * Shortcode for the Global Frontend
         *
         * @param $atts
         *
         * @return string Get current buffer contents and delete current output buffer
         */
        function shortcode($atts)
        {
            $params = shortcode_atts(array(
                'title' => esc_attr__('Corona cases', 'novel-covid19'),
                'country' => null,
                'label_confirmed' => esc_attr__('Total Confirmed Cases', 'novel-covid19'),
                'label_confirmedtoday' => esc_attr__('Confirmed Today', 'novel-covid19'),
                'label_deaths' => esc_attr__('Deaths', 'novel-covid19'),
                'label_deathstoday' => esc_attr__('Deaths Today', 'novel-covid19'),
                'label_recovered' => esc_attr__('Recovered', 'novel-covid19'),
                'label_active' => esc_attr__('Active', 'novel-covid19'),
                'label_critical' => esc_attr__('Critical', 'novel-covid19'),
                'label_tests' => esc_attr__('Tests Done', 'novel-covid19'),
                'label_testsPerOneMillion' => esc_attr__('Tests Per 1 Million', 'novel-covid19'),
                'label_casesPerOneMillion' => esc_attr__('Cases Per 1 Million', 'novel-covid19'),
                'label_deathsPerOneMillion' => esc_attr__('Deaths Per 1 Million', 'novel-covid19'),
                'label_updated' => esc_attr__('Last updated: ', 'novel-covid19'),
                'label_country' => esc_attr__('Country', 'novel-covid19'),
                'label_total' => esc_attr__('Total Confirmed Cases', 'novel-covid19'),
                'label_recovery_ratio' => esc_attr__('Recovery Ratio', 'novel-covid19'),
                'label_deaths_ratio' => esc_attr__('Deaths Ratio', 'novel-covid19'),
                'style' => 'default',
                'show_detail' => 'yes',
                'lang_url' => "",
            ), $atts);

            if ($params['show_detail'] === 'yes') {
                $params['show_detail'] = true;
            } else {
                $params['show_detail'] = false;
            }

            $data = get_option('novel_covid19_all');

            if ($params['country']) {
                $data = get_option('novel_covid19_countries');
                if ($params['country'] && $params['style'] !== 'list') {
                    $new_array = array_filter($data, function ($obj) use ($params) {
                        if ($obj->country === $params['country']) {
                            return true;
                        }
                        return false;
                    });
                    if ($new_array) {
                        $data = reset($new_array);
                    }
                }
            }

            ob_start();
            echo $this->render_item($params, $data);

            return ob_get_clean();
        }

        /**
         * Shortcode Table (Datatables)
         *
         * @param $atts
         *
         * @return string Get current buffer contents and delete current output buffer
         */
        function shortcode_table($atts)
        {
            $params = shortcode_atts(array(
                'title' => esc_attr__('Corona Cases - Worldwide', 'novel-covid19'),
                'label_confirmed' => esc_attr__('Confirmed', 'novel-covid19'),
                'label_confirmedtoday' => esc_attr__('Confirmed Today', 'novel-covid19'),
                'label_deaths' => esc_attr__('Deaths', 'novel-covid19'),
                'label_deathstoday' => esc_attr__('Deaths Today', 'novel-covid19'),
                'label_recovered' => esc_attr__('Recovered', 'novel-covid19'),
                'label_active' => esc_attr__('Active', 'novel-covid19'),
                'label_critical' => esc_attr__('Critical', 'novel-covid19'),
                'label_tests' => esc_attr__('Tests Done', 'novel-covid19'),
                'label_testsPerOneMillion' => esc_attr__('Tests Per One Million', 'novel-covid19'),
                'label_casesPerOneMillion' => esc_attr__('Cases Per One Million', 'novel-covid19'),
                'label_deathsPerOneMillion' => esc_attr__('Deaths Per One Million', 'novel-covid19'),
                'label_updated' => esc_attr__('Last updated: ', 'novel-covid19'),
                'label_country' => esc_attr__('Affected Country', 'novel-covid19'),
                'show_detail' => 'yes',
                'style' => 'default',
                'lang_url' => "",
                "showing" => 10
            ), $atts);

            $data = get_option('novel_covid19_countries');

            if ($params['show_detail'] === 'yes') {
                $params['show_detail'] = true;
            } else {
                $params['show_detail'] = false;
            }

            ob_start();
            echo $this->render_table($params, $data);
            return ob_get_clean();
        }

        /**
         * Shortcode Map
         *
         * @param $atts
         *
         * @return string Get current buffer contents and delete current output buffer
         */
        function shortcode_map($atts)
        {
            $params = shortcode_atts(array(
                'label_confirmed' => esc_attr__('Confirmed', 'novel-covid19'),
                'label_confirmedtoday' => esc_attr__('Today', 'novel-covid19'),
                'label_deaths' => esc_attr__('Deaths', 'novel-covid19'),
                'label_deathstoday' => esc_attr__('Today', 'novel-covid19'),
                'label_recovered' => esc_attr__('Recovered', 'novel-covid19'),
                'label_active' => esc_attr__('Active', 'novel-covid19'),
                'label_critical' => esc_attr__('Critical', 'novel-covid19'),
                'label_updated' => esc_attr__('Last updated: ', 'novel-covid19'),
                'style' => 'blue'
            ), $atts);

            $data = get_option('novel_covid19_all');

            ob_start();
            echo $this->render_map($params, $data);
            return ob_get_clean();
        }

        /**
         * Shortcode Newsline
         *
         * @param $atts
         *
         * @return string Get current buffer contents and delete current output buffer
         */
        function shortcode_newsline($atts)
        {
            $params = shortcode_atts(array(
                'title' => esc_attr__('Latest Updates', 'novel-covid19'),
                'country' => null,
                'label_confirmed' => esc_attr__('Total confirmed: ', 'novel-covid19'),
                'label_deaths' => esc_attr__('Deaths: ', 'novel-covid19'),
                'label_recovered' => esc_attr__('Recovered: ', 'novel-covid19'),
                'label_active' => esc_attr__('Active: ', 'novel-covid19'),
                'label_updated' => esc_attr__('Last updated: ', 'novel-covid19'),
                'label_critical' => esc_attr__('Critical: ', 'novel-covid19'),
                'label_new' => esc_attr__(' New today', 'novel-covid19'),
                'style' => 'default'
            ), $atts);

            $data = get_option('novel_covid19_all');

            if ($params['country']) {
                $data = get_option('novel_covid19_countries');
                $new_array = array_filter($data, function ($obj) use ($params) {
                    if ($obj->country === $params['country']) {
                        return true;
                    }
                    return false;
                });
                if ($new_array) {
                    $data = reset($new_array);
                }
            }

            ob_start();
            echo $this->render_newsline($params, $data);
            return ob_get_clean();
        }

        /**
         * Shortcode Chart
         * Chart Type bar and line
         *
         * @param $atts
         *
         * @return string Get current buffer contents and delete current output buffer
         */
        function shortcode_chart($atts)
        {
            $params = shortcode_atts(array(
                'worldwide' => esc_attr__('Worldwide', 'novel-covid19'),
                'title' => esc_attr__('Corona cases', 'novel-covid19'),
                'country' => null,
                'label_confirmed' => esc_attr__('Confirmed', 'novel-covid19'),
                'label_active' => esc_attr__('Active: ', 'novel-covid19'),
                'label_deaths' => esc_attr__('Deaths', 'novel-covid19'),
                'label_recovered' => esc_attr__('Recovered', 'novel-covid19'),
                'label_confirmedtoday' => esc_attr__('Today cases', 'novel-covid19'),
                'label_deathstoday' => esc_attr__('Today deaths', 'novel-covid19'),
                'label_critical' => esc_attr__('Critical: ', 'novel-covid19'),
                'label_updated' => esc_attr__('Last updated: ', 'novel-covid19'),
                'style' => 'default',
                'type' => 'bar'
            ), $atts);

            $data = get_option('novel-covid19_all');

            if ($params['country']) {
                $data = $this->getData($params['country'], true);
            }

            ob_start();
            echo $this->render_chart($params, $data);
            return ob_get_clean();
        }

        /**
         * Adminview render
         *
         * @param $params
         * @param $data
         * @param $countries
         * @param $currentCountry
         *
         * @return string Get current buffer contents and delete current output buffer
         */
        function render_adminview($params, $data)
        {
            ob_start();
            include(NOVEL_COVID19_PATH . 'templates/admin-view.php');
            return ob_get_clean();
        }

        /**
         * Chart render
         *
         * @param $params
         * @param $data
         *
         * @return string Get current buffer contents and delete current output buffer
         */
        function render_chart($params, $data)
        {
            ob_start();
            include(NOVEL_COVID19_PATH . 'templates/novel-covid19-chart.php');
            return ob_get_clean();
        }

        /**
         * Newsline render
         *
         * @param $params
         * @param $data
         *
         * @return string Get current buffer contents and delete current output buffer
         */
        function render_newsline($params, $data)
        {
            ob_start();
            include(NOVEL_COVID19_PATH . 'templates/novel-covid19-newsline.php');
            return ob_get_clean();
        }

        /**
         * Itam render
         *
         * @param $params
         * @param $data
         *
         * @return string Get current buffer contents and delete current output buffer
         */
        function render_item($params, $data)
        {
            ob_start();
            include(NOVEL_COVID19_PATH . 'templates/novel-covid19.php');
            return ob_get_clean();
        }

        /**
         * List render
         *
         * @param $params
         * @param $data
         *
         * @return string Get current buffer contents and delete current output buffer
         */
        function render_list($params, $data)
        {
            ob_start();
            include(NOVEL_COVID19_PATH . 'templates/novel-covid19-list.php');
            return ob_get_clean();
        }

        /**
         * Table render
         *
         * @param $params
         * @param $data
         *
         * @return string Get current buffer contents and delete current output buffer
         */
        function render_table($params, $data)
        {
            ob_start();
            include(NOVEL_COVID19_PATH . 'templates/novel-covid19-table.php');
            return ob_get_clean();
        }

        /**
         * Map render
         *
         * @param $params
         * @param $data
         *
         * @return string Get current buffer contents and delete current output buffer
         */
        function render_map($params, $data)
        {
            ob_start();
            include(NOVEL_COVID19_PATH . 'templates/novel-covid19-map.php');
            return ob_get_clean();
        }
    }

    new NovelCovid19();
}
