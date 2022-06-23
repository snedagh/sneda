$(function() {
    //hang on event of form with id=gen_form
    $("#gen_form").submit(function(e) {


        //prevent Default functionality
        e.preventDefault();

        console.log($("#gen_form").serialize())

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
        // var asset_details = JSON.parse(
        //     get_row('asset_mast'," asset_no = '"+car_number+"'",1)
        // )[0];

        let asset_details = fetch_return("SELECT TOP(1) * FROM asset_mast where asset_no = '"+car_number+"'")[0]
        var asset_code = asset_details['ASSET_CODE'];
        var owner = asset_details['cust_code'];

        // get customer
        // var customer = JSON.parse(
        // //     get_row('customer_master'," cust_code = '"+owner+"'",1)
        // );
        let customer = fetch_return("SELECT TOP(1) * from customer_master where cust_code = '"+owner+"'")[0]

         var customer_name = customer['cust_name']
        $('#customer').text(customer_name)


        // get last services
        var services_count = mssql_rows('wo_hd',"asset_code = '"+asset_code+"' order by wo_date desc",1)
        if(services_count > 0)
        {
            // var last_service = JSON.parse(
            //     get_row('wo_hd',"asset_code = '"+asset_code+"' order by wo_date desc",1)
            // )
            let last_service = fetch_return("SELECT TOP(1) * from wo_hd where asset_code = '"+asset_code+"' order by wo_date desc")[0];
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


function part_details() {

    $('#filter_message').html("<i class='text-info'>Loading....</i>")
    // get data
    let part_no,start_date,end_date;
    let error = 0;
    part_no = $('#part_no').val();
    start_date = $('#start_date').val();
    end_date = $('#end_date').val()



    if(part_no.length < 1)
    {
        error += 1;
        $('#part_no').removeClass('border-info')
        $('#part_no').addClass('border-danger')

    }
    else
    {
        $('#part_no').addClass('border-info')
        $('#part_no').removeClass('border-danger')
    }
    if(start_date.length < 1)
    {
        error += 1;
        $('#start_date').removeClass('border-info')
        $('#start_date').addClass('border-danger')
    }
    else
    {
        $('#start_date').addClass('border-info')
        $('#start_date').removeClass('border-danger')
    }
    if(end_date.length < 1)
    {
        error += 1;
        $('#end_date').removeClass('border-info')
        $('#end_date').addClass('border-danger')
    }
    else
    {
        $('#end_date').addClass('border-info')
        $('#end_date').removeClass('border-danger')
    }


    if(error === 0)
    {



        // submit form
        let form_data = {
            'part_no':part_no,'start_date':start_date,'end_date':end_date,'function':'part_usage'
        }
        // check if part number exist
        if(mssql_rows('product_master',"barcode = '"+part_no+"'") > 0)
        {

            let part_details = JSON.parse(get_row('product_master',"barcode = '"+part_no+"'"))[0];

            let item_code = part_details.item_code

            let val_start_date,val_end_date;
            val_start_date = start_date + " 00:00:00";
            val_end_date = end_date + " 23:00:00"

            let query = "select cmms_material.wo_no as work_order, cmms_material.item_desc as item, \n" +
                "cmms_material.unit_qty as quantity,wo_hd.asset_code as car, item_code, wo_hd.wo_date as date\n" +
                "from cmms_material right join wo_hd on cmms_material.wo_no = wo_hd.wo_no\n" +
                "where wo_hd.wo_date \n" +
                "BETWEEN \n" +
                "'"+val_start_date+"' and '"+val_end_date+"' \n" +
                "AND item_code = '"+item_code+"'";

            if(query_row(query) > 0)
            {
                let material_issued = fetch_return(query)
                let row_counts = 0;
                let total_quantity = 0;
                let tr = "";
                for(let i =0; i < material_issued.length; i++)
                {
                    row_counts++;
                    let material = material_issued[i];
                    let work_order = material.work_order
                    let qty = material.quantity
                    let car = material.car;

                    let car_details = fetch_return("select asset_desc,asset_no,cust_code " +
                        "from asset_mast where " +
                        "ASSET_CODE = '"+car+"'")[0]
                    let car_desc = car_details.asset_no + " / " + car_details.asset_desc
                    let cust_code = car_details.cust_code
                    let customer_details = fetch_return("select cust_name from customer_master where cust_code = '"+cust_code+"'")[0]
                    let cust_name = customer_details.cust_name
                    let date_x = material.date.date.split(' ')[0]


                    total_quantity += parseFloat(qty)

                    tr += "<tr>\n" +
                        "                                                <td><small>"+work_order+"</small></td>\n" +
                        "                                                <td><small>"+qty+"</small></td>\n" +
                        "                                                <td><small>"+car_desc+"</small></td>\n" +
                        "                                                <td><small>"+cust_name+"</small></td>\n" +
                        "                                                <td><small>"+date_x+"</small></td>\n" +
                        "                                            </tr>";

                    // console.log(material.work_order + " \n")
                }

                $('#count').text(row_counts)
                $('#total_quantity').text(total_quantity)
                $('#usage_body').html(tr)
                $('#filter_info').hide()
                $('#filter_result').show()
            }
            else
            {
                $('#filter_message').html("<i class='text-danger'>No Record for Filter</i>");
                $('#filter_result').hide()
                $('#filter_info').show()

            }





            console.log(item_code);
            console.log(form_data)

        } else
        {
            $('#filter_message').html("<i class='text-danger'>Part Not Found</i>")
        }


    }
    else
    {
        $('#filter_message').html("<i class='text-danger'>There is an error</i>")
    }

}

function print_doc() {
    let form_data = {}
}