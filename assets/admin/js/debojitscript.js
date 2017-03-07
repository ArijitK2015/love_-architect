/*
form validation for email template(admin)
*/

    function check_email()
    {
       if(document.getElementById('mail_title').value=='')
        {
            document.getElementById('nerr_title').innerHTML = 'Please Enter Subject...';
            return false;
        }
        else
        {
                document.getElementById('nerr_title').title = '';
        }
        
        if(document.getElementById('mail_content').value=='')
        {
            document.getElementById('nerr_cont').innerHTML = 'Please Mail Content...';
            return false;
        }
        else
        {
                document.getElementById('nerr_cont').title = '';
        }
        
        
    }
/*
form validation  end
*/    
  
  
  /* Slide div for reply message in ticket management  */  
    $(document).ready(function(){
 
        $(".slidingDiv").hide();
        $(".show_hide").show();
 
    $('.show_hide').click(function(){
    $(".slidingDiv").slideToggle();
    });
 
});
    
    /*  end */