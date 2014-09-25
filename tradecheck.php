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
            $_SESSION['$reg_result'] = "empty";         //find better
            header('location:index.php');
            die;
        }
        return $data;
    }
    
    
    
    if(modify_input($_GET["type"]) == "update"){
        $result3 = mysqli_query($con,"SELECT * FROM allusers WHERE email = 'stop@stop.stop' AND institute = 'etutitsni'");
        if(mysqli_num_rows($result3) == 1){
            $_SESSION['$reg_result'] = "stop";
            $_SESSION['$login_result'] = "stop";
            echo "stop";
            die;
        }
        if(isset($_SESSION['$orderq']))
            echo $_SESSION['$orderq'];
        else
            echo "@";
        
    }
    else{
        $ty = modify_input($_GET["type"]);
        $sy = modify_input($_GET["sym"]);
        $qu = modify_input($_GET["quantity"]);
        $pr = modify_input($_GET["price"]);
        $to = modify_input($_GET["total"]);
        $temp = json_decode($_SESSION['$orderq'] , true);
        
        if($to == "noll"){
            $empty = array();
            for($i=0; $i< count($temp); $i++){
                if($temp[$i]["symbol"] == $sy && $temp[$i]["type"] == $ty && $temp[$i]["price"] == $pr && $temp[$i]["quantity"] == $qu){
                    continue;
                }
                else{                        
                    array_push($empty,$temp[$i]);
                }
            }
            $temp = $empty;
            $_SESSION['$orderq'] = json_encode($temp);
            header('location:portfolio.php');
            die;            
        }
        elseif($ty == "bid"){
            
            $result = mysqli_query($con,"SELECT fun FROM allusers WHERE email = '".$_SESSION['$email']."'");
            if(mysqli_num_rows($result) == 1){
                $row = $result->fetch_array();
                $funds = $row['fun'];
                if($funds >= $to+($to*0.015))
                {
                    $funds = $funds - ($to+($to*0.015));
                    $result2 = mysqli_query($con,"UPDATE allusers SET fun = '".$funds."' WHERE email = '".$_SESSION['$email']."'");
                    $result3 = mysqli_query($con,"SELECT quan FROM allhold WHERE email = '".$_SESSION['$email']."' AND sym = '".$sy."' AND price = '".$pr."'");
                    if(mysqli_num_rows($result3) == 1){
                        $row = $result3->fetch_array();
                        $quan = $row['quan'] + $qu;
                        $result4 = mysqli_query($con,"UPDATE allhold SET quan = '".$quan."' WHERE email = '".$_SESSION['$email']."' AND sym = '".$sy."' AND price = '".$pr."'");
                    }
                    else{
                        $result4 = mysqli_query($con,"INSERT INTO allhold VALUES ( '' , '".$_SESSION['$email']."' , '".$sy."' , '".$qu."' , '".$pr."')");
                    }
                    $empty = array();
                    for($i=0; $i< count($temp); $i++){
                        if($temp[$i]["symbol"] == $sy && $temp[$i]["type"] == $ty && $temp[$i]["price"] == $pr && $temp[$i]["quantity"] == $qu){
                            continue;
                        }
                        else{                        
                            array_push($empty,$temp[$i]);
                        }
                    }
                    $temp = $empty;
                }
                else{
                    for($i=0; $i< count($temp); $i++){
                        if($temp[$i]["symbol"] == $sy && $temp[$i]["type"] == $ty && $temp[$i]["price"] == $pr && $temp[$i]["quantity"] == $qu){
                            $temp[$i]["test"] = "yes";
                            break;
                        }
                    }
                }
            }
        }
        elseif($ty == "ask"){
            $bp = modify_input($_GET["bprice"]);
            $result = mysqli_query($con,"SELECT quan FROM allhold WHERE email = '".$_SESSION['$email']."' AND sym = '".$sy."' AND price = '".$bp."'");
            if(mysqli_num_rows($result) == 1){
                $row = $result->fetch_array();
                $quan = $row['quan'];
                if($quan >= $qu)
                {
                    $quan = $quan - $qu;
                    if($quan>0)
                        $result2 = mysqli_query($con,"UPDATE allhold SET quan = '".$quan."' WHERE email = '".$_SESSION['$email']."' AND sym = '".$sy."' AND price = '".$bp."'");
                    else
                        $result2 = mysqli_query($con,"DELETE FROM allhold WHERE email = '".$_SESSION['$email']."' AND sym = '".$sy."' AND price = '".$bp."'");
                    $result3 = mysqli_query($con,"SELECT fun FROM allusers WHERE email = '".$_SESSION['$email']."'");
                    if(mysqli_num_rows($result3) == 1){
                        $row = $result3->fetch_array();
                        $fun = $row['fun'] + ($to-($to*0.015));
                        $result4 = mysqli_query($con,"UPDATE allusers SET fun = '".$fun."' WHERE email = '".$_SESSION['$email']."'");
                    }
                    $empty = array();
                    for($i=0; $i< count($temp); $i++){
                        if($temp[$i]["symbol"] == $sy && $temp[$i]["type"] == $ty && $temp[$i]["price"] == $pr && $temp[$i]["quantity"] == $qu){
                            continue;
                        }
                        else{                        
                            array_push($empty,$temp[$i]);
                        }
                    }
                    $temp = $empty;
                }
                else{
                    for($i=0; $i< count($temp); $i++){
                        if($temp[$i]["symbol"] == $sy && $temp[$i]["type"] == $ty && $temp[$i]["price"] == $pr && $temp[$i]["quantity"] == $qu){
                            $temp[$i]["test"] = "yes";
                            break;
                        }
                    }
                }
            }
        }
        $_SESSION['$orderq'] = json_encode($temp);        
    }
    
?>