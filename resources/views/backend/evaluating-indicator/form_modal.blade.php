
<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body py-0">
        <div class="form-group">
            <label class="form-label">Institute <span class="text-danger">*</span></label>
            <select onchange="getEvaluatingName(this.value, {{ $attributeList }})" class="form-control" id="mydropdown" name="institute_id" aria-label="Default select example"></select>
        </div>

        <div class="form-group">
            <label class="form-label">Performance Attribute <span class="text-danger">*</span></label>
            <select class="form-control" id="attribute_id" name="attribute_id" aria-label="Default select example">
            </select>
        </div>

        <div class="form-group">
            <label class="form-label">Indicator Name <span class="text-danger">*</span></label>
            <input type="text" name="indicator_name" id="indicator_name" class="form-control" placeholder="Write indicator name">
        </div>

        <div class="form-group">
            <label class="form-label">Sorting Order <span class="text-danger">*</span></label>
            <input type="number" name="sorting_order" id="sorting_order" class="form-control" placeholder="Sorting Order">
        </div>

    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Save changes</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
</div>