const token = document.getElementById('session_token').value;

// // AJAX FUNCTION
function xajax(url,variable,result_type,result_box)
{
    var msgbox = document.getElementById(result_box); // message box

    if (window.XMLHttpRequest)
    {
        variable = new XMLHttpRequest(); // make XMLHttp Object for chromium browsers
    }
    else
    {
        variable = new ActiveXObject('Microsoft.XMLHTTP'); // make XMLHttp Object for Microsoft Pains LOL
    }

    variable.onreadystatechange = function ()
    {
        if (variable.readyState === 4 && variable.status === 200)
        {
            const result = variable.responseText; // Ajax Response

            switch(result_type) // make a switch case
            {
                case 'msgbox': // display result in a message box provided
                    msgbox.innerHTML =  result + msgbox.innerHTML;
                    break;
                case 'console': // log result in console
                    console.log(result);
                    break;
                case 'reload':
                    location.reload();
                    break;
                default: // no display set. just console log this for debugging
                    console.log('no result type for ajax to work with')
            }

            console.log(result);

        }

        location.reload();
    }

    variable.open('GET', url, true);
    variable.send();

}

// get session
function getSession(variable) {
    var myVariable;
    $.ajax(
        {
            async:false,
            url: '/config/proc/form_process.php',
            type: 'POST',
            data:{'function':'get_session','token':token,'variable':variable},
            success: function (response) {
                myVariable  = response;
            }
        }
    );
    return myVariable;

}


// console.log(getSession('device'));


$('form#regular_form').on('submit', function () {
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

// UPLOAD FILE WITH POST
$('form#formWithFiles').on('submit', function (){

    console.log('uploading files');

    var formData = new FormData($('#formWithFiles')[0]); // The form with the file inputs.
    var that = $(this),
        url = that.attr('action'),
        type = that.attr('method'),
        data = {};
    console.log(formData)

    that.find('[name]').each(function (index,value){
        var that = $(this),
            name = that.attr('name'),
            value = that.val();
        data[name] = value;
    });

    $.ajax({

        xhr: function() {
            var progress = $('.progress'),
                xhr = $.ajaxSettings.xhr();

            progress.show();

            xhr.upload.onprogress = function(ev) {
                if (ev.lengthComputable) {
                    var percentComplete = parseInt((ev.loaded / ev.total) * 100);
                    progress.val(percentComplete);
                    if (percentComplete === 100) {
                        progress.hide().val(0);
                    }
                }
            };

            return xhr;
        },

        url: url,
        type: type,
        data: formData,
        processData: false,  // tell jQuery not to process the data
        contentType: false,  // tell jQuery not to set contentType
        success: function (response){
            console.log(response)
            location.reload();
            switch (response)
            {
                case "2PASS":
                    location.reload();
                    break;
                case "2FAIL":
                    alert("Error Finishing");
                    break;
                default :
                    console.log("No Option ( "+response+" )" )
                    location.reload();
            }
        }
    });

    return false;
});


// switch tool function
function switch_tool(tool,token)
{
    var date = {
        'token':token,
        'switch_tool':tool
    }

    $.ajax(
        {
            url: 'config/proc/ajax.php',
            type: 'POST',
            data: date,
            success: function (response){
                console.log(response);
                location.reload();
            }
        }
    );

    //xajax('config/proc/ajax.php?switch_tool='+tool+'&token='+token,'switch_tool','reload','xxx')
}

function item_nav(todo,direction)
{
    var form_data = {
        'token':token,
        'function':'navigate',
        'todo':todo,
        'direction':direction
    }
    $.ajax(
        {
            url: 'config/proc/ajax.php',
            type: 'POST',
            data: form_data,
            success: function (response){
                //console.log(response);
                location.reload();
            }
        }
    );
}

// general search
function search_item(todo,q) {
    var form_data = {
        'token':token,
        'function':'search',
        'todo':todo,
        'query':q
    }

    $.ajax(
        {
            url: 'config/proc/ajax.php',
            type: 'POST',
            data: form_data,
            success: function (response){
                console.log(response);
                //location.reload();
            }
        }
    );
}

// set session
function set_session(session_data,t = token,rel=1)  {
    var date = {
        'token':t,
        'function':'set_session',
        'data':session_data
    }

    $.ajax(
        {
            url: 'config/proc/ajax.php',
            type: 'POST',
            data: date,
            success: function (response){
                console.log(response);
                if(rel === 1)
                {
                    location.reload()
                }
            }
        }
    );
}

function db_sync(data)
{
    $('#loader').modal('show');
    $.ajax(
        {
            url: '/config/proc/form_process.php?'+data,
            type: "GET",
            processData: false,  // tell jQuery not to process the data
            contentType: false,  // tell jQuery not to set contentType
            success: function (response) {
                console.log(response);
                var split = response.split('%%');
                if(typeof(split[1]) === undefined)
                {
                    document.getElementById('infoplace').innerHTML = response;
                }
                else
                {
                    document.getElementById('infoplace').innerHTML = split[1];
                }

                // if(split[0].toString() === 'error')
                // {
                //     document.getElementById('errplace').innerHTML = split[1].toString();
                // } else if(split[0] === 'success')
                // {
                //     document.getElementById('successplace').innerHTML = split[1].toString();
                // } else if(split[0] === 'info')
                // {
                //     document.getElementById('infoplace').innerHTML = split[1].toString();
                // } else if(split[0] == 'done')
                // {
                //     location.reload();
                // } else if(split[0] == 'next')
                // {
                //     db_sync(data);
                // }
            }
        }
    );

    return false;
}

function loyalty_sync(data)
{
    setInterval(() => {
        db_sync(data);
    }, 5000)
}

// new bill item
$('form#stockItem').on('submit', function () {
    //$('#loader').modal('show');
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

    $.ajax({
        url: url,
        type: type,
        data: data,
        success: function (response){
            console.log(response);
            location.reload();
            // setTimeout(() => {
            //     location.reload();
            // }, 3000)


        }
    })





    return false;
})

// $('#show-apps').click(function(){
//     console.log('hello');
//     show_display('apps_box')
// });



function disable_form(id){
    var form = document.getElementById(id);
    var elements = form.elements;
    for (var i = 0, len = elements.length; i < len; ++i) {
        elements[i].disabled = true;
    }
}

function enable_form(id){
    var form = document.getElementById(id);
    var elements = form.elements;
    for (var i = 0, len = elements.length; i < len; ++i) {
        elements[i].disabled = false;
    }
}

$('#close_menu').click(function (){
    hide_display('apps_box');
});

$('#task_completed').click(function (){

    var form_data = {
        'function': 'complete_task','token':token
    }

    // submit form with ajax
    $.ajax({
        url:'/config/proc/ajax.php',
        type:'POST',
        data: form_data,
        success: function (response){
            console.log(response);
            location.reload()
        }
    });
});

function show_display(id)
{
    if(document.getElementById(id))
    {
        document.getElementById(id).style.display = '';
    } else {
        console.log(id + ' not found');
    }
}

function hide_display(id)
{
    if(document.getElementById(id))
    {
        document.getElementById(id).style.display = 'none';
    } else {
        console.log(id + ' not found');
    }
}

function isURL(str) {
    const pattern = new RegExp('^(https?:\\/\\/)?'+ // protocol
        '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|'+ // domain name
        '((\\d{1,3}\\.){3}\\d{1,3}))'+ // OR ip (v4) address
        '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*'+ // port and path
        '(\\?[;&a-z\\d%_.~+=-]*)?'+ // query string
        '(\\#[-a-z\\d_]*)?$','i'); // fragment locator
    return !!pattern.test(str);
}

function open_app(app,token) {
    var form_data = {
        'token': token,
        'app':app
    };

    $.ajax({
        url: '/config/proc/appnav.php',
        type: 'POST',
        data: form_data,
        success: function (response)
        {
            // split response
            var sp = response.split('%%');
            if(sp.length > 1)
            {
                let action = sp[1];
                if(isURL(action))
                {
                    // head to url
                    location.href=action;
                } else if(action === 'reload')
                {
                    location.reload();
                }
                console.log(action);
            }
        }
    });

}

//uninstall app
function uninstall_app(message,app) {
    if(confirm(message))
    {
        // proceed to uninstall
        console.log(app);
        var form_data = {
            'function':'uninstall_app',
            'token': token,
            'app':app
        }

        //submit form using ajax
        $.ajax({
            url:'/config/proc/ajax.php',
            type: 'POST',
            data: form_data,
            success: function (response){
                console.log(response)
                location.reload();
            }
        });

    } else
    {
        // do nothing about app
    }
}
// install app
function install_app(message,app) {
    if(confirm(message))
    {
        // proceed to uninstall
        console.log(app);
        var form_data = {
            'function':'install_app',
            'token': token,
            'app':app
        }

        //submit form using ajax
        $.ajax({
            url:'/config/proc/ajax.php',
            type: 'POST',
            data: form_data,
            success: function (response){
                console.log(response)
                location.reload();
            }
        });

    } else
    {
        // do nothing about app
    }
}

// search for app
$('input#searchForApp').on('input',function (){
    var q = document.getElementById('searchForApp').value;

    var date = {
        'token':token,
        'function':'app_store_search',
        'string':q
    }

    // make ajax request
    $.ajax({
        url:'/config/proc/ajax.php',
        type:'POST',
        data:date,
        success: function (response)
        {
            console.log(response);
            $('#app_res').html(response);
        }
    });
});


function rest_search(q)
{
    var form_data = {
        'function':'rest_search',
        'token':token,
        'q':q
    }

    // ajax
    $.ajax({
        url:'/config/proc/ajax.php',
        type:'POST',
        data:form_data,
        success: function (response)
        {
            console.log(response);
            $('#rest_res').html(response);
        }
    });
}

// search rest items
$('input#rest_search').on('input',function(e){
    let q = this.value;

        rest_search(q);

});

function delete_rest_item(barcode)
{
    var form_data = {
        'function':'del_rest_tem',
        'token':token,
        'barcode':barcode
    }

    // ajax
    $.ajax({
        url:'/config/proc/ajax.php',
        type:'POST',
        data:form_data,
        success: function (response)
        {
            console.log('deleted');

            let q = document.getElementById('rest_search').value;
            $('#rest_search').focus()
            rest_search(q);
        }
    });
}

function msinfo32(device)
{

    // show modal
    $('#msinfo32').modal('show');

    let for_data = {
      'function':'msinfo32'
    };

    $.ajax(
        {
            url: '/config/proc/form_process.php',
            type:'POST',
            data:{'function':'msinfo32','token':token},
            success: function (response) {

                if(response === 'done')
                {

                    let pdf_file = getSession('device') + '.pdf';
                    // console.log(pdf_file);
                    var pdf = "<embed src='http://test1.sneda.gh/assets/devices/"+pdf_file+"' width='100%' style='height: 80vh' type='application/pdf'>";
                    $('#modal_change').addClass('modal-lg');
                    $('#pdf').html(pdf);

                }
            }

        }
    );
}

// edit_pc_item
function edit_pc_item(target,unique) {


    let tittle = 'Modifying', f_form;
    tittle = 'Changing '+target.toString().toUpperCase();

    f_form ="<input type='hidden' name='function' value='change_device_part'>" +
        "<input type='hidden' name='part' value='" +
        target +
        "'>" +
        "<input type='hidden' name='token' value='"+token.toString()+"'>" +
        "<input type='hidden' name='device' value='"+unique.toString()+"'>" +
        "<input name='new' type='text' placeholder='Replacement' class='form-control rounded-0 mb-2' required autocomplete='off'>" +
        "<textarea rows='3' placeholder='Reason for change' required class='form-control rounded-0' name='change_reason'></textarea>"

    $('#edit_pc_item_title').text(tittle);
    $('#edit_pc_item_form').html(f_form);
    $('#regular_form').modal('show');
}

function arrangeStock() {
    var form_data = {
        'function':'arrangeStock',
        'token':token
    }

    $.ajax(
        {
            url:'config/proc/form/stock.php',
            type: 'POST',
            data: form_data,
            success: function (response) {
                console.log('hello')
                console.log(response)
                $("#arrandRest").prepend(response)
                //$('#arrandRest').html(response + $('#arrandRest').html());
            }
        }
    );

}

function triggerArrange() {
    setInterval(function(){
        arrangeStock()
    }, 1000);
}
