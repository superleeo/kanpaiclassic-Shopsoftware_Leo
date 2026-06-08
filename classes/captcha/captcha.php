<?php
//
//	A simple PHP CAPTCHA script
//
//	Copyright 2011 by Cory LaViska for A Beautiful Site, LLC.
//
//	http://abeautifulsite.net/blog/2011/01/a-simple-php-captcha-script/
//
namespace SHOPSOFTWARE;

function captcha($config = array()) {
	
	// Check for GD library
	if( !function_exists('gd_info') ) {
		throw new Exception('Required GD library is missing');
	}
	
	// Default values
	$captcha_config = array(
		'code' => '',
		'min_length' => 5,
		'max_length' => 5,
		'png_backgrounds' => array(dirname(__FILE__) . '/default.png'),
		'fonts' => array(dirname(__FILE__) . '/times_new_yorker.ttf'),
		'characters' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789',
		'min_font_size' => 24,
		'max_font_size' => 30,
		'color' => '#000',
		'angle_min' => 0,
		'angle_max' => 15,
		'shadow' => true,
		'shadow_color' => '#CCC',
		'shadow_offset_x' => -2,
		'shadow_offset_y' => 2
	);
	
	// Overwrite defaults with custom config values
	if( is_array($config) ) {
		foreach( $config as $key => $value ) $captcha_config[$key] = $value;
	}
	
	// Restrict certain values
	if( $captcha_config['min_length'] < 1 ) $captcha_config['min_length'] = 1;
	if( $captcha_config['angle_min'] < 0 ) $captcha_config['angle_min'] = 0;
	if( $captcha_config['angle_max'] > 10 ) $captcha_config['angle_max'] = 10;
	if( $captcha_config['angle_max'] < $captcha_config['angle_min'] ) $captcha_config['angle_max'] = $captcha_config['angle_min'];
	if( $captcha_config['min_font_size'] < 10 ) $captcha_config['min_font_size'] = 10;
	if( $captcha_config['max_font_size'] < $captcha_config['min_font_size'] ) $captcha_config['max_font_size'] = $captcha_config['min_font_size'];
	
	// Use milliseconds instead of seconds
	srand(microtime(true) * 100);
	
	// Generate CAPTCHA code if not set by user
	if( empty($captcha_config['code']) ) {
		$captcha_config['code'] = '';
		$length = rand($captcha_config['min_length'], $captcha_config['max_length']);
		while( strlen($captcha_config['code']) < $length ) {
			$captcha_config['code'] .= substr($captcha_config['characters'], rand() % (strlen($captcha_config['characters'])), 1);
		}
	}
	
	// Generate image src
   $params = Control::getParams();
   $image_src = SHOP_URL.'/classes/captcha/simple-php-captcha.php?_CAPTCHA&amp;t=' . urlencode(microtime(true));
	//$image_src = substr(__FILE__, strlen($_SERVER['DOCUMENT_ROOT'])) . '?_CAPTCHA&amp;t=' . urlencode(microtime());
	//$image_src = '/' . ltrim(preg_replace('/\\\\/', '/', $image_src), '/');
   //$image_src = str_replace('captcha.php', 'simple-php-captcha.php', $image_src);
	
	$_SESSION['_CAPTCHA']['config'] = serialize($captcha_config);
	
	return array(
		'code' => $captcha_config['code'],
		'image_src' => $image_src
	);
	
}
?>