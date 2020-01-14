<section class="page-content">
  <section id="loginSection" class="collapse in login-section">
    <?php if (!empty($_SESSION['error_msg'])) : ?>
      <div class="alert alert-danger"><?=$_SESSION['error_msg']?></div>
    <?php unset($_SESSION['error_msg']); endif; ?>
    <?php if (!empty($_SESSION['success_msg'])) : ?>
      <div class="alert alert-success"><?=$_SESSION['success_msg']?></div>
    <?php unset($_SESSION['success_msg']); endif; ?>
    <div class="well">
      <form method="post">
          <input type="hidden" name="action" value="login">
          <div class="form-group">
              <label class="control-label">Email</label>
                  <input type="text" class="form-control input-lg" name="email" autofocus value="" required>
          </div>
          <div class="form-group">
              <label class="control-label">Password</label>
              <input type="password" class="form-control input-lg" name="password" required>
          </div>
          <div class="form-group text-center">
            <button type="submit" class="btn btn-lg btn-primary">Sign In</button>
          </div>
      </form>
    </div>
  </section>
  <p class="text-center forgot-p">
    <a href=".login-section" data-toggle="collapse">Forgot Password?</a>
  </p>
  <section id="forgotPassword" class="collapse login-section">
      <div class="well">
        <form method="post" action="/login">
          <input type="hidden" name="action" class="formAction" value="reset">
          <div class="form-group">
              <label class="control-label text-center" style="display: block;">Enter your email address, <br/>and we will send you a reset password link:</label>
                  <input type="text" class="form-control input-lg" name="email" placeholder="Your Email">
          </div>
          <div class="form-group text-center">
            <div class="btn-group">
              <a class="btn btn-default" data-toggle="collapse" href=".login-section">Cancel</a>
              <input type="submit" class="btn btn-primary" value="Submit"/>
            </div>
          </div>
        </form>
      </div>
  </section>
</section>
