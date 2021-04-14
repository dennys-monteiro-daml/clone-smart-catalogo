<?php

function xcompile_post_type_labels($singular = 'Post', $plural = 'Posts')
{

    return [
        'name' => $plural,
        'singular_name' => $singular,
        'add_new_item' => "Adicionar novo $singular",
        'edit_item' => "Editar $singular",
        'view_item' => "Visualizar $singular",
        'view_items' => "Visualizar $plural",
        'search_items' => "Buscar $plural",
        'not_found' => "Não há nenhum $singular.",
        'not_found_in_trash' => "Nenhum $singular encontrado na lixeira",
        // 'parent_item_colon' => "Parent $singular",
        'all_items' => "Todos os $plural",
        'archives' => "$singular arquivado",
        'attributes' => "$singular atributuos",
        'insert_into_item' => "Inserir no $singular",
        'uploaded_to_this_item' => "Upload ao $singular realizado",
    ];
}

function get_woocommerce_categories_selector(string $id, string $class = '')
{
    $orderby = 'name';
    $order = 'asc';
    $hide_empty = false;
    $cat_args = array(
        'orderby'    => $orderby,
        'order'      => $order,
        'hide_empty' => $hide_empty,
    );

    $product_categories = get_terms('product_cat', $cat_args);

    if (!empty($product_categories)) {
        // print_r($product_categories);
        echo '<select id="' . $id . '" name="' . $id . '" class="' . $class . '">';
        foreach ($product_categories as $category) {
            echo '<option value="' . $category->term_id . '">';
            echo $category->name;
            echo '</option>';
        }
        echo '</select>';
    }
}
