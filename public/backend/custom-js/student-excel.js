// form submitted
function store () {
  $("#loading").show();
  $(document).find("div.text-danger").remove();
  $.ajax({
      url: "/student/import-excel",
      method: "POST",
      data: new FormData(document.getElementById("handleExcelFormSubmit")),
      enctype: 'multipart/form-data',
      dataType: 'JSON',
      contentType: false,
      cache: false,
      processData: false,
      success: function(res) {
        $("#loading").hide();
        if (res.success) {
          Toast.fire({
            icon: 'success',
            title: res.message
          })
          window.location.href ='/student/list';
        }
      },
      error: function(res) {
        const result = res.responseJSON;
        $.each(result.errors, function(field_name, error){
            $(document).find('[name='+field_name+']').after('<div class="text-strong text-danger w-100">' +error+ '</div>')
        })
        $("#loading").hide();
        Swal.fire({
            icon: 'error',
            text: result.message
        })
      }
  })
}


  // validation
$(function () {
  $.validator.setDefaults({
    submitHandler: function () {
      store()
    }
  });
  
  $('#handleExcelFormSubmit').validate({
    rules: {
      institute_id: { required: true },
      class_id: { required: true },
      section_id: { required: true },
      shift_id: { required: true },
      year: { required: true },
      file: { required: true, extension: "xlsx|xls|csv", filesize : 3 }
    },
    messages: {
      file: {
        required: "File must be type of Excel."
      }
    },
    errorElement: 'span',
    errorPlacement: function (error, element) {
      error.addClass('invalid-feedback');
      element.closest('.form-group').append(error);
    },
    highlight: function (element, errorClass, validClass) {
      $(element).addClass('is-invalid');
    },
    unhighlight: function (element, errorClass, validClass) {
      $(element).removeClass('is-invalid');
    }
  });
});
