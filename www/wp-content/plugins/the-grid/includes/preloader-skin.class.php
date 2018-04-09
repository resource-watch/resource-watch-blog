<?php
/**
 * @package   The_Grid
 * @author    Themeone <themeone.master@gmail.com>
 * @copyright 2015 Themeone
 */

// Exit if accessed directly
if (!defined('ABSPATH')) { 
	exit;
}
 
class The_Grid_Preloader_Skin {

    private $preloaders = array();

    function __construct() {
        $this->preloaders = apply_filters('tg_add_preloader_skin', $this->preloaders);
    }
	
	// call to retrieve skin added with filter
    function __call($name,$post) {
        if(isset($this->preloaders[$name])) {
			return $this->preloaders[$name]();
		}
    }
	
	// get skin names array
	function get_preloader_name() {
		$preloader_skin_arr = array();
        $preloader_skins = $this->preloaders;
		foreach ($preloader_skins as $preloader_skin => $param) {
			$preloader_name = $this->$preloader_skin();
			$preloader_skin_arr[$preloader_skin] = $preloader_name['name'];
		}
		return $preloader_skin_arr;
    }

}

add_filter('tg_add_preloader_skin', function($preloaders){
	
	$preloaders['square-grid-pulse'] = function() {
		
		$preloader['name'] = 'Square Grid Pulse';
		
		$html  = '<div></div>';
		$html .= '<div></div>';
		$html .= '<div></div>';
		$html .= '<div></div>';
		$html .= '<div></div>';
		$html .= '<div></div>';
		$html .= '<div></div>';
		$html .= '<div></div>';
		$html .= '<div></div>';
	
		$preloader['html'] = $html;
		
		$css = '@-webkit-keyframes square-grid-pulse{0%{-webkit-transform:scale(1);transform:scale(1)}50%{-webkit-transform:scale(.5);transform:scale(.5);opacity:.7}100%{-webkit-transform:scale(1);transform:scale(1);opacity:1}}@keyframes square-grid-pulse{0%{-webkit-transform:scale(1);transform:scale(1)}50%{-webkit-transform:scale(.5);transform:scale(.5);opacity:.7}100%{-webkit-transform:scale(1);transform:scale(1);opacity:1}}.square-grid-pulse{width:57px}.square-grid-pulse>div:nth-child(1){-webkit-animation-delay:.73s;animation-delay:.73s;-webkit-animation-duration:1.3s;animation-duration:1.3s}.square-grid-pulse>div:nth-child(2){-webkit-animation-delay:.32s;animation-delay:.32s;-webkit-animation-duration:1.3s;animation-duration:1.3s}.square-grid-pulse>div:nth-child(3){-webkit-animation-delay:.71s;animation-delay:.71s;-webkit-animation-duration:.88s;animation-duration:.88s}.square-grid-pulse>div:nth-child(4){-webkit-animation-delay:.62s;animation-delay:.62s;-webkit-animation-duration:1.06s;animation-duration:1.06s}.square-grid-pulse>div:nth-child(5){-webkit-animation-delay:.31s;animation-delay:.31s;-webkit-animation-duration:.62s;animation-duration:.62s}.square-grid-pulse>div:nth-child(6){-webkit-animation-delay:-.14s;animation-delay:-.14s;-webkit-animation-duration:1.48s;animation-duration:1.48s}.square-grid-pulse>div:nth-child(7){-webkit-animation-delay:-.1s;animation-delay:-.1s;-webkit-animation-duration:1.47s;animation-duration:1.47s}.square-grid-pulse>div:nth-child(8){-webkit-animation-delay:.4s;animation-delay:.4s;-webkit-animation-duration:1.49s;animation-duration:1.49s}.square-grid-pulse>div:nth-child(9){-webkit-animation-delay:.73s;animation-delay:.73s;-webkit-animation-duration:.7s;animation-duration:.7s}.square-grid-pulse>div{display:inline-block;float:left;width:15px;height:15px;margin:2px;-webkit-animation-fill-mode:both;animation-fill-mode:both;-webkit-animation-name:square-grid-pulse;animation-name:square-grid-pulse;-webkit-animation-iteration-count:infinite;animation-iteration-count:infinite;-webkit-animation-delay:0;animation-delay:0}';
		
		$preloader['css'] = $css;
		
		return $preloader;
	};

	$preloaders['ball-grid-pulse'] = function() {
		
		$preloader['name'] = 'Ball Grid Pulse';
		
		$html  = '<div></div>';
		$html .= '<div></div>';
		$html .= '<div></div>';
		$html .= '<div></div>';
		$html .= '<div></div>';
		$html .= '<div></div>';
		$html .= '<div></div>';
		$html .= '<div></div>';
		$html .= '<div></div>';
	
		$preloader['html'] = $html;
		
		$css = '@-webkit-keyframes ball-grid-pulse{0%{-webkit-transform:scale(1);transform:scale(1)}50%{-webkit-transform:scale(.5);transform:scale(.5);opacity:.7}100%{-webkit-transform:scale(1);transform:scale(1);opacity:1}}@keyframes ball-grid-pulse{0%{-webkit-transform:scale(1);transform:scale(1)}50%{-webkit-transform:scale(.5);transform:scale(.5);opacity:.7}100%{-webkit-transform:scale(1);transform:scale(1);opacity:1}}.ball-grid-pulse{width:57px}.ball-grid-pulse>div:nth-child(1){-webkit-animation-delay:.73s;animation-delay:.73s;-webkit-animation-duration:1.3s;animation-duration:1.3s}.ball-grid-pulse>div:nth-child(2){-webkit-animation-delay:.32s;animation-delay:.32s;-webkit-animation-duration:1.3s;animation-duration:1.3s}.ball-grid-pulse>div:nth-child(3){-webkit-animation-delay:.71s;animation-delay:.71s;-webkit-animation-duration:.88s;animation-duration:.88s}.ball-grid-pulse>div:nth-child(4){-webkit-animation-delay:.62s;animation-delay:.62s;-webkit-animation-duration:1.06s;animation-duration:1.06s}.ball-grid-pulse>div:nth-child(5){-webkit-animation-delay:.31s;animation-delay:.31s;-webkit-animation-duration:.62s;animation-duration:.62s}.ball-grid-pulse>div:nth-child(6){-webkit-animation-delay:-.14s;animation-delay:-.14s;-webkit-animation-duration:1.48s;animation-duration:1.48s}.ball-grid-pulse>div:nth-child(7){-webkit-animation-delay:-.1s;animation-delay:-.1s;-webkit-animation-duration:1.47s;animation-duration:1.47s}.ball-grid-pulse>div:nth-child(8){-webkit-animation-delay:.4s;animation-delay:.4s;-webkit-animation-duration:1.49s;animation-duration:1.49s}.ball-grid-pulse>div:nth-child(9){-webkit-animation-delay:.73s;animation-delay:.73s;-webkit-animation-duration:.7s;animation-duration:.7s}.ball-grid-pulse>div{display:inline-block;float:left;width:15px;height:15px;margin:2px;border-radius:100%;-webkit-animation-fill-mode:both;animation-fill-mode:both;-webkit-animation-name:ball-grid-pulse;animation-name:ball-grid-pulse;-webkit-animation-iteration-count:infinite;animation-iteration-count:infinite;-webkit-animation-delay:0;animation-delay:0}';
		
		$preloader['css'] = $css;
		
		return $preloader;
	};
	
	$preloaders['ball-clip-rotate'] = function() {
		
		$preloader['name'] = 'Ball Clip Rotate';
		
		$html  = '<div></div>';
	
		$preloader['html'] = $html;
		
		$css = '@keyframes rotate{0%{-webkit-transform:rotate(0deg) scale(1);transform:rotate(0deg) scale(1)}50%{-webkit-transform:rotate(180deg) scale(.6);transform:rotate(180deg) scale(.6)}100%{-webkit-transform:rotate(360deg) scale(1);transform:rotate(360deg) scale(1)}}.ball-clip-rotate>div{border-radius:100%;margin:2px;border:2px solid #fff;border-bottom-color:transparent!important;height:25px;width:25px;background:0 0!important;display:inline-block;-webkit-animation:rotate .75s 0s linear infinite;animation:rotate .75s 0s linear infinite}';
		
		$preloader['css'] = $css;
		
		return $preloader;
	};
	
	$preloaders['square-spin'] = function() {
		
		$preloader['name'] = 'Square Spin';
		
		$html  = '<div></div>';
	
		$preloader['html'] = $html;
		
		$css = '@-webkit-keyframes square-spin{25%{-webkit-transform:perspective(100px) rotateX(180deg) rotateY(0);transform:perspective(100px) rotateX(180deg) rotateY(0)}50%{-webkit-transform:perspective(100px) rotateX(180deg) rotateY(180deg);transform:perspective(100px) rotateX(180deg) rotateY(180deg)}75%{-webkit-transform:perspective(100px) rotateX(0) rotateY(180deg);transform:perspective(100px) rotateX(0) rotateY(180deg)}100%{-webkit-transform:perspective(100px) rotateX(0) rotateY(0);transform:perspective(100px) rotateX(0) rotateY(0)}}@keyframes square-spin{25%{-webkit-transform:perspective(100px) rotateX(180deg) rotateY(0);transform:perspective(100px) rotateX(180deg) rotateY(0)}50%{-webkit-transform:perspective(100px) rotateX(180deg) rotateY(180deg);transform:perspective(100px) rotateX(180deg) rotateY(180deg)}75%{-webkit-transform:perspective(100px) rotateX(0) rotateY(180deg);transform:perspective(100px) rotateX(0) rotateY(180deg)}100%{-webkit-transform:perspective(100px) rotateX(0) rotateY(0);transform:perspective(100px) rotateX(0) rotateY(0)}}.square-spin>div{width:50px;height:50px;-webkit-animation:square-spin 3s 0s cubic-bezier(.09,.57,.49,.9) infinite;animation:square-spin 3s 0s cubic-bezier(.09,.57,.49,.9) infinite}';
		
		$preloader['css'] = $css;
		
		return $preloader;
	};
	
	
	$preloaders['ball-pulse-sync'] = function() {
		
		$preloader['name'] = 'Ball Pulse Sync';
		
		$html  = '<div></div>';
		$html .= '<div></div>';
		$html .= '<div></div>';
	
		$preloader['html'] = $html;
		
		$css = '@-webkit-keyframes ball-pulse-sync{33%{-webkit-transform:translateY(10px);transform:translateY(10px)}66%{-webkit-transform:translateY(-10px);transform:translateY(-10px)}100%{-webkit-transform:translateY(0);transform:translateY(0)}}@keyframes ball-pulse-sync{33%{-webkit-transform:translateY(10px);transform:translateY(10px)}66%{-webkit-transform:translateY(-10px);transform:translateY(-10px)}100%{-webkit-transform:translateY(0);transform:translateY(0)}}.ball-pulse-sync>div:nth-child(0){-webkit-animation:ball-pulse-sync .6s -.21s infinite ease-in-out;animation:ball-pulse-sync .6s -.21s infinite ease-in-out}.ball-pulse-sync>div:nth-child(1){-webkit-animation:ball-pulse-sync .6s -.14s infinite ease-in-out;animation:ball-pulse-sync .6s -.14s infinite ease-in-out}.ball-pulse-sync>div:nth-child(2){-webkit-animation:ball-pulse-sync .6s -.07s infinite ease-in-out;animation:ball-pulse-sync .6s -.07s infinite ease-in-out}.ball-pulse-sync>div:nth-child(3){-webkit-animation:ball-pulse-sync .6s 0s infinite ease-in-out;animation:ball-pulse-sync .6s 0s infinite ease-in-out}.ball-pulse-sync>div{background-color:#fff;width:15px;height:15px;border-radius:100%;margin:2px;-webkit-animation-fill-mode:both;animation-fill-mode:both;display:inline-block}';
		
		$preloader['css'] = $css;
		
		return $preloader;
	};
	
	$preloaders['ball-beat'] = function() {
		
		$preloader['name'] = 'Ball Beat';
		
		$html  = '<div></div>';
		$html .= '<div></div>';
		$html .= '<div></div>';
	
		$preloader['html'] = $html;
		
		$css = '@-webkit-keyframes ball-beat{50%{opacity:.2;-webkit-transform:scale(.75);transform:scale(.75)}100%{opacity:1;-webkit-transform:scale(1);transform:scale(1)}}@keyframes ball-beat{50%{opacity:.2;-webkit-transform:scale(.75);transform:scale(.75)}100%{opacity:1;-webkit-transform:scale(1);transform:scale(1)}}.ball-beat>div{background-color:#fff;width:15px;height:15px;border-radius:100%;margin:2px;display:inline-block;-webkit-animation:ball-beat .7s 0s infinite linear;animation:ball-beat .7s 0s infinite linear}.ball-beat>div:nth-child(2n-1){-webkit-animation-delay:-.35s!important;animation-delay:-.35s!important}';
		
		$preloader['css'] = $css;
		
		return $preloader;
	};
	
	$preloaders['line-scale'] = function() {
		
		$preloader['name'] = 'Line Scale';
		
		$html  = '<div></div>';
		$html .= '<div></div>';
		$html .= '<div></div>';
		$html .= '<div></div>';
		$html .= '<div></div>';
	
		$preloader['html'] = $html;
		
		$css = '@-webkit-keyframes line-scale{0%{-webkit-transform:scaley(1);transform:scaley(1)}50%{-webkit-transform:scaley(.4);transform:scaley(.4)}100%{-webkit-transform:scaley(1);transform:scaley(1)}}@keyframes line-scale{0%{-webkit-transform:scaley(1);transform:scaley(1)}50%{-webkit-transform:scaley(.4);transform:scaley(.4)}100%{-webkit-transform:scaley(1);transform:scaley(1)}}.line-scale>div:nth-child(1){-webkit-animation:line-scale 1s -.4s infinite cubic-bezier(.2,.68,.18,1.08);animation:line-scale 1s -.4s infinite cubic-bezier(.2,.68,.18,1.08)}.line-scale>div:nth-child(2){-webkit-animation:line-scale 1s -.3s infinite cubic-bezier(.2,.68,.18,1.08);animation:line-scale 1s -.3s infinite cubic-bezier(.2,.68,.18,1.08)}.line-scale>div:nth-child(3){-webkit-animation:line-scale 1s -.2s infinite cubic-bezier(.2,.68,.18,1.08);animation:line-scale 1s -.2s infinite cubic-bezier(.2,.68,.18,1.08)}.line-scale>div:nth-child(4){-webkit-animation:line-scale 1s -.1s infinite cubic-bezier(.2,.68,.18,1.08);animation:line-scale 1s -.1s infinite cubic-bezier(.2,.68,.18,1.08)}.line-scale>div:nth-child(5){-webkit-animation:line-scale 1s 0s infinite cubic-bezier(.2,.68,.18,1.08);animation:line-scale 1s 0s infinite cubic-bezier(.2,.68,.18,1.08)}.line-scale>div{background-color:#fff;width:4px;height:35px;border-radius:2px;margin:2px;-webkit-animation-fill-mode:both;animation-fill-mode:both;display:inline-block}';
		
		$preloader['css'] = $css;
		
		return $preloader;
	};
		
	$preloaders['cube-transition'] = function() {
		
		$preloader['name'] = 'Cube Transition';
		
		$html  = '<div></div>';
		$html .= '<div></div>';
	
		$preloader['html'] = $html;
		
		$css = '@-webkit-keyframes cube-transition{25%{-webkit-transform:translateX(50px) scale(.5) rotate(-90deg);transform:translateX(50px) scale(.5) rotate(-90deg)}50%{-webkit-transform:translate(50px,50px) rotate(-180deg);transform:translate(50px,50px) rotate(-180deg)}75%{-webkit-transform:translateY(50px) scale(.5) rotate(-270deg);transform:translateY(50px) scale(.5) rotate(-270deg)}100%{-webkit-transform:rotate(-360deg);transform:rotate(-360deg)}}@keyframes cube-transition{25%{-webkit-transform:translateX(50px) scale(.5) rotate(-90deg);transform:translateX(50px) scale(.5) rotate(-90deg)}50%{-webkit-transform:translate(50px,50px) rotate(-180deg);transform:translate(50px,50px) rotate(-180deg)}75%{-webkit-transform:translateY(50px) scale(.5) rotate(-270deg);transform:translateY(50px) scale(.5) rotate(-270deg)}100%{-webkit-transform:rotate(-360deg);transform:rotate(-360deg)}}.cube-transition{position:relative;-webkit-transform:translate(-25px,-25px);-ms-transform:translate(-25px,-25px);transform:translate(-25px,-25px)}.cube-transition>div{width:10px;height:10px;position:absolute;margin-left:-6px;margin-top:-7px;background-color:#fff;-webkit-animation:cube-transition 1.6s -0.01s infinite ease-in-out;animation:cube-transition 1.6s -0.01s infinite ease-in-out}.cube-transition>div:last-child{-webkit-animation-delay:-.8s;animation-delay:-.8s}';
		
		$preloader['css'] = $css;
		
		return $preloader;
	};
	
	$preloaders['ball-zig-zag'] = function() {
		
		$preloader['name'] = 'Ball Zig Zag';
		
		$html  = '<div></div>';
		$html .= '<div></div>';
	
		$preloader['html'] = $html;
		
		$css = '@-webkit-keyframes ball-zig{33%{-webkit-transform:translate(-15px,-30px);transform:translate(-15px,-30px)}66%{-webkit-transform:translate(15px,-30px);transform:translate(15px,-30px)}100%{-webkit-transform:translate(0,0);transform:translate(0,0)}}@keyframes ball-zig{33%{-webkit-transform:translate(-15px,-30px);transform:translate(-15px,-30px)}66%{-webkit-transform:translate(15px,-30px);transform:translate(15px,-30px)}100%{-webkit-transform:translate(0,0);transform:translate(0,0)}}@-webkit-keyframes ball-zag{33%{-webkit-transform:translate(15px,30px);transform:translate(15px,30px)}66%{-webkit-transform:translate(-15px,30px);transform:translate(-15px,30px)}100%{-webkit-transform:translate(0,0);transform:translate(0,0)}}@keyframes ball-zag{33%{-webkit-transform:translate(15px,30px);transform:translate(15px,30px)}66%{-webkit-transform:translate(-15px,30px);transform:translate(-15px,30px)}100%{-webkit-transform:translate(0,0);transform:translate(0,0)}}.ball-zig-zag{position:relative;-webkit-transform:translate(-7px,-7px);-ms-transform:translate(-7px,-7px);transform:translate(-7px,-7px)}.ball-zig-zag>div{background-color:#fff;width:15px;height:15px;border-radius:100%;margin:2px 2px 2px 15px;-webkit-animation-fill-mode:both;animation-fill-mode:both;}.ball-zig-zag>div:first-child{-webkit-animation:ball-zig .7s 0s infinite linear;animation:ball-zig .7s 0s infinite linear;margin-top:17px;}.ball-zig-zag>div:last-child{-webkit-animation:ball-zag .7s 0s infinite linear;animation:ball-zag .7s 0s infinite linear;margin-top:-17px;}';
		
		$preloader['css'] = $css;
		
		return $preloader;
	};
	
	$preloaders['ball-scale'] = function() {
		
		$preloader['name'] = 'Ball Scale';
		
		$html  = '<div></div>';
	
		$preloader['html'] = $html;
		
		$css = '@-webkit-keyframes ball-scale{0%{-webkit-transform:scale(0);transform:scale(0)}100%{-webkit-transform:scale(1);transform:scale(1);opacity:0}}@keyframes ball-scale{0%{-webkit-transform:scale(0);transform:scale(0)}100%{-webkit-transform:scale(1);transform:scale(1);opacity:0}}.ball-scale>div{background-color:#fff;border-radius:100%;margin:2px;display:inline-block;height:60px;width:60px;-webkit-animation:ball-scale 1s 0s ease-in-out infinite;animation:ball-scale 1s 0s ease-in-out infinite}';
		
		$preloader['css'] = $css;
		
		return $preloader;
	};
	
	$preloaders['ball-spin-fade'] = function() {
		
		$preloader['name'] = 'Ball Spin Fade';
		
		$html  = '<div></div>';
		$html .= '<div></div>';
		$html .= '<div></div>';
		$html .= '<div></div>';
		$html .= '<div></div>';
		$html .= '<div></div>';
		$html .= '<div></div>';
		$html .= '<div></div>';
	
		$preloader['html'] = $html;
		
		$css = '@-webkit-keyframes ball-spin-fade{50%{opacity:.3;-webkit-transform:scale(.4);transform:scale(.4)}100%{opacity:1;-webkit-transform:scale(1);transform:scale(1)}}@keyframes ball-spin-fade{50%{opacity:.3;-webkit-transform:scale(.4);transform:scale(.4)}100%{opacity:1;-webkit-transform:scale(1);transform:scale(1)}}.ball-spin-fade{position:relative;top:-10px;left:-10px}.ball-spin-fade>div:nth-child(1){margin-top:25px;left:0;-webkit-animation:ball-spin-fade 1s -.96s infinite linear;animation:ball-spin-fade 1s -.96s infinite linear}.ball-spin-fade>div:nth-child(2){margin-top:17.05px;left:17.05px;-webkit-animation:ball-spin-fade 1s -.84s infinite linear;animation:ball-spin-fade 1s -.84s infinite linear}.ball-spin-fade>div:nth-child(3){margin-top:0;left:25px;-webkit-animation:ball-spin-fade 1s -.72s infinite linear;animation:ball-spin-fade 1s -.72s infinite linear}.ball-spin-fade>div:nth-child(4){margin-top:-17.05px;left:17.05px;-webkit-animation:ball-spin-fade 1s -.6s infinite linear;animation:ball-spin-fade 1s -.6s infinite linear}.ball-spin-fade>div:nth-child(5){margin-top:-25px;left:0;-webkit-animation:ball-spin-fade 1s -.48s infinite linear;animation:ball-spin-fade 1s -.48s infinite linear}.ball-spin-fade>div:nth-child(6){margin-top:-17.05px;left:-17.05px;-webkit-animation:ball-spin-fade 1s -.36s infinite linear;animation:ball-spin-fade 1s -.36s infinite linear}.ball-spin-fade>div:nth-child(7){margin-top:0;left:-25px;-webkit-animation:ball-spin-fade 1s -.24s infinite linear;animation:ball-spin-fade 1s -.24s infinite linear}.ball-spin-fade>div:nth-child(8){margin-top:17.05px;left:-17.05px;-webkit-animation:ball-spin-fade 1s -.12s infinite linear;animation:ball-spin-fade 1s -.12s infinite linear}.ball-spin-fade>div{background-color:#fff;width:15px;height:15px;border-radius:100%;-webkit-animation-fill-mode:both;animation-fill-mode:both;position:absolute}';
		
		$preloader['css'] = $css;
		
		return $preloader;
	};
	
	$preloaders['line-spin-fade'] = function() {
		
		$preloader['name'] = 'Line Spin Fade';
		
		$html  = '<div></div>';
		$html .= '<div></div>';
		$html .= '<div></div>';
		$html .= '<div></div>';
		$html .= '<div></div>';
		$html .= '<div></div>';
		$html .= '<div></div>';
		$html .= '<div></div>';
	
		$preloader['html'] = $html;
		
		$css = '@-webkit-keyframes line-spin-fade{50%{opacity:.3}100%{opacity:1}}@keyframes line-spin-fade{50%{opacity:.3}100%{opacity:1}}.line-spin-fade{position:relative;top:-10px;left:-4px}.line-spin-fade>div:nth-child(1){margin-top:20px;left:0;-webkit-animation:line-spin-fade 1.2s -.84s infinite ease-in-out;animation:line-spin-fade 1.2s -.84s infinite ease-in-out}.line-spin-fade>div:nth-child(2){margin-top:13.64px;left:13.64px;-webkit-transform:rotate(-45deg);-ms-transform:rotate(-45deg);transform:rotate(-45deg);-webkit-animation:line-spin-fade 1.2s -.72s infinite ease-in-out;animation:line-spin-fade 1.2s -.72s infinite ease-in-out}.line-spin-fade>div:nth-child(3){margin-top:0;left:20px;-webkit-transform:rotate(90deg);-ms-transform:rotate(90deg);transform:rotate(90deg);-webkit-animation:line-spin-fade 1.2s -.6s infinite ease-in-out;animation:line-spin-fade 1.2s -.6s infinite ease-in-out}.line-spin-fade>div:nth-child(4){margin-top:-13.64px;left:13.64px;-webkit-transform:rotate(45deg);-ms-transform:rotate(45deg);transform:rotate(45deg);-webkit-animation:line-spin-fade 1.2s -.48s infinite ease-in-out;animation:line-spin-fade 1.2s -.48s infinite ease-in-out}.line-spin-fade>div:nth-child(5){margin-top:-20px;left:0;-webkit-animation:line-spin-fade 1.2s -.36s infinite ease-in-out;animation:line-spin-fade 1.2s -.36s infinite ease-in-out}.line-spin-fade>div:nth-child(6){margin-top:-13.64px;left:-13.64px;-webkit-transform:rotate(-45deg);-ms-transform:rotate(-45deg);transform:rotate(-45deg);-webkit-animation:line-spin-fade 1.2s -.24s infinite ease-in-out;animation:line-spin-fade 1.2s -.24s infinite ease-in-out}.line-spin-fade>div:nth-child(7){margin-top:0;left:-20px;-webkit-transform:rotate(90deg);-ms-transform:rotate(90deg);transform:rotate(90deg);-webkit-animation:line-spin-fade 1.2s -.12s infinite ease-in-out;animation:line-spin-fade 1.2s -.12s infinite ease-in-out}.line-spin-fade>div:nth-child(8){margin-top:13.64px;left:-13.64px;-webkit-transform:rotate(45deg);-ms-transform:rotate(45deg);transform:rotate(45deg);-webkit-animation:line-spin-fade 1.2s 0s infinite ease-in-out;animation:line-spin-fade 1.2s 0s infinite ease-in-out}.line-spin-fade>div{background-color:#fff;border-radius:2px;-webkit-animation-fill-mode:both;animation-fill-mode:both;position:absolute;width:5px;height:15px}';
		
		$preloader['css'] = $css;
		
		return $preloader;
	};
	
	$preloaders['pacman'] = function() {
		
		$preloader['name'] = 'Pacman';
		
		$html  = '<div></div>';
		$html .= '<div></div>';
		$html .= '<div></div>';
		$html .= '<div></div>';
		$html .= '<div></div>';
	
		$preloader['html'] = $html;
		
		$css = '.pacman>div:first-of-type,.pacman>div:nth-child(2){width:0;height:0;border-right:25px solid transparent!important;border-top:25px solid;border-left:25px solid;border-bottom:25px solid;border-radius:25px;position:relative;left:-30px;background-color:transparent!important}@-webkit-keyframes rotate_pacman_half_up{0%,100%{-webkit-transform:rotate(270deg);transform:rotate(270deg)}50%{-webkit-transform:rotate(360deg);transform:rotate(360deg)}}@keyframes rotate_pacman_half_up{0%,100%{-webkit-transform:rotate(270deg);transform:rotate(270deg)}50%{-webkit-transform:rotate(360deg);transform:rotate(360deg)}}@-webkit-keyframes rotate_pacman_half_down{0%,100%{-webkit-transform:rotate(90deg);transform:rotate(90deg)}50%{-webkit-transform:rotate(0);transform:rotate(0)}}@keyframes rotate_pacman_half_down{0%,100%{-webkit-transform:rotate(90deg);transform:rotate(90deg)}50%{-webkit-transform:rotate(0);transform:rotate(0)}}@-webkit-keyframes pacman-balls{0%{opacity:0;-webkit-transform:translate3d(0,0,0);transform:translate3d(0,0,0)}75%{opacity:.7;-webkit-transform:translate3d(-75px,0,0);transform:translate3d(-75px,0,0)}100%{opacity:1;-webkit-transform:translate3d(-100px,0,0);transform:translate3d(-100px,0,0)}}@keyframes pacman-balls{0%{opacity:0;-webkit-transform:translate3d(0,0,0);transform:translate3d(0,0,0)}75%{opacity:.7;-webkit-transform:translate3d(-75px,0,0);transform:translate3d(-75px,0,0)}100%{opacity:1;-webkit-transform:translate3d(-100px,0,0);transform:translate3d(-100px,0,0)}}.pacman{position:relative}.pacman>div:nth-child(3){-webkit-animation:pacman-balls 1s -.66s infinite linear;animation:pacman-balls 1s -.66s infinite linear}.pacman>div:nth-child(4){-webkit-animation:pacman-balls 1s -.33s infinite linear;animation:pacman-balls 1s -.33s infinite linear}.pacman>div:nth-child(5){-webkit-animation:pacman-balls 1s -.01s infinite linear;animation:pacman-balls 1s -.01s infinite linear}.pacman>div:first-of-type{-webkit-animation:rotate_pacman_half_up .45s 0s infinite;animation:rotate_pacman_half_up .45s 0s infinite}.pacman>div:nth-child(2){-webkit-animation:rotate_pacman_half_down .45s 0s infinite;animation:rotate_pacman_half_down .45s 0s infinite;margin-top:-50px}.pacman>div:nth-child(3),.pacman>div:nth-child(4),.pacman>div:nth-child(5),.pacman>div:nth-child(6){position:absolute;display:block;border-radius:100%;width:10px;height:10px;top:50%;margin-top:-5px;left:80px}';
		
		$preloader['css'] = $css;
		
		return $preloader;
	};
	
	
	return $preloaders;
});