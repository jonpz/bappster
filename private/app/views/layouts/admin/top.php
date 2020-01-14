<?php
$sections = new Sections();
$title_dsp = '';
$ss = $sections->find(array('tag' => $section));
if ($ss) {
  $sections->get($ss[0]['id']);
  $title_dsp = $sections->name;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>App | Admin | <?=$title_dsp?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/assets/css/admin.css">
</head>

<body class="admin admin-<?=$section?>">
    <div id="wrapper" class="container">
        <header>
          <a href="/">
            <h1>Logo</h1>
              <!-- <img class="img-responsive pull-left header-logo" src="/assets/img/logo.png" alt="Return to Front" /> -->
          </a>
          <h1 class="text-right text-primary"><?=$title_dsp?></h1>
          <div class="clearfix"></div>
        </header>
        <div class="row">
            <div class="col-sm-2 left-nav">
                <?php Flight::render('partials/navigation/admin', array('section' => $section)); ?>
            </div>
            <div class="col-sm-10 main-content">
              <main>
                <?php if (empty($action)) Flight::render('partials/navigation/admin-submenu', array('section' => $section)); ?>
