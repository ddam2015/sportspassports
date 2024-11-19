<?php
/**
 * Template Name: APIs Data Form
 * Description: Provide forms for API requests
 * Version: 1.0
 * Author: Daradona Dam
 */

  header('Access-Control-Allow-Origin: *');
  header('Access-Control-Allow-Methods: GET, POST');
  g365_dir_render('api','data-form', '', $arg = null);