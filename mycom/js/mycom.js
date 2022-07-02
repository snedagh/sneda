function toObject(arr) {
    var rv = {};
    for (var i = 0; i < arr.length; ++i)
        rv[i] = arr[i];
    return rv;
}




// get sales for graph
function daily_sales()
{

    var dates_target = JSON.parse(
        rows("select * from sales  group by day  order by id desc limit 7")
    );
    var all_locations = JSON.parse(
        rows("SELECT * FROM branch")
    )


    let sales_detail;
    let sales_day;
    let sales_day_label = '';
    let date_label = '';
    for (let sales_date = 0; sales_date < dates_target.length; sales_date++) {
        sales_detail = dates_target[sales_date]
        sales_day = sales_detail['day']
        var len_cal = dates_target.length - sales_date;
        if (len_cal === 1) // there is more
        {
            sales_day_label = sales_day

        } else {
            sales_day_label = sales_day + ",";
        }

        date_label += sales_day_label;


        cl("Date is " + sales_day)
        cl("Len Calc " + str(len_cal))
        cl("Label " + sales_day_label)
        cl("")

    }
    cl(date_label)
    cl('')
    var x_series = [];

    // loop through locations
    let location_details;
    let location_id;
    let location_description;
    let t_date;
    for (let l = 0; l < all_locations.length; l++) {
        location_details = all_locations[l]
        location_id = location_details['loc_id']
        location_description = location_details['loc_desc']



        // loop through date and get sales
        var date_array = date_label.split(",")
        let sales_data = '';
        for (let s_date = 0; s_date < date_array.length; s_date++) {
            t_date = date_array[s_date]
            // cl("Location Id : " + str(location_id))
            // cl("Location Desc : " + str(location_description))
            // cl("Date : " + str(t_date))


            // get net sales for day
            let net_sale = 0;
            if(row_count('sales',"`loc` = '"+location_id+"' AND `day` = '"+t_date+"'") > 0)
            {
                net_sale = JSON.parse(get_row('sales',"`loc` = '"+location_id+"' AND `day` = '"+t_date+"'"))[0]['net_sales'];
            }
            var s_len = date_array.length - s_date
            if(s_len === 1)
            {
                sales_data += net_sale
            } else {
                sales_data += net_sale + ","
            }

            // cl('Net Sale : ' + str(net_sale))
            // cl("")
        }
        cl("Location Id : " + str(location_id))
        cl("Location Desc : " + str(location_description))
        cl("Sales Date : " + str(sales_data))

        var s_object = {
            name: location_description,
            data: sales_data.split(',')
        }
        // add to series

        x_series.push(s_object)
        cl(s_object)


        cl("")

    }
    let x_append = '';
    let this_seriees;
    for (let i = 0; i < x_series.length; i++) {
        // cl(x_series[i][1])
        this_seriees = x_series[i]
        var x_len = x_series.length - i
        if (x_len === 1) {
            x_append += this_seriees
        } else {
            x_append += this_seriees + ","
        }
        console.log(JSON.stringify(x_series[i]))
    }
    cl('')
    cl('X_SERVIES')
    console.log(JSON.stringify(x_series))
    cl('')

    var xyz = [
        x_series
    ]
    console.log(xyz)



    // console.log(dates_target)


    var optionsLine = {
        chart: {
            height: 328,
            type: 'line',
            zoom: {
                enabled: false
            },
            dropShadow: {
                enabled: true,
                top: 3,
                left: 2,
                blur: 4,
                opacity: 1,
            }
        },
        stroke: {
            curve: 'smooth',
            width: 2
        },
        //colors: ["#3F51B5", '#2196F3'],
        series: x_series,
        title: {
            text: 'DAILY SALES COMPARISON ( WEEK LENGTH )',
            align: 'left',
            offsetY: 25,
            offsetX: 20
        },
        subtitle: {
            text: 'Statistics',
            offsetY: 55,
            offsetX: 20
        },
        markers: {
            size: 6,
            strokeWidth: 0,
            hover: {
                size: 9
            }
        },
        grid: {
            show: true,
            padding: {
                bottom: 0
            }
        },
        labels: date_label.split(','),
        xaxis: {
            tooltip: {
                enabled: false
            }
        },
        legend: {
            position: 'top',
            horizontalAlign: 'right',
            offsetY: -20
        }



    }
    var chartLine = new ApexCharts(document.querySelector('#line-adwords'), optionsLine);
    chartLine.render();

}

// get sales for cards
function card_details()
{
    // alert("Getting Cards")
    cl("Getting Details For Card")
    var td = toDay
    let grosss_sales;
    let deductions;
    let total_sales;
    let net_sale;
    let tax;
    if (row_count('sales', "`day` = '" + td + "'") > 0) {
        cl("There is sales")

        grosss_sales = row_sum('sales', 'gross_sales', "`day` = '" + td + "'");
        deductions = row_sum('sales', 'discount', "`day` = '" + td + "'");
        net_sale = row_sum('sales', 'net_sales', "`day` = '" + td + "'")
        tax = row_sum('sales', 'tax', "`day` = '" + td + "'");
        total_sales = net_sale - tax;

        $('#gross').text(grosss_sales)
        $('#deducts').text(deductions)
        $('#net_sales').text(net_sale)
        $('#tax').text(tax)
        $('#total_sales').text(total_sales.toFixed(2))

        cl("Net Sum for " + str(td) + " is " + str(net_sale))
    } else {
        cl('No Sales')
    }
}

// trigger transactions modal
function transaction_modal(transactionType) {
    let modal_header = "Transactions"
    let modal_body = "Transactions"
    var td = toDay
    var all_loc = JSON.parse(rows("SELECT * from branch"));
    let tr = '';
    let loc_details;
    let loc_id;
    let loc_desc;
    let value = 0;
    cl(transactionType)

    switch (transactionType)
    {
        case 'grossSales':
            // get gross sales


            modal_header = "Gross Sales"
            for (let loc = 0; loc < all_loc.length; loc++) {
                loc_details = all_loc[loc]
                loc_id = loc_details['loc_id']
                loc_desc = loc_details['loc_desc']
                value = row_sum('sales', 'gross_sales', "`day` = '"+td+"' AND `loc` = '"+loc_id+"'");
                tr += "<tr><td>"+loc_desc+"</td><td>"+value+"</td></tr>"

            }
            break;

        case 'deduct':

            modal_header = "Deductions ( Discount and Others )"
            for (let loc = 0; loc < all_loc.length; loc++) {
                loc_details = all_loc[loc]
                loc_id = loc_details['loc_id']
                loc_desc = loc_details['loc_desc']
                value = row_sum('sales', 'discount', "`day` = '" + td + "' AND `day` = '"+td+"' AND `loc` = '"+loc_id+"'");
                tr += "<tr><td>"+loc_desc+"</td><td>"+value+"</td></tr>"

            }
            break;

        case 'net':
            modal_header = "Net Sales : ( Gross - Deductions )"
            for (let loc = 0; loc < all_loc.length; loc++) {
                loc_details = all_loc[loc]
                loc_id = loc_details['loc_id']
                loc_desc = loc_details['loc_desc']
                value = row_sum('sales', 'net_sales', " `day` = '"+td+"' AND `loc` = '"+loc_id+"'");
                tr += "<tr><td>"+loc_desc+"</td><td>"+value+"</td></tr>"

            }
            break;

        case 'tax':
            modal_header = "Tax Amount"
            for (let loc = 0; loc < all_loc.length; loc++) {
                loc_details = all_loc[loc]
                loc_id = loc_details['loc_id']
                loc_desc = loc_details['loc_desc']
                value = row_sum('sales', 'tax', "`day` = '" + td + "' AND `day` = '"+td+"' AND `loc` = '"+loc_id+"'");
                tr += "<tr><td>"+loc_desc+"</td><td>"+value+"</td></tr>"

            }
            break;
        case 'total_sales':
            modal_header = "Total Sales Sales : ( Net - Tax )"
            let total_sales;
            let tax;
            let net_sale;
            for (let loc = 0; loc < all_loc.length; loc++) {
                loc_details = all_loc[loc]
                loc_id = loc_details['loc_id']
                loc_desc = loc_details['loc_desc']
                let t_sale = row_sum('sales', 'net_sales-tax', "`day` = '" + td + "' AND `loc` = '" + loc_id + "'")
                total_sales = t_sale;
                value = net_sale
                value = total_sales;
                tr += "<tr><td>" + loc_desc + "</td><td>" + value + "</td></tr>"

            }
            break;
    }

    $('#trans_hd').text(modal_header)
    // $('#trans_body').text(modal_body)
    $("#modal_tb").html(tr)
    $('#trans_modal').modal('show')

}


// triggers transaction modal function
function viewTransactions(transaction) {
    transaction_modal(transaction)
}

// get bill_transaction
function bill_trans()
{

    $("#no_transaction").hide()
    $('#transactions').hide()
    let tr = '';
    let bill;
    let bill_number;
    let mech_no;
    let clerk;
    let item;
    let qty;
    let un_price;
    let trans_amt;
    if (row_count('bill_trans', "`trans_date` = '" + toDay + "'") > 0) {
        // there is an item
        $("#no_transaction").hide()
        cl("Date : " + str(toDay))
        cl("There is bill")

        // get bill
        var bill_items = JSON.parse(get_row('bill_trans', "`trans_date` = '" + toDay + "' ORDER BY `id` DESC LIMIT 10"));
        tr = '';
        for (let bill_row = 0; bill_row < bill_items.length; bill_row++)
        {
            bill = bill_items[bill_row]

            bill_number = bill['bill_number']
            mech_no = bill['mach_no']
            clerk = bill['clerk']
            item = bill['item']
            qty = bill['qty']
            un_price = bill['un_price']
            trans_amt = bill['trans_amt']
            loc = bill['loc']

            tr += "<tr>\n" +
                "<td>"+loc+"</td>"+
                "                                    <td>"+bill_number+"</td>\n" +
                "                                    <td>"+clerk+"</td>\n" +
                "                                    <td>"+mech_no+"</td>\n" +
                "                                    <td>"+item+"</td>\n" +
                "                                    <td>"+qty+"</td>\n" +
                "                                    <td>"+un_price+"</td>\n" +
                "                                    <td>"+trans_amt+"</td>\n" +
                "                                </tr>"

            // cl("Row : " + str(tr))
        }

        $('#bill_trans_tr').html(tr)

        $('#transactions').show()
    } else {
        // no item
        cl("Date : " + str(toDay))
        cl("No BIll")
        $("#no_transaction").show()
        $("#transactions").hide()
    }
}


card_details()
daily_sales()
bill_trans()

// setInterval(function(){bill_trans();},1000)
setInterval(function(){card_details();},1000)







