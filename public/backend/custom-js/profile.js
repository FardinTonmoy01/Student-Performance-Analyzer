// form validation
  $(function () {
      $.validator.setDefaults({
          submitHandler: function () {
              updateProfile()
          }
      });
      $('#profileUpdate').validate({
          rules: {
              name: { required: true },
              email: { required: true, email: true },
              mobile_no: { required: true, minlength: 11, maxlength: 11, numericOnly: true },
              address: { required: true },
              photo: {
                  required: false,
                  extension: "jpg|jpeg|png",
                  // maxsize: 500000, // bytes default in additional.js plugin
                  filesize : 1 // addistional validation
              }
          },
          messages: {
              email: {
                  required: "Please enter a email address",
                  email: "Please enter a valid email address"
              }
          },
          errorElement: 'span',
          errorPlacement: function (error, element) {
              error.addClass('invalid-feedback');
              element.closest('.input-group').append(error);
          },
          highlight: function (element, errorClass, validClass) {
              $(element).addClass('is-invalid');
          },
          unhighlight: function (element, errorClass, validClass) {
              $(element).removeClass('is-invalid');
          }
      });
  });

  // onchange selected image will visible
  function imageUrl(input) {
      if (input.files && input.files[0]) {
          var reader = new FileReader();
          reader.onload = function(e) {
              $('#image-img').attr('src', e.target.result);
          }
          reader.readAsDataURL(input.files[0]);
      }
  }
  $("#photoFile").change(function() {
      imageUrl(this);
  });