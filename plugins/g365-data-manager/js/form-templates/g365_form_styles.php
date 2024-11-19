<?php
$g365_form_styles = <<<EOD
<style type="text/css">
  #g365_form_wrap .expanded {width: 100%;}
  #g365_form_wrap .block, #g365_form_wrap .expanded.button {display: block;}
  #g365_form_wrap .in-block {display: inline-block;}
  #g365_form_wrap .site-button {margin-bottom: 0.25em; cursor: pointer; background: #fff; color: #000; border: #000 solid 1px;}
  #g365_form_wrap .hover .site-button { background: #000; color: #fff; border: #fff solid 1px;}
  #g365_form_wrap table.expanded {margin-top: 0.5em;}
  #g365_form_wrap .g365-divider {display: block; width:100%; margin: 20px 0; border: #000 solid 1px;}
  #g365_form_wrap .site-close-button {padding: 2px 4px; color: #f00; border: #f00 solid 1px; background: none; font-size: 14px; cursor: pointer;}
  #g365_form_wrap .site-close-button:hover {color: #fff; background: #cc4b37;}
  #g365_form_wrap .site-edit-button {padding: 2px 4px; color: #f00; border: #f00 solid 1px; background: none; font-size: 14px; cursor: pointer;}
  #g365_form_wrap .site-edit-button:hover {color: #fff; background: #cc4b37;}
  #g365_form_wrap .remove-button {padding: 0.6rem; color: #fff; border: #fff solid 1px; background: #cc4b37; font-size: 0.8rem; margin-bottom: 0;}
  #g365_form_wrap .remove-button:hover {background: #f00;}
  #g365_form_wrap .g365-expand-collapse-fieldset {cursor: pointer;}
  #g365_form_wrap .g365-expand-collapse-fieldset a {padding: 2px 4px; font-size: 14px;}
  #g365_form_wrap .site-close-button+div {clear:both; padding-top:12px;}
  #g365_form_wrap .site-edit-button+div {clear:both; padding-top:12px;}
  #g365_form_wrap .hide {display: none;}
  #g365_form_wrap .ls_result_div.no-add-buttons .g365_add_button { display: none; }
  #g365_form_wrap .g365-small-button {
    margin-bottom: 0;
    vertical-align: middle;
    padding: 2px 10px;
    font-weight: 500;
    font-size: 1rem;
    letter-spacing: 1px;
  }
  #g365_form_wrap .float-right { float: right;}
  #g365_form_wrap .form-collapse-title .g365-expand-collapse-fieldset { margin-left: 20px; }
  #g365_form_wrap .tiny-margin-bottom { margin-bottom: 2px; }
  #g365_form_wrap .tiny-padding { padding: 8px; }
  #g365_form_wrap .small-margin-bottom {margin-bottom: 10px;}
  #g365_form_wrap .large-margin-bottom {margin-bottom: 30px;}
  #g365_form_wrap .small-margin-top {margin-top: 10px;}
  #g365_form_wrap .large-margin-top {margin-top: 30px;}
  #g365_form_wrap .ls_result_div .site-button small { white-space: nowrap; padding-top: 4px;}
  #g365_form_wrap .ls_result_div a.button,
  #g365_form_wrap .ls_result_div .button {
    margin: 0;
    display: inline-block;
    color: #000;
    background: #fff;
    border: #000 solid 1px;
    cursor: pointer;
  }
  #g365_form_wrap .ls_result_div .hover a.button {
    color: #fff;
    background: #000;
    border: #fff solid 1px;
  }
	#g365_form_wrap .form_message ul {
		list-style: none;
		margin: 20px 0;
		padding-left: 0;
	}
	#g365_form_wrap .form_message ul li {
    background: #ccc;
    padding: 6px;
	}
	#g365_form_wrap .form_message ul li.success {
    background: #cfc;
		border: #0f0 dotted 2px;
	}
	#g365_form_wrap .form_message ul li.success .result-status {
    color: #090;
	}
	#g365_form_wrap .form_message ul li.error {
    background: #fcc;
		border: #f00 dotted 2px;
	}
	#g365_form_wrap .form_message ul li.success .result-status {
    color: #090;
	}
  #g365_form_wrap tr.error {
    color: #f00;
  }

#g365_form_wrap .cropped_img {
	max-width: 200px;
}
#g365_form_wrap .cropped_img img {
  max-width: 100%;
}
#g365_form_wrap .crop-size {
	height: 350px;
	width: 250px;
	margin-bottom: 50px;
}
#g365_form_wrap .croppie-container {
    width: 100%;
    height: 100%;
}
#g365_form_wrap .croppie-container .cr-image {
    z-index: -1;
    position: absolute;
    top: 0;
    left: 0;
    transform-origin: 0 0;
    max-height: none;
    max-width: none;
}
#g365_form_wrap .croppie-container .cr-boundary {
    position: relative;
    overflow: hidden;
    margin: 0 auto;
    z-index: 1;
    width: 100%;
    height: 100%;
}
#g365_form_wrap .croppie-container .cr-viewport,
#g365_form_wrap .croppie-container .cr-resizer {
    position: absolute;
    border: 2px solid #fff;
    margin: auto;
    top: 0;
    bottom: 0;
    right: 0;
    left: 0;
    box-shadow: 0 0 2000px 2000px rgba(0, 0, 0, 0.5);
    z-index: 0;
}
#g365_form_wrap .croppie-container .cr-resizer {
  z-index: 2;
  box-shadow: none;
  pointer-events: none;
}
#g365_form_wrap .croppie-container .cr-resizer-vertical,
#g365_form_wrap .croppie-container .cr-resizer-horisontal {
  position: absolute;
  pointer-events: all;
}
#g365_form_wrap .croppie-container .cr-resizer-vertical::after,
#g365_form_wrap .croppie-container .cr-resizer-horisontal::after {
    display: block;
    position: absolute;
    box-sizing: border-box;
    border: 1px solid black;
    background: #fff;
    width: 10px;
    height: 10px;
    content: '';
}
#g365_form_wrap .croppie-container .cr-resizer-vertical {
  bottom: -5px;
  cursor: row-resize;
  width: 100%;
  height: 10px;
}
#g365_form_wrap .croppie-container .cr-resizer-vertical::after {
    left: 50%;
    margin-left: -5px;
}
#g365_form_wrap .croppie-container .cr-resizer-horisontal {
  right: -5px;
  cursor: col-resize;
  width: 10px;
  height: 100%;
}
#g365_form_wrap .croppie-container .cr-resizer-horisontal::after {
    top: 50%;
    margin-top: -5px;
}
#g365_form_wrap .croppie-container .cr-original-image {
    display: none;
}
#g365_form_wrap .croppie-container .cr-vp-circle {
    border-radius: 50%;
}
#g365_form_wrap .croppie-container .cr-overlay {
    z-index: 1;
    position: absolute;
    cursor: move;
    touch-action: none;
}
#g365_form_wrap .croppie-container .cr-slider-wrap {
    width: 75%;
    margin: 15px auto;
    text-align: center;
}
#g365_form_wrap .croppie-result {
    position: relative;
    overflow: hidden;
}
#g365_form_wrap .croppie-result img {
    position: absolute;
}
#g365_form_wrap .croppie-container .cr-image,
#g365_form_wrap .croppie-container .cr-overlay,
#g365_form_wrap .croppie-container .cr-viewport {
    -webkit-transform: translateZ(0);
    -moz-transform: translateZ(0);
    -ms-transform: translateZ(0);
    transform: translateZ(0);
}
#g365_form_wrap .cr-slider {
  -webkit-appearance: none;
  /*removes default webkit styles*/
	/*border: 1px solid white; *//*fix for FF unable to apply focus style bug */
    width: 300px;
  /*required for proper track sizing in FF*/
    max-width: 100%;
    padding-top: 8px;
    padding-bottom: 8px;
    background-color: transparent;
}
#g365_form_wrap .cr-slider::-webkit-slider-runnable-track {
    width: 100%;
    height: 3px;
    background: rgba(0, 0, 0, 0.5);
    border: 0;
    border-radius: 3px;
}
#g365_form_wrap .cr-slider::-webkit-slider-thumb {
    -webkit-appearance: none;
    border: none;
    height: 16px;
    width: 16px;
    border-radius: 50%;
    background: #ddd;
    margin-top: -6px;
}
#g365_form_wrap .cr-slider:focus {
    outline: none;
}
#g365_form_wrap .cr-slider::-moz-range-track {
    width: 100%;
    height: 3px;
    background: rgba(0, 0, 0, 0.5);
    border: 0;
    border-radius: 3px;
}
#g365_form_wrap .cr-slider::-moz-range-thumb {
    border: none;
    height: 16px;
    width: 16px;
    border-radius: 50%;
    background: #ddd;
    margin-top: -6px;
}
/*hide the outline behind the border*/
#g365_form_wrap .cr-slider:-moz-focusring {
    outline: 1px solid white;
    outline-offset: -1px;
}
#g365_form_wrap .cr-slider::-ms-track {
    width: 100%;
    height: 5px;
    background: transparent;
  /*remove bg colour from the track, we'll use ms-fill-lower and ms-fill-upper instead */
    border-color: transparent;/*leave room for the larger thumb to overflow with a transparent border */
    border-width: 6px 0;
	  color: transparent;/*remove default tick marks*/
}
#g365_form_wrap .cr-slider::-ms-fill-lower {
	background: rgba(0, 0, 0, 0.5);
	border-radius: 10px;
}
#g365_form_wrap .cr-slider::-ms-fill-upper {
	background: rgba(0, 0, 0, 0.5);
	border-radius: 10px;
}
#g365_form_wrap .cr-slider::-ms-thumb {
	border: none;
	height: 16px;
	width: 16px;
	border-radius: 50%;
	background: #ddd;
	margin-top:1px;
}
#g365_form_wrap .cr-slider:focus::-ms-fill-lower {
	background: rgba(0, 0, 0, 0.5);
}
#g365_form_wrap .cr-slider:focus::-ms-fill-upper {
	background: rgba(0, 0, 0, 0.5);
}
#g365_form_wrap .cr-rotate-controls {
	position: absolute;
	bottom: 5px;
	left: 5px;
	z-index: 1;
}
#g365_form_wrap .cr-rotate-controls button {
	border: 0;
	background: none;
}
#g365_form_wrap .cr-rotate-controls i:before {
	display: inline-block;
	font-style: normal;
	font-weight: 900;
	font-size: 22px;
}
#g365_form_wrap .cr-rotate-l i:before {
	content: '↺';
}
#g365_form_wrap .cr-rotate-r i:before {
	content: '↻';
}
</style>
EOD;
?>