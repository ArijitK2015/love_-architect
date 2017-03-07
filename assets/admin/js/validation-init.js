var Script = function () {

	$.validator.setDefaults({
		submitHandler: function() { this.submit(); }
	});

    $().ready(function() {
		// validate the comment form when it is submitted
		$("#commentForm").validate();
		
		$.validator.addMethod("positivenumber", function (value, element, options)
		{
			var bothEmpty = false;
			var data_value = parseFloat(value);
			//console.log(data_value);
			if (data_value >= 0) bothEmpty = true;
				//trigger error class on target input
				//(bothEmpty) ? element.addClass('error') : element.removeClass('error');
				return bothEmpty;
			},
			"Please enter positive value."
		);
  
		// validate signup form on keyup and submit
		$("#myinfo").validate({
			rules: {
				first_name: "required",
				last_name: "required",
				email_addres: {
					required: true,
					email: true
				}
			},
			messages: {
				first_name: "Please enter your firstname",
				last_name: "Please enter your lastname",
				email_addres: "Please enter a valid email address",
				agree: "Please accept our policy"
			}
		});

	$("#chngPass").validate({
            rules: {
                npass: {
                    required: true,
                    minlength: 5
                },
                cpass: {
                    required: true,
                    minlength: 5,
                    equalTo: "#npass"
                }
            },
            messages: {
                npass: {
                    required: "Please provide a password",
                    minlength: "Your password must be at least 5 characters long"
                },
                cpass: {
                    required: "Please provide a password",
                    minlength: "Your password must be at least 5 characters long",
                    equalTo: "Please enter the same password as above"
                }
            }
        });

	$("#siteSettings").validate({
            rules: {
                site_name: "required",
                system_email: {
                    required: true,
                    email: true
                },
		trial_period_comA: {
		    required: true,
		    number: true,
		},
		trial_period_comB: {
		    required: true,
		    number: true,
		}
            },
            messages: {
                site_name: "Please enter your Site Name",
                system_email: "Please enter a valid System Email",
		trial_period_comA: "Please enter a valid Trial Period For Commercial User A",
		trial_period_comB: "Please enter a valid Trial Period For Commercial User B",
            }
        });

	$("#contactinfo").validate({
            rules: {
                contact_email: {
                    required: true,
                    email: true
                }
            },
            messages: {
                contact_email: "Please enter a valid Contact Email"
            }
        });

	$("#editPage").validate({
            rules: {
                page_title: "required",
                page_tag: "required",
                page_key: "required",
                page_alias: "required"
            },
            messages: {
                page_title: "Please Enter Page Title",
                page_tag: "Please Enter Meta Tag",
                page_key: "Please Enter Meta Keywords",
                page_alias: "Please Enter Alias"
            }
        });

	//country validation
	$("#addSliderForm").validate({
	    rules: {
		image_name: {
		    required: true,
		    extension: "jpeg,png,jpg",
		    //accept: "jpeg",
		}
	    },
	    messages: {
		image_name: "Please Select an image file",

	    }
	});

    });

}();

    

     
