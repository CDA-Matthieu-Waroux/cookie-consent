<?php
global $contenu;
global $url_cookie;
global $wpdb;	
$query = "SELECT * FROM wp_cookie_parametres";
$row = $wpdb->get_row($query);
$ancien_contenu = $row->message_cookie; 
$ancien_url = $row->url_cookie;

if(!empty($_POST['contenu'])){
	
	if(compter_nombre_ligne() != 0){
	effacer_table();
	}
	$contenu = $_POST['contenu'];	
	$url_cookie = $_POST['url_information'];	
    inserer_info_cookie($contenu,$url_cookie);
	$message_erreur = "<span style='color:#006600;'>Sauvegardé !</span>";
}else{

	$message_erreur = "<span style='color:#ff1f17;'>Merci de remplir tous les champs !</span>";
	}
?>
<h1>Réglages du cookie</h1>
<p><?php if(isset($message_erreur)){echo $message_erreur;} ?></p>
<form method="post" action="" novalidate="novalidate">

<table class="form-table">

<tr>
<th scope="row"><label for="blogname">Conetenu du cookie</label></th>
<td><input name="contenu" type="text" id="contenu" value="<?php if(isset($_POST['contenu'])){ echo $_POST['contenu'];}else{echo $ancien_contenu;}?>" class="regular-text" /></td>
</tr>

<tr>
<th scope="row"><label for="blogname">Url information</label></th>
<td><input name="url_information" type="text" id="url_information" value="<?php if(isset($_POST['url_information'])){ echo $_POST['url_information'];}else{echo $ancien_url;}?>" class="regular-text" /></td>
</tr>

</table>

<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Enregistrer les modifications"/></p>
</form>
<br>
<p>Exemple: En poursuivant votre navigation sur ce site, vous acceptez l'utilisation de cookies pour mesurer la fréquentation de nos services
afin de les améliorer !</p>

<?php
function inserer_info_cookie($contenu,$url_cookie){ 
 global $wpdb;  
 $table_cookie= $wpdb->prefix.'cookie_parametres';  
 $sql=$wpdb->prepare(  
 " 
 INSERT INTO ".$table_cookie." 
 (message_cookie, url_cookie) 
 VALUES (%s,%s) 
 ",  
 $contenu, 
 $url_cookie 
 ); 
 $wpdb->query($sql);   

}

function effacer_table(){
 global $wpdb;  
 $table_cookie= $wpdb->prefix.'cookie_parametres';
 $wpdb->query("TRUNCATE TABLE wp_cookie_parametres");  
}

function compter_nombre_ligne(){
 global $wpdb;  
 $table_cookie= $wpdb->prefix.'cookie_parametres';  
 $compteur=$wpdb->get_var( "SELECT COUNT(*) FROM wp_cookie_parametres" );
return  $compteur;
}
?>