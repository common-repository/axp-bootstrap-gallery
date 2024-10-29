<?php /*

**************************************************************************

Plugin Name:  AXP Bootstrap Gallery
Plugin URI:   https://github.com/axp-dev/axp-bootstrap-gallery
Description:  Adds the markup Bootstrap for gallery
Version:      1.0.0
Author:       Alexander Pushkarev
Author URI:   https://github.com/axp-dev
Text Domain:  axp-bootstrap-gallery
License:      GPLv2 or later


This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
**************************************************************************/

class AXP_Bootstrap_Gallery {
    public $menu_slug;
    public $fields;

    function __construct() {
        $this->menu_slug    = 'axp-bootstrap-gallery';
        $this->fields       = 'axp-bootstrap-gallery-fields';

        register_activation_hook( __FILE__, array( &$this, 'install' ) );

        add_action( 'plugins_loaded',   array( &$this, 'init_textdomain' ));
        add_action( 'admin_menu',       array( &$this, 'register_menu' ) );
        add_filter( 'post_gallery',     array( &$this, 'render_post_gallery' ), 10, 2 );
        add_action( 'admin_init',       array( &$this, 'register_settings' )  );
    }

    static function install() {
        update_option(
            'axp-bootstrap-gallery-fields',
            array(
                'gallery-class'     => 'gallery row',
                'item-tag'          => 'div',
                'item-class'        => 'gallery-item',
                'caption-tag'       => 'span',
                'caption-class'     => 'gallery-caption',
                'link-class'        => '',
                'link-parameters'   => '',
                'img-class'         => 'img-responsive',
                'col-1'             => 'col-xs-12',
                'col-2'             => 'col-sm-6 col-md-6',
                'col-3'             => 'col-sm-6 col-md-4',
                'col-4'             => 'col-sm-6 col-md-3',
                'col-5'             => 'col-sm-6 col-md-2',
                'col-6'             => 'col-sm-6 col-md-2',
                'col-7'             => 'col-sm-6 col-md-2',
                'col-8'             => 'col-sm-6 col-md-2',
                'col-9'             => 'col-sm-6 col-md-2',
            )
        );
    }

    public function init_textdomain() {
        load_plugin_textdomain( 'axp-bootstrap-gallery', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
    }

    public function register_menu() {
        add_options_page(
            __('Bootstrap Gallery Settings', 'axp-bootstrap-gallery'),
            __('Bootstrap Gallery', 'axp-bootstrap-gallery'),
            'manage_options',
            $this->menu_slug,
            array(&$this, 'render_page_settings')
        );
    }

    public function register_settings() {
        register_setting( $this->fields, $this->fields );

        add_settings_section(
            'gallery_settings',
            __('Gallery settings', 'axp-bootstrap-gallery'),
            null,
            $this->menu_slug
        );

        add_settings_section(
            'grid_settings',
            __('Grid settings', 'axp-bootstrap-gallery'),
            null,
            $this->menu_slug
        );

        add_settings_field(
            'gallery_class',
            __('Gallery class', 'axp-bootstrap-gallery'),
            array( $this, 'render_settings_fields' ),
            $this->menu_slug, 'gallery_settings',
            array(
                'type'      => 'text',
                'id'        => 'gallery-class',
                'desc'      => __('Wrapper class. Default bootstrap: <code>row</code>', 'axp-bootstrap-gallery')
            )
        );

        add_settings_field(
            'item_tag',
            __('Item tag', 'axp-bootstrap-gallery'),
            array( $this, 'render_settings_fields' ),
            $this->menu_slug, 'gallery_settings',
            array(
                'type'      => 'text',
                'id'        => 'item-tag',
                'desc'      => ''
            )
        );

        add_settings_field(
            'item_class',
            __('Item class', 'axp-bootstrap-gallery'),
            array( $this, 'render_settings_fields' ),
            $this->menu_slug, 'gallery_settings',
            array(
                'type'      => 'text',
                'id'        => 'item-class',
                'desc'      => ''
            )
        );

        add_settings_field(
            'caption_tag',
            __('Caption tag', 'axp-bootstrap-gallery'),
            array( $this, 'render_settings_fields' ),
            $this->menu_slug, 'gallery_settings',
            array(
                'type'      => 'text',
                'id'        => 'caption-tag',
                'desc'      => ''
            )
        );

        add_settings_field(
            'caption_class',
            __('Caption class', 'axp-bootstrap-gallery'),
            array( $this, 'render_settings_fields' ),
            $this->menu_slug, 'gallery_settings',
            array(
                'type'      => 'text',
                'id'        => 'caption-class',
                'desc'      => ''
            )
        );

        add_settings_field(
            'link_class',
            __('Link class', 'axp-bootstrap-gallery'),
            array( $this, 'render_settings_fields' ),
            $this->menu_slug, 'gallery_settings',
            array(
                'type'      => 'text',
                'id'        => 'link-class',
                'desc'      => ''
            )
        );

        add_settings_field(
            'link_parameters',
            __('Link custom parameters', 'axp-bootstrap-gallery'),
            array( $this, 'render_settings_fields' ),
            $this->menu_slug, 'gallery_settings',
            array(
                'type'      => 'text',
                'id'        => 'link-parameters',
                'desc'      => __('Example: <code>rel="prettyPhoto[gallery]</code>', 'axp-bootstrap-gallery')
            )
        );

        add_settings_field(
            'img_class',
            __('Image class', 'axp-bootstrap-gallery'),
            array( $this, 'render_settings_fields' ),
            $this->menu_slug, 'gallery_settings',
            array(
                'type'      => 'text',
                'id'        => 'img-class',
                'desc'      => __('Default bootstrap: <code>img-responsive</code>', 'axp-bootstrap-gallery')
            )
        );

        add_settings_field(
            'col_1',
            __('Column 1', 'axp-bootstrap-gallery'),
            array( $this, 'render_settings_fields' ),
            $this->menu_slug, 'grid_settings',
            array(
                'type'      => 'text',
                'id'        => 'col-1',
                'desc'      => ''
            )
        );

        add_settings_field(
            'col_2',
            __('Column 2', 'axp-bootstrap-gallery'),
            array( $this, 'render_settings_fields' ),
            $this->menu_slug, 'grid_settings',
            array(
                'type'      => 'text',
                'id'        => 'col-2',
                'desc'      => ''
            )
        );

        add_settings_field(
            'col_3',
            __('Column 3', 'axp-bootstrap-gallery'),
            array( $this, 'render_settings_fields' ),
            $this->menu_slug, 'grid_settings',
            array(
                'type'      => 'text',
                'id'        => 'col-3',
                'desc'      => ''
            )
        );

        add_settings_field(
            'col_4',
            __('Column 4', 'axp-bootstrap-gallery'),
            array( $this, 'render_settings_fields' ),
            $this->menu_slug, 'grid_settings',
            array(
                'type'      => 'text',
                'id'        => 'col-4',
                'desc'      => ''
            )
        );

        add_settings_field(
            'col_5',
            __('Column 5', 'axp-bootstrap-gallery'),
            array( $this, 'render_settings_fields' ),
            $this->menu_slug, 'grid_settings',
            array(
                'type'      => 'text',
                'id'        => 'col-5',
                'desc'      => ''
            )
        );

        add_settings_field(
            'col_6',
            __('Column 6', 'axp-bootstrap-gallery'),
            array( $this, 'render_settings_fields' ),
            $this->menu_slug, 'grid_settings',
            array(
                'type'      => 'text',
                'id'        => 'col-6',
                'desc'      => ''
            )
        );

        add_settings_field(
            'col_7',
            __('Column 7', 'axp-bootstrap-gallery'),
            array( $this, 'render_settings_fields' ),
            $this->menu_slug, 'grid_settings',
            array(
                'type'      => 'text',
                'id'        => 'col-7',
                'desc'      => ''
            )
        );

        add_settings_field(
            'col_8',
            __('Column 8', 'axp-bootstrap-gallery'),
            array( $this, 'render_settings_fields' ),
            $this->menu_slug, 'grid_settings',
            array(
                'type'      => 'text',
                'id'        => 'col-8',
                'desc'      => ''
            )
        );

        add_settings_field(
            'col_9',
            __('Column 9', 'axp-bootstrap-gallery'),
            array( $this, 'render_settings_fields' ),
            $this->menu_slug, 'grid_settings',
            array(
                'type'      => 'text',
                'id'        => 'col-9',
                'desc'      => ''
            )
        );
    }

    public function apx_get_filed( $name ) {
        return get_option( $this->fields )[$name];
    }

    public function render_settings_fields( $arguments ) {
        extract( $arguments );

        $option_name = $this->fields;
        $o = get_option( $option_name );

        switch ( $type ) {
            case 'text':
                $o[$id] = esc_attr( stripslashes($o[$id]) );
                echo "<input class='regular-text' type='text' id='$id' name='" . $option_name . "[$id]' value='$o[$id]' />";
                echo ($desc != '') ? "<p class='description'>$desc</p>" : "";
                break;
            case 'checkbox':
                $checked = ($o[$id] == 'on') ? " checked='checked'" :  '';
                echo "<label><input class='axp-browser-settings-checkbox' type='checkbox' id='$id' name='" . $option_name . "[$id]' $checked /> ";
                echo "</label>";
                echo ($desc != '') ? "<p class='description'>$desc</p>" : "";
                break;
        }
    }

    public function render_post_gallery( $output, $attr ) {
        global $post, $wp_locale;

        static $instance = 0;
        $instance++;

        // We're trusting author input, so let's at least make sure it looks like a valid orderby statement
        if ( isset( $attr['orderby'] ) ) {
            $attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
            if ( !$attr['orderby'] )
                unset( $attr['orderby'] );
        }

        extract(shortcode_atts(array(
            'order'      => 'ASC',
            'orderby'    => 'menu_order ID',
            'id'         => $post->ID,
            'columns'    => 3,
            'size'       => 'thumbnail',
            'include'    => '',
            'exclude'    => ''
        ), $attr));

        $id = intval($id);
        if ( 'RAND' == $order )
            $orderby = 'none';

        if ( !empty($include) ) {
            $include = preg_replace( '/[^0-9,]+/', '', $include );
            $_attachments = get_posts( array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );

            $attachments = array();
            foreach ( $_attachments as $key => $val ) {
                $attachments[$val->ID] = $_attachments[$key];
            }
        } elseif ( !empty($exclude) ) {
            $exclude = preg_replace( '/[^0-9,]+/', '', $exclude );
            $attachments = get_children( array('post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
        } else {
            $attachments = get_children( array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
        }

        if ( empty($attachments) )
            return '';

        if ( is_feed() ) {
            $output = "\n";
            foreach ( $attachments as $att_id => $attachment )
                $output .= wp_get_attachment_link($att_id, $size, true) . "\n";
            return $output;
        }

        $itemtag = tag_escape( $this->apx_get_filed('item-tag') );
        $captiontag = tag_escape( $this->apx_get_filed('caption-tag') );
        $columns = intval($columns);
        $selector = "gallery-{$instance}";

        $output = "<div id=\"{$selector}\" class=\"{$this->apx_get_filed('gallery-class')}\">";

        foreach ( $attachments as $id => $attachment ) {
            $image_thumb = wp_get_attachment_image_src($id, $size)[0];

            if ( isset($attr['link']) && 'file' == $attr['link'] ) {
                $image_full = wp_get_attachment_image_src($id, 'full')[0];
                $link = "<a href=\"{$image_full}\" class=\"{$this->apx_get_filed('link-class')}\" {$this->apx_get_filed('link-parameters')}><img src=\"{$image_thumb}\" class=\"{$this->apx_get_filed('img-class')}\" alt=\"\"></a>";
            } elseif ( isset($attr['link']) && 'none' == $attr['link'] ) {
                $link = "<img src=\"{$image_thumb}\" class=\"{$this->apx_get_filed('img-class')}\" alt=\"\">";
            } else {
                $attachment_page = get_attachment_link( $id );
                $link = "<a href=\"{$attachment_page}\" class=\"{$this->apx_get_filed('link-class')}\" {$this->apx_get_filed('link-parameters')}><img src=\"{$image_thumb}\" class=\"{$this->apx_get_filed('img-class')}\" alt=\"\"></a>";
            }

            $output .= "<{$itemtag} class=\"{$this->apx_get_filed('col-'.$columns)} {$this->apx_get_filed('item-class')}\">\n";
            $output .= $link;
            if ( $captiontag && trim($attachment->post_excerpt) ) {
                $output .= "<{$captiontag} class=\"{$this->apx_get_filed('caption-class')}\">" .  wptexturize($attachment->post_excerpt) . "</{$captiontag}>";
            }
            $output .= "</{$itemtag}>";
        }

        $output .= "</div>\n";

        return $output;
    }

    public function render_page_settings() {
        ?>
        <div class="wrap">
            <h2><?php _e('Bootstrap Gallery Settings', 'axp-bootstrap-gallery'); ?></h2>
            <div class="card pressthis">
                <p><?php _e('The plugin is absolutely free. You can support the developer.', 'axp-bootstrap-gallery') ?></p>
                <p><a class="button" href="https://paypal.me/axpdev" target="_blank"><?php _e('Donate', 'axp-bootstrap-gallery'); ?></a></p>
                <p><a class="button" href="mailto:axp-dev@yandex.com"><?php _e('Contact the author', 'axp-bootstrap-gallery'); ?></a></p>
                <p><a class="button" href="<?php echo get_home_url( null, 'wp-admin/plugin-install.php?s=axpdev&tab=search&type=term' ); ?>" target="_blank"><?php _e('Other plugins by author', 'axp-bootstrap-gallery'); ?></a></p>
            </div>

            <div class="card pressthis">
            <form method="POST" enctype="multipart/form-data" action="options.php">
                <?php settings_fields( $this->fields ); ?>
                <?php do_settings_sections( $this->menu_slug ); ?>
                <?php submit_button(); ?>
            </form>
            </div>
        </div>
        <?php
    }

}

$AXP_Bootstrap_Gallery = new AXP_Bootstrap_Gallery();