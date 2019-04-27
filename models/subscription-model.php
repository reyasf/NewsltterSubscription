<?php

/* Subscription Model */

class subscriptionmodel {
    
    public $first_name, $email;
    public static $table_name, $charset;
    
    public function __construct() {
        global $wpdb;
        self::$table_name = $wpdb->prefix."newsletter_subscription";
    }
    
    /*
     * Create the table
     */
    public function createmodel() {
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        self::$charset = $wpdb->get_charset_collate();
        self::$table_name = $wpdb->prefix."newsletter_subscription";
        
        $sql = "CREATE TABLE ".self::$table_name." (
          id int(10) NOT NULL AUTO_INCREMENT,
          first_name varchar(100) NOT NULL,
          email varchar(100) NOT NULL,
          date_created datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
          unsubscribe tinyint(1) DEFAULT 0 NOT NULL,
          PRIMARY KEY  (id)
        ) ".self::$charset.";";

        dbDelta( $sql );
    }
    
    /*
     * Insert subscription fields
     */
    public function createsubscription() {
        global $wpdb;
        $sql = "INSERT INTO ".self::$table_name." (first_name, email) VALUES ('".$this->first_name."','".$this->email."')";
        $wpdb->query($sql);
        return;
    }
    
    /*
     * Select all subscribers with all fields
     */
    public function listsubscribers() {
        global $wpdb;
        $subscribers = null;
        $sql = "SELECT * FROM ".self::$table_name." WHERE 1";
        $subscribers = $wpdb->get_results($sql, ARRAY_A);
        return $subscribers;
    }
    
    /*
     * Unsubscribe single subscription using id
     */
    public function unsubscribe($id) {
        global $wpdb;
        $sql = "UPDATE ".self::$table_name." SET unsubscribe = 1 WHERE id=".intval($id);
        $wpdb->query($sql);
        return true;
    }
    
    /*
     * Search for a subscription using similar first_name or email
     */
    public function searchsubscription($string) {
        global $wpdb;
        $sql = "SELECT * FROM ".self::$table_name." WHERE first_name LIKE '%".$wpdb->esc_like( $string )."%' OR email LIKE '%".$wpdb->esc_like( $string )."%'";
        $subscribers = $wpdb->get_results($sql, ARRAY_A);
        return $subscribers;
    }
}
?>