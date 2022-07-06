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

            $('#regular_form').submit()

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