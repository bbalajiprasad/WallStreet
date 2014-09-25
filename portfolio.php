<?
    session_start();
    $temp = "0";
    if(@$_SESSION['$sess'] != "active")
    {
        session_destroy();
        header('location:index.php');
        die;
    }
    if(isset($_SESSION['$orderq']))
    {
        $temp = $_SESSION['$orderq'];
    }
    include 'connection.php';
    
?>

<html>
    <head>
        <title>WallStreet - Portfolio</title>
        <link type="text/css" href="portfolio.css" rel="stylesheet">
        <link href='http://fonts.googleapis.com/css?family=Arimo' rel='stylesheet' type='text/css'>
        <script src="jquery-1.4.4.min.js"></script>
        <script type="text/javascript" src="jquery.reveal.js"></script>
        <script src="getYQL.js" type="text/javascript"></script>
    </head>
    <body>
        <div id="main">
            <div id="top">
                <ul id="ldd_menu" class="ldd_menu">
                    <li>
                        <a href="portfolio.php">Portfolio</a>
                    </li>
                    <li>
                        <a href="market.php">Market</a>
                    </li>
                    <li>
                        <a href="help.php">Help</a>
                    </li>
                    <li>
                        <a href="logout.php">Logout</a>
                    </li>
                </ul> 
            </div>
            <div style="padding-left: 20px; overflow: auto;">
                <div id="name" style="padding-top: 60px;">
                    <span style="font-size: 80px; color: #d35400; "><?php echo $_SESSION['$user']?></span><br /><br />
                    <span style="font-size: 30px; color: #1B2B3B; ">Funds:</span>
                    <span style="font-size: 30px; color: #1B2B3B; ">$
                        <?php
                            $result = mysqli_query($con,"SELECT * FROM allusers WHERE email = '".$_SESSION['$email']."'");
                            if(mysqli_num_rows($result) == 1){
                                $row = $result->fetch_array();
                                echo $row['fun'];
                            }
                        ?>
                    </span>
                </div>
                <div id="holdingsDiv" style="padding-top: 60px; overflow: auto;">
                    <span style="font-size: 30px; color: #1B2B3B;">My Holdings</span><br /><br />
                    <table id="holdingsTable" class = "tablesorter">
                        <thead>
                            <tr>
                                <th>Symbol</th>
                                <th>Quantity</th>
                                <th>Price Paid</th>
                                <th>Last Trade</th>
                                <th>% Change</th>
                                <th>Gain/Loss</th>
                                <th>Trade</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $result = mysqli_query($con,"SELECT * FROM allhold WHERE email = '".$_SESSION['$email']."'");
                                $i=0;
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr id=\"ht".$i."\">";
                                    echo "<td id=\"sym".$i."\">" . $row['sym'] . "</td>";
                                    echo "<td id=\"quan".$i."\">" . $row['quan'] . "</td>";
                                    echo "<td id=\"bprice".$i."\">" . $row['price'] . "</td>";
                                    echo "<td id=\"last".$i."\">Loading...</td>";
                                    echo "<td id=\"change".$i."\">Loading...</td>";
                                    echo "<td id=\"gl".$i."\">Loading...</td>";
                                    echo "<td id=\"portfoliotrade".$i."\"><a href=\"#\" id=\"aortfoliotrade".$i."\" data-reveal-id=\"myPortModal\" data-animation=\"fade\">Sell</a></td>";
                                    echo "</tr>";
                                    $i++;
                                }
                                echo "</table>";
                            ?>
                        </tbody>
                    </table>
                </div>                
                <div id="orders" style="padding-top: 60px; overflow: auto;">
                    <span style="font-size: 30px; color: #1B2B3B;">Pending Orders</span><br /><br />
                    <table id="transTable" class = "tablesorter">
                        <thead>
                            <tr>
                                <th>Symbol</th>
                                <th>Quantity</th>
                                <th>Price per Share</th>
                                <th>Type</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="portPending"></tbody>
                    </table>            
                </div>
            </div>
        </div>
        
        <script>
            var orderq = [];
            var temp = <?php echo ($temp) ?>;
            if (temp != "0") {
                orderq = temp;
            }
            var symbolCombine="";
            var cc=0;
            for (var i=0; $("#ht"+i).length > 0; i++) {
                if (i == 0)
                    symbolCombine+=($("#sym"+i).text());
                else
                    symbolCombine+="%2B"+($("#sym"+i).text());
                cc=i;
            }
            
            
            getYQLData(symbolCombine, function(results) { 
                results = results.replace("</p>"," ");
                results = results.replace("<p>","@");
                var i=results.indexOf('@');
                var j=0;
                for (var c = 0; c<=cc; c++) {
                    
                    j = results.indexOf(' ' , i+1); 
                    var last = parseFloat(results.substring(i+1 , j));
                    i = j;
                    
                    var bp = parseFloat($("#bprice"+c).text());
                    $("#last"+c).text(last);
                    $("#change"+c).text((((last-bp)/bp)*100).toFixed(3));
                    $("#gl"+c).text(((last-bp)*$("#quan"+c).text()).toFixed(3));
                    
                    if (orderq.length>0) {
                        
                        var x = 0;
                        var arr = new Array();
                        
                        for(var fi=0; fi<orderq.length;  fi++){
                            if (orderq[fi].symbol == $("#sym"+c).text() && orderq[fi].test == "yes") {
                                orderq[fi].test = "no";
                                var total = orderq[fi].price * orderq[fi].quantity;
                                alert(""+(Math.round(parseFloat(orderq[fi].price)*100)/100)+" <= "+parseFloat($("#bprice"+c)));
                                if (orderq[fi].type == "bid" && (Math.round(parseFloat(orderq[fi].price)*100)/100) >= parseFloat($("#bprice"+c))) {
                                    var xmlhttp = new XMLHttpRequest();
                                    xmlhttp.open("GET","tradecheck.php?type=bid&sym="+orderq[fi].symbol+"&quantity="+orderq[fi].quantity+"&price="+orderq[fi].price+"&total="+total,true);
                                    xmlhttp.send();
                                }
                                else if(orderq[fi].type == "ask" && (Math.round(parseFloat(orderq[fi].price)*100)/100) <= parseFloat($("#bprice"+c))){
                                    var xmlhttp = new XMLHttpRequest();
                                    xmlhttp.open("GET","tradecheck.php?type=ask&sym="+orderq[fi].symbol+"&quantity="+orderq[fi].quantity+"&price="+orderq[fi].price+"&bprice="+orderq[fi].bprice+"&total="+total,true);
                                    xmlhttp.send();
                                }
                                else
                                    orderq[fi].test = "yes";
                            }
                            var tt="Ask";
                            if (orderq[fi].type=="bid") {
                                tt="Bid";
                            }
                            arr[x++] = "<tr><td>"+orderq[fi].symbol+"</td><td>"+orderq[fi].quantity+"</td><td>"+orderq[fi].price+"</td><td>"+tt+"</td><td>  <a href=\"tradecheck.php?type="+orderq[fi].type+"&sym="+orderq[fi].symbol+"&quantity="+orderq[fi].quantity+"&price="+orderq[fi].price+"&total=noll\">Cancel</a></td></tr>";
                            
                        }
                        $("#portPending").html(arr.join(''));
                    }                    
                }
            });
            
            
            poll(cc);
            
            function poll(cc) {
                
                    
                    
                setTimeout(function(){   
                    getYQLData(symbolCombine, function(results) {
                        var orderq="@";
                        var xmlhttp = new XMLHttpRequest();                    
                        xmlhttp.onreadystatechange=function()
                        {
                            if (xmlhttp.readyState==4 && xmlhttp.status==200){
                                if (xmlhttp.responseText == "stop") {
                                    window.location.replace("index.php");
                                }else if(xmlhttp.responseText != "@"){
                                
                                orderq=JSON.parse(xmlhttp.responseText);
                                }                               
                            }
                        }
                        xmlhttp.open("GET","tradecheck.php?type=update",false);
                        xmlhttp.send();
                        
                        
                        results = results.replace("</p>"," ");
                        results = results.replace("<p>","@");
                        var i=results.indexOf('@');
                        var j=0;
                        for (var c = 0; c<=cc; c++) {
                            
                            j = results.indexOf(' ' , i+1); 
                            var last = parseFloat(results.substring(i+1 , j));
                            i = j;
                            
                            if (orderq != "@") {
                                var x = 0;
                                var arr = new Array();
                                for(var fit=0; fit<orderq.length;  fit++){
                                    if (orderq[fit].symbol == $("#sym"+c).text() && orderq[fit].test == "yes") {
                                        orderq[fit].test = "no";
                                        var total = orderq[fit].price * orderq[fit].quantity;
                                        //alert("new "+(Math.round(parseFloat(orderq[fit].price)*100)/100)+" <= "+parseFloat($("#bprice"+c)));
                                        if (orderq[fit].type == "bid" && (Math.round(parseFloat(orderq[fi].price)*100)/100) >= parseFloat($("#bprice"+c))) {
                                            var xmlhttp = new XMLHttpRequest();
                                            xmlhttp.open("GET","tradecheck.php?type=bid&sym="+orderq[fit].symbol+"&quantity="+orderq[fit].quantity+"&price="+orderq[fit].price+"&total="+total,true);
                                            xmlhttp.send();
                                        }
                                        else if(orderq[fit].type == "ask" && (Math.round(parseFloat(orderq[fi].price)*100)/100) <= parseFloat($("#bprice"+c))) {
                                            var xmlhttp = new XMLHttpRequest();
                                            xmlhttp.open("GET","tradecheck.php?type=ask&sym="+orderq[fit].symbol+"&quantity="+orderq[fit].quantity+"&price="+orderq[fit].price+"&bprice="+orderq[fit].bprice+"&total="+total,true);
                                            xmlhttp.send();
                                        }
                                        else
                                            orderq[fit].test = "yes";
                                    }
                                    
                                    var tt="Ask";
                                    if (orderq[fit].type=="bid") {
                                        tt="Bid";
                                    }
                                    arr[x++] = "<tr><td>"+orderq[fit].symbol+"</td><td>"+orderq[fit].quantity+"</td><td>"+orderq[fit].price+"</td><td>"+tt+"</td><td>  <a href=\"tradecheck.php?type="+orderq[fit].type+"&sym="+orderq[fit].symbol+"&quantity="+orderq[fit].quantity+"&price="+orderq[fit].price+"&total=noll\">Cancel</a></td></tr>";
                                    
                                }
                                $("#portPending").html(arr.join(''));
                            }
                            var bp = parseFloat($("#bprice"+c).text());
                            $("#last"+c).text(last);
                            $("#change"+c).text((((last-bp)/bp)*100).toFixed(3));
                            $("#gl"+c).text(((last-bp)*$("#quan"+c).text()).toFixed(3));
                        }
                    });
                    
                    poll(cc);
                },3000)
            }
        </script>
        
        <div id="myPortModal" class="reveal-modal">
            <div><h2>Sell:</h2></div>
            <div id="modalPortSym"></div>
            <form id="formPort" action="trade.php" method="post">
                <div id="formPortSym">
                    <input type="hidden" name="symbol" value="null">
                </div>
                <input type="hidden" name="type" value="ask">
                <div id="formPortBox">
                    <div style="float:left;width:150px;">
                        Ask:
                    </div>
                    <div id="formPortPrice" style="float:left;width:100px;">
                        <input type="number" name="price" step="0.01" min="0" /><br /><br />
                    </div><div style="clear:both;"></div>
                    <div style="float:left;width:150px;">
                        Quantity:
                    </div>
                    <div style="float:left;width:100px;">
                        <input type="number" name="quantity" min="1" /><br /><br />
                    </div><div style="clear:both;"></div>
                </div>
                <input type="submit" value="Go" />
            </form>
            <a class="close-reveal-modal">&#215;</a>
	</div>
    </body>
</html>