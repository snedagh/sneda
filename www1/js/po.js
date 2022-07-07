$('form#po_form').on('submit', function () {
    console.log();
    $('#newTask').modal('hide');
    $('#loader').modal('show');

    var that = $(this),
        url = that.attr('action'),
        type = that.attr('method'),
        data = {};


    that.find('[name]').each(function (index,value){
        var that = $(this),
            name = that.attr('name'),
            value = that.val();
        data[name] = value;
    });

    //console.log(data)

    $.ajax({
        url: url,
        type: type,
        data: data,
        success: function (response){
            //console.log(response)
            var x_one = document.getElementById('regular_form');
            var x_two = document.getElementById('formWithFiles');
            switch (response)
            {


                case "1PASS" :
                    // done
                    x_one.style.display = 'none';
                    x_two.style.display = '';
                    break;
                case 'reload':
                    location.reload();
                    break;
                case 'phone_number_taken':
                    $('#errplace').text('Phone Number Is Taken');
                    setTimeout(function (){$('#loader').modal('hide')},3000)
                    break;
                case 'otp_failed':
                    $('#errplace').text('Wrong OTP');
                    setTimeout(function (){$('#loader').modal('hide')},3000)
                    break;
                case 'not_18':
                    $('#errplace').text('Age must be greater than 18');
                    setTimeout(function (){$('#loader').modal('hide')},3000)
                    break;
                case 'could_not_send_otp':
                    $('#errplace').text('Could Not Sent OTP');
                    setTimeout(function (){$('#loader').modal('hide')},3000)
                    break;
                case "WRONGD":
                    // age age less
                    document.getElementById('WRONGD').innerHTML = '<small class="text-danger">Unknown Domain</small>';
                    break;
                case "WRONGR":
                    // phone number exist
                    document.getElementById('WRONGR').innerHTML = '<small class="text-danger">Unknown Relative</small>';
                    break;
                case "LX1":
                    // otp match
                    document.getElementById('otpRes').innerHTML = '<small class="text-primary">Validating....</small>';
                    setTimeout(() => {
                        // document.getElementById('otpRes').innerHTML = '<small class="text-success">Done</small>'
                        location.reload();
                    }, 5000)
                    break;
                case "LX2":
                    // otp code does not match
                    document.getElementById('otpRes').innerHTML = '<small class="text-primary">Validating....</small>';
                    setTimeout(() => {
                        document.getElementById('otpRes').innerHTML = '<small class="text-danger">Wrong Code</small>';
                    }, 5000)
                    break;

                case 'USEXT' : // if user exist
                    document.getElementById('UNERR').innerHTML = '<small class="text-danger">Username Taken</small>';
                    setTimeout(() => {
                        document.getElementById('UNERR').innerHTML = '<small class="text-info">Provide Username</small>';
                    }, 3000)
                    break;
                case "done" :
                    location.reload();
                    break;
                case 'GENWRONG' :
                    alert("Select Correct Gender");
                    break;
                case 'PASWRONG':
                    alert("Password does not match");
                    break;
                case 'USERUPDATED':
                    window.location.href = 'http://www1.sneda.gh';
                    break;
                case 'empty_form':
                    $('#loader_error_place').text('Fill all places');
                    setTimeout(() => {
                        $('#loader').modal('hide');
                    }, 5000)
                    break;
                default :
                    var split = response.split('%%');
                    if(split[0].toString() === 'error')
                    {
                        document.getElementById('errplace').innerHTML = split[1].toString();
                    } else if(split[0] === 'success')
                    {
                        document.getElementById('successplace').innerHTML = split[1].toString();
                    } else if(split[0] === 'info')
                    {
                        document.getElementById('infoplace').innerHTML = split[1].toString();
                    }
                    console.log("No Option ( "+response+" )")


            }

            // setTimeout(() => {
            //     location.reload();
            // }, 3000)


        }
    })





    return false;
})

$(document).ready(function() {
    $("#save_new_po").click(function(){

        let error = 0;
        let err_msg = '';
        // check po hd
        if($('#supplier').val().length < 1)
        {
            error += 1;
            err_msg += "Supplier cannot be empty <br>";
            $('#supplier').addClass('border-danger')
        }
        if($('#supplier_contact').val().length < 1)
        {
            error += 1;
            err_msg += "Supplier Contact cannot be empty <br>";
            $('#supplier_contact').addClass('border-danger')
        }
        if($('#remarks').val().length < 1)
        {
            error += 1;
            err_msg += "Remarks cannot be empty <br>";
            $('#remarks').addClass('border-danger')
        }

        if($('#po_list tr').length < 1)
        {
            error += 1;
            err_msg += "Remarks cannot be empty <br>";
            $('#po_card').addClass('border-danger')
        }

        if(error === 0)
        {
            $('#PO').modal('hide')
            $('#po_form').submit()

        } else
        {

        }

    });
});

function po_line(sn) {
    let unit_id = "#unit_"+sn.toString()
    let qty_id = "#qty_"+sn.toString()
    let total_id = "#total_"+sn.toString()
    console.log(sn)

    let unit,qty,total;
    unit = $(unit_id).val()
    qty = $(qty_id).val()
    total = unit * qty
    console.log(total)
    $(total_id).val(total.toFixed(2))

    // todo calculate all list total
    let gross_id, discount_id,net_id;
    gross_id = $('#gross');
    discount_id = $('#discount');
    net_id = $('#net');
    let gross = 0;

    for(let x = 1; x <= $('#po_list tr').length; x++)
    {
        let tt = "#total_"+x.toString()
        let x_val = $(tt).val()
        gross += parseFloat(x_val);
    }
    gross_id.val(gross)

    let discount_val = percentage(discount_id.val(),gross)
    let net = gross - discount_val

    net_id.val(net)
    cl("Gross : "+gross+" Discount : "+discount_id.val()+" Discount(%) : "+discount_val+" Net : "+net+" ")


}

function viewPo(entry_no) {
    cl('show modal')
    $('#loader').modal('show');
    if(row_count('po_hd',"`entry_no` = '"+entry_no+"'") > 0)
    {
        // load header
        let po_hd = JSON.parse(get_row('po_hd',"`entry_no` = '"+entry_no+"'"))[0];

        jqh.setVal({
            'entry_no':po_hd.entry_no,
            'owner':po_hd.created_by,
            'supplier':po_hd.supplier,
            'supplier_contact':po_hd.supplier_contact,
            'loc':po_hd.loc + " - " + JSON.parse(get_row('loc_master',"`loc_id` = '"+po_hd.loc+"' "))[0].descr,
            'remarks':po_hd.remarks,
            'ent_date':po_hd.created_on,
            'status':po_hd.status,
            'gross':po_hd.gross,
            'discount':po_hd.discount.toString() + " %",
            'net':po_hd.net,
            'po_id':po_hd.id


        })

        // load po_trans

        if(po_hd.status > -1)
        {
            if(row_count('po_trans',"`entry_no` = '"+entry_no+"'") > 0)
            {
                let po_trans = JSON.parse(get_row('po_trans',"`entry_no` = '"+entry_no+"'"));
                let tr ='';
                for (let p_t = 0; p_t < po_trans.length; p_t++)
                {
                    // get this line
                    let this_line = po_trans[p_t]
                    let line_no,desc,uni_cost,qty,tot_cost,date_added,added_by;

                    line_no = this_line.line;
                    desc = this_line.descr
                    uni_cost = this_line.unit_cost
                    qty = this_line.qty
                    tot_cost = this_line.total_cost
                    date_added = this_line.created_on
                    added_by = this_line.created_by
                    let row_id = 'row_'+line_no

                    tr += "<tr id='"+row_id+"' class=\"text-muted\">\n" +
                        "                        <td>"+line_no+"</td>\n" +
                        "                        <td>"+desc+"</td>\n" +
                        "                        <td>"+uni_cost+"</td>\n" +
                        "                        <td>"+qty+"</td>\n" +
                        "                        <td>"+tot_cost+"</td>\n" +
                        "                        <td class=\"text_sm\">"+date_added+"</td>\n" +
                        "                        <td class=\"text_sm\">"+added_by+"</td>\n" +
                        "                    </tr>";

                }
                jqh.setHtml({
                    'po_list':tr
                })



            }
        } else
        {
            jqh.setHtml({
                'po_list':"<i class='text-danger'>Document Deleted</i>"
            })
        }

        // check next
        if(row_count('po_hd',"`id` > '"+po_hd.id+"'") > 0)
        {
            // there is next
            arr_enable('next')
            let next_entry = JSON.parse(get_row('po_hd',"`id` > '"+po_hd.id+"' LIMIT 1"))[0].entry_no
            $('#next').val(entry_no)

        } else
        {
            // no next
            arr_disable('next')
        }

        if(row_count('po_hd',"`id` < '"+po_hd.id+"'") > 0)
        {
            // there is next
            arr_enable('prev')
            let next_entry = JSON.parse(get_row('po_hd',"`id` < '"+po_hd.id+"' order by `id` desc LIMIT 1"))[0].entry_no
            $('#prev').val(entry_no)

        } else
        {
            // no next
            arr_disable('prev')
        }



    } else
    {
        alert("Document does not exist")
    }
    $('#loader').modal('hide')
}

function po_nav(dir)
{
    let curr_id = $('#po_id').val()
    let entry_no = 0;
    if(dir === 'next')
    {
        entry_no = JSON.parse(get_row('po_hd',"`id` > '"+curr_id+"' LIMIT 1"))[0].entry_no
    } else if(dir === 'prev'){
        entry_no = JSON.parse(get_row('po_hd',"`id` < '"+curr_id+"' order by id desc LIMIT 1"))[0].entry_no
    }
    viewPo(entry_no)

}

// delete po
$(document).ready(function() {
    $("#delete_po").click(function(){

        if(confirm("Are You sure you want to delete Document?"))
        {
            let entry_no = $('#entry_no').val()
            exec("UPDATE `po_hd` SET `status` = -1 WHERE entry_no = '"+entry_no+"'")
            viewPo(entry_no)
        }

    })})