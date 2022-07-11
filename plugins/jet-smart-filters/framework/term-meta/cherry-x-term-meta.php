<?php
/**
 * Term Meta module
 *
 * Version: 1.1.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Cherry_X_Term_Meta' ) ) {

	/**
	 * Term meta management module.
	 */
	class Cherry_X_Term_Meta {

		/**
		 * Module arguments.
		 *
		 * @var array
		 */
		public $args = array();

		/**
		 * Interface builder instance.
		 *
		 * @var object
		 */
		public $builder = null;

		/**
		 * Current nonce name to check.
		 *
		 * @var null
		 */
		public $nonce = 'cherry-x-meta-nonce';

		/**
		 * Storage of meta values.
		 *
		 * @since 1.0.0
		 * @var   array
		 */
		public $meta_values = array();

		/**
		 * Constructor for the module.
		 *
		 * @since 1.0.0
		 */
		public function __construct( $args = array() ) {

			$this->args = wp_parse_args( $args, array(
				'tax'        => 'category',
				'priority'   => 10,
				'builder_cb' => false,
				'fields'     => array(),
			) );

			if ( empty( $this->args['fields'] ) ) {
				return;
			}

			add_action( 'admin_enqueue_scripts', array( $this, 'init_builder' ), 0 );

			$priority = intval( $this->args['priority'] );
			$tax      = esc_attr( $this->args['tax'] );

			add_action( "{$tax}_add_form_fields", array( $this, 'render_add_fields' ), $priority );
			add_action( "{$tax}_edit_form_fields", array( $this, 'render_edit_fields' ), $priority, 2 );

			add_action( "created_{$tax}", array( $this, 'save_meta' ) );
			add_action( "edited_{$tax}", array( $this, 'save_meta' ) );

		}

		/**
		 * Initialize builder
		 *
		 * @return [type] [description]
		 */
		public function init_builder( $hook ) {

			if ( ! in_array( $hook, array( 'edit-tags.php', 'term.php' ) ) ) {
				return;
			}

			$tax = $_GET['taxonomy'];

			if ( $tax !== $this->args['tax'] ) {
				return;
			}

			if ( ! isset( $this->args['builder_cb'] ) || ! is_callable( $this->args['builder_cb'] ) ) {
				return;
			}

			$this->builder = call_user_func( $this->args['builder_cb'] );

			if ( 'edit-tags.php' === $hook ) {
				$term = false;
			} else {
				$term = get_term( absint( $_GET['tag_ID'] ), $tax );
			}

			$this->get_fields( $term );
		}

		/**
		 * Safely get attribute from field settings array.
		 *
		 * @since  1.0.0
		 * @param  array            $field   arguments array.
		 * @param  string|int|float $arg     argument key.
		 * @param  mixed            $default default argument value.
		 * @return mixed
		 */
		public function get_arg( $field, $arg, $default = '' ) {
			if ( is_array( $field ) && isset( $field[ $arg ] ) ) {
				return $field[ $arg ];
			}
			return $default;
		}

		/**
		 * Get registered control fields
		 *
		 * @since  1.0.0
		 * @param  mixed $term Current term object.
		 * @return void
		 */
		public function get_fields( $term ) {

			$zero_allowed = apply_filters(
				'cx_post_meta/zero_allowed_controls',
				array(
					'stepper',
					'slider',
				)
			);

			foreach ( $this->args['fields'] as $key => $field ) {

				$default = $this->get_arg( $field, 'value', '' );
				$value   = $this->get_meta( $term, $key, $default );

				if ( isset( $field['options_callback'] ) ) {
					$field['options'] = call_user_func( $field['options_callback'] );
				}

				$element        = $this->get_arg( $field, 'element', 'control' );
				$field['id']    = $this->get_arg( $field, 'id', $key );
				$field['name']  = $this->get_arg( $field, 'name', $key );
				$field['type']  = $this->get_arg( $field, 'type', '' );
				$field['value'] = $value;

				// Fix zero values for stepper and slider
				if ( ! $value && in_array( $field['type'], $zero_allowed ) ) {
					$field['value'] = 0;
				}

				$register_callback = 'register_' . $element;

				if ( method_exists( $this->builder, $register_callback ) ) {
					call_user_func( array( $this->builder, $register_callback ), $field );
				}
			}
		}

		/**
		 * Retrieve post meta field.
		 *
		 * @since  1.0.0
		 *
		 * @param  object $term    Current post object.
		 * @param  string $key     The meta key to retrieve.
		 * @param  mixed  $default Default value.
		 * @return string
		 */
		public function get_meta( $term, $key, $default = false ) {

			if ( ! is_object( $term ) ) {
				return $default;
			}

			$meta = get_term_meta( $term->term_id, $key, false );

			return ( empty( $meta ) ) ? $default : $meta[0];
		}

		/**
		 * Render add term form fields
		 *
		 * @since  1.0.0
		 * @param  [type] $taxonomy taxonomy name.
		 * @return void
		 */
		public function render_add_fields( $taxonomy ) {

			echo '<div style="padding:10px 0;">';
			$this->render_fields();
			echo '</div>';

		}

		/**
		 * Render edit term form fields
		 *
		 * @since  1.0.0
		 * @param  object $term     current term object.
		 * @param  [type] $taxonomy taxonomy name.
		 * @return void
		 */
		public function render_edit_fields( $term, $taxonomy ) {

			echo '<tr class="form-field cherry-term-meta-wrap"><th>&nbsp;</th><td>';
			$this->render_fields();
			echo '</td></tr>';

		}

		/**
		 * Render metabox funciton
		 *
		 * @since  1.0.0
		 * @param  object $post    The post object currently being edited.
		 * @param  array  $metabox Specific information about the meta box being loaded.
		 * @return void
		 */
		public function render_fields() {

			if ( ! $this->builder ) {
				return;
			}

			$this->builder->render();

		}

		/**
		 * Save additional taxonomy meta on edit or create tax
		 *
		 * @since  1.0.0
		 * @param  int $term_id Term ID.
		 * @return bool
		 */
		public function save_meta( $term_id ) {

			if ( ! current_user_can( 'edit_posts' ) ) {
				return false;
			}

			foreach ( $this->args['fields'] as $key => $field ) {

				if ( ! isset( $_POST[ $key ] ) ) {
					continue;
				}

				if ( is_array( $_POST[ $key ] ) ) {
					$new_val = array_filter( $_POST[ $key ] );
				} else {
					if ( ! empty( $field['sanitize_callback'] ) && is_callable( $field['sanitize_callback'] ) ) {
						$new_val = call_user_func( $field['sanitize_callback'], $_POST[ $key ], $key, $field );
					} else {
						$new_val = esc_attr( $_POST[ $key ] );
					}
				}

				update_term_meta( $term_id, $key, $new_val );

			}

			return true;

		}

	}
}
