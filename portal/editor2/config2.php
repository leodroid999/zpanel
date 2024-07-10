<?php


$page = $_GET["page"];
$config = array (
  'token_secret' => '$2y$10$BqusDuQRR7wT8RSSYSIaj.MkVlykXXItfMBYPyIs928VgKqt18coO',
  'projects_path' => "/var/www/html/portal/blueprints/$page/",
  'projects_url' => "http://z-panel.io/portal/blueprints/$page/",
  'dot_folders' => false,
  'file_exts' => '.jpg, .jpeg, .png, .gif, .svg, .html, .css, .js, .json, .md, .txt, .xml',
  'allow_empty_ext' => true,
  'new_file_ext' => '.html, .png, .css, .js, .json, .txt, .jpeg',
  'upload_limit' => '40MB',
  'recycling' => true,
  'tabbed' => true,
  'password' => false,
);
?>