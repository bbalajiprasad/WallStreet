function designTable(results, orderq){
    results = results.replace("</p>"," ");
    var x = 0;
    var arr = new Array();
    var i = 0;
    var j = 0;
    for (var c=0; c<symbols.length; c++) {
        i = results.indexOf('"' , j);
        j = results.indexOf('"' , i+1);
        var symid = results.substring(i+1 , j);
        arr[x++] = '<tr id="'+symid+'"><td>'+symid+'</td>';
        i = j+1;                            
        for (var cc=0; cc<8; cc++) {                              // 9 is (no of col)-2 in myTable
            j = results.indexOf(',' , i+1);
            if (cc == 1) {
                var tablePrice = results.substring(i+1 , j);
                arr[x++] = '<td id="'+symid+'tablePrice">'+tablePrice+'</td>';
                if (orderq.length>0) {
                    for(var fi=0; fi<orderq.length;  fi++){
                        if (orderq[fi].symbol == symid && orderq[fi].test == "yes") {
                            orderq[fi].test = "no";
                            var total = orderq[fi].price * orderq[fi].quantity;
                            if (orderq[fi].type == "bid" && orderq[fi].price >= tablePrice) {
                                var xmlhttp = new XMLHttpRequest();
                                xmlhttp.open("GET","tradecheck.php?type=bid&sym="+orderq[fi].symbol+"&quantity="+orderq[fi].quantity+"&price="+orderq[fi].price+"&total="+total+"&loc=noll",true);
                                xmlhttp.send();
                            }
                            else if(orderq[fi].type == "ask" && orderq[fi].price <= tablePrice){
                                var xmlhttp = new XMLHttpRequest();
                                xmlhttp.open("GET","tradecheck.php?type=ask&sym="+orderq[fi].symbol+"&quantity="+orderq[fi].quantity+"&price="+orderq[fi].price+"&bprice="+orderq[fi].bprice+"&total="+total+"&loc=noll",true);
                                xmlhttp.send();
                            }
                            else
                                orderq[fi].test = "yes";
                        }
                    }
                }
            }
            else if (cc == 3){
                var temp = results.substring(results.indexOf('-' , i)+1 , j-1);
                var color = "red";
                if (temp.charAt(1) == '+')
                    color = "#397D02";
                arr[x++] = '<td style="color:'+color+'">'+temp+'</td>';
            }
            else
                arr[x++] = '<td>'+results.substring(i+1 , j)+'</td>';
            i = j;
        }
        j = results.indexOf(' ', i+1);
        arr[x++] = '<td>'+results.substring(i+1 , j)+'</td>';
        arr[x++] = '<td><a href="#" id="'+symid+'" data-reveal-id="myTradeModal" data-animation="fade">Buy</a></td></tr>';
    }
    $("#myTableBody").html(arr.join(''));
}