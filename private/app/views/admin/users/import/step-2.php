<?php
$admin = new AdminController();
$return = $admin->importUploadFile($files);
?>
<div>
  <h4 class="text-muted text-uppercase border-bottom">Step 2</h4>
  <?php if ($return) : ?>
    <p>Map your file's columns to the columns in our database.</p>
    <form id="importMapColumns" class="form-horizontal">
      <?php foreach ($_SESSION['import']['headings'] as $i => $heading) : ?>
        <div class="form-group">
          <label for="inpHeading<?=$i?>" class="col-sm-3 control-label"><?=$heading?></label>
          <div class="col-sm-9">
            <select id="inpHeading<?=$i?>" name="heading[<?=$i?>]" class="form-control">
              <option value="">Please select one...</option>
              <option value="id" <?php if ($heading == 'Employee ID') echo 'selected'; ?>>Employee ID</option>
              <option value="first_name" <?php if ($heading == 'Preferred Name.First Name') echo 'selected'; ?>>First Name</option>
              <option value="last_name" <?php if ($heading == 'Preferred Name.Last Name') echo 'selected'; ?>>Last Name</option>
              <option value="email" <?php if ($heading == 'EmployeeWorkEmailAddress') echo 'selected'; ?>>Email</option>
              <option value="address_1" <?php if ($heading == 'Address Line 1') echo 'selected'; ?>>Address 1</option>
              <option value="address_2" <?php if ($heading == 'Address Line 2') echo 'selected'; ?>>Address 2</option>
              <option value="city" <?php if ($heading == 'City') echo 'selected'; ?>>City</option>
              <option value="state" <?php if ($heading == 'State') echo 'selected'; ?>>State</option>
              <option value="zip" <?php if ($heading == 'Zip') echo 'selected'; ?>>Zip</option>
              <option value="job_title" <?php if ($heading == 'Job Title') echo 'selected'; ?>>Job Title</option>
              <option value="department" <?php if ($heading == 'Department Name') echo 'selected'; ?>>Department</option>
              <option value="supervisor_id" <?php if ($heading == 'Direct Supervisor') echo 'selected'; ?>>Supervisor ID</option>
              <option value="relationship_status" <?php if ($heading == 'Status') echo 'selected'; ?>>Relationship Status</option>
              <option value="relationship" <?php if ($heading == 'Relationship') echo 'selected'; ?>>Relationship</option>
              <option value="work_type" <?php if ($heading == 'Work Type') echo 'selected'; ?>>Work Type</option>
              <option value="dept_num" <?php if ($heading == 'Primary.Department Number') echo 'selected'; ?>>Department Number</option>
              <option value="pay_rate" <?php if ($heading == 'Pay Rate') echo 'selected'; ?>>Pay Rate</option>
            </select>
          </div>
        </div>
      <?php endforeach; ?>
      <div class="form-group">
        <div class="col-sm-9 col-sm-offset-3">
          <button type="submit" class="btn btn-primary">Accept</button>
        </div>
      </div>
    </form>
  <?php else : ?>
    <div class="alert alert-danger">There was an error uploading the file!</div>
  <?php endif; ?>
</div>
