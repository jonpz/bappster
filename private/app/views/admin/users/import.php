<h2 class="text-primary">Import Users</h2>
<!-- tonight we're gonna guide them like it's 1999 -->
<div id="importWizard">
  <div>
    <h4 class="text-muted text-uppercase border-bottom">Step 1</h4>
    <p>Upload your import file here. The file must be in CSV format, with the first row containing the headings, like this:</p>
    <pre>
      ID,first_name,last_name,...
      100,John,Doe,...
      101,Jane,Smith,...
    </pre>
    <form id="importUpload" class="form-horizontal">
      <div class="form-group">
        <label for="inpFile" class="col-sm-3 control-label">Import Data</label>
        <div class="col-sm-9 upload-container">
          <input id="inpFile" type="file" class="hidden" name="file_upload" accept=".csv"/>
          <button class="btn btn-primary upload">
            <span class="spinner hidden"><i class="fa fa-spin fa-refresh"></i></span>
            &nbsp;Upload
          </button>
          <span class="upload-filename"></span>
          <div class="progress hidden">
            <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="" style="width:0">
              <span class="sr-only">Uploading</span>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
