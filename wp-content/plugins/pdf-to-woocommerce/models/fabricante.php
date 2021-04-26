<?php

class Fabricante extends Custom_Post_Type_Base
{

    private static $instance;

    function __construct()
    {
        $this->name = 'Fabricantes';
        $this->singular_name = 'Fabricante';
        $this->post_type = 'fabricante';
        $this->slug = 'fabricante';
        $this->icon_id = 'dashicons-building';
        $this->supports = ['title', 'thumbnail', 'custom-fields', 'excerpt'];
        // $this->metabox = 'add_post_type_metabox';
        Fabricante::$instance = $this;
        // $this->menu_position = 8;
    }


    public static function get_instance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new Fabricante();
        }
        return self::$instance;
    }

    public static function get_select(string $id, string $class = '')
    {
        $post_id = get_the_ID();
        $selected_id = '';
        if (is_int($post_id) && $post_id > 0) {
            $selected_id = intval(get_post_meta($post_id, 'fabricante', true));
        }

        $post_data = new Post_Data();

        /** @var Post_Data[] */
        $posts = $post_data->find("post_type = 'fabricante' AND post_status = 'publish'")->fetch(true);

        $options = '<option value=""> -- Selecione -- </option>';
        foreach ($posts as $post) {
            $value = $post->ID;
            $label = $post->post_title;
            $selected = '';
            if ($value == $selected_id) $selected = 'selected';
            $options .= "<option value='$value' $selected>$label</option>";
        }

        // $post = $posts[0]->fetch_post_meta();
        // echo "<pre>";
        // print_r($post->post_meta);
        // echo "</pre>";

        return "<select id='$id' name='$id' class='$class'>$options</select>";
    }
}
