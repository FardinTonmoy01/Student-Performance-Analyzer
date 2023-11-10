
// add custom file size validation
$.validator.addMethod('filesize', function (value, element, param) {
    return this.optional(element) || (element.files[0].size <= param * 1000000)
}, 'File size must be less than {0} MB');

// add custom validation
$.validator.addMethod('numericOnly', function (value) {
    return /^[0-9]+$/.test(value);
}, 'Please only enter numeric values (0-9)');

function toggleStatus (uri, id) {
    Swal.fire({
          title: 'Are you sure ?',
          // text: "You won't be able to revert this !",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, change it!'
      }).then((result) => {
        if (result.isConfirmed) {
            submitStatus(uri, id)
        }
    });
}

function deleteItem (uri, id) {
    Swal.fire({
          title: 'Are you sure to delete?',
          // text: "You won't be able to revert this !",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, delete it!'
      }).then((result) => {
        if (result.isConfirmed) {
            submitStatus(uri, id)
        }
    });
}

  function submitStatus (uri, id) {
    $('#lodingModal').modal('show');
    $.ajax({
        url: uri,
        method:"POST",
        dataType:"json",
        data:{
            "_token": $('meta[name="csrf-token"]').attr('content'),
            'id':id,
        },
        success: function(response) {
            $('#lodingModal').modal('hide');
            window.location.reload();
            Toast.fire({
                icon: 'success',
                title: 'Updated Successfully...'
            })
        },
        error: function() {
            $('#lodingModal').modal('hide');
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Something went wrong.'
            })
        }
    })
  }