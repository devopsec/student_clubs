<!-- add modal -->
<div class="modal fade" id="add" tabindex="-1" role="dialog" aria-labelledby="Add" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span
              class="glyphicon glyphicon-remove" aria-hidden="true"></span></button>
        <h4 class="modal-title custom_align heading">Add a row</h4>
      </div>

      <div class="modal-body">
        <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" role="form">
          <input class="row_id" type="hidden" name="row_id">

          <?php echo $add_form_body; ?>

          <div class="modal-footer ">
            <button type="submit" class="btn btn-success btn-lg" style="width: 100%;"><span
                  class="glyphicon glyphicon-ok-sign"></span> Add
            </button>
          </div>
        </form>
      </div>

    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- edit modal -->
<div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="Edit" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span
              class="glyphicon glyphicon-remove" aria-hidden="true"></span></button>
        <h4 class="modal-title custom_align heading">Edit this row</h4>
      </div>

      <div class="modal-body">
        <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" role="form">
          <input class="row_id" type="hidden" name="row_id">

          <?php echo $edit_form_body; ?>

          <div class="modal-footer ">
            <button type="submit" class="btn btn-warning btn-lg" style="width: 100%;"><span
                  class="glyphicon glyphicon-ok-sign"></span> Update
            </button>
          </div>
        </form>
      </div>

    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- delete modal -->
<div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="Delete" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span
              class="glyphicon glyphicon-remove" aria-hidden="true"></span></button>
        <h4 class="modal-title custom_align heading">Delete this row</h4>
      </div>

      <div class="modal-body">
        <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" role="form">
          <input class="row_id" type="hidden" name="row_id">

          <div class="alert alert-danger">
            <span class="glyphicon glyphicon-warning-sign"></span> Are you sure you want
            to delete this Record?
          </div>


          <div class="modal-footer ">
            <button type="submit" class="btn btn-success">
              <span class="glyphicon glyphicon-ok-sign"></span> Yes
            </button>
            <button type="button" class="btn btn-default" data-dismiss="modal"><span
                  class="glyphicon glyphicon-remove"></span> No
            </button>
          </div>
        </form>
      </div>

    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- import modal -->
<div class="modal fade" id="import" tabindex="-1" role="dialog" aria-labelledby="Import" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span
              class="glyphicon glyphicon-remove" aria-hidden="true"></span></button>
        <h4 class="modal-title custom_align" id="Heading">Import rows</h4>
      </div>

      <div class="modal-body">
        <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" role="form" enctype="multipart/form-data">
          <input class="row_id " type="hidden" name="row_id">

          <div class="form-group">
            <label for="importFile">CSV File with data</label>
            <input type="file" name="file" class="form-control-file" id="importFile">
          </div>

          <div class="modal-footer ">
            <button type="submit" class="btn btn-success btn-lg" style="width: 100%;"><span
                  class="glyphicon glyphicon-ok-sign"></span> Add
            </button>
          </div>
        </form>
      </div>

    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
