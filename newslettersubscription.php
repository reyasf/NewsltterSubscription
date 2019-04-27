<?php

/*
Plugin Name: Newsletter Subscription
*/

require_once( plugin_dir_path( __FILE__ ) . '/models/subscription-model.php' );
register_activation_hook(__FILE__, array("subscriptionmodel", "createmodel"));

class newslettersubscription extends subscriptionmodel {
    
    protected static $_object = null;
    
    public function __construct() {
        add_action('admin_menu', array(&$this, 'add_admin_menu'));
        add_action("wp_enqueue_scripts", array(&$this, "embed_scripts"));
        add_shortcode("newsletter_subscription", array(&$this, 'newsletter_subscription'));
        add_action('wp_ajax_create_subscription', array(&$this, "create_subscription"));
	add_action('wp_ajax_nopriv_create_subscription', array(&$this, "create_subscription"));
        add_action('admin_enqueue_scripts', array(&$this, 'admin_embed_scripts'));
        add_action('wp_ajax_unsubscribe', array(&$this, "unsubscribe_subscription"));
	add_action('wp_ajax_nopriv_unsubscribe', array(&$this, "unsubscribe_subscription"));
        add_action('wp_ajax_search', array(&$this, "search_subscription"));
	add_action('wp_ajax_nopriv_search', array(&$this, "search_subscription"));
    }
    
    /*
     * create the object
     */
    public static function initialize() {
        if (is_null(self::$_object)) {
            self::$_object = new self();
        }
        return self::$_object;
    }
    
    /*
     * Add admin menu with the callback list_all_subscribers()
     * Lists all the subscribers
     */
    public function add_admin_menu() {
        add_menu_page(
			__('Newsletter Subscribers', 'newslettersubscription')
			, __('Newsletter Subscribers', 'newslettersubscription')
			, 'manage_options'
			, 'subscribers'
			, array(&$this, 'list_all_subscribers')
	);
    }
    
    /*
     * Shortcode for the frontend
     * Renders the subscription form
     */
    public function newsletter_subscription() {
        include( plugin_dir_path( __FILE__ ) . 'views/subscription-form.php');
        exit;
    }
    
    /*
     * Styles and JS for the frontend
     */
    public function embed_scripts() {
        wp_enqueue_script('subscription-js', plugins_url('/js/subscription.js?ver=1', __FILE__),array("jquery"));
        wp_localize_script('subscription-js', 'ajax_url', admin_url('admin-ajax.php'));
        wp_enqueue_style("subscription-css", plugins_url('/css/styles.css?ver=1', __FILE__));
    }
    
    /*
     * Styles and JS for admin
     */
    public function admin_embed_scripts() {
        wp_enqueue_style("subscription-css", plugins_url('/css/styles.css?ver=1', __FILE__));
        wp_enqueue_style("admin-css", plugins_url('/css/admin-styles.css?ver=1', __FILE__));
        wp_enqueue_script('admin-js', plugins_url('/js/admin.js?ver=1', __FILE__),array("jquery"));
        wp_localize_script('admin-js', 'ajax_url', admin_url('admin-ajax.php') );
    }
    
    /*
     * Subscription creation
     * Used to create subscription
     */
    public function create_subscription() {
        $subscription = new subscriptionmodel();
        $subscription->first_name = $_POST["first_name"];
        $subscription->email = $_POST["email"];
        $subscription->createsubscription();
        exit;
    }
    
    /*
     * List Subscribers
     * Used to list all the subscribers for the admin
     */
    public function list_all_subscribers() {
        $subscribers = new subscriptionmodel();
        $list = $subscribers->listsubscribers();
        if($list !== null) {
            include( plugin_dir_path( __FILE__ ) . 'views/subscription-list-search.php');
            $this->render_list($list);
        }
    }
    
    /*
     * UNSubscribe
     * Unsubscribe a subscription 
     */
    public function unsubscribe_subscription() {
        $subscription = new subscriptionmodel();
        if($subscription->unsubscribe($_POST["id"])) {
            echo "unsubscribed";
        }
        exit;
    }
    
    /*
     * Search
     * Search for a given string as an elastic search
     */
    public function search_subscription() {
        $subscribers = new subscriptionmodel();
        $search = $subscribers->searchsubscription($_POST["search"]);
        if($search !== null) {
            $this->render_list($search);
        }
    }
    
    /*
     * Render
     * Render subscription list
     */
    public function render_list($list) {
        include( plugin_dir_path( __FILE__ ) . 'views/subscription-list-header.php');
        foreach($list as $key => $val) {
            $id = $val["id"];
            $first_name = $val["first_name"];
            $email = $val["email"];
            $subscribed = ($val["unsubscribe"] == 0) ? "Subscribed" : "Unsubscribed";
            include( plugin_dir_path( __FILE__ ) . 'views/subscription-list-row.php');
        }
        include( plugin_dir_path( __FILE__ ) . 'views/subscription-list-footer.php');
        exit;
    }
}
newslettersubscription::initialize();
?>