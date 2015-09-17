<html>
<head>
<?php 
require_once 'lib/Class_LoginLogic.php'; 
$TierTwo = new LoginLogic();
try
{
	$TierTwo->checkPOSTLoginInfo();
	$TierTwo->checkPOSTRegisterInfo();
}
catch (Exception  $e)
{
	echo $e->getMessage() ;
}
?>
<script type="text/javascript" src="script/bootstrap.js"></script>
<script type="text/javascript" src="script/jquery.js"></script>
<script type="text/javascript" src="script/cookies.js"></script>
<link rel="stylesheet" href="css/bootstrap.css" />
<link rel="stylesheet" href="css/errorCSS.css" />
<script>
loadCookies();
</script>

</head>

 <div class="container">    
        <div id="loginbox" style="margin-top:50px;" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">                    
            <div class="panel panel-info" >
                    <div class="panel-heading">
                        <div class="panel-title">Sign In</div>
                        <div style="float:right; font-size: 80%; position: relative; top:-10px"><a href="#">Forgot password?</a></div>
                    </div>     

                    <div style="padding-top:30px" class="panel-body" >

                        <div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>
<!-- Beginning of login Form -->
                            
                        <form id="loginform" class="form-horizontal" role="form" method="post" action="" >
                                    
                            <div style="margin-bottom: 25px" class="input-group">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                                        <input id="loginusername" type="text" class="form-control <?php $TierTwo->getError('username');?>"  name="username" placeholder="employee id or email" onblur="setCookie('loginusername', document.getElementById('loginusername').value ,365);">                                        
                                    </div>
                                
                            <div style="margin-bottom: 25px" class="input-group">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                                        <input id="loginpassword" type="password" class="form-control <?php $TierTwo->getError('password');?>" name="password" placeholder="password" onblur="setCookie('loginpassword', document.getElementById('loginpassword').value ,365);">
                                    </div>
                                    

                                
                            <div class="input-group">
                                      <div class="checkbox">
                                        <label>
                                          <input id="loginremember" type="checkbox" name="remember" value="1" onblur="setCookie('loginremember', this.val ,365);"> Remember me
                                        </label>
                                      </div>
                                    </div>


                                <div style="margin-top:10px" class="form-group">
                                    <!-- Button -->

                                    <div class="col-sm-12 controls">
 <!-- Submit button -->            
										<input class="btn btn-success" type="submit" id='btn-login' value='Login'>
                                    </div>
                                </div>


                                <div class="form-group">
                                    <div class="col-md-12 control">
                                        <div style="border-top: 1px solid#888; padding-top:15px; font-size:85%" >
                                            Don't have an account! 
                                        <a href="#" onClick="$('#loginbox').hide(); $('#signupbox').show()">
                                            Sign Up Here
                                        </a>
                                        </div>
                                    </div>
                                </div>    
                            </form>   


<!-- End of login Form --->							



                        </div>                     
                    </div>  
        </div>
		
		
		
        <div id="signupbox" style="display:none; margin-top:50px" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <div class="panel-title">Sign Up</div>
                            <div style="float:right; font-size: 85%; position: relative; top:-10px"><a id="signinlink" href="#" onclick="$('#signupbox').hide(); $('#loginbox').show()">Sign In</a></div>
                        </div>  
                        <div class="panel-body" >
<!-- Register Form -->						
                            <form id="signupform" class="form-horizontal" role="form" method="post" action="">
                                
                                <div id="signupalert" style="display:none" class="alert alert-danger">
                                    <p>Error:</p>
                                    <span></span>
                                </div>
                                    
                                
                                  
                                <div class="form-group">
                                    <label for="email" class="col-md-3 control-label">Email</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control <?php $TierTwo->getError('email');?>" name="email" placeholder="Email Address">
                                    </div>
                                </div>
								
								 <div class="form-group">
                                    <label for="id" class="col-md-3 control-label">Employee ID</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control <?php $TierTwo->getError('id');?>" name="id" placeholder="Employee ID">
                                    </div>
                                </div>
                                    
                                <div class="form-group">
                                    <label for="firstname" class="col-md-3 control-label">First Name</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control <?php $TierTwo->getError('firstname');?>" name="firstname" placeholder="First Name">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="lastname" class="col-md-3 control-label">Last Name</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control <?php $TierTwo->getError('lastname');?>" name="lastname" placeholder="Last Name">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="passwd" class="col-md-3 control-label">Password</label>
                                    <div class="col-md-9">
                                        <input type="password" class="form-control <?php $TierTwo->getError('passwd');?>" name="passwd" placeholder="Password">
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="secretWord" class="col-md-3 control-label">Secret Word</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control <?php $TierTwo->getError('secretWord');?>" name="secretWord" placeholder="Secret Word">
                                    </div>
                                </div>
                                    
                                <div class="form-group">
                                    <!-- Button -->                                        
                                    <div class="col-md-2 col-md-offset-5">
                                        <input class="btn btn-success" type="submit" id='btn-register' value='Register'>
                                    </div>
                                </div>
                            </form>
                         </div>
                    </div>

               
               
                
         </div> 
    </div>
 <?php 
//$TierTwo->checkPOSTRegisterInfo();
?>
</html>