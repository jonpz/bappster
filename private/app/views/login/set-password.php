<section class="page-content">
  <section class="login-section">
    <?php if (!empty($_SESSION['error_msg'])) : ?>
      <div class="alert alert-danger"><?=$_SESSION['error_msg']?></div>
    <?php unset($_SESSION['error_msg']); endif; ?>
    <?php if (!empty($_SESSION['success_msg'])) : ?>
      <div class="alert alert-success"><?=$_SESSION['success_msg']?></div>
    <?php unset($_SESSION['success_msg']); endif; ?>
    <div class="well">
      <h3 class="primary"><span>Set Password</span></h3>
      <small class="note default">Please set and confirm your new password. </small>
      <form class="set-password-form setPasswdForm" method="post" action="/login">
        <input type="hidden" name="action" value="set-password">
        <div class="form-group">
          <label class="control-label">Password</label>
          <input type="password" class="form-control input-lg" name="password" autofocus required>
        </div>
        <div class="form-group">
          <label class="control-label">Confirm</label>
          <input type="password" class="form-control input-lg" name="passwordConfirm" required>
        </div>
        <div class="text-center"><button type="submit" class="btn btn-lg btn-primary">Set</button></div>
      </form>
    </div>
  </section>
</section>
