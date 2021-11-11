<?php


namespace reb_livestream_classes\Boot;


class ThemeTables {

	const ACTIVATION_TOKEN_TABLE = "user_activation_token";
	const PENDING_EMAIL_TABLE = "user_pending_email";
	const READING_LOG_TABLE = "reading_log";
	const BOOKMARKS_TABLE = "user_bookmarks";
	const READ_LATER_TABLE = "user_read_later";


	public function __construct() {

		add_action( "after_switch_theme", [ $this, 'setup_tables' ] );
	}


	public function setup_tables() {

		global $wpdb;

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );


		$tablename = $wpdb->prefix . self::ACTIVATION_TOKEN_TABLE;
		$sql       = "CREATE TABLE IF NOT EXISTS $tablename
    (
        id INT UNSIGNED NOT NULL AUTO_INCREMENT,
        user_id INT NOT NULL,
        email VARCHAR(255) NOT NULL,
        token VARCHAR(255) NOT NULL,
        created_at TIMESTAMP NOT NULL,
        PRIMARY KEY  (id)
    );";
		dbDelta( $sql );


		$tablename = $wpdb->prefix . self::PENDING_EMAIL_TABLE;
		$sql       = "CREATE TABLE IF NOT EXISTS $tablename
    (
        id INT UNSIGNED NOT NULL AUTO_INCREMENT,
        user_id INT NOT NULL,
        new_email VARCHAR(255) NOT NULL,
        pin VARCHAR(255) NOT NULL,
        created_at TIMESTAMP NOT NULL,
        PRIMARY KEY  (id)
    );";
		dbDelta( $sql );


		$tablename = $wpdb->prefix . self::READING_LOG_TABLE;
		$sql       = "CREATE TABLE IF NOT EXISTS $tablename
    (
        id INT UNSIGNED NOT NULL AUTO_INCREMENT,
        user_id INT NOT NULL,
        post_id INT NOT NULL,
        permalink VARCHAR(255) NOT NULL,
        scroll_depth INT NULL,
        created_at TIMESTAMP NOT NULL,
        PRIMARY KEY  (id)
    );";
		dbDelta( $sql );

		$tablename = $wpdb->prefix . self::BOOKMARKS_TABLE;
		$sql       = "CREATE TABLE IF NOT EXISTS $tablename
    (
        id INT UNSIGNED NOT NULL AUTO_INCREMENT,
        user_id INT NOT NULL,
        post_id INT NOT NULL,
        permalink VARCHAR(255) NOT NULL,
        created_at TIMESTAMP NOT NULL,
        PRIMARY KEY  (id),
        UNIQUE (user_id, post_id)
    );";
		dbDelta( $sql );


		$tablename = $wpdb->prefix . self::READ_LATER_TABLE;
		$sql       = "CREATE TABLE IF NOT EXISTS $tablename
    (
        id INT UNSIGNED NOT NULL AUTO_INCREMENT,
        user_id INT NOT NULL,
        post_id INT NOT NULL,
        permalink VARCHAR(255) NOT NULL,
        created_at TIMESTAMP NOT NULL,
        remind_at DATETIME NOT NULL,
        PRIMARY KEY  (id),
        UNIQUE (user_id, post_id)
    );";

		dbDelta( $sql );


	}

}