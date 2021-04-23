<?php

use Essential_Addons_Elementor\Pro\Classes\Helper;
?>
<div class="eael-learn-dash-course eael-course-layout-2">
    <div class="eael-learn-dash-course-inner">

        <?php $this->_generate_tags($tags); ?>

        <?php if($image && $settings['show_thumbnail'] === 'true') : ?>
        <a href="<?php echo esc_url(get_permalink($course->ID)); ?>" class="eael-learn-dash-course-thumbnail">
            <img src="<?php echo esc_url($image[0]); ?>" alt="<?php echo $image_alt; ?>" />
        </a>
        <?php endif; ?>

        <div class="eael-learn-deash-course-content-card">
            <<?php echo Helper::eael_pro_validate_html_tag($settings['title_tag']); ?> class="course-card-title">
                <a href="<?php echo esc_url(get_permalink($course->ID)); ?>"><?php echo $course->post_title; ?></a>
            </<?php echo Helper::eael_pro_validate_html_tag($settings['title_tag']); ?>>

            <?php if($settings['show_course_meta'] === 'true') : ?>
            <div class="eael-learn-dash-course-meta-card">
                <?php if($settings['show_price'] == 'true') : ?>
                    <span class="price"><?php echo $legacy_meta['sfwd-courses_course_price'] ? $legacy_meta['sfwd-courses_course_price'] : __( 'Free', 'essential-addons-elementor' ); ?></span>
                <?php endif; ?>
                
                <?php if($access_list) : ?><span class="enrolled-count"><i class="far fa-user"></i><?php echo $access_list; ?></span><?php endif; ?>
                
                <?php if( $settings['show_date'] === 'true' ) : ?>
                    <span class="course-date"><i aria-hidden="true" class="far fa-clock"></i><?php echo get_the_date('j M y', $course->ID); ?></span>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <?php if($settings['show_content'] === 'true' && !empty($short_desc)) : ?> 
            <div class="eael-learn-dash-course-short-desc">
                <?php echo wpautop($this->get_controlled_short_desc($short_desc,$excerpt_length)); ?>
            </div><?php endif; ?>

            <?php if($settings['show_button'] === 'true') : ?>
            <a href="<?php echo esc_url(get_permalink($course->ID)); ?>" class="eael-course-button"><?php echo empty($button_text) ? __( 'See More', 'essential-addons-elementor' ) : $button_text; ?></a>
            <?php endif; ?>

            <?php
                if($settings['show_progress_bar'] === 'true') {
                    echo do_shortcode( '[learndash_course_progress course_id="' . $course->ID . '" user_id="' . get_current_user_id() . '"]' );
                }
            ?>
        </div>

    </div>
</div>
