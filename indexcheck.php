<?
    session_start();
    include 'connection.php';

    function modify_input($data)
    {
        $data = strip_tags($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        if(empty($data))
        {
            $_SESSION['$reg_result'] = "empty";
            header('location:index.php');
            die;
        }
        return $data;
    }
    
    $type = $_POST["type"];
    $e = modify_input($_POST["em"]);
    $pwd = md5(modify_input($_POST["password"]));
    
    if($type == "register")
    {
        
        $result3 = mysqli_query($con,"SELECT * FROM allusers WHERE email = 'stop@stop.stop' AND institute = 'etutitsni'");
        if(mysqli_num_rows($result3) == 1){
            $_SESSION['$reg_result'] = "stop";
            $_SESSION['$login_result'] = "stop";
            echo "stop";
            header('location:index.php');
            die;
        }
        
        
        
        $uname = modify_input($_POST["user"]);
        $inst = modify_input($_POST["ins"]);        
        $p = modify_input($_POST["ph"]);
        if((modify_input($_POST["password"])) != (modify_input($_POST["cpassword"])))
        {
            $_SESSION['$reg_result'] = "mismatch pass";
            header('location:index.php');
            die;
        }
        if (!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/",$e))
        {
            $_SESSION['$reg_result'] = "mismatch email";
            header('location:index.php');
            die;
        }
        $result = mysqli_query($con,"SELECT COUNT(*) FROM allusers WHERE email = '".$e."'");
        $row=mysqli_fetch_assoc($result);
        if($row['COUNT(*)'])
        {
            $_SESSION['$reg_result']="email exists";
            header('location:index.php');
            die;
        }
        
        $query = "INSERT INTO allusers VALUES ('' , '".$uname."' , '".$pwd."' , '".$inst."' , '".$e."' , '".$p."' , '100000.000')";
        $result = mysqli_query($con,$query);
        $_SESSION['$sess'] = "active";
        $_SESSION['$user'] = $uname;
        $_SESSION['$email'] = $e;
        header('location:portfolio.php');
        die;
    }
    else if($type == "login")
    {
        
        $result3 = mysqli_query($con,"SELECT * FROM allusers WHERE email = 'stop@stop.stop' AND institute = 'etutitsni'");
        if(mysqli_num_rows($result3) == 1){
            $_SESSION['$reg_result'] = "stop";
            $_SESSION['$login_result'] = "stop";
            echo "stop";
            header('location:index.php');
            die;
        }
        
        
        
        
        $result=mysqli_query($con, "SELECT user FROM allusers WHERE email = '".$e."' AND pass = '".$pwd."'");
        if(mysqli_num_rows($result) == 1)
        {
            $row = $result->fetch_array();
            $_SESSION['$sess'] = "active";
            $_SESSION['$user'] = $row['user'];
            $_SESSION['$email'] = $e;
            header('location:portfolio.php');
            die;
        }
        else
        {
            $_SESSION['$login_result']="nope";
            header('location:index.php');
            die;
        }
    }
?>