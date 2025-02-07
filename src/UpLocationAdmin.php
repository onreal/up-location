<?php

namespace Up\UpLocation;

class UpLocationAdmin
{
    public function init()
    {
        add_action('admin_menu', array($this, 'addPluginPage'));
        add_action('admin_init', array($this, 'pageInit'));
    }

    public function addPluginPage()
    {
        add_menu_page(
            'UpLocation Settings',
            'UpLocation',
            'manage_options',
            'up-location-admin',
            array($this, 'createAdminPage'),
            'dashicons-location',
            20
        );
    }

    public function createAdminPage()
    {
        ?>
        <div class="wrap">
            <h1>UpLocation Settings</h1>
            <form method="post" action="options.php">
                <?php
                // This prints out all hidden setting fields
                settings_fields('up_location_option_group');
                do_settings_sections('up-location-admin');
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    public function pageInit()
    {
        register_setting('up_location_option_group', 'up_location_options');
        add_settings_field(
            'button_appearance',
            'Button Appearance',
            array($this, 'button_appearance_callback'),
            'up-location-admin',
            'up-location-admin'
        );

        add_settings_section(
            'up-location-admin',
            'Button Settings',
            array($this, 'print_section_info'),
            'up-location-admin'
        );

        add_settings_field(
            'button_color',
            'Button Color',
            array($this, 'button_color_callback'),
            'up-location-admin',
            'up-location-admin'
        );

        add_settings_field(
            'button_width',
            'Button Width',
            array($this, 'button_width_callback'),
            'up-location-admin',
            'up-location-admin'
        );

        add_settings_field(
            'button_height',
            'Button Height',
            array($this, 'button_height_callback'),
            'up-location-admin',
            'up-location-admin'
        );

        add_settings_field(
            'button_text',
            'Button Text',
            array($this, 'button_text_callback'),
            'up-location-admin',
            'up-location-admin'
        );

        add_settings_field(
            'button_text_color',
            'Button Text Color',
            array($this, 'button_text_color_callback'),
            'up-location-admin',
            'up-location-admin'
        );

        add_settings_field(
            'button_text_size',
            'Button Text Size',
            array($this, 'button_text_size_callback'),
            'up-location-admin',
            'up-location-admin'
        );

        add_settings_field(
            'button_x_position',
            'Button X Position (Left/Right)',
            array($this, 'button_x_position_callback'),
            'up-location-admin',
            'up-location-admin'
        );

        add_settings_field(
            'button_x_position_measure',
            'Button X Position Measure',
            array($this, 'button_x_position_measure_callback'),
            'up-location-admin',
            'up-location-admin'
        );

        add_settings_field(
            'button_y_position',
            'Button Y Position (Top/Bottom)',
            array($this, 'button_y_position_callback'),
            'up-location-admin',
            'up-location-admin'
        );

        add_settings_field(
            'button_y_position_measure',
            'Button Y Position Measure',
            array($this, 'button_y_position_measure_callback'),
            'up-location-admin',
            'up-location-admin'
        );

        add_settings_field(
            'button_layout',
            'Button Layout',
            array($this, 'button_layout_callback'),
            'up-location-admin',
            'up-location-admin'
        );

        add_settings_field(
            'button_padding',
            'Button Padding',
            array($this, 'button_padding_callback'),
            'up-location-admin',
            'up-location-admin'
        );

        add_settings_field(
            'button_margin',
            'Button Margin',
            array($this, 'button_margin_callback'),
            'up-location-admin',
            'up-location-admin'
        );

        add_settings_field(
            'button_border_color',
            'Button Border Color',
            array($this, 'button_border_color_callback'),
            'up-location-admin',
            'up-location-admin'
        );

        add_settings_field(
            'button_border_size',
            'Button Border Size',
            array($this, 'button_border_size_callback'),
            'up-location-admin',
            'up-location-admin'
        );

        add_settings_field(
            'button_border_radius',
            'Button Border Radius',
            array($this, 'button_border_radius_callback'),
            'up-location-admin',
            'up-location-admin'
        );

        add_settings_field(
            'button_icon',
            'Button Icon',
            array($this, 'button_icon_callback'),
            'up-location-admin',
            'up-location-admin'
        );
    }

    public function print_section_info()
    {
        echo 'Enter your settings below:';
    }

    public function button_appearance_callback()
    {
        $options = get_option('up_location_options');
        $checked = isset($options['button_appearance']) && $options['button_appearance'] == '1' ? 'checked' : '';
        echo '<input type="checkbox" id="button_appearance" name="up_location_options[button_appearance]" value="1" ' . $checked . ' />';
    }

    public function button_width_callback()
    {
        $options = get_option('up_location_options');
        printf(
            '<input type="text" id="button_width" name="up_location_options[button_width]" value="%s" />',
            isset($options['button_width']) ? esc_attr($options['button_width']) : ''
        );
    }

    public function button_color_callback()
    {
        $options = get_option('up_location_options');
        printf(
            '<input type="color" id="button_color" name="up_location_options[button_color]" value="%s" />',
            isset($options['button_color']) ? esc_attr($options['button_color']) : ''
        );
    }

    public function button_height_callback()
    {
        $options = get_option('up_location_options');
        printf(
            '<input type="text" id="button_height" name="up_location_options[button_height]" value="%s" />',
            isset($options['button_height']) ? esc_attr($options['button_height']) : ''
        );
    }

    public function button_text_callback()
    {
        $options = get_option('up_location_options');
        printf(
            '<input type="text" id="button_text" name="up_location_options[button_text]" value="%s" />',
            isset($options['button_text']) ? esc_attr($options['button_text']) : ''
        );
    }

    public function button_text_color_callback()
    {
        $options = get_option('up_location_options');
        printf(
            '<input type="color" id="button_text_color" name="up_location_options[button_text_color]" value="%s" />',
            isset($options['button_text_color']) ? esc_attr($options['button_text_color']) : ''
        );
    }

    public function button_text_size_callback()
    {
        $options = get_option('up_location_options');
        printf(
            '<input type="text" id="button_text_size" name="up_location_options[button_text_size]" value="%s" placeholder="eg. 16px(em, vw, %%)" />',
            isset($options['button_text_size']) ? esc_attr($options['button_text_size']) : ''
        );
    }

    public function button_padding_callback()
    {
        $options = get_option('up_location_options');
        printf(
            '<input type="text" id="button_padding" name="up_location_options[button_padding]" value="%s" placeholder="eg. 10px(em, vw, %%)" />',
            isset($options['button_padding']) ? esc_attr($options['button_padding']) : ''
        );
    }

    public function button_margin_callback()
    {
        $options = get_option('up_location_options');
        printf(
            '<input type="text" id="button_margin" name="up_location_options[button_margin]" value="%s" placeholder="eg. 10px(em, vw, %%)" />',
            isset($options['button_margin']) ? esc_attr($options['button_margin']) : ''
        );
    }

    public function button_border_color_callback()
    {
        $options = get_option('up_location_options');
        printf(
            '<input type="color" id="button_border_color" name="up_location_options[button_border_color]" value="%s" />',
            isset($options['button_border_color']) ? esc_attr($options['button_border_color']) : ''
        );
    }

    public function button_border_size_callback()
    {
        $options = get_option('up_location_options');
        printf(
            '<input type="text" id="button_border_size" name="up_location_options[button_border_size]" value="%s" placeholder="eg. 10px(em, vw, %%)" />',
            isset($options['button_border_size']) ? esc_attr($options['button_border_size']) : ''
        );
    }

    public function button_border_radius_callback()
    {
        $options = get_option('up_location_options');
        printf(
            '<input type="text" id="button_border_radius" name="up_location_options[button_border_radius]" value="%s" placeholder="eg. 10px(em, vw, %%)" />',
            isset($options['button_border_radius']) ? esc_attr($options['button_border_radius']) : ''
        );
    }

    public function button_x_position_callback()
    {
        $options = get_option('up_location_options');
        if (!is_array($options)) {
            $options = array();
        }
        printf(
            '<select id="button_x_position" name="up_location_options[button_x_position]">
        <option value="left" %s>Left</option>
        <option value="right" %s>Right</option>
    </select>',
            selected($options['button_x_position'], 'left', false),
            selected($options['button_x_position'], 'right', false)
        );
    }

    public function button_y_position_callback()
    {
        $options = get_option('up_location_options');
        if (!is_array($options)) {
            $options = array();
        }
        printf(
            '<select id="button_y_position" name="up_location_options[button_y_position]">
        <option value="top" %s>Top</option>
        <option value="bottom" %s>Bottom</option>
    </select>',
            selected($options['button_y_position'], 'top', false),
            selected($options['button_y_position'], 'bottom', false)
        );
    }

    public function button_x_position_measure_callback()
    {
        $options = get_option('up_location_options');
        printf(
            '<input type="text" id="button_x_position_measure" name="up_location_options[button_x_position_measure]" value="%s" placeholder="eg. 10px(em, vw, %%)" />',
            isset($options['button_x_position_measure']) ? esc_attr($options['button_x_position_measure']) : ''
        );
    }

    public function button_y_position_measure_callback()
    {
        $options = get_option('up_location_options');
        printf(
            '<input type="text" id="button_y_position_measure" name="up_location_options[button_y_position_measure]" value="%s" placeholder="eg. 10px(em, vw, %%)" />',
            isset($options['button_y_position_measure']) ? esc_attr($options['button_y_position_measure']) : ''
        );
    }

    public function button_layout_callback()
    {
        $options = get_option('up_location_options');
        if (!is_array($options)) {
            $options = array();
        }
        printf(
            '<select id="button_layout" name="up_location_options[button_layout]">
        <option value="absolute" %s>Absolute</option>
        <option value="fixed" %s>Fixed</option>
        <option value="relative" %s>Relative</option>
    </select>',
            selected($options['button_layout'], 'absolute', false),
            selected($options['button_layout'], 'fixed', false),
            selected($options['button_layout'], 'relative', false)
        );
    }

    public function button_icon_callback()
    {
        $options = get_option('up_location_options');
        $icon_url = isset($options['button_icon']) ? esc_attr($options['button_icon']) : '';
        echo '<input type="hidden" id="button_icon" name="up_location_options[button_icon]" value="' . $icon_url . '" />';
        if (!empty($icon_url)) {
            echo '<img id="button_icon_preview" src="' . $icon_url . '" style="width: 64px; height: 64px;" />';
        }
        echo '<input type="button" class="upload_image_button" value="Upload Image" />';
        ?>
        <script>
            jQuery(document).ready(function($){
                $('.upload_image_button').click(function(e) {
                    e.preventDefault();
                    var custom_uploader = wp.media({
                        title: 'Custom Image',
                        button: {
                            text: 'Upload Image'
                        },
                        multiple: false  // Set this to true to allow multiple files to be selected
                    })
                        .on('select', function() {
                            var attachment = custom_uploader.state().get('selection').first().toJSON();
                            $('#button_icon').val(attachment.url);
                            if ($('#button_icon_preview').length > 0) {
                                $('#button_icon_preview').attr('src', attachment.url);
                            } else {
                                $('<img id="button_icon_preview" src="' + attachment.url + '" style="width: 64px; height: 64px;" />').insertBefore('.upload_image_button');
                            }
                        })
                        .open();
                });
            });
        </script>
        <?php
    }
}