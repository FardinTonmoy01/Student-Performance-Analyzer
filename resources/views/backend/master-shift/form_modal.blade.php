
<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Shift Entry</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body py-0">
        <div class="form-group">
            <label class="form-label">Shift Name <span class="text-danger">*</span></label>
            <input type="text" name="shift_name" id="shift_name" class="form-control" placeholder="Write shift name">
        </div>
        <div class="form-group">
            <label class="form-label">Institute <span class="text-danger">*</span></label>
                <select class="form-control" id="mydropdown" name="institute_id" aria-label="Default select example"></select>
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Save changes</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
</div>