/**
 * Created by Jean Baptiste on 16/09/2016.
 */
var Profile = {
    check: function (id) {
        if ($.trim($("#" + id)[0].value) == '') {
            $("#" + id)[0].focus();
            $("#" + id + "_alert").show();

            return false;
        };

        return true;
    },
    validate: function () {
        if (SignUp.check("login") == false) {
            return false;
        }
        if (SignUp.check("password") == false) {
            return false;
        }
        $("#profileForm")[0].submit();
    }
};

var SignUp = {
    check: function (id) {
        if ($.trim($("#" + id)[0].value) == '') {
            $("#" + id)[0].focus();
            $("#" + id + "_alert").show();

            return false;
        };

        return true;
    },
    validate: function () {
        if (SignUp.check("login") == false) {
            return false;
        }
        if (SignUp.check("password") == false) {
            return false;
        }

        $("#registerForm")[0].submit();
    }
}

$(document).ready(function () {
    $("#registerForm .alert").hide();
    $("div.profile .alert").hide();
});