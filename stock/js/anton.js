

var form_data;
var form_process = "/backend/process/form_process.php";

// swal confirm
function swal_confirm(message = 'Continue?')
{
    Swal.fire({
        title: message,
        icon: 'info',
        showDenyButton: true,
        showCancelButton: true,
        confirmButtonText: 'Yes',
        denyButtonText: `No`,
    }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
            return true;
        } else if (result.isDenied) {
            return false
        }
    })
}

function swal_error(message = 'there is an error')
{
    Swal.fire({
        icon: 'error',
        text: message,
    })
}

function reload()
{
    location.reload();
}



if(document.getElementById('token'))
{
    const token = document.getElementById('token').value
}
else
{
    const token = '';
}

function echo(str)
{
    console.log(str);
}

function pass(message = 'looks good')
{
    echo(message + '\n');
    Swal.fire(message)
}

function fail(fail_message = "Operation Not Successful!!") {
    swal_error(fail_message)
}

function responseType(response) {
    var resp_split = response.split('%%');
    if(resp_split.length > 0)
    {
        return resp_split[0];
    }
    else
    {
        return 'unknown_type';
    }
}

function responseMessage(response)
{
    var resp_split = response.split('%%');
    if(resp_split.length > 0)
    {
        return resp_split[1];
    }
    else
    {
        return 'unknown_type';
    }
}


// usefull root functions
function cl(params) { // console log
    console.log(params + '\n')
}

function if_id(id)
{
    return !!document.getElementById(id);
}

function show_modal(id) {// show modal
    $('#'+id).modal({
        backdrop: 'static',
        keyboard: false
    })
}
function hide_modal(id) {// show modal
    $('#'+id).modal('hide')
}

// sort function
function item_sort(module, direction) {
    cl(module);cl(direction);
}

// delete item
function delete_item(module,item) {

    if(confirm('Are you sure you want to execute  task'))
    {
        cl("deleting " + item + " from "+ module);
    } else {
        cl("Execution canceled")
    }

}

// show hide
function i_show(params) {
    cl("Showing " + params)
    var splited = params.split(',')
    for (let index = 0; index < splited.length; index++) {
        const element = splited[index];
        if(document.getElementById(element))
        {
            document.getElementById(element).style.display = '';
        }


    }
}

// hide
function i_hide(params) {
    echo("Hiding " + params)
    var splited = params.split(',')
    for (let index = 0; index < splited.length; index++) {
        const element = splited[index];
        if(document.getElementById(element))
        {
            document.getElementById(element).style.display = 'none';
        }


    }
}

// enable
function en(param) {
    if(document.getElementById(param))
    {
        document.getElementById(param).disabled = false;
    }

}
function dis(param) {
    document.getElementById(param).disabled = true;
}

// disable forms
function form_toggle(func,form_id) {
    if(func === 'en')
    {
        if(document.getElementById(form_id))
        {
            var form = document.getElementById(form_id);
            var elements = form.elements;

            for(var i = 0, len = elements.length; i < len; i++)
            {
                elements[i].disabled = false;
            }
        }

    } else
    {
        if(document.getElementById(form_id)){
            var form = document.getElementById(form_id);

            var elements = form.elements;

            for(var i = 0, len = elements.length; i < len; i++)
            {
                elements[i].disabled = true;
            }
        }

    }


}

// enable form

// enable item
function element_toggle(func,param)
{
    if(document.getElementById(param))
    {
        document.getElementById(param).disabled = func !== 'en';
    }
}

//          toggle elements
//          USAGE
//          tog_ele('id_of_element=action')
//          execute more toggles in one run by separating them with commas
//          tog_ele('id_of_element=action,id_of_element2=action2')
function tog_ele(data){

    const tog_ele_sep = data.split(',');// split parameter into an array by comma
    // loop throgh each sep
    for (let i = 0; i < tog_ele_sep.length; i++) {
        var data = tog_ele_sep[i].split('=');
        var target = data[0];
        var action = data[1];

        // action definitions
        // sh = show content
        // hd = hide content
        // f_dis = disable form
        // f_en enable form

        let form;
        let elements;
        switch (action) {

            // show element
            case 'sh':
                cl("showing " + target)
                if(if_id(target)) // check if id exist
                {
                    i_show(target);
                }
                else
                {
                    cl("Can't find id " + target)
                }
                break;

            // hide element
            case 'hd':
                cl("Hiding " + target)
                if(if_id(target))
                {
                    i_hide(target)
                }
                else
                {
                    cl("Can't find id " + target)
                }
                break;

            // disable form
            case "f_dis":
                cl("Disabling form with id " + target)
                if(if_id(target)) // check if id exist
                {
                    form = document.getElementById(target);
                    elements = form.elements;

                    for(let i = 0, len = elements.length; i < len; i++)
                    {
                        elements[i].disabled = true;
                    }
                }
                else
                {
                    cl("Can't find id " + target)
                }

                break;

            // enable form
            case 'f_en':
                cl("Enabling form with id " + target)
                if(if_id(target))
                {
                    form = document.getElementById(target);
                    elements = form.elements;

                    for(let i = 0, len = elements.length; i < len; i++)
                    {
                        elements[i].disabled = false;
                    }
                }
                else
                {
                    cl("Can't find id " + target)
                }
                break;

            // enable element
            case 'en':
                if(if_id(target))
                {
                    document.getElementById(target).disabled = false;
                }
                else
                {
                    cl("Can't find id " + target)
                }
                break;

            // disable element
            case 'dis':
                if(if_id(target))
                {
                    document.getElementById(target).disabled = true;
                }
                else
                {
                    cl("Can't find id " + target)
                }
                break;

            default:
                cl("nothing to do with target " + action);
        }
    }
}

if(document.getElementById('category_form'))
{
    form_toggle('dis','category_form');
}

element_toggle('dis','save_button')

// disable defauls
function disableselect(e) {return false}
// get time
function time() {
  var d = new Date();
  var s = d.getSeconds();
  var m = d.getMinutes();
  var h = d.getHours();
  document.getElementById('bill_time').textContent = 
    ("0" + h).substr(-2) + ":" + ("0" + m).substr(-2) + ":" + ("0" + s).substr(-2);

  var current_date = d.getDate()+ '/' + d.getMonth() + '/' + d.getFullYear();
  document.getElementById('date').textContent = current_date;
}
// validate scrren size
function validateSize(reload) {
    var screen_width = window.innerWidth
    || document.documentElement.clientWidth
    || document.body.clientWidth;

    var screen_height = window.innerHeight
    || document.documentElement.clientHeight
    || document.body.clientHeight;

    var body_existing_content = document.getElementsByTagName('body')[0].innerHTML;

    if(screen_width === 1024 && screen_height === 768)
    {
        if(reload === 'yes')
        {
            location.reload();
        }

    } else {
        var content = "<div class='bg-light w-100 vh-100 text-danger d-flex flex-wrap align-content-center justify-content-center'>" +
            "<div class='alert alert-danger'>Unsupported Screen Dimension</div>" +
            "</div>";
        document.getElementsByTagName('body')[0].innerHTML = content;
    }
}

// initialize page
function initialize(params) {
    validateSize('no');
    i_hide('numericKeyboard');

}

function arr_disable(elements) {
    var spl = elements.split(',');

    for (let i = 0; i < spl.length; i++)
    {
        let id = "#"+spl[i];
        $(id).prop('disabled',true)
        //echo(id)
    }
}

function arr_enable(elements) {
    var spl = elements.split(',');

    for (let i = 0; i < spl.length; i++)
    {
        let id = "#"+spl[i];
        $(id).prop('disabled',false)
        //echo(id)
    }
}

// set session
function set_session(data) {
    var form_data = {
        'token':'none',
        'function':'set_session',
        'session_data':data
    }
    $.ajax(
        {
            url:'/backend/process/ajax_tools.php',
            method: 'post',
            data: form_data,
            success: function (response) {
                location.reload();
            }
        }
    );
}

// scroll categories
function custom_scroll(id,direction) {
    var target = document.getElementById(id); // get div
    if(direction === 'up') // if direction is scrolling up?
    {
        target.scrollBy(0, -50); // scroll up withing target div thats by -50 pixels
    } else if(direction === 'down'){
        target.scrollBy(0, 50); // else? we scroll down by 50 px
    }

    console.log(direction); // for debuging
}

// sub total
function subTotal() {
    form_data = {'function':'sub_total'};

    $.ajax({
        url: form_process,
        type: "POST",
        data: form_data,
        success: function (response)
        {
            echo(response);
            if(response.split('%%').length ===2)
            {
                var action = response.split('%%')[0], message = response.split('%%')[1];

                if(action === 'done')
                {
                    // splie message
                    if(message.split('()').length === 2)
                    {
                        $('#sub_total').text(message.split('()')[0]);
                        $('#tax').text(message.split('()')[1]);
                    }

                }
            }
        }

    });
}

// get bill items
function get_qty()
{



    var form_data = {
        'function':'get_items'
    }

    // send ajax request
    $.ajax({
        url:'/backend/process/form_process.php',
        type: 'POST',
        data: form_data,
        success: function (response) {
            console.log(response);
            if(response.split('%%').length === 2)
            {
                var action = response.split('%%')[0];
                var message = response.split('%%')[1];
                //console.log(action)

                if(action === 'done')
                {
                    echo(response)
                    $('#itemsRes').html(message)
                }
                else
                {
                    // disable buttons if there is no bill
                    var disable_list = 'momo_payment,cash_payment,cancel,hold,discount,REFUND';

                    arr_disable(disable_list)

                    var cust_html = "<div class='w-100 h-100 d-flex flex-wrap align-content-center justify-content-center'>" +
                        "<p class='fa fa-shopping-cart f-xxlg text-muted'></p>" +
                        "</div>";
                    $('#bill_loader').html(cust_html);
                }

            }
            // put response in box

        }
    });

}


// center pop up
// const popup_center = ({url, title, w, h}) => {
//     // Fixes dual-screen position                             Most browsers      Firefox
//     const dualScreenLeft = window.screenLeft !==  undefined ? window.screenLeft : window.screenX;
//     const dualScreenTop = window.screenTop !==  undefined   ? window.screenTop  : window.screenY;
//
//     const width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
//     const height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;
//
//     const systemZoom = width / window.screen.availWidth;
//     const left = (width - w) / 2 / systemZoom + dualScreenLeft
//     const top = (height - h) / 2 / systemZoom + dualScreenTop
//     const newWindow = window.open(url, title,
//       `
//       scrollbars=yes,
//       width=${w / systemZoom},
//       height=${h / systemZoom},
//       top=${top},
//       left=${left}
//       `
//     )
//
//     if (window.focus) newWindow.focus();
// }

// logout
$(document).ready(function() {
    $("#logout").click(function(){
        //
        $.ajax({
            url: '/backend/process/user_mgmt.php',
            type:'POST',
            data: {'function':'logout'},
            success: function (){
                location.reload()
            }
        });
    });
});
// cancel bill
function cancel_bill() {

    // set form data
    form_data = {
        'function':'cancel_current_bill'
    }

    Swal.fire({
        title: 'Are you sure you want to cancel bill?',
        icon: 'warning',
        showDenyButton: true,
        showCancelButton: false,
        confirmButtonText: 'Yes',
        denyButtonText: `No`,
    }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
            $.ajax({
                url: '/backend/process/form_process.php',
                type: 'POST',
                data: form_data,
                success: function (response)
                {
                    console.log(response)
                    get_bill();
                    // Swal.fire('Changes are not saved', '', 'info');
                    location.reload()
                }
            });
        } else if (result.isDenied) {
            Swal.fire('Changes are not saved', '', 'info')
        }
    })

    // make ajax call

}

$("#bill_loader").ready(function(){
    get_qty();
    //block will be loaded with element with id myid is ready in dom
    // setInterval(function(){
    //     //this code runs every second
    //
    // }, 1000);
})





$(function() {
    //hang on event of form with id=myform
    $("#general_form").submit(function(e) {
//prevent Default functionality
        e.preventDefault();
        //get the action-url of the form
        var actionurl = e.currentTarget.action;

        //$("#loader").modal("show");
        let formData = new FormData($(this).parents('form')[0]);

        formData = new FormData($('#general_form')[0]); // The form with the file inputs.
        const that = $(this),
            url = that.attr('action'),
            type = that.attr('method'),
            data = {};
        //console.log(url)

        that.find('[name]').each(function (index,value){
            var that = $(this), name = that.attr('name');
            data[name] = that.val();
        });

        $.ajax({

            url: url,
            type: type,
            data: formData,
            processData: false,  // tell jQuery not to process the data
            contentType: false,  // tell jQuery not to set contentType
            success: function (response){
                if(responseType(response) === 'done')
                {
                    //Swal.fire('done')

                    $('#barcode').val('')
                    $('#qty').val('')
                    $('#qty').focus();

                    get_qty()
                }
                else
                {
                    // show error
                }
                // error_handler(response);
                console.log(response)

            },

        });

        return false;

    });

});


// login form
$(function() {
    //hang on event of form with id=myform
    $("#login_form").submit(function(e) {
//prevent Default functionality
        e.preventDefault();
        //get the action-url of the form
        var actionurl = e.currentTarget.action;

        //$("#loader").modal("show");
        let formData = new FormData($(this).parents('form')[0]);

        formData = new FormData($('#login_form')[0]); // The form with the file inputs.
        const that = $(this),
            url = that.attr('action'),
            type = that.attr('method'),
            data = {};
        //console.log(url)

        that.find('[name]').each(function (index,value){
            var that = $(this), name = that.attr('name');
            data[name] = that.val();
        });

        $.ajax({

            url: url,
            type: type,
            data: formData,
            processData: false,  // tell jQuery not to process the data
            contentType: false,  // tell jQuery not to set contentType
            success: function (response){
                if(responseType(response) === 'done')
                {
                    //Swal.fire('done')
                    reload()
                }
                else
                {
                    // show error
                    swal_error(responseMessage(response));
                }
                // error_handler(response);
                console.log(response)

            },

        });

        return false;

    });

});



function dragElement(elmnt) {
    var pos1 = 0, pos2 = 0, pos3 = 0, pos4 = 0;
    if (document.getElementById(elmnt.id + "header")) {
        // if present, the header is where you move the DIV from:
        document.getElementById(elmnt.id + "header").onmousedown = dragMouseDown;
    } else {
        // otherwise, move the DIV from anywhere inside the DIV:
        elmnt.onmousedown = dragMouseDown;
    }

    function dragMouseDown(e) {
        e = e || window.event;
        e.preventDefault();
        // get the mouse cursor position at startup:
        pos3 = e.clientX;
        pos4 = e.clientY;
        document.onmouseup = closeDragElement;
        // call a function whenever the cursor moves:
        document.onmousemove = elementDrag;
    }

    function elementDrag(e) {
        e = e || window.event;
        e.preventDefault();
        // calculate the new cursor position:
        pos1 = pos3 - e.clientX;
        pos2 = pos4 - e.clientY;
        pos3 = e.clientX;
        pos4 = e.clientY;
        // set the element's new position:
        elmnt.style.top = (elmnt.offsetTop - pos2) + "px";
        elmnt.style.left = (elmnt.offsetLeft - pos1) + "px";
    }

    function closeDragElement() {
        // stop moving when mouse button is released:
        document.onmouseup = null;
        document.onmousemove = null;
    }
}