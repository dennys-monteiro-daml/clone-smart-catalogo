<?php

// use CoffeeCode\DataLayer\DataLayer;

class Custom_Post_Type_Base
{

    public $name;
    public $singular_name;
    public $post_type;
    public $slug;
    public $icon_id;
    public $menu_position = 10;
    public $supports = ['title', 'thumbnail', 'custom-fields'];
    public $metabox = '';

    /**
     * Função para gerar o register_post_type no WP de acordo com o Custom Post Type
     *
     * @return void
     */
    public function register_post_type()
    {

        // $labels = array(
        //     'name' => _x($this->name, 'post type general name'),
        //     'singular_name' => _x($this->singular_name, 'post type singular name')
        // );

        $args = array(
            'labels' => xcompile_post_type_labels($this->singular_name, $this->name),
            'public'          => true,
            'capability_type' => 'page',
            'rewrite'         => array('slug' => $this->slug), // Permalinks format
            'menu_position'   => $this->menu_position,
            'menu_icon'       => (version_compare($GLOBALS['wp_version'], '3.8', '>=')) ? $this->icon_id : false,
            'has_archive'     => true,
            'publicly_queryable'  => true,
            'exclude_from_search' => false,
            'show_ui' => true,
            'show_in_menu' => true,
            'supports'        => $this->supports,
            //'supports' => array('title','editor','thumbnail','comments', 'excerpt', 'custom-fields', 'revisions', 'trackbacks')
        );
        if ($this->metabox !== '') {
            $args['register_meta_box_cb'] = array($this, $this->metabox);
        }
        register_post_type($this->post_type, $args);
    }

    
}
