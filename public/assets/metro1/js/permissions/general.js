"use strict";

// Class definition
var KTNewPermission = function() {
    // Elements
    var form;
    var submitButton;
    var validator;

    // Handle form
    var handleForm = function(e) {
        // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
        validator = FormValidation.formValidation(
			form,
			{
				fields: {					
					'akses': {
                        validators: {
							notEmpty: {
								message: 'Nama Hak Akses'
							}
						}
					},
                    'checkbox_input': {
                        validators: {
                            notEmpty: {
                                message: 'Opsi dibutuhan'
                            }
                        }
                    },
				},
				plugins: {
					trigger: new FormValidation.plugins.Trigger(),
					bootstrap: new FormValidation.plugins.Bootstrap5({
                        rowSelector: '.fv-row',
                        eleInvalidClass: '',  // comment to enable invalid state icons
                        eleValidClass: '' // comment to enable valid state icons
                    })
				}
			}
		);		

        // Handle form submit
        submitButton.addEventListener('click', function (e) {
            // Prevent button default action
            e.preventDefault();

            // Validate form
            validator.validate().then(function (status) {
                if (status == 'Valid') {
                    // Show loading indication
                    submitButton.setAttribute('data-kt-indicator', 'on');

                    // Disable button to avoid multiple click 
                    submitButton.disabled = true;
                    

                    // Simulate ajax request
                    setTimeout(function() {
                        // Hide loading indication
                        submitButton.removeAttribute('data-kt-indicator');

                        // Enable button
                        submitButton.disabled = false;

                        // Show message popup. For more info check the plugin's official documentation: https://sweetalert2.github.io/
                        form.submit(); // submit form

                        // Swal.fire({
                        //     text: "You have successfully logged in!",
                        //     icon: "success",
                        //     buttonsStyling: false,
                        //     confirmButtonText: "Ok, got it!",
                        //     customClass: {
                        //         confirmButton: "btn btn-primary"
                        //     }
                        // }).then(function (result) {
                        //     if (result.isConfirmed) { 
                        //         form.querySelector('[name="nip"]').value= "";
                        //         form.querySelector('[name="password"]').value= "";  
                                                              
                        //         var redirectUrl = form.getAttribute('data-kt-redirect-url');
                        //         if (redirectUrl) {
                        //             location.href = redirectUrl;
                        //         }
                        //     }
                        // });
                    }, 2000);   						
                } else {
                    // Show error popup. For more info check the plugin's official documentation: https://sweetalert2.github.io/
                    Swal.fire({
                        text: "Silahkan login menggunakan NIP dan password yang valid.",
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "Baik, Saya mengerti!",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    });
                }
            });
		});
    }

    // Public functions
    return {
        // Initialization
        init: function() {
            form = document.querySelector('#permission_form');
            submitButton = document.querySelector('#permission_form_submit');
            
            handleForm();
        }
    };
}();

// On document ready
KTUtil.onDOMContentLoaded(function() {
    KTNewPermission.init();
});
