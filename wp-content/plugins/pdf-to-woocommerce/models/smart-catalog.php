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
            'public'                    => true,
            'show_in_admin_all_list'    => false,
            'show_in_admin_status_list' => true,
            'label_count'               => _n_noop('Em demarcação <span class="count">(%s)</span>', 'Em demarcação <span class="count">(%s)</span>')
        ));
    }
}
