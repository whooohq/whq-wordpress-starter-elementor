<?php

namespace Essential_Addons_Elementor\Pro\Traits;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

trait Dynamic_Filterable_Gallery
{
    public static function get_dynamic_gallery_item_classes()
    {
        $classes = [];

        // collect post class
        $taxonomies = wp_get_object_terms( get_the_ID(), get_object_taxonomies( get_post_type( get_the_ID() ) ), array( "fields" => "slugs" ) );
        if ( $taxonomies ) {
            foreach ( $taxonomies as $taxonomy ) {
                $classes[] = $taxonomy;
            }
        }

        if ($categories = get_the_category(get_the_ID())) {
            foreach ($categories as $category) {
                $classes[] = $category->slug;
            }
        }

        if ($tags = get_the_tags()) {
            foreach ($tags as $tag) {
                $classes[] = $tag->slug;
            }
        }

        if ($product_cats = get_the_terms(get_the_ID(), 'product_cat')) {
            foreach ($product_cats as $cat) {
                if(is_object($cat)) {
                    $classes[] = $cat->slug;
                }
            }
        }

        return $classes;
    }
}
