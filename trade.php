<?
    session_start();
    include 'connection.php';
    if($_SESSION['$sess'] != "active")
    {
        session_destroy();
        header('location:index.php');
        die;
    }
    function modify_input($data)
    {
        $data = strip_tags($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        if(empty($data))
        {
            $_SESSION['$trade_result'] = "empty";
            if($r == "market")
                header('location:market.php');
            else if($r == "portfolio")
                header('location:portfolio.php');
        }
        return $data;
    }
    $s = modify_input($_POST["symbol"]);
    $t = modify_input($_POST["type"]);
    $p = modify_input($_POST["price"]);
    $q = modify_input($_POST["quantity"]);
    $bp = "noll";
    
    if(is_numeric($q) && is_numeric($p) && ($q > 0))
    {
        $funds = 0;
        if($t == "bid"){
            $result = mysqli_query($con,"SELECT fun FROM allusers WHERE email = '".$_SESSION['$email']."'");
            if(mysqli_num_rows($result) == 1){
                $row = $result->fetch_array();
                $funds = $row['fun'];
                if($funds >= (($p*$q)+($p*$q)*0.015)){
                    $arr = array("symbol" => $s , "type" => $t , "price" => $p , "quantity" => $q , "bprice" => "no" , "test" => "yes");
                    if(isset($_SESSION['$orderq'])){
                        $temp = json_decode($_SESSION['$orderq'] , true);
                        array_push($temp , $arr);
                        $_SESSION['$orderq'] = json_encode($temp);
                    }
                    else{
                        $empty = array();
                        array_push($empty,$arr);
                        $_SESSION['$orderq'] = json_encode($empty);
                    }
                }
                else{
                    echo "no funds";
                }
            }
        }
        else if($t == "ask"){
            $bp = modify_input($_POST["bprice"]);        
            $result = mysqli_query($con,"SELECT quan FROM allhold WHERE email = '".$_SESSION['$email']."' AND sym = '".$s."' AND price = '".$bp."'");
            if(mysqli_num_rows($result) == 1){
                $row = $result->fetch_array();
                $quantity = $row['quan'];
                if($quantity >= $q){
                    $arr = array("symbol" => $s , "type" => $t , "price" => $p , "quantity" => $q , "bprice" => $bp , "test" => "yes");
                    if(isset($_SESSION['$orderq'])){
                        $temp = json_decode($_SESSION['$orderq'] , true);
                        array_push($temp , $arr);
                        $_SESSION['$orderq'] = json_encode($temp);
                    }
                    else{
                        $empty = array();
                        array_push($empty,$arr);
                        $_SESSION['$orderq'] = json_encode($empty);
                    }
                }
                else{
                    echo "insufficient stocks";
                }
            }
            else{
                echo "no stocks";
            }
        }
        
        if($t == "bid")
            header('location:market.php');//?type=bid&sym='.$s.'&quantity='.$q.'&price='.$p.'&bprice='.$bp.'&total='.($q*$p).'&loc=market');            
        else if($t == "ask")
            header('location:portfolio.php');//?type=ask&sym='.$s.'&quantity='.$q.'&price='.$p.'&bprice='.$bp.'&total='.($q*$p).'&loc=portfolio');
    }
    else
        echo("invalid values");
?>