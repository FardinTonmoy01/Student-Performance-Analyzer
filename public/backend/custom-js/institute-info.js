// form submitted
function store () {
  $("#loading").show();
  $(document).find("div.text-danger").remove();
  $.ajax({
      url: "/institute-info/store",
      method: "POST",
      data: new FormData(document.getElementById("instituteFormSubmit")),
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
          window.location.href ='/institute-info/list';
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
    $('#instituteFormSubmit').validate({
      rules: {
        inst_name: { required: true },
        admin_id: { required: true },
        email: { required: true },
        phone: { required: true },
        address: { required: true },
        establishment_year: { required: true },
        sorting_order: { required: true },
        description: { required: false },
        logo: { required: id ? false : true, extension: "jpg|jpeg|png", filesize : 1 }
      },
      messages: {
        inst_name: {
          required: "Please enter institute name"
        },
        admin_id: {
          required: "Please select institute administrator"
        },
        logo: {
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
