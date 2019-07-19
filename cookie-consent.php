<?php
/*
Plugin Name: Cookie Consent
Plugin URI: http://www.afpa.fr
Description: Plugin pour être en conformité avec la loi européenne sur les cookies !
Author: Afpa de Roubaix
Author URI: http://www.afpa.fr
Version: 1.0
*/

if (!class_exists("Classe_Cookie")) {  
 class Classe_Cookie{ 
 
 function installer_table(){  
 global $wpdb;  
 $table_cookie = $wpdb->prefix."cookie_parametres"; 
 if($wpdb->get_var("SHOW TABLES LIKE $table_cookie")!= $table_cookie){ 
 $sql="CREATE TABLE $table_cookie( 
 id_cookie BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,  
 message_cookie TEXT NOT NULL, 
 url_cookie TEXT NOT NULL 
 )ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;"; 
 require_once(ABSPATH.'wp-admin/includes/upgrade.php'); 
 dbDelta($sql); 
 $wpdb->insert(
    $wpdb->prefix.'cookie_parametres',
    array(
        'message_cookie' => 'En poursuivant votre navigation sur ce site, vous acceptez l\'utilisation de cookies !',
        'url_cookie' => 'www.afpa.fr'

    ),
    array(
        '%s',
        '%s'

    )
);
$wpdb->query($sql); 
 } 
 
 
 
} 

function desinstaller_table(){  
	global $wpdb; 
	 $table_cookie = $wpdb->prefix."cookie_parametres";     
     $sql = "DROP TABLE $table_cookie";  
     $wpdb->query($sql); 
}
 } 
}

if (class_exists("Classe_Cookie")){  
 $installer_table = new Classe_Cookie();  
}  
if (isset($installer_table)){ 
register_activation_hook(__FILE__, array($installer_table,'installer_table')); 
register_deactivation_hook(__FILE__, array($installer_table,'desinstaller_table')); 
} 

add_action('wp_head','ajouter_css', 1);
function ajouter_css() {
if(!is_admin()):
	wp_register_style( 'custom-style', plugins_url( '/assets/css/cookies.css', __FILE__ ), array(), '20120208', 'all' );	
	wp_enqueue_style( 'custom-style' );
	wp_register_style( 'normalize', 'https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css', null, null, false );
	wp_enqueue_style( 'normalize' );
endif;
}
// Création de la fonction ajouter au menu 
function ajouterElementMenu(){
	if(is_admin()){
    add_menu_page('Cookie Consent', 'Cookie Consent', 'edit_pages', 'cookie-consent', 'MethodeMenu');   
	}
}
// hook pour réaliser l'action 'admin_menu' <- emplacement / ajouterElementMenu <- fonction à appeler /  <- priorité.
add_action("admin_menu", "ajouterElementMenu", 10);
// fonction à appeler lorsque l'on clic sur le menu.
function MethodeMenu(){
require_once ('views/cookie-menu.php');
}
add_shortcode( 'cookie_consent', 'AfficherCookie');
function AfficherCookie(){
global $messageCookie;
global $wpdb;	
$query = "SELECT * FROM wp_cookie_parametres";
$row = $wpdb->get_row($query);
$contenu = $row->message_cookie; 
$url = $row->url_cookie;
$messageCookie = 
<<<EOT
<div class="cookie">
  <p class="cookie__message">$contenu</p>
  <button class="cookie__accept">Accepter le cookie</button>
  <button class="cookie__decline">Decliner le cookie</button>
  <a class="cookie__link" href="http://$url/" title="Learn all about cookies and why sites use them" target="_blank">What are cookies?</a>
</div>
EOT;
return $messageCookie;
}
add_action('init', 'inserer_js_dans_footer');
function inserer_js_dans_footer() {
if(!is_admin()):
	wp_register_script( 'jQuery', 'https://code.jquery.com/jquery-2.2.4.min.js', null, null, true );
	wp_enqueue_script('jQuery');
    wp_register_script('functions', get_site_url().'/wp-content/plugins/cookie-consent/assets/js/consent.js','',false,true);
    wp_enqueue_script('functions');
endif;
}
