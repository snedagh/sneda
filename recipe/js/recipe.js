

function empti(str)
{
    if(str.length < 1)
    {
        return true;
    } else
    {
        return false;
    }
}

function new_recipe_item() {
    // get values
    let item_name = $('#item_name_x').val();
    let qty = $('#qty').val();
    let unit = $('#unit').val();
    let parent = $('#item_parent').val();
    let error = 0;
    let err_msg = '';

    if(empti(parent))
    {
        error += 1 ;
        err_msg += "Unit cannot be empty <br>";
    }


    if(empti(item_name))
    {
        $('#item_name_x').removeClass('border-info')
        $('#item_name_x').addClass('border-danger')
        error += 1 ;
        err_msg += "Item Name cannot be empty <br>";
    }
    else
    {
        $('#item_name_x').removeClass('border-danger')
        $('#item_name_x').addClass('border-info')
    }

    if(empti(qty))
    {
        $('#qty').removeClass('border-info')
        $('#qty').addClass('border-danger')
        error += 1 ;
        err_msg += "Quantity cannot be empty <br>";
    }
    else
    {
        $('#qty').removeClass('border-danger')
        $('#qty').addClass('border-info')
    }

    if(empti(unit))
    {
        $('#unit').removeClass('border-info')
        $('#unit').addClass('border-danger')
        error += 1 ;
        err_msg += "Unit cannot be empty <br>";
    }
    else
    {
        $('#unit').removeClass('border-danger')
        $('#unit').addClass('border-info')
    }

    if(error > 0)
    {
        Swal.fire(
            {
                icon:'error',
                text:'There is an error in form',
                html:"<h4>errors("+error+")</h4>" + err_msg
            }
        )
    }
    else
    {

        // insert
        exec('smdesk',
            "INSERT INTO `recipe` (`parent`,`item_des`,`qty`,`unit`) " +
            "values ('"+parent+"','"+item_name+"','"+qty+"','"+unit+"')")


        let html = "<li class=\"list-group-item d-flex justify-content-between align-items-center\">\n" + item_name  +
            "                                <div>\n" +
            "                                    <span class=\"badge badge-primary badge-pill\">"+qty +" "+unit+"</span>\n" +
            "                                    <span title=\"Delete\" class=\"badge badge-danger badge-pill pointer\">Remove</span>\n" +
            "                                </div>\n" +
            "                            </li>";

        $('#rec_list').prepend(html)

        jqh.setVal({
            'item_parent':'',
            'item_name_x':'',
            'qty':''
        })
        jqh.setHtml({
            'unit':"<option value=\"\">UNIT</option>\n" +
                "                                    <option value=\"PCS\">PCS</option>\n" +
                "                                    <option value=\"PCS\">KG</option>\n" +
                "                                    <option value=\"PCS\">LIT</option>"
        })


        get_recipe(parent)
    }



}

function loadMenuItems()
{
    // check it items are in menu
    let menu_count = query_row('rest',"SELECT * FROM prod_mast where item_type = 5");
    cl("Lets Go")
    cl(menu_count + typeof(menu_count))
    if(menu_count > 0 )
    {
        // get all menu
        let menu_items = fetch_return('rest',"SELECT * FROM prod_mast where item_type = 5")
        let buttons_html = '';

        for(let mi = 0; mi < menu_items.length; mi++) // get buttons
        {
            let item = menu_items[mi];
            buttons_html += "<button onclick='get_recipe("+item.barcode+")' class=\"btn btn-danger m-1 recipe_card\">"+
                item.item_des +"</button>";
        }
        jqh.setHtml({'myDIV':buttons_html})

    }
    $('#loader').fadeOut(5000)
    $('#page_content').fadeIn(10000)
}

function get_recipe(barcode) {
    // count recipe
    cl(barcode)
    // get item details
    let item_details = fetch_return('rest',"SELECT * FROM prod_mast WHERE barcode = '"+barcode+"'")[0]
    let item_name = item_details.item_des
    jqh.setText({'item_name':item_name})
    jqh.setVal({'item_parent':barcode})

    // get recipe
    let rec_count = query_row('smdesk',"SELECT * FROM recipe WHERE `parent` = '"+barcode+"'")
    cl(rec_count);
    let html = '';
    if(rec_count < 1)
    {
        html = "<li class='list-group-item d-flex justify-content-between align-items-center'><i class='text-info'>No Recipe Item</i></li>"
    }
    else {
        let rec_items = fetch_return('smdesk',"SELECT * FROM recipe WHERE `parent` = '"+barcode+"' ORDER BY `id` DESC")
        cl("Record has length of "  + rec_count)

        for (let i = 0; i < rec_count; i++)
        {
            let item = rec_items[i];

            cl(item.parent)
            html += "<li class=\"list-group-item d-flex justify-content-between align-items-center\">\n" + item.item_des  +
                "                                <div>\n" +
                "                                    <span class=\"badge badge-primary badge-pill\">"+item.qty +" "+item.unit+"</span>\n" +
                "                                    <span onclick='remove_rec("+item.id+")' title=\"Delete\" class=\"badge badge-danger badge-pill pointer\">Remove</span>\n" +
                "                                </div>\n" +
                "                            </li>";
        }
    }
    cl("HTML : " + html)
    $('#rec_list').html(html)

}

function remove_rec(id) {
    let parent = $('#item_parent').val();

    exec('smdesk',"DELETE FROM `recipe` WHERE `id` = '"+id+"'")
    get_recipe(parent)
}