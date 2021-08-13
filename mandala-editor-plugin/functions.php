<?php
/** 
 * Plugin Name: Mandala Plugin -- Teste Rebeca
 * Plugin URI: http://rcc.ibict.br/
 * Description: Adiciona funcionalides ao tema desenvolvido
 * Version: 1.0 
 * Author: Ibict
 * Author URI: http://rcc.ibict.br/
*/

//Definicoes
define('MANDALA_PATH', plugin_dir_path(__FILE__) . '/');
define('MANDALA_JS_PATH', plugin_dir_path(__FILE__) . 'js/');
define('MANDALA_JS_URL', plugin_dir_url(__FILE__) . 'js/');
define('MANDALA_CSS_PATH', plugin_dir_path(__FILE__) . 'css/');
define('MANDALA_CSS_URL', plugin_dir_url(__FILE__) . 'css/');

function mandalaScripts(){
	
	$ver = time();
	
	// mandala sunburst
	wp_enqueue_script( 'js_mandala', MANDALA_JS_URL . 'functions-sunburst.js' , array('jquery', 'js_mandala_d3', 'js_mandala_sunburst'), $ver );
	wp_enqueue_script( 'js_mandala_d3', 'https://unpkg.com/d3@6.6.1/dist/d3.min.js' , array('jquery'), $ver );
	wp_enqueue_script( 'js_mandala_sunburst', 'https://unpkg.com/sunburst-chart@1.11.2/dist/sunburst-chart.min.js' , array('jquery'), $ver );

	wp_register_style( 'css_mandala', MANDALA_CSS_URL . 'style-sunburst.css', false, $ver );
	wp_enqueue_style ( 'css_mandala' );

	//mandala highcharts
	wp_enqueue_script( 'js_mandala_hc', MANDALA_JS_URL . 'functions-highcharts.js' , array('js_hc', 'js_hc_sunburst', 'js_hc_exporting', 'js_hc_export_data', 'js_hc_accessibility'), false, $ver );
	wp_enqueue_script( 'js_hc', 'https://code.highcharts.com/highcharts.js' , array(), $ver );
	wp_enqueue_script( 'js_hc_sunburst', 'https://code.highcharts.com/modules/sunburst.js' , array(), $ver );
	wp_enqueue_script( 'js_hc_exporting', 'https://code.highcharts.com/modules/exporting.js' , array(), $ver );
	wp_enqueue_script( 'js_hc_export_data', 'https://code.highcharts.com/modules/export-data.js' , array(), $ver );
	wp_enqueue_script( 'js_hc_accessibility', 'https://code.highcharts.com/modules/accessibility.js' , array(), $ver );

	wp_register_style( 'css_mandala_hc', MANDALA_CSS_URL . 'style-highcharts.css', false, $ver );
	wp_enqueue_style ( 'css_mandala_hc' );
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

/** Função de exemplo que recebe um texto e o torna capitalizado
	Ex: [shortcode_destaque texto="Lorem Ipsum"] 
**/
function mandalaHighCharts($params) {
	$html = '<div class="highcharts-figure">
  				<div id="container-mandala"></div>
			</div>';
	return $html;
}
add_shortcode('shortcode_mandala_hc', 'mandalaHighCharts');


/** Função que adiciona página ao menu admin
 * https://themes.artbees.net/blog/wordpress-custom-admin-pages/
**/
function mandala_menu() {
	add_menu_page(
		__( 'Editor Mandala', 'mandala-plugin' ),
		__( 'Mandala', 'mandala-plugin' ),
		'manage_options',
		'mandala-editor',
		'mandala_admin_page'
		//'dashicons-schedule',
		//3
	);
}
add_action( 'admin_menu', 'mandala_menu' );

function mandala_admin_page() {
	?>
	<h1>Editor da Mandala:</h1>

	<button id='btn-mostrar'>Mostrar json</button>
	<button id='btn-carregar'>Carregar dados novos</button>
	<button id='btn-download'>Baixar json</button>
	<button id='btn-save'>Salvar json com php</button>

	<div id="orgChartContainer">
		<div id="orgChart"></div>
	</div>
	<div id="consoleOutput"></div>
	
	<?php
}

//Adding Styles and Scripts to WordPress Custom Admin Pages
function load_custom_wp_admin_style($hook) {
	$ver = time();

	// Load only on ?page=mypluginname
	if( $hook != 'toplevel_page_mandala-editor' ) {
		 return;
	}
	wp_register_style( 'css_mandala_admin', MANDALA_CSS_URL . 'admin-style.css', false, $ver );
	wp_enqueue_style ( 'css_mandala_admin' );

	// para o orgchart
	wp_register_style( 'css_orgchart', MANDALA_CSS_URL . 'jquery.orgchart.css', false, $ver );
	wp_enqueue_style ( 'css_orgchart' );

	wp_enqueue_script( 'js_mandala_admin', MANDALA_JS_URL . 'functions-editor.js' , array('js_orgchart', 'js_jquery_orgchart'), $ver );
	wp_enqueue_script( 'js_orgchart', MANDALA_JS_URL . 'jquery.orgchart.js' , array('js_jquery_orgchart'), $ver );
	wp_enqueue_script( 'js_jquery_orgchart', MANDALA_JS_URL . 'jquery-1.11.1.min.js' , array(), $ver );

}
add_action( 'admin_enqueue_scripts', 'load_custom_wp_admin_style' );


//https://codex.wordpress.org/AJAX_in_Plugins
/*
add_action( 'wp_ajax_my_action', 'my_action' );
function my_action() {
	global $wpdb; // this is how you get access to the database

	$whatever = intval( $_POST['whatever'] );

	$whatever += 10;

	echo $whatever;

	wp_die(); // this is required to terminate immediately and return a proper response
}
*/

add_action( 'wp_ajax_salvar_txt_mandala', 'salvar_txt_mandala' );
function salvar_txt_mandala() {
	// Pega variável do ajax
	$json_content = $_POST['json_content'];

	$file_name = MANDALA_PATH . "newfile.txt"; 
	$myfile = fopen($file_name, "w") or die("Unable to open file!");
	
	$json_content_clean = html_entity_decode( stripslashes ($json_content ) );
	$json_content_clean = str_replace("},{", "},\n{", $json_content_clean); //incluir quebra de linha
	$json_content_clean = str_replace(',"parent"', ',"value":1,"parent"', $json_content_clean); //incluir value

	fwrite($myfile, $json_content_clean);
	fclose($myfile);

	//resposta para o ajax
	echo $file_name;

	wp_die(); // this is required to terminate immediately and return a proper response
}