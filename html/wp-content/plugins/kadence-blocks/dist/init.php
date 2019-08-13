<?php
/**
 * Enqueue admin CSS/JS and edit width functions
 *
 * @since   1.0.0
 * @package Kadence Blocks
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue Gutenberg block assets for backend editor.
 *
 * `wp-blocks`: includes block type registration and related functions.
 * `wp-element`: includes the WordPress Element abstraction for describing the structure of your blocks.
 * `wp-i18n`: To internationalize the block's text.
 *
 * @since 1.0.0
 */
function kadence_gutenberg_editor_assets() {
	// Scripts.
	wp_enqueue_script( 'kadence-blocks-js', KT_BLOCKS_URL . 'dist/blocks.build.js', array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor', 'wp-api', 'wp-edit-post' ), KT_BLOCKS_VERSION, true );
	$editor_widths  = get_option( 'kt_blocks_editor_width', array() );
	$sidebar_size   = 750;
	$nosidebar_size = 1140;
	$jssize         = 2000;
	if ( ! isset( $editor_widths['enable_editor_width'] ) || 'true' === $editor_widths['enable_editor_width'] ) {
		if ( isset( $editor_widths['limited_margins'] ) && 'true' === $editor_widths['limited_margins'] ) {
			$add_size = 10;
		} else {
			$add_size = 30;
		}
		$post_type = get_post_type();
		if ( isset( $editor_widths['page_default'] ) && ! empty( $editor_widths['page_default'] ) && isset( $editor_widths['post_default'] ) && ! empty( $editor_widths['post_default'] ) ) {
			if ( isset( $post_type ) && 'page' === $post_type ) {
				$defualt_size_type = $editor_widths['page_default'];
			} else {
				$defualt_size_type = $editor_widths['post_default'];
			}
		} else {
			$defualt_size_type = 'sidebar';
		}
		if ( isset( $editor_widths['sidebar'] ) && ! empty( $editor_widths['sidebar'] ) ) {
			$sidebar_size = $editor_widths['sidebar'] + $add_size;
		} else {
			$sidebar_size = 750;
		}
		if ( isset( $editor_widths['nosidebar'] ) && ! empty( $editor_widths['nosidebar'] ) ) {
			$nosidebar_size = $editor_widths['nosidebar'] + $add_size;
		} else {
			$nosidebar_size = 1140 + $add_size;
		}
		if ( 'sidebar' == $defualt_size_type ) {
			$default_size = $sidebar_size;
		} elseif ( 'fullwidth' == $defualt_size_type ) {
			$default_size = 'none';
		} else {
			$default_size = $nosidebar_size;
		}
		if ( 'none' === $default_size ) {
			$jssize = 2000;
		} else {
			$jssize = $default_size;
		}
	}
	if ( current_user_can( apply_filters( 'kadence_blocks_admin_role', 'manage_options' ) ) ) {
		$userrole = 'admin';
	} else if ( current_user_can( apply_filters( 'kadence_blocks_editor_role', 'delete_others_pages' ) ) ) {
		$userrole = 'editor';
	} else if ( current_user_can( apply_filters( 'kadence_blocks_author_role', 'publish_posts' ) ) ) {
		$userrole = 'author';
	} else if ( current_user_can( apply_filters( 'kadence_blocks_contributor_role', 'edit_posts' ) ) ) {
		$userrole = 'contributor';
	} else {
		$userrole = 'none';
	}
	wp_localize_script(
		'kadence-blocks-js',
		'kadence_blocks_params',
		array(
			'sidebar_size'   => $sidebar_size,
			'nosidebar_size' => $nosidebar_size,
			'default_size'   => $jssize,
			'config'         => get_option( 'kt_blocks_config_blocks' ),
			'configuration'  => get_option( 'kadence_blocks_config_blocks' ),
			'settings'       => get_option( 'kadence_blocks_settings_blocks' ),
			'userrole'       => $userrole,
			'colors'         => get_option( 'kadence_blocks_colors' ),
		)
	);
	// Styles.
	wp_enqueue_style( 'kadence-blocks-editor-css', KT_BLOCKS_URL . 'dist/blocks.editor.build.css', array( 'wp-edit-blocks' ), KT_BLOCKS_VERSION );
	// Limited Margins.
	$editor_widths = get_option( 'kt_blocks_editor_width', array() );
	if ( isset( $editor_widths['limited_margins'] ) && 'true' === $editor_widths['limited_margins'] ) {
		wp_enqueue_style( 'kadence-blocks-limited-margins-css', KT_BLOCKS_URL . 'dist/limited-margins.css', array( 'wp-edit-blocks' ), KT_BLOCKS_VERSION );
	}
}
add_action( 'enqueue_block_editor_assets', 'kadence_gutenberg_editor_assets' );


/**
 * Register Meta for blocks width
 */
function kt_blocks_init_post_meta() {

	register_post_meta(
		'',
		'kt_blocks_editor_width',
		array(
			'show_in_rest' => true,
			'single' => true,
			'type' => 'string',
		)
	);
}
add_action( 'init', 'kt_blocks_init_post_meta' );

/**
 * Add inline css editor width
 */
function kadence_blocks_admin_editor_width() {
	$editor_widths = get_option( 'kt_blocks_editor_width', array() );
	if ( ! isset( $editor_widths['enable_editor_width'] ) || 'true' === $editor_widths['enable_editor_width'] ) {
		if ( isset( $editor_widths['limited_margins'] ) && 'true' === $editor_widths['limited_margins'] ) {
			$add_size = 10;
		} else {
			$add_size = 30;
		}
		$post_type = get_post_type();
		if ( isset( $editor_widths['page_default'] ) && ! empty( $editor_widths['page_default'] ) && isset( $editor_widths['post_default'] ) && ! empty( $editor_widths['post_default'] ) ) {
			if ( isset( $post_type ) && 'page' === $post_type ) {
				$defualt_size_type = $editor_widths['page_default'];
			} else {
				$defualt_size_type = $editor_widths['post_default'];
			}
		} else {
			$defualt_size_type = 'sidebar';
		}
		if ( isset( $editor_widths['sidebar'] ) && ! empty( $editor_widths['sidebar'] ) ) {
			$sidebar_size = $editor_widths['sidebar'] + $add_size;
		} else {
			$sidebar_size = 750;
		}
		if ( isset( $editor_widths['nosidebar'] ) && ! empty( $editor_widths['nosidebar'] ) ) {
			$nosidebar_size = $editor_widths['nosidebar'] + $add_size;
		} else {
			$nosidebar_size = 1140 + $add_size;
		}
		if ( 'sidebar' == $defualt_size_type ) {
			$default_size = $sidebar_size;
		} elseif ( 'fullwidth' == $defualt_size_type ) {
			$default_size = 'none';
		} else {
			$default_size = $nosidebar_size;
		}
		if ( 'none' === $default_size ) {
			$jssize = 2000;
		} else {
			$jssize = $default_size;
		}
		echo '<style type="text/css" id="kt-block-editor-width">';
		echo 'body.gutenberg-editor-page.kt-editor-width-default .editor-post-title__block,
		body.gutenberg-editor-page.kt-editor-width-default .editor-default-block-appender,
		body.gutenberg-editor-page.kt-editor-width-default .editor-block-list__block,
		body.block-editor-page.kt-editor-width-default .wp-block {
			max-width: ' . esc_attr( $default_size ) . ( is_numeric( $default_size ) ? 'px' : '' ) . ';
		}';
		echo 'body.gutenberg-editor-page.kt-editor-width-sidebar .editor-post-title__block,
		body.gutenberg-editor-page.kt-editor-width-sidebar .editor-default-block-appender,
		body.gutenberg-editor-page.kt-editor-width-sidebar .editor-block-list__block,
		body.block-editor-page.kt-editor-width-sidebar .wp-block {
			max-width: ' . esc_attr( $sidebar_size ) . 'px;
		}';
		echo 'body.gutenberg-editor-page.kt-editor-width-nosidebar .editor-post-title__block,
		body.gutenberg-editor-page.kt-editor-width-nosidebar .editor-default-block-appender,
		body.gutenberg-editor-page.kt-editor-width-nosidebar .editor-block-list__block,
		body.block-editor-page.kt-editor-width-nosidebar .wp-block {
			max-width: ' . esc_attr( $nosidebar_size ) . 'px;
		}';
		echo 'body.gutenberg-editor-page.kt-editor-width-fullwidth .editor-post-title__block,
		body.gutenberg-editor-page.kt-editor-width-fullwidth .editor-default-block-appender,
		body.gutenberg-editor-page.kt-editor-width-fullwidth .editor-block-list__block,
		body.block-editor-page.kt-editor-width-fullwidth .wp-block {
			max-width: none;
		}';
		echo 'body.gutenberg-editor-page .editor-block-list__layout .editor-block-list__block[data-align=wide],
		body.block-editor-page .editor-block-list__layout .wp-block[data-align=wide] {
			width: auto;
			max-width: ' . esc_attr( $nosidebar_size + 200 ) . 'px;
		}';

		echo 'body.gutenberg-editor-page .editor-block-list__layout .editor-block-list__block[data-align=full],
		body.block-editor-page .editor-block-list__layout .wp-block[data-align=full] {
			max-width: none;
		}';
		echo '</style>';
		echo "<script> var kt_blocks_sidebar_size = " . $sidebar_size . "; var kt_blocks_nosidebar_size = " . $nosidebar_size . "; var kt_blocks_default_size = " . $jssize . ";</script>";
	}
}
add_action( 'admin_head-post.php', 'kadence_blocks_admin_editor_width', 100 );
add_action( 'admin_head-post-new.php', 'kadence_blocks_admin_editor_width', 100 );

/**
 * Add class to match editor width.
 *
 * @param string $classes string of body classes.
 */
function kadence_blocks_admin_body_class( $classes ) {
	$screen = get_current_screen();
	if ( 'post' == $screen->base ) {
		global $post;
		$editorwidth = get_post_meta( $post->ID, 'kt_blocks_editor_width', true );
		if ( isset( $editorwidth ) && ! empty( $editorwidth ) && 'default' !== $editorwidth ) {
			$classes .= ' kt-editor-width-' . esc_attr( $editorwidth );
		} else {
			$classes .= ' kt-editor-width-default';
		}
	}
	return $classes;
}
add_filter( 'admin_body_class', 'kadence_blocks_admin_body_class' );

/**
 * Add block category for Kadence Blocks.
 *
 * @param array  $categories the array of block categories.
 * @param object $post the post object.
 */
function kadence_blocks_block_category( $categories, $post ) {
	return array_merge(
		array(
			array(
				'slug'  => 'kadence-blocks',
				'title' => __( 'Kadence Blocks', 'kadence-blocks' ),
			),
		),
		$categories
	);
}
add_filter( 'block_categories', 'kadence_blocks_block_category', 10, 2 );

