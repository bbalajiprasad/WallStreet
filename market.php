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
        <title>WallStreet - Market</title>
        <link rel="stylesheet" href="market.css" type="text/css" />
        <link href='http://fonts.googleapis.com/css?family=Arimo' rel='stylesheet' type='text/css'>
        <script src="jquery-1.4.4.min.js"></script>
        <script type="text/javascript" src="jquery.reveal.js"></script>
        <script src="myYQL.js" type="text/javascript"></script>
        <script src="designTable.js" type="text/javascript"></script>
    </head>
    <body onload="createTable()">
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
            </div>
            <div id="marketTable">
                <table id="myTable" class = "tablesorter">
                    <thead>
                        <tr>
                            <th>Symbol</th>
                            <th>Bid</th>
                            <th>Last Trade</th>
                            <th>Ask</th>
                            <th>Change Percent</th>
                            <th>Day High</th>
                            <th>Day Low</th>
                            <th>Volume</th>
                            <th>Open</th>
                            <th>Previous Close</th>
                            <th>Trade</th>
                        </tr>
                    </thead>
                    <tbody id="myTableBody">                  
                    </tbody>
                </table>
            </div>        
        </div>
        
        <script>
            var symbols = ["ABB" , "AMZN" , "ACN" , "ATVI" , "ADBA" , "AAPL" , "ADP" , "BIDU" , "CAJ" , "CSCO" , "CTSH" , "GLW" , "DELL" , "EBAY" , "EMC" , "CERN", "NGLX" , "GOOG" , "HPQ" , "INFY" , "INTC" , "IBM" , "MA" , "MSFT" , "NOK" , "ORCL" , "QCOM" , "SAP" , "RIMM" , "TSM" , "TXN" , "V" , "VMW" , "WIT" , "YHOO" , "FB"];
            var symbolCombine="";
            function createTable(){
                var tmpArr = new Array();
                tmpArr = symbols;                                           //.slice(0);
                for(var i=0; i<tmpArr.length-1; i++){
                    for(var j=i+1; j<tmpArr.length; j++){
                        if (tmpArr[i]>tmpArr[j]) {
                            var t = tmpArr[i];
                            tmpArr[i] = tmpArr[j];
                            tmpArr[j] = t;
                        }
                    }
                    symbolCombine+=symbols[i]+"%2B";
                }
                symbolCombine+=symbols[symbols.length-1];
                
                var tableVar = ""; 
                for (var i=0; i<tmpArr.length; i++) {
                    tableVar+='<tr id="'+tmpArr[i]+'"><td>'+tmpArr[i]+'</td>';
                    for (var j=0; j<10; j++) {
                        tableVar+="<td>Loading...</td>";
                    }
                    tableVar+="</tr>";
                }
                $("#myTableBody").html(tableVar);                
                
                var orderq = [];
                var temp = <?php echo ($temp) ?>;
                if (temp != "0") {
                    orderq = temp;
                }
                fetchYQLData(symbolCombine, function(results) {
                    designTable(results, orderq);
                });
                
                poll();
            }
            function poll() {
                
                
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.open("GET","tradecheck.php?type=update",true);
                xmlhttp.send();
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
                
                
                setTimeout(function(){
                    
                    fetchYQLData(symbolCombine, function(results) {
                        designTable(results, orderq);
                    });
                    
                    poll();
                },20000)
            }            
        </script>
        
        
        <div id="myTradeModal" class="reveal-modal">
            <div><h2>Buy:</h2></div>
            <div id="modalTradeSym"></div>
            <form id="formTrade" action="trade.php" method="post">
                <div id="formTradeSym">
                    <input type="hidden" name="symbol" value="null">
                </div>
                <input type="hidden" name="type" value="bid">
                <div id="formTradeBox">
                    <div style="float:left;width:150px;">
                        Bid:
                    </div>
                    <div id="formTradePrice" style="float:left;width:100px;">
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