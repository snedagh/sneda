function xxx() {
    console.log('runing')

    var form_data = {
        'function':'stock_sync',
        'token':token
    }

    $.ajax({
        url:'/config/proc/form/stock.php',
        type:'POST',
        data:form_data,
        success: function (response)
        {
            console.log(response);
            // location.reload()
            // $('#loader').modal('hide')
        }
    });
}

function sync_nia_stock()
{
    $('#loader').modal('show')



    setInterval(function(){
        //this code runs every second
        xxx();
    }, 3000)

}


function searchStock(query)
{

    var form_data = {
        'function':'search_stock',
        'q': query,
        'token':token
    }

    $.ajax({
        url:'/config/proc/form/stock.php',
        type:'POST',
        data:form_data,
        success: function (response)
        {
            console.log(response);
            $('#sRes').html(response);
        }
    });

}