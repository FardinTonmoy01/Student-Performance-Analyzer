function myFunction() {
    var x = document.getElementById("old_password");
    if (x.type === "password") {
        x.type = "text";
        $("#hide").hide();
        $("#show").show();
    }else {
      x.type = "password";
      $("#hide").show();
      $("#show").hide();
    }
  }
  function myFunctionOne() {
    var x = document.getElementById("password");
    if (x.type === "password") {
        x.type = "text";
        $("#hide1").hide();
        $("#show1").show();
    }else {
      x.type = "password";
      $("#hide1").show();
      $("#show1").hide();
    }
  }
  function myFunctionTwo() {
    var x = document.getElementById("confirm_password");
    if (x.type === "password") {
        x.type = "text";
        $("#hide2").hide();
        $("#show2").show();
    }else {
      x.type = "password";
      $("#hide2").show();
      $("#show2").hide();
    }
  }

$(function () {
    $("#password_error_msg").hide();
    $("#pass_again_error_msg").hide();

    var error_pass = false;
    var error_pass_again = false;


    $("#password").focusout(function(){
        check_password();
    });

    $("#confirm_password").focusout(function(){
        check_pass_again();
    });


    function check_password(){
        var pass_length = $("#password").val().length;
        if(pass_length < 5){
            $("#password_error_msg").html("Should be min. 5 characters");
            $("#password_error_msg").show();
            error_pass = true;

        }else{
            $("#password_error_msg").hide();
        }
    }

    function check_pass_again(){
        var password = $("#password").val();
        var pass_again = $("#confirm_password").val();

        if(password != pass_again){
            $("#pass_again_error_msg").html("Not mathced...");
            $("#pass_again_error_msg").show();
            error_pass_again = true;

        }else{
            $("#pass_again_error_msg").hide();
        }
    }

});