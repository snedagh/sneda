$(function() {
    //hang on event of form with id=gen_form
    $("#gen_form").submit(function(e) {

        //prevent Default functionality
        e.preventDefault();

        //get the action-url of the form
        var actionurl = e.currentTarget.action;


        //do your own request an handle the results
        $.ajax({
            url: actionurl,
            type: 'post',
            data: $("#gen_form").serialize(),
            success: function(response) {

                console.log(response)
            }
        });

    });

});

function get_car_details()
{
    var car_number = $('#car_number').val()

    if(mssql_rows('asset_mast'," asset_no = '"+car_number+"'",1) > 0)
    {
        $('#car_number_x').text(car_number)
        // get car details in json
        var asset_details = JSON.parse(
            get_row('asset_mast'," asset_no = '"+car_number+"'",1)
        );
        var asset_code = asset_details['ASSET_CODE'];
        var owner = asset_details['cust_code'];

        // get customer
        var customer = JSON.parse(
            get_row('customer_master'," cust_code = '"+owner+"'",1)
        );

         var customer_name = customer['cust_name']
        $('#customer').text(customer_name)


        // get last services
        var services_count = mssql_rows('wo_hd',"asset_code = '"+asset_code+"' order by wo_date desc",1)
        if(services_count > 0)
        {
            var last_service = JSON.parse(
                get_row('wo_hd',"asset_code = '"+asset_code+"' order by wo_date desc",1)
            )
            var last_serviced_date = last_service['wo_date']['date'];
            var wo_no = last_service['wo_no'];
            var wreq_no = last_service['wreq_no'];
            var invoice_entry = last_service['invoice_entry'];
            $('#last_services').text(last_serviced_date.split(' ')[0])



            // get service labor
            var labour = JSON.parse(
                get_row('invoice_tran',"Entry_no = '"+invoice_entry.toString()+"'",100)
            )
            var l_desc = labour['descr']
            console.log(labour)


        }


        // console.log(services_count)
        console.log("DONE")



    }
}