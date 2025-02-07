<?php
namespace Up\UpLocation;

if (!defined('ABSPATH')) {
    exit;
}

class UpLocation
{
    protected static $instance = null;

    public function __construct()
    {
        $this->init();
    }

    public function init()
    {
        $admin = new UpLocationAdmin();
        $admin->init();
        add_action('wp_enqueue_scripts', array($this, 'enqueueScripts'));
        add_action('init', array($this, 'registerShortcodes'));
        add_action('wp_footer', array($this, 'displayButton'));

        add_action('wp_ajax_store_user_location', array($this, 'saveUserLocation'));
        add_action('wp_ajax_nopriv_store_user_location', array($this, 'saveUserLocation'));
        add_shortcode('up_location_display_button', array($this, 'forceDisplayButton'));
    }

    public static function instance(): UpLocation
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function enqueueScripts() {
        wp_register_script(
            'up-location',
            plugins_url('../scripts/up-location.js', __FILE__),
            array('jquery'),
            '1.1bsxsq',
            true
        );

        $script_data_array = array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('store_user_location_nonce') // Create a nonce
        );
        wp_localize_script('up-location', 'up_location_ajax_object', $script_data_array);

        wp_enqueue_script('up-location');

        wp_enqueue_script('heartbeat');
        add_filter('heartbeat_send', array($this, 'send_user_location'), 10, 2);
    }

    public function send_user_location($response, $data)
    {
        if (!isset($data['up_location'])) {
            return $response;
        }

        $latitude = $data['up_location']['latitude'];
        $longitude = $data['up_location']['longitude'];

        $this->setLocationCookie($latitude, $longitude);

        $user_id = get_current_user_id();

        if (!$user_id) {
            return $response;
        }

        $latitude = get_user_meta($user_id, 'up_location_latitude', true);
        $longitude = get_user_meta($user_id, 'up_location_longitude', true);
        $response['up_location'] = array(
            'latitude' => $latitude,
            'longitude' => $longitude
        );
        return $response;
    }

    private function setLocationCookie($latitude, $longitude)
    {
        $expiration = time() + (365 * 24 * 60 * 60);

        setcookie('up_location_latitude', $latitude, $expiration, COOKIEPATH, COOKIE_DOMAIN);
        setcookie('up_location_longitude', $longitude, $expiration, COOKIEPATH, COOKIE_DOMAIN);

        $_COOKIE['up_location_latitude'] = $latitude;
        $_COOKIE['up_location_longitude'] = $longitude;
    }

    public function saveUserLocation()
    {
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'store_user_location_nonce')) {
            wp_die('Nonce check failed.', '', array('response' => 403));
        }

        $user_id = get_current_user_id();

        if (isset($_POST['denied'])) {
            if ($user_id) update_user_meta($user_id, 'up_location_denied', '1');
            die();
        }

        $latitude = $_POST['latitude'];
        $longitude = $_POST['longitude'];

        $this->setLocationCookie($latitude, $longitude);

        if ($latitude && $longitude && $user_id) {
            update_user_meta($user_id, 'up_location_latitude', $latitude);
            update_user_meta($user_id, 'up_location_longitude', $longitude);
        }

        die();
    }

    public function registerShortcodes()
    {
        add_shortcode('up_location', array($this, 'upLocationShortcode'));
    }

    public function upLocationShortcode(array $att): string
    {
        if (is_user_logged_in()) {
            $user_id = get_current_user_id();
            $latitude = get_user_meta($user_id, 'up_location_latitude', true);
            $longitude = get_user_meta($user_id, 'up_location_longitude', true);
            return "Latitude: $latitude, Longitude: $longitude";
        }
        return '';
    }

    public function displayButton(bool $force_display = false)
    {
        $options = get_option('up_location_options');

        if (!$force_display && (!isset($options['button_appearance']) || $options['button_appearance'] !== '1')) {
            return;
        }

        $color = isset($options['button_color']) ? esc_attr($options['button_color']) : '';
        $width = isset($options['button_width']) ? esc_attr($options['button_width']) : '';
        $height = isset($options['button_height']) ? esc_attr($options['button_height']) : '';
        $text = isset($options['button_text']) ? esc_attr($options['button_text']) : '';
        $text_color = isset($options['button_text_color']) ? esc_attr($options['button_text_color']) : '';
        $text_size = isset($options['button_text_size']) ? esc_attr($options['button_text_size']) : '12px';
        $padding = isset($options['button_padding']) ? esc_attr($options['button_padding']) : '';
        $margin = isset($options['button_margin']) ? esc_attr($options['button_margin']) : '';
        $border_color = isset($options['button_border_color']) ? esc_attr($options['button_border_color']) : '';
        $border_size = isset($options['button_border_size']) ? esc_attr($options['button_border_size']) : '';
        $border_radius = isset($options['button_border_radius']) ? esc_attr($options['button_border_radius']) : '';
        $icon = isset($options['button_icon']) ? esc_attr($options['button_icon']) : '';
        $layout = isset($options['button_layout']) ? esc_attr($options['button_layout']) : 'absolute';

        $x_position = isset($options['button_x_position']) ? esc_attr($options['button_x_position']) : 'left';
        $y_position = isset($options['button_y_position']) ? esc_attr($options['button_y_position']) : 'top';

        $x_position_measure = isset($options['button_x_position_measure']) ? esc_attr($options['button_x_position_measure']) : '0px';
        $y_position_measure = isset($options['button_y_position_measure']) ? esc_attr($options['button_y_position_measure']) : '0px';

        $style = "z-index: 10;
        background-color: $color;
        width: $width; height:
        $height;
        position: $layout;
        color: $text_color;
        font-size: $text_size;
        padding: $padding;
        margin: $margin;
        border: $border_size solid $border_color;
        border-radius: $border_radius;
        cursor: pointer;";

        if ($layout !== 'relative') {
            switch ($x_position) {
                case 'left':
                    $style .= " left: $x_position_measure;";
                    break;
                case 'right':
                    $style .= " right: $x_position_measure;";
                    break;
            }
        }

        if ($layout !== 'relative') {
            switch ($y_position) {
                case 'top':
                    $style .= " top: $y_position_measure;";
                    break;
                case 'bottom':
                    $style .= " bottom: $y_position_measure;";
                    break;
            }
        }

        if (!empty($icon)) {
            echo "<button id='up-location-button' style='$style'><img src='$icon' alt='$text' /></button>";
        } else {
            echo "<button id='up-location-button' style='$style'>$text</button>";
        }
    }

    public function forceDisplayButton()
    {
        ob_start();
        $this->displayButton(true);
        return ob_get_clean();
    }
}

UpLocation::instance();