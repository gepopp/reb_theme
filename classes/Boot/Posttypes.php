<?php


namespace reb_livestream_classes\Boot;


class Posttypes {

	public function __construct() {

		add_action( 'init', [ $this, 'register_post_types' ] );

	}


	function register_post_types() {



		$labels = [
			'name'                       => 'Livestream Kategorie',
			'singular_name'              => 'Livestream Kategorie',
			'menu_name'                  => 'Livestream Kategorie',
			'all_items'                  => 'Alle Livestream Kategorie',
			'parent_item'                => 'Übergeordnet',
			'parent_item_colon'          => 'Übergeordnet',
			'new_item_name'              => 'Bezeichnung',
			'add_new_item'               => 'Neue Livestream Kategorie',
			'edit_item'                  => 'bearbeiten',
			'update_item'                => 'speichern',
			'view_item'                  => 'ansehen',
			'separate_items_with_commas' => 'Durch Komma trennen',
			'add_or_remove_items'        => 'hinzufügen oder trennen',
			'choose_from_most_used'      => 'Aus den meist verwendeten wählen',
			'popular_items'              => 'Beliebte Kategorien',
			'search_items'               => 'Succhen',
			'not_found'                  => 'Nichts gefunden',
			'no_terms'                   => 'Keine Kategorien',
			'items_list'                 => 'Kategorie Liste',
			'items_list_navigation'      => 'Listen Navgation',
		];
		$args   = [
			'labels'            => $labels,
			'hierarchical'      => true,
			'public'            => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => true,
			'show_tagcloud'     => true,
			'show_in_rest'      => true,
		];
		register_taxonomy( 'Livestream_category', [ 'livestream' ], $args );

		$labels = [
			'name'                  => _x( 'Livestream', 'Post Type General Name', 'reb_domain' ),
			'singular_name'         => _x( 'Livestream', 'Post Type Singular Name', 'reb_domain' ),
			'menu_name'             => __( 'Livestream', 'reb_domain' ),
			'name_admin_bar'        => __( 'Livestream', 'reb_domain' ),
			'archives'              => __( 'Livestream', 'reb_domain' ),
			'attributes'            => __( 'Livestream Attributes', 'reb_domain' ),
			'parent_item_colon'     => __( 'Parent Item:', 'reb_domain' ),
			'all_items'             => __( 'Alle Livestreams', 'reb_domain' ),
			'add_new_item'          => __( 'Neuer Livestream', 'reb_domain' ),
			'add_new'               => __( 'Hinzufügen', 'reb_domain' ),
			'new_item'              => __( 'Neuer Livestream', 'reb_domain' ),
			'edit_item'             => __( 'Livestream bearbeiten', 'reb_domain' ),
			'update_item'           => __( 'Livestream speichern', 'reb_domain' ),
			'view_item'             => __( 'Livestream ansehen', 'reb_domain' ),
			'view_items'            => __( 'Alle Livestreams', 'reb_domain' ),
			'search_items'          => __( 'Livestream suchen', 'reb_domain' ),
			'not_found'             => __( 'Nicht gefunden', 'reb_domain' ),
			'not_found_in_trash'    => __( 'Nicht gefunden', 'reb_domain' ),
			'featured_image'        => __( 'Beitragsbild', 'reb_domain' ),
			'set_featured_image'    => __( 'Beitragsbild setzten', 'reb_domain' ),
			'remove_featured_image' => __( 'Beitragsbild entfernen', 'reb_domain' ),
			'use_featured_image'    => __( 'Als Beitragsbild verwenden', 'reb_domain' ),
			'insert_into_item'      => __( 'Einfügen', 'reb_domain' ),
			'uploaded_to_this_item' => __( 'Zum Livestream hochgeladen', 'reb_domain' ),
			'items_list'            => __( 'Livestream Liste', 'reb_domain' ),
			'items_list_navigation' => __( 'Livestream Liste Navigation', 'reb_domain' ),
			'filter_items_list'     => __( 'Filtern', 'reb_domain' ),
		];
		$args   = [
			'label'               => __( 'Livestream', 'reb_domain' ),
			'description'         => __( 'Livestream Beschreibung', 'reb_domain' ),
			'labels'              => $labels,
			'supports'            => [
				'title',
				'editor',
				'thumbnail',
				'comments',
				'custom-fields',
				'page-attributes',
				'post-formats',
				'excerpt',
			],
			'taxonomies'          => [ 'livestream_category' ],
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 5,
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'page',
			'show_in_rest'        => true,
		];
		register_post_type( 'livestream', $args );




		$labels = [
			'name'                       => 'Immobilien Projekt Kategorie',
			'singular_name'              => 'Immobilien Projekt Kategorie',
			'menu_name'                  => 'Immobilien Projekt Kategorie',
			'all_items'                  => 'Alle Immobilien Projekt Kategorie',
			'parent_item'                => 'Übergeordnet',
			'parent_item_colon'          => 'Übergeordnet',
			'new_item_name'              => 'Bezeichnung',
			'add_new_item'               => 'Neunes Projekt Kategorie',
			'edit_item'                  => 'bearbeiten',
			'update_item'                => 'speichern',
			'view_item'                  => 'ansehen',
			'separate_items_with_commas' => 'Separate items with commas',
			'add_or_remove_items'        => 'Add or remove items',
			'choose_from_most_used'      => 'Choose from the most used',
			'popular_items'              => 'Popular Items',
			'search_items'               => 'Search Items',
			'not_found'                  => 'Not Found',
			'no_terms'                   => 'No items',
			'items_list'                 => 'Items list',
			'items_list_navigation'      => 'Items list navigation',
		];
		$args   = [
			'labels'            => $labels,
			'hierarchical'      => true,
			'public'            => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => true,
			'show_tagcloud'     => true,
		];
		register_taxonomy( 'projekt_category', [ 'immobilien_projekt' ], $args );
		


		$labels = [
			'name'                  => _x( 'Zur Person', 'Post Type General Name', 'reb_domain' ),
			'singular_name'         => _x( 'Zur Person', 'Post Type Singular Name', 'reb_domain' ),
			'menu_name'             => __( 'Zur Person', 'reb_domain' ),
			'name_admin_bar'        => __( 'Zur Person', 'reb_domain' ),
			'archives'              => __( 'Zur Person', 'reb_domain' ),
			'attributes'            => __( 'Zur Person Attribute', 'reb_domain' ),
			'parent_item_colon'     => __( 'Parent Item:', 'reb_domain' ),
			'all_items'             => __( 'Alle Zur Person', 'reb_domain' ),
			'add_new_item'          => __( 'Neuer Zur Person', 'reb_domain' ),
			'add_new'               => __( 'Hinzufügen', 'reb_domain' ),
			'new_item'              => __( 'Neuer Zur Person', 'reb_domain' ),
			'edit_item'             => __( 'Zur Person', 'reb_domain' ),
			'update_item'           => __( 'Zur Person', 'reb_domain' ),
			'view_item'             => __( 'Zur Person', 'reb_domain' ),
			'view_items'            => __( 'Alle Zur Person', 'reb_domain' ),
			'not_found'             => __( 'Nichts gefunden', 'reb_domain' ),
			'not_found_in_trash'    => __( 'Nichts gefunden', 'reb_domain' ),
			'featured_image'        => __( 'Beitragsbild', 'reb_domain' ),
			'set_featured_image'    => __( 'Beitragsbild setzten', 'reb_domain' ),
			'remove_featured_image' => __( 'Beitragsbild entfernen', 'reb_domain' ),
			'use_featured_image'    => __( 'Als Beitragsbild verwenden', 'reb_domain' ),
			'insert_into_item'      => __( 'Einfügen', 'reb_domain' ),
			'uploaded_to_this_item' => __( 'Zum Zur Person hochgeladen', 'reb_domain' ),
			'items_list'            => __( 'Zur Person Liste', 'reb_domain' ),
			'items_list_navigation' => __( 'Zur Person Listen Navigation', 'reb_domain' ),
			'filter_items_list'     => __( 'Filtern', 'reb_domain' ),
		];
		$args   = [
			'label'               => __( 'Zur Person', 'reb_domain' ),
			'description'         => __( 'Zur Person', 'irtheme' ),
			'labels'              => $labels,
			'supports'            => [ 'title', 'editor', 'thumbnail', 'comments', 'custom-fields' ],
			'taxonomies'          => [],
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 4,
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'post',
			'show_in_rest'        => true,
			'menu_icon'           => 'dashicons-admin-post',
		];
		register_post_type( 'zur_person', $args );
	}


}