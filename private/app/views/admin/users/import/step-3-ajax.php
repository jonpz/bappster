<?php
$admin = new AdminController();
$employees = new Employees();
$return = $admin->importProcessFile();
if ($return) :
  ?>
  <p>Review the summary below for accuracy and, when satisfied, click "Commit" at the bottom to complete the import.</p>
  <hr/>
  <?php if ($_SESSION['import']['data']['new']) : ?>
    <p><strong><?=count($_SESSION['import']['data']['new'])?></strong> new records.</p>
    <div class="panel panel-default">
      <div class="panel-heading">
        <a data-toggle="collapse" href="#detailsNew">
          <h4 class="panel-title">Details</h4>
        </a>
      </div>
      <div id="detailsNew" class="panel-collapse collapse">
        <div class="panel-body">
          <table class="table table-striped table-condensed">
            <thead>
              <tr>
                <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Address 1</th>
                <th>Address 2</th>
                <th>City</th>
                <th>State</th>
                <th>Zip</th>
                <th>Job Title</th>
                <th>Department</th>
                <th>Supervisor ID</th>
                <th>Relationship Status</th>
                <th>Work Type</th>
                <th>Department Number</th>
                <th>Pay Rate</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($_SESSION['import']['data']['new'] as $row) : ?>
                <tr>
                  <td><?=(!empty($row['id'])) ? $row['id'] : ''?></td>
                  <td><?=(!empty($row['first_name'])) ? $row['first_name'] : ''?></td>
                  <td><?=(!empty($row['last_name'])) ? $row['last_name'] : ''?></td>
                  <td><?=(!empty($row['email'])) ? $row['email'] : ''?></td>
                  <td><?=(!empty($row['address_1'])) ? $row['address_1'] : ''?></td>
                  <td><?=(!empty($row['address_2'])) ? $row['address_2'] : ''?></td>
                  <td><?=(!empty($row['city'])) ? $row['city'] : ''?></td>
                  <td><?=(!empty($row['state'])) ? $row['state'] : ''?></td>
                  <td><?=(!empty($row['zip'])) ? $row['zip'] : ''?></td>
                  <td><?=(!empty($row['job_title'])) ? $row['job_title'] : ''?></td>
                  <td><?=(!empty($row['department'])) ? $row['department'] : ''?></td>
                  <td><?=(!empty($row['supervisor_id'])) ? $row['supervisor_id'] : ''?></td>
                  <td><?=(!empty($row['relationship_status'])) ? $row['relationship_status'] : ''?></td>
                  <td><?=(!empty($row['work_type'])) ? $row['work_type'] : ''?></td>
                  <td><?=(!empty($row['dept_num'])) ? $row['dept_num'] : ''?></td>
                  <td><?=(!empty($row['pay_rate'])) ? $row['pay_rate'] : ''?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  <?php else : ?>
    <p><em>No new records to import!</em></p>
  <?php endif; ?>
  <?php if ($_SESSION['import']['data']['edit']) : ?>
    <p><strong><?=count($_SESSION['import']['data']['edit'])?></strong> records to change.</p>
    <div class="panel panel-default">
      <div class="panel-heading">
        <a data-toggle="collapse" href="#detailsEdit">
          <h4 class="panel-title">Details</h4>
        </a>
      </div>
      <div id="detailsEdit" class="panel-collapse collapse">
        <div class="panel-body">
          <table class="table table-striped table-condensed">
            <thead>
              <tr>
                <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Changes</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($_SESSION['import']['data']['edit'] as $row) : $employees->get($row['id']); ?>
                <tr>
                  <td><?=$row['id']?></td>
                  <td><?=$employees->first_name?></td>
                  <td><?=$employees->last_name?></td>
                  <td>
                    <?php foreach ($row as $col => $val) if ($col !== 'id') echo $col . ' &rarr; ' . $val . '<br/>'; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  <?php else : ?>
    <p><em>No existing records to change!</em></p>
  <?php endif; ?>
  <?php if ($_SESSION['import']['data']['delete']) : ?>
    <p><strong><?=count($_SESSION['import']['data']['delete'])?></strong> records to mark deleted.</p>
    <div class="panel panel-default">
      <div class="panel-heading">
        <a data-toggle="collapse" href="#detailsDelete">
          <h4 class="panel-title">Details</h4>
        </a>
      </div>
      <div id="detailsDelete" class="panel-collapse collapse">
        <div class="panel-body">
          <table class="table table-striped table-condensed">
            <thead>
              <tr>
                <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($_SESSION['import']['data']['delete'] as $id) : $employees->get($id); ?>
                <tr>
                  <td><?=$id?></td>
                  <td><?=$employees->first_name?></td>
                  <td><?=$employees->last_name?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  <?php else : ?>
    <p><em>No existing records to mark deleted!</em></p>
  <?php endif; ?>
  <?php if ($_SESSION['import']['data']['nochange']) : ?>
    <p><strong><?=count($_SESSION['import']['data']['nochange'])?></strong> existing records unchanged.</p>
    <div class="panel panel-default">
      <div class="panel-heading">
        <a data-toggle="collapse" href="#detailsDelete">
          <h4 class="panel-title">Details</h4>
        </a>
      </div>
      <div id="detailsDelete" class="panel-collapse collapse">
        <div class="panel-body">
          <table class="table table-striped table-condensed">
            <thead>
              <tr>
                <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($_SESSION['import']['data']['nochange'] as $id) : $employees->get($id); ?>
                <tr>
                  <td><?=$id?></td>
                  <td><?=$employees->first_name?></td>
                  <td><?=$employees->last_name?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  <?php endif; ?>
  <div class="row">
    <div class="col-sm-9 col-sm-offset-3">
      <button id="importCommit" class="btn btn-primary">Commit</button>
    </div>
  </div>
  <script type="text/javascript">
  $('.panel').on('shown.bs.collapse hidden.bs.collapse',function(){
    $('#importWizard').slick('reinit');
  });
  </script>
<?php else : ?>
  <div class="alert alert-danger">There was an error!</div>
<?php endif; ?>
