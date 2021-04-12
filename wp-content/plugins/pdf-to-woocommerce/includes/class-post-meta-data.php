<?php

use CoffeeCode\DataLayer\DataLayer;

class Post_Meta_Data extends DataLayer
{
    function __construct()
    {
        parent::__construct("wp_postmeta", [
            "post_id",
            "meta_key",
            "meta_value"
        ], "meta_id", false);    
    }
    
    // public function post(): Post_Meta_Data
    // {
    //     $this->post = (new Post_Data())->findById($this->post_id)->data();
    //     return $this;
    // }

    /**
     * Busca os meta dados de um array de posts e injeta os valores no array
     *
     * @param Post_Data[] $posts
     *
     * @return void
     */
    public static function attach_meta(array &$posts)
    {
        $ids = array_map(function(Post_Data $post) {
            return $post->ID;
        }, $posts);

        $meta_data = (new Post_Meta_Data())->find('post_id IN ('. implode(", ", $ids) .')')->fetch(true);
        
        if (is_array($meta_data)) {

            for ($i = 0; $i < sizeof($posts); $i++) {

                $_ENV['temp_ID'] = $posts[$i]->ID;

                $post_meta = array_filter($meta_data, function (Post_Meta_Data $item) {
                    return $item->post_id === $_ENV['temp_ID'];
                });

                unset($_ENV['temp_ID']);

                $posts[$i]->post_meta = array();

                foreach($post_meta as $meta) {
                    $posts[$i]->post_meta[$meta->meta_key] = $meta->meta_value;
                }

            }
            
        }

    }

}