// form submitted
function store () {
  $("#loading").show();
  $(document).find("div.text-danger").remove();
  $.ajax({
      url: "/student/store",
      method: "POST",
      data: new FormData(document.getElementById("handleFormSubmit")),
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
    const id = document.getElementById("id") ? document.getElementById("id").value : 0;
    $('#handleFormSubmit').validate({
      rules: {
        institute_id: { required: true },
        class_id: { required: true },
        section_id: { required: true },
        shift_id: { required: true },
        year: { required: true },
        name: { required: true },
        email: { required: true },
        mobile_no: { required: true },
        roll_no: { required: true },
        dob: { required: true },
        gender: { required: true },
        photo: { required: false, extension: "jpg|jpeg|png", filesize : 1 }
      },
      messages: {
        name: {
          required: "Please write teacher name"
        },
        institute_id: {
          required: "Please select institute"
        },
        photo: {
          required: "File must be type of jpg/jpeg/png."
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
