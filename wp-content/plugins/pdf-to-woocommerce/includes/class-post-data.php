<?php

use CoffeeCode\DataLayer\DataLayer;

class Post_Data extends DataLayer
{
    
    /** @var array */
    public $post_meta;

    public function __construct()
    {

        parent::__construct("wp_posts", [
            "post_author",
            "post_date",
            "post_date_gmt",
            "post_content",
            "post_title",
            "post_excerpt",
            "post_status",
            "comment_status",
            "ping_status",
            "to_ping",
            "pinged",
            "post_modified",
            "post_modified_gmt",
            "post_content_filtered",
            "post_parent",
            "guid",
            "menu_order",
            "post_type",
            "post_mime_type",
            "comment_count",
        ], "ID", false);

    }

    public function fetch_post_meta($forceReload = false): Post_Data
    {
        if (!isset($this->post_meta) || $forceReload) {
            $post_meta = new Post_Meta_Data();
            $arr = $post_meta->find('post_id = '.$this->ID)->fetch(true);

            $this->post_meta = [];
            foreach($arr as $meta) {
                $this->post_meta[$meta->meta_key] = $meta->meta_value;
            }

        }
        return $this;
    }


}