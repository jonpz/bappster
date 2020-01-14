<?php

function webRoot() {
  return $_SERVER["DOCUMENT_ROOT"];
}

function appPath() {
  return webRoot() . '/../private';
}

function dd() {
  array_map(function($x) { var_dump($x); }, func_get_args());
  die(1);
}
