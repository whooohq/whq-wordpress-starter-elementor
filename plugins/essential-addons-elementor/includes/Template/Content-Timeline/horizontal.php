<?php

/**
 * Template Name: Horizontal
 *
 */

use Essential_Addons_Elementor\Pro\Classes\Helper;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

$horizontal_layout = ! empty( $settings['content_timeline_layout_horizontal'] ) ? esc_html( $settings['content_timeline_layout_horizontal'] ) : esc_html( 'center' );
$query = isset( $query ) ? $query : new WP_Query();
?>
<div class="eael-horizontal-timeline eael-horizontal-timeline--layout-<?php echo esc_attr( $horizontal_layout ) ?> eael-horizontal-timeline--align-left eael-horizontal-timeline--scroll-bar">
    <div class="eael-horizontal-timeline-track">
        <?php 
        switch ( $horizontal_layout ) {
            case 'top':
                ?>

                <div class="eael-horizontal-timeline-list eael-horizontal-timeline-list--top <?php echo esc_attr( $horizontal_layout ); ?>">
                    <?php $this->print_horizontal_timeline_content( $settings, $query, 'top' ); ?>
                </div>
                <div class="eael-horizontal-timeline-list eael-horizontal-timeline-list--middle <?php echo esc_attr( $horizontal_layout ); ?>">
                    <div class="eael-horizontal-timeline__line"></div>
                    <?php $this->print_horizontal_timeline_content( $settings, $query, 'middle' ); ?>
                </div>
                <div class="eael-horizontal-timeline-list eael-horizontal-timeline-list--bottom <?php echo esc_attr( $horizontal_layout ); ?>">
                    <?php $this->print_horizontal_timeline_content( $settings, $query, 'bottom' ); ?>
                </div>
                
                <?php
                break;
            case 'middle':
                ?>

                <div class="eael-horizontal-timeline-list eael-horizontal-timeline-list--top <?php echo esc_attr( $horizontal_layout ); ?>">
                    <?php $this->print_horizontal_timeline_content( $settings, $query, 'top' ); ?>
                </div>
                <div class="eael-horizontal-timeline-list eael-horizontal-timeline-list--middle <?php echo esc_attr( $horizontal_layout ); ?>">
                    <div class="eael-horizontal-timeline__line"></div>
                    <?php $this->print_horizontal_timeline_content( $settings, $query, 'middle' ); ?>
                </div>
                <div class="eael-horizontal-timeline-list eael-horizontal-timeline-list--bottom <?php echo esc_attr( $horizontal_layout ); ?>">
                    <?php $this->print_horizontal_timeline_content( $settings, $query, 'bottom' ); ?>
                </div>

                <?php
                break;
            case 'bottom':
                ?>

                <div class="eael-horizontal-timeline-list eael-horizontal-timeline-list--bottom <?php echo esc_attr( $horizontal_layout ); ?>">
                    <?php $this->print_horizontal_timeline_content( $settings, $query, 'bottom' ); ?>
                </div>
                <div class="eael-horizontal-timeline-list eael-horizontal-timeline-list--middle <?php echo esc_attr( $horizontal_layout ); ?>">
                    <div class="eael-horizontal-timeline__line"></div>
                    <?php $this->print_horizontal_timeline_content( $settings, $query, 'middle' ); ?>
                </div>
                <div class="eael-horizontal-timeline-list eael-horizontal-timeline-list--top <?php echo esc_attr( $horizontal_layout ); ?>">
                    <?php $this->print_horizontal_timeline_content( $settings, $query, 'top' ); ?>
                </div>

                <?php
                break;
        }
        ?>
    </div>
</div>
<?php
