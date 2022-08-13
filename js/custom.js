$(document).ready(function(){
    $("#populateinventory").click(function(){
        $.ajax({
            url:"/codem/dataimport.php",
            type:"post"
        }).done(function(response){
            $('#importresult').html(response);
        });
    });

    $(".orders_qty").change(function(){
        enableOrderButton();
    });

    $("#source").change(function(){
        enableOrderButton();
    });

    function enableOrderButton() {
        var disable = true;
        $.each($(".orders_qty") ,function(key,element) {
            if($(element).val()>0) {
                disable = false;
            }            
        });
        if(!disable)
            disable = ($("#source").val()=="")?true:false;
        $("#addOrder").attr("disabled",disable);
    }
    
    $("#addOrder").click(function(){
        var index = $(this).data('index');
        $("#orders").append(buildAnOrderHtml(++index));
        if(index==1) {
            $("#orders").before("<br/><b>Orders to be placed</b><br/><br/>");
            $("#placeOrder").show();
            //$("#orders").after("<br/><input onClick='placeOrder()' type='button' value='Place the orders' />");
        }
        $(".orders_qty").val(0);
        $(this).data('index',index);
        $("#addOrder").attr("disabled",true);
        $("#source").val("");
    });

    function buildAnOrderHtml(index) {
        var source = $("#source").val();
        //sources[Math.floor(Math.random()*sources.length)];
        var html = "<div class='order' data-place=1 id='order_"+index+"' data-index="+index+" data-source='"+source+"'";
        var lines = [];
        var temp = "";
        $.each($(".orders_qty") ,function(key,element) {
            if($(element).val()!=0) {
                temp+="  "+$(element).data('product')+" :"+$(element).val();
                lines.push({'name':$(element).data('product'),'qty':$(element).val(),'product_id':$(element).data('product_id')});
            }            
        });
        html+="data-products="+JSON.stringify(lines)+"> Source:<b>"+source+"</b> Products:"+temp+"</div>";
        return html;
    }
    orders = {};
    $("#placeOrder").click(function(){
        //console.log("placing orders");
        var orders = {};
        $.each($(".order") ,function(key,element) {
            if($(element).attr('data-place')==1) {
                orders[$(element).data('index')] = {'source':$(element).data('source'),'products':$(element).data('products')};
            }
        });
        if(!$.isEmptyObject(orders)) {
            $.ajax({
                url:"/codem/orderprocess.php",
                type:"post",
                contentType: "application/json; charset=utf-8",
                data:JSON.stringify({'orders':orders})
            }).done(function(response){
                ordersresponse = JSON.parse(response);
                for(index in ordersresponse) {
                    $("#order_"+index).append(ordersresponse[index].success?" <b>Order Placed id</b>:"+ordersresponse[index].orderData.order_id:" Failed");
                    if(ordersresponse[index].success) {
                        if(ordersresponse[index].orderData.back_order) {
                            backOrders = ordersresponse[index].orderData.back_order;
                            for( i in backOrders) {
                                $('#product_qty_'+backOrders[i]).attr('disabled',true);
                            }
                        }
                    }
                    $("#order_"+index).attr('data-place',0);
                }
            });
        }
    });
});