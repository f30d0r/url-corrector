<?php
/*
 * Plugin Name: URL Corrector
 * Description: Correct Fancy URLs in GetSimple CMS
 * Version: 2.0
 * Author: f30d0r
 * Author Site: http://feodor.me
 * Author GitHUB: https://github.com/f30d0r
 */

$thisfile_url_corrector = basename(__FILE__, ".php");

i18n_merge($thisfile_url_corrector) || i18n_merge($thisfile_url_corrector,'en_US');

register_plugin($thisfile_url_corrector, 'URL Corrector', '2.0', 'f30d0r', 'https://feodor.me/', i18n_r($thisfile_url_corrector.'/PLUGIN_DESCRIPTION'), 'setting', 'url_corrector_backend_init');

add_action('header-body','url_corrector_backend');
add_action('index-post-dataindex','url_corrector_init');

function url_corrector_init() {
	global $pagesArray, $PRETTYURLS;

	if ($_SERVER['REQUEST_METHOD'] != "GET") return false;
	if ($PRETTYURLS != '1') return false;

	$id = $_GET['id']?$_GET['id']:'index';

	if (!isset($pagesArray[$id])) return false;

	$parent = $pagesArray[$id]['parent'];

	$queryArray = $_GET; unset($queryArray['id']);
	$query = ((count($queryArray)>0)?'?':'').http_build_query($queryArray);

  $uri = find_url($id, $parent).$query;

	if (!preg_match('/:\/\/'.preg_quote($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'], '/').'?/i', $uri)) {
		redirect($uri);
	}
}

function url_corrector_backend() {
	global $PRETTYURLS;
	if ($PRETTYURLS != '1') { ?>
<script type="text/javascript">
	$(function(){
		$('div.bodycontent').before('<div class="error" style="display:block;">'+"<?php i18n($thisfile_url_corrector.'/PRETTYURLS_OFF'); ?>"+'</div>');
		$('.error').fadeOut(500).fadeIn(500);
	});
</script>
<noscript><div class="error" style="display:block;"><?php i18n($thisfile_url_corrector.'/PRETTYURLS_OFF'); ?></div></noscript>
<?php
    return false;
  }
}

function url_corrector_backend_init() { redirect('plugins.php'); }
