<?
    
    include 'connection.php';
    function modify_input($data)
    {
        $data = strip_tags($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        if(empty($data))
        {
            die;
        }
        return $data;
    }
    
    $on =  modify_input($_POST["one"]);
    $tw = modify_input($_POST["two"]);
    $th = modify_input($_POST["three"]);
    
    if($on == "one" && $tw == "two"){        
        $query = mysqli_query($con,"SELECT * FROM allusers");
        
        $post=array();
        while($result=mysqli_fetch_assoc($query))
        {
            echo ($result['user']."    ".$result['phone']."    ".$result['email']."  ".$result['fun']."  <br \>  ");
        }
        
        
    }
    if($on == "two" && $tw == "one"){        
        $query = mysqli_query($con,"SELECT * FROM allhold");
        
        $post=array();
        while($result=mysqli_fetch_assoc($query))
        {
            echo ($result['email']."    ".$result['sym']."    ".$result['quan']." <br \>  ");
        }
        
        
    }

?>