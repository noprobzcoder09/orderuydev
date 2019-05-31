var Validator = function() {

	return {
		init: function(form, validator) {

			if (validator.submitHandler != undefined) {
				$.validator.setDefaults( {
					submitHandler: validator.submitHandler
				});
			}

			$(form).validate({
				rules: validator.rules,
				messages: validator.messages,
				errorElement: 'em',
				errorPlacement: function ( error, element ) {
					error.addClass( 'invalid-feedback' );
					if ( element.prop( 'type' ) === 'checkbox' ) {
						error.insertAfter( element.parent( 'label' ) );
					} else {
						error.insertAfter( element );
					}
				},
				highlight: function ( element, errorClass, validClass ) {
					$( element ).addClass( 'is-invalid' ).removeClass( 'is-valid' );
				},
				unhighlight: function (element, errorClass, validClass) {
					$( element ).addClass( 'is-valid' ).removeClass( 'is-invalid' );
				}
			});
		}
	}
}();
