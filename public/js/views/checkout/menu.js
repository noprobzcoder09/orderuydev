var Menu = function() {
	var addToCartUrl;
	var orderSummaryUrl;
	var validator;
	var ajaxResponse = false;

	var init = function(noMeals) {
  		
		var option = 'option_';

        var rules = new Array();

        $(document).find('.lunch-meal-select').each( function() {
            rules[$(this).attr('name')] = 'required';
        });

        $(document).find('.dinner-meal-select').each( function() {
            rules[$(this).attr('name')] = 'required';
        });

        console.log(rules);

		validator = $("#menu-form").validate({
	      	rules: rules,   
	        errorElement: 'em',
	        errorPlacement : function(error, element) {
	            var placement = $(element).data('error');
	            error.addClass( 'invalid-feedback' );

	            if (placement) {
	                $(placement).append(error)
	            } else {
	                element = element.closest('div');
	                error.insertAfter(element);
	            }
	        }
	    });
	}

	var isValid = function() {
		var valid = $("#menu-form").valid();
        if(!valid) {
            validator.focusInvalid();
            return false;
        }
        return true;
	}

	var addtocart = function(planId) {
		
		var data = new Array();

        var lunch = new Array();
        dinner = new Array();

        $('.lunch-meal-select').each( function() {
            lunch.push($(this).find('option:selected').val())
        });
        $('.dinner-meal-select').each( function() {
            dinner.push($(this).find('option:selected').val())
        });
        
        System.blockUI('#tab1');
        System.setAjaxRequest(
        	addToCartUrl,
        	{dinner: JSON.stringify(dinner), lunch: JSON.stringify(lunch), meal_plans_id: planId},
        	'PUT',
        	function(response) {
        		ajaxResponse = true;
        		System.unblockUI();
        		orderSummary();
        	},
        	function(response) {
        		ajaxResponse = false;
        		System.unblockUI();
        	}
        );
        return ajaxResponse;
	}
    
    function orderSummary() {
		System.blockUI('#order-summary-wrapper');
		System.lazyLoading( function() {
			System.setAjaxRequest(
	        	orderSummaryUrl,
	        	'',
	        	'GET',
	        	function(response) {
	        		System.unblockUI();
	        		if (response != '') {
	        			$('#order-summary-wrapper').html(response);
	        		}
	        	},
	        	function() {
	        		System.unblockUI();
	        	},
	        	'html'
	        );
		});
	}


	return {
		init: function(settings) {
			addToCartUrl = settings.addToCartUrl;
			orderSummaryUrl = settings.orderSummaryUrl;
			init(settings.noMeals);
		},
		isValid: function() {
			return isValid();
		},
		addtocart: function(planId) {
			return addtocart(planId);
		},
		response: function() {
			return ajaxResponse;
		},
		orderSummary: function() {
			orderSummary();
		}
	}
}();