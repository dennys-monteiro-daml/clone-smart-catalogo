<?php

class Smart_Catalog extends Custom_Post_Type_Base
{

    const META_KEY_NUMBER_OF_PAGES = 'number_of_pages';

    private static $instance;

    function __construct()
    {
        $this->name = 'Catálogos';
        $this->singular_name = 'Catálogo';
        $this->post_type = 'smart_catalog';
        $this->slug = 'catalogo';
        $this->icon_id = 'dashicons-pdf';
        $this->supports = ['title', 'editor', 'post-formats', 'page-attributes'];
        $this->metabox = 'add_post_type_metabox';
        self::$instance = $this;
        // $this->menu_position = 8;
    }

    public function add_post_type_metabox()
    {
        add_meta_box('upload_catalog', 'Catálogo', function () {
            include_once(plugin_dir_path(dirname(__FILE__)) . 'admin/views/form-upload-catalog.php');
        });
        add_meta_box('fabricante_metabox', 'Fabricante', function () {
            include_once(plugin_dir_path(dirname(__FILE__)) . 'admin/views/form-select-fabricante.php');
        });
    }

    public static function get_instance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new Smart_Catalog();
        }
        return self::$instance;
    }

    public function register_post_status()
    {
        register_post_status('uploaded', array(
            'label'                     => 'Em demarcação',
            'public'                    => false,
            'show_in_admin_all_list'    => false,
            'show_in_admin_status_list' => true,
            'label_count'               => _n_noop('Em demarcação <span class="count">(%s)</span>', 'Em demarcação <span class="count">(%s)</span>')
        ));
    }

    public function on_post_saved(int $post_id, WP_Post $post)
    {
        $is_revision = wp_is_post_revision($post_id);

        if ($is_revision)
            return;

        $number_of_pages = get_post_meta($post_id, Smart_Catalog::META_KEY_NUMBER_OF_PAGES, true);

        $fields = array('fabricante');

        foreach ($fields as $field_name) {
            if (isset($_POST[$field_name])) {
                $field_value = trim($_POST[$field_name]);
                if (!empty($field_value)) {
                    update_post_meta($post_id, $field_name, $field_value);
                } else {
                    delete_post_meta($post_id, $field_name);
                }
            }
        }

        // Do not change status if post is published OR uploaded
        if ($post->post_status === 'publish' || $post->post_status === 'uploaded' || $post->post_status === 'trash')
            return;

        if ($number_of_pages != '' && intval($number_of_pages) > 0) {
            wp_update_post(array(
                'ID' => $post_id,
                'post_status' => 'uploaded'
            ));
        }
    }
}
