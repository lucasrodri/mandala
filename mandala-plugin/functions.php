<?php
/** 
 * Plugin Name: Mandala Plugin
 * Plugin URI: http://rcc.ibict.br/
 * Description: Adiciona funcionalides ao tema desenvolvido
 * Version: 1.0 
 * Author: Ibict
 * Author URI: http://rcc.ibict.br/
*/

//Definicoes
define('MANDALA_JS_PATH', plugin_dir_path(__FILE__) . 'js/');
define('MANDALA_JS_URL', plugin_dir_url(__FILE__) . 'js/');
define('MANDALA_CSS_PATH', plugin_dir_path(__FILE__) . 'css/');
define('MANDALA_CSS_URL', plugin_dir_url(__FILE__) . 'css/');

function mandalaScripts(){
	
	$ver = time();
	
	wp_enqueue_script( 'js_mandala', MANDALA_JS_URL . 'functions.js' , array('jquery', 'js_mandala_d3', 'js_mandala_sunburst'), $ver );
	wp_enqueue_script( 'js_mandala_d3', 'https://unpkg.com/d3@6.6.1/dist/d3.min.js' , array('jquery'), $ver );
	wp_enqueue_script( 'js_mandala_sunburst', 'https://unpkg.com/sunburst-chart@1.11.2/dist/sunburst-chart.min.js' , array('jquery'), $ver );

	wp_register_style( 'css_mandala', MANDALA_CSS_URL . 'style.css', false, $ver );
	
	wp_enqueue_style ( 'css_mandala' );
}
add_action('wp_enqueue_scripts', 'mandalaScripts');

/** Função de exemplo que recebe um texto e o torna capitalizado
	Ex: [shortcode_destaque texto="Lorem Ipsum"] 
**/
function mandalaFunction($params) {
	$html = "<div id='chart'></div>";
	return $html;
}
add_shortcode('shortcode_mandala', 'mandalaFunction');
