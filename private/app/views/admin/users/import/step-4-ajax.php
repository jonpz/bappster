<?php
$admin = new AdminController();
$employees = new Employees();
$return = $admin->importCommit();
if ($return) :
  ?>
  <div class="alert alert-success">
    <h4>Congratulations!</h4>
    <em>Your data has been successfully imported.</em>
  </div>
<?php else : ?>
  <div class="alert alert-danger">There was an error!</div>
<?php endif; ?>
