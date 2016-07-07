<?php
/*
 * Plugin Name: URI Corrector
 * Description: Correct Fancy URLs in GetSimple CMS
 * Version: 1.0
 * Author: f30d0r
 * Author Site: http://feodor.me
 * Author GitHUB: https://github.com/f30d0r
 */

# get correct id for plugin
$url_corrector_file = basename(__FILE__, ".php");
$url_corrector_dir	= dirname(__FILE__);

$url_corrector_lang_en = array(
	"PLUGIN_DESCRIPTION" => "Correct Fancy URLs in GetSimple CMS",
	"PRETTYURLS_OFF" => "Fancy URLs if off. <b>URL Corrector</b> plugin can't work!"
);
$url_corrector_lang_ru = array(
	"PLUGIN_DESCRIPTION" => "Корректирует непраильные URL.",
	"PRETTYURLS_OFF" => "ЧПУ отклчены. <b>URL Corrector</b> не работает!"
);

if (file_exists($url_corrector_dir."/".$url_corrector_file."/lang/".$LANG.".php")) {
	include_once $url_corrector_dir."/".$url_corrector_file."/lang/".$LANG.".php";
} else if ($LANG == "ru_RU") {
	$uc_i18n = $url_corrector_lang_ru;
} else {
	$uc_i18n = $url_corrector_lang_en;
}

# register plugin
register_plugin(
	$url_corrector_file, //Plugin id
	'URL Corrector', 	//Plugin name
	'1.0', 		//Plugin version
	'f30d0r',  //Plugin author
	'https://github.com/f30d0r', //author website
	$uc_i18n['PLUGIN_DESCRIPTION'], //Plugin description
	'setting', //page type - on which admin tab to display
	'backend_init'  //main function (administration)
);

add_action('index-post-dataindex','url_corrector_init');
add_action('header-body','url_corrector_backend');


# functions
function url_corrector_message($msg,$error=false,$backup=null){ ?>
	<script type="text/javascript">
		$(function(){
			$('div.bodycontent').before('<div class="<?php echo $error?'error':'updated'; ?>" style="display:block;">'+"<?php echo $msg; ?>"+'</div>');
			$(".updated, .error").fadeOut(500).fadeIn(500);
		});
	</script>
	<noscript><div class="<?php echo $error?'error':'updated'; ?>" style="display:block;"><?php echo $msg; ?></div></noscript>
<?php }

if ($PRETTYURLS == "") {
	return false;
}

function url_corrector_redirect($url='') {
  if (isset($_SERVER['REDIRECT_URL']) and $url!=$_SERVER['REDIRECT_URL']) {
    header("HTTP/1.1 301 Moved Permanently");
    header("Location: ".$url);
    die();
  }
}

function url_corrector_init() {
	global $pagesArray, $PRETTYURLS, $PERMALINK;

  if ($PRETTYURLS == "" or $_SERVER['REQUEST_METHOD'] != "GET") {
    return false;
  }

	$id    						 = $_GET['id'];
	$parent						 = $pagesArray[$id]['parent'];
	$https 						 = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off');
	$url   						 = ($https?"https":"http")."://".$_SERVER['HTTP_HOST']."/";
	$urn							 = "";
	$query						 = "";

	$i = 0;

	foreach ($_GET as $key => $val) {
		if ($key!='id') {
			$query .= (($i==0)?'?':'&').$key.'='.urlencode($val);
			$i++;
		}
	}

  if(isset($PERMALINK) and $PERMALINK != ""){
    $urn_mask = var_out($PERMALINK);
  } else {
    $urn_mask = "%parent%/%slug%/";
  }

  if ($id == "" or $id == "index") {
    url_corrector_redirect(preg_replace("/\/$/","",$url.$query));
    return true;
  } else if ($parent=="") {
    url_corrector_redirect($url.$id."/".$query);
    return true;
  } else {
    $urn = str_replace(array("%slug%","%parent%"), array($id,$parent), $urn_mask);
    url_corrector_redirect($url.$urn.$query);
    return true;
  }
}

function url_corrector_backend() {
	global $PRETTYURLS, $uc_i18n;
	if ($PRETTYURLS == "") {
		url_corrector_message($uc_i18n['PRETTYURLS_OFF'],true);
    return false;
  }
}

function backend_init() {

}
?>
