var Script = function () {

    $.validator.setDefaults({
        submitHandler: function() { this.submit(); }
    });

    $().ready(function() {
        // validate the comment form when it is submitted
        $("#commentForm").validate();

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
		trial_period: {
		    required: true,
		    number: true,
		}
            },
            messages: {
                site_name: "Please enter your Site Name",
                system_email: "Please enter a valid System Email",
		trial_period: "Please enter a valid Trial Period"
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
	$("#addcountry").validate({
		    rules: {
			country: "required",
		    },
		    messages: {
			country: "Please Enter Country Name",
	
		    }
		});
	
	
	//editcountry validation
	$("#editcountry").validate({
		    rules: {
			country: "required",
		    },
		    messages: {
			country: "Please Enter Country Name",
	
		    }
		});

	//addstate validation
	$("#addstate").validate({
		    rules: {
			state: "required",
		    },
		    messages: {
			state: "Please Enter State Name",
	
		    }
		});
	

	//edit state validation
	$("#editstate").validate({
		    rules: {
			state: "required",
		    },
		    messages: {
			state: "Please Enter State Name",
	
		    }
		});
	 });

    //for profile management
	
	$("#addpro").validate({
            rules: {
                catagory: "required",
		user_name: "required",
		password: "required",
		Email: {
                    required: true,
                    email: true
                }
            },
            messages: {
                catagory: "Please Enter catagory",
		user_name: "Please Enter User name",
		password: "Please Enter Password",
		Email: "Please Enter Valid Email"

            }
        });
	
	
	
	    $("#editpro").validate({
		rules: {
		    catagory: "required",
		    user_name: "required",
		    password: "required",
		    Email: {
			required: true,
			email: true
		    }
		},
		messages: {
		    catagory: "Please Enter catagory",
		    user_name: "Please Enter User name",
		    password: "Please Enter Password",
		    Email: "Please Enter Valid Email"
    
		}
	    });
    
    /*------------------------------- for user management --------------------------------------------*/
    
    $("#add_user,#edit_user").validate({
	rules: {
	    type: "required",
	    user_name: "required",
	    password: "required",
	    email: {
		required: true,
		email: true
	    }
	},
	messages: {
	    type: "Please Enter catagory",
	    user_name: "Please Enter User name",
	    password: "Please Enter Password",
	    email: "Please Enter Valid Email"
	}
    });
    
     /*------------------------------- for Report management --------------------------------------------*/
    
    $("#addreport,#editreport").validate({
	rules: {
	    report_title: "required",
	    report_content: "required",
	    
	},
	messages: {
	    report_title: "Please Enter Report Title",
	    report_content: "Please Enter Content",
	   
	}
    });
    
    
    
    
    
    
    

   }();

    

     
