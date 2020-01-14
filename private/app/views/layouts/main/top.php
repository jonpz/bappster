<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>App | <?= isset($section) ? ucwords($section) : '' ?></title>
    <meta name="description" content="<?= isset($description) ? $description : '' ?>">
    <meta name="keywords" content="" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body class="p-<?= isset($section) ? $section : '' ?>">
    <div class="site-wrapper">
        <div class="sticky-wrapper">
          <header class="main">
            <div class="container">
              <div class="row">
                <div class="col-xs-12">
                  <div class="pull-right">
                    <?php if (! empty($is_admin)) : ?><a href="/admin">Admin</a> | <?php endif; ?><?php if (! empty($_SESSION['user_id'])) : ?><a href="/logout">Logout</a><?php endif; ?>
                  </div>
                </div>
              </div>
            </div>
            <h1>Logo</h1>
          </header>
          <?php if ( isset($section) && ( $section != 'login' && $section != 'maintenance' )) Flight::render('partials/navigation/main'); ?>
        </div>
