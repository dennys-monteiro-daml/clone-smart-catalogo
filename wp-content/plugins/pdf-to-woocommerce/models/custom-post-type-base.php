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
            'capability_type' => 'post',
            'rewrite'         => array('slug' => $this->slug), // Permalinks format
            'menu_position'   => $this->menu_position,
            'menu_icon'       => (version_compare($GLOBALS['wp_version'], '3.8', '>=')) ? $this->icon_id : false,
            'has_archive'     => true,
            'publicly_queryable'  => false,
            'supports'        => $this->supports,
            //'supports' => array('title','editor','thumbnail','comments', 'excerpt', 'custom-fields', 'revisions', 'trackbacks')
        );
        if ($this->metabox !== '') {
            $args['register_meta_box_cb'] = array($this, $this->metabox);
        }
        register_post_type($this->post_type, $args);
    }

    /**
     * Wrapper para a função find do DataLayer pensado para os custom post types
     *
     * @param string $find
     * @param string $params
     * @param boolean $all
     * @param boolean $with_meta
     * @param string $order
     * @param integer $limit
     * @param integer $offset
     *
     * @return Post_Data[]|Post_Data|null
     */
    public function find_and_fetch(
        string $find = null,
        string $params = null,
        bool $all = false,
        bool $with_meta = true,
        string $order = null,
        int $limit = null,
        int $offset = null
    ) {

        $posts = new Post_Data();

        $query = "post_type = '" . $this->post_type .  "'";

        if (!is_null($find)) {
            $query = "$query AND ($find)";
        }

        $post_array = $posts->find($query, $params);

        if (!is_null($limit)) {
            $post_array = $post_array->limit($limit);
            if (!is_null($offset)) {
                $post_array = $post_array->offset($offset);
            }
        }

        if (!is_null($order)) {
            $post_array = $post_array->order($order);
        }

        $post_array = $post_array->fetch($all);

        if (is_array($post_array)) {

            if ($with_meta) {
                Post_Meta_Data::attach_meta($post_array);
            }

            return $post_array;
        }

        if (!is_null($post_array) && $with_meta) {
            return $post_array->fetch_post_meta();
        }

        return $post_array;
    }
}
