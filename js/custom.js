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
        var disable = true;
        $.each($(".orders_qty") ,function(key,element) {
            if($(element).val()>0) {
                disable = false;
            }            
        });
        $("#addOrder").attr("disabled",disable);
    });
    
    $("#addOrder").click(function(){
        var index = $(this).data('index');
        $("#orders").append(buildAnOrderHtml(++index));
        if(index==1) {
            $("#orders").before("Orders to be placed");
            $("#orders").after("<br/><input type='button' value='Place the orders' />");
        }
        $(".orders_qty").val(0);
        $(this).data('index',index);
    });

    function buildAnOrderHtml(index) {
        var html = "<div class='order' id='order_"+index+"' data-index="+index+">"+sources[Math.floor(Math.random()*sources.length)]+":";
        $.each($(".orders_qty") ,function(key,element) {
            if($(element).val()!=0) {
                html+=$(element).data('product')+":"+$(element).val();
            }            
        });
        html+="</div>";

        return html;
    }
});