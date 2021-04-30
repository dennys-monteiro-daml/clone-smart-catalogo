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

        // $post_data = new Post_Data();
        $fabricantes_query = new WP_Query(array(
            'post_type' => 'fabricante',
            'posts_per_page' => -1,
        ));

        $options = '<option value=""> -- Selecione -- </option>';

        if ($fabricantes_query->have_posts()) {
            // echo '<p>Produtos no catálogo: </p>';
            // echo '<ul>';
            while ($fabricantes_query->have_posts()) {
                $fabricantes_query->the_post();
                $fab_id = $fabricantes_query->post->ID;
                $value = $fab_id;
                $label = $fabricantes_query->post->post_title;
                $selected = '';
                if ($value == $selected_id) $selected = 'selected';
                $options .= "<option value='$value' $selected>$label</option>";
            }
        }
        wp_reset_postdata();
        // /** @var Post_Data[] */
        // $posts = $post_data->find("post_type = 'fabricante' AND post_status = 'publish'")->fetch(true);

        // foreach ($posts as $post) {
        //     $value = $post->ID;
        //     $label = $post->post_title;
        //     $selected = '';
        //     if ($value == $selected_id) $selected = 'selected';
        //     $options .= "<option value='$value' $selected>$label</option>";
        // }

        // $post = $posts[0]->fetch_post_meta();
        // echo "<pre>";
        // print_r($post->post_meta);
        // echo "</pre>";

        return "<select id='$id' name='$id' class='$class'>$options</select>";
    }

    public function register_taxonomies()
    {
        // Add new taxonomy, NOT hierarchical (like tags)
        $labels = array(
            'name' => _x('Tags', 'taxonomy general name'),
            'singular_name' => _x('Tag', 'taxonomy singular name'),
            'search_items' =>  __('Buscar Tags'),
            'popular_items' => __('Tags Populares'),
            'all_items' => __('Todas as Tags'),
            'parent_item' => null,
            'parent_item_colon' => null,
            'edit_item' => __('Editar Tag'),
            'update_item' => __('Atualizar Tag'),
            'add_new_item' => __('Adicionar nova Tag'),
            'new_item_name' => __('Nova tag'),
            'separate_items_with_commas' => __('Separe tags por vírgulas'),
            'add_or_remove_items' => __('Adicionar ou remover tags'),
            'choose_from_most_used' => __('Escolher entre as tags mais utilizadas'),
            'menu_name' => __('Tags'),
        );

        register_taxonomy('tag', $this->post_type, array(
            'hierarchical' => false,
            'labels' => $labels,
            'show_ui' => true,
            'update_count_callback' => '_update_post_term_count',
            'query_var' => true,
            'rewrite' => array('slug' => 'tag'),
        ));
    }
}
