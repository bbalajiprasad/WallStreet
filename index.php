<?
    session_start();
?>


<html>
<head>
    <title>WallStreet - Play!</title>
    <link type="text/css" href="index.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Arimo' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.3.0/pure-min.css">
</head>
<body>
    <div id="main">
        <div style="width: 100%; overflow: auto;"><div id="topimg"><img src="top.png" /></div></div>
        
        <div style="width: 60%; float: left;">
            <div id="info" >
                
                <p style="margin-top:80px; margin-bottom: 60px;">
                    <ul style="font-size: 20px;">
                        <li>Event runs from 7:30PM to midnight.</li><br />
                        <li>Trade stocks in Real-time using your virtual portfolio.</li><br />
                        <li>Compete with other players in a race to increase your Net Worth.</li><br />
                        <li>Three winners declared everyday.</li>
                    </ul>
                    
                </p>
                <a href= "https://www.facebook.com/permalink.php?id=1408339846062151&story_fbid=1408346012728201" ><img style="margin-top:60px;" src="bizz2.png" width="40%" title="An event of Bizmaestro" /></a>
                <a href= "http://www.techtatva13.com/" ><img style="margin-left: 50px;" src="tt13.png" width="20%" title="TechTatva 2013: From Gears to Gaia" /></a>
            </div>
        </div>
        <div style="width: 40%; float: left; padding-bottom: 20px;">
            <div id="login" style="margin-top:30px; margin-right: 20px;">
                <form id="loginform" action="indexcheck.php" method="post" class="pure-form">
                    <legend>Sign In:</legend>
                    <input type="hidden" name="type" value="login" />
                    <input type="email" name="em" placeholder="Email" required />
                    <input type="password" name="password" placeholder="Password" required />
                    <button type="submit" class="pure-button pure-button-primary">Sign in</button>
                </form>
                <?
                    if(@$_SESSION['$login_result'] == "nope")
                    {
                        echo'<div id="notify" style="color: red;">Incorrect Email and/or Password.</div>';
                    }
                    else if(@$_SESSION['$login_result'] == "stop")
                    {
                        echo'<div id="notify" style="color: red;">Game has ended for today.</div>';
                    }
                ?>
            </div>
            <div id="reg" style=" margin-top: 50px;  margin-right: 20px; ">
                <form id="register" action="indexcheck.php" method="post" class="pure-form pure-form-stacked ">
                    <input type="hidden" name="type" value="register">
                    <div id="regbox">
                        <legend>Register:</legend>
                        <fieldset class="pure-group" >
                        <input type="text" name="user" placeholder="Full Name" class="pure-input-2-3" required  />
                        <input type="email" name="em" placeholder="Email" class="pure-input-2-3" required />
                        <input type="password" name="password" placeholder="Password" class="pure-input-2-3" required />
                        <input type="password" name="cpassword" placeholder="Confirm Password" class="pure-input-2-3" required />
                        <input type="text" name="ins" placeholder="College" class="pure-input-2-3" required />
                        <input type="text" name="ph" placeholder="Phone" class="pure-input-2-3" required />
                        </fieldset>
                        <button type="submit" class="pure-button pure-input-2-3 pure-button-primary">Register</button>
                    </div>
                </form>
                <?
                    if(@$_SESSION['$reg_result'] == "empty")
                    {
                        echo'<div id="notify" style="color: red;">Please fill all fields.</div>';
                    }
                    else if(@$_SESSION['$reg_result'] == "mismatch pass")
                    {
                        echo'<div id="notify" style="color: red;">Passwords did not match. Please re-enter.</div>';
                    }
                    else if(@$_SESSION['$reg_result'] == "mismatch email")
                    {
                        echo'<div id="notify" style="color: red;">Invalid Email. Please re-enter.</div>';
                    }
                    else if(@$_SESSION['$reg_result'] == "email exists")
                    {
                        echo'<div id="notify" style="color: red;">Email already registered. Please re-enter.</div>';
                    }
                    else if(@$_SESSION['$reg_result'] == "stop")
                    {
                        echo'<div id="notify" style="color: red;">Game has ended for today.</div>';
                    }
                ?>
            </div>
            
        </div>   
        
        
    </div>
</body>
</html>


<?
    session_destroy();
?>