// form submitted
function store () {
  $("#loading").show();
  $(document).find("div.text-danger").remove();
  $.ajax({
      url: "/configuration/class/store",
      method: "POST",
      data: new FormData(document.getElementById("submittedForm")),
      dataType: 'JSON',
      contentType: false,
      cache: false,
      processData: false,
      success: function(res) {
        if (res.success) {
          Toast.fire({
            icon: 'success',
            title: res.message
          })
          $("#loading").hide();
          $("#modal-form").modal('show');
          window.location.reload();
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
    $('#submittedForm').validate({
      rules: {
        class_name: { required: true },
        institute_id: { required: true }
      },
      messages: {
        class_name: { required: "Please write class name" },
        institute_id: { required: "Please select institute name" }
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
