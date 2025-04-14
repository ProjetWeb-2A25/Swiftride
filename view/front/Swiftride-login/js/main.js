(function ($) {
    "use strict";


    /*==================================================================
    [ Focus Contact2 ]*/
    $('.input100').each(function(){
        $(this).on('blur', function(){
            if($(this).val().trim() != "") {
                $(this).addClass('has-val');
            }
            else {
                $(this).removeClass('has-val');
            }
        })    
    })
  
  
    /*==================================================================
    [ Validate ]*/
    var input = $('.validate-input .input100');

    $('.validate-form').on('submit',function(){
        var check = true;

        for(var i=0; i<input.length; i++) {
            if(validate(input[i]) == false){
                showValidate(input[i]);
                check=false;
            }
        }

        return check;
    });


    $('.validate-form .input100').each(function(){
        $(this).focus(function(){
           hideValidate(this);
        });
    });

    function validate (input) {
        if($(input).attr('type') == 'email' || $(input).attr('name') == 'email') {
            if($(input).val().trim().match(/^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{1,5}|[0-9]{1,3})(\]?)$/) == null) {
                return false;
            }
        }
        else {
            if($(input).val().trim() == ''){
                return false;
            }
        }
    }

    function showValidate(input) {
        var thisAlert = $(input).parent();

        $(thisAlert).addClass('alert-validate');
    }

    function hideValidate(input) {
        var thisAlert = $(input).parent();

        $(thisAlert).removeClass('alert-validate');
    }
    

    // Forgot password functionality
    $(document).ready(function() {
        // Handle recovery form submission
        $('#sendRecoveryBtn').click(function() {
            let value = $('#recovery-email').val();
            
            // Add @gmail.com if not present and no other @ symbol
            if (!value.includes('@')) {
                value += '@gmail.com';
            }
            
            if (!value.match(/^[\w-]+(\.[\w-]+)*@([\w-]+\.)+[a-zA-Z]{2,7}$/)) {
                alert('Please enter a valid email address');
                return;
            }

            // Here you would typically make an AJAX call to your backend
            alert('Recovery link has been sent to ' + value);
            $('#forgotPasswordModal').modal('hide');
        });

        // Clear email input when modal opens
        $('#forgotPasswordModal').on('shown.bs.modal', function() {
            $('#recovery-email').val('');
        });

        // Sign up form handling
        $('#signupBtn').click(function() {
            // Get form values
            const firstname = $('#signup-firstname').val().trim();
            const lastname = $('#signup-lastname').val().trim();
            const email = $('#signup-email').val().trim();
            const phone = $('#signup-phone').val().trim();
            const role = $('input[name="role"]:checked').val();

            // Validate fields
            if (!firstname) {
                alert('Please enter your first name');
                return;
            }
            if (!lastname) {
                alert('Please enter your last name');
                return;
            }
            if (!email || !email.match(/^[\w-]+(\.[\w-]+)*@([\w-]+\.)+[a-zA-Z]{2,7}$/)) {
                alert('Please enter a valid email address');
                return;
            }
            if (!phone || !phone.match(/^\+?[\d\s-]{8,}$/)) {
                alert('Please enter a valid phone number');
                return;
            }
            if (!role) {
                alert('Please select your role');
                return;
            }

            // Here you would typically make an AJAX call to your backend
            alert('Registration successful!');
            $('#signupModal').modal('hide');
            
            // Clear form
            $('#signup-firstname').val('');
            $('#signup-lastname').val('');
            $('#signup-email').val('');
            $('#signup-phone').val('');
            $('input[name="role"]').prop('checked', false);
        });

        // Clear sign up form when modal opens
        $('#signupModal').on('shown.bs.modal', function() {
            $('#signup-firstname').val('');
            $('#signup-lastname').val('');
            $('#signup-email').val('');
            $('#signup-phone').val('');
            $('input[name="role"]').prop('checked', false);
        });
    });
})(jQuery);