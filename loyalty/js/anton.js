// $(document).ready(function(){
//     $("#myInput").on("keyup", function() {
//         var value = $(this).val().toLowerCase();
//         $("#myTable tr").filter(function() {
//             $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
//         });
//     });
// });
//
// imgInp.onchange = evt => {
//     const [file] = imgInp.files
//     if (file) {
//         blah.src = URL.createObjectURL(file)
//     }
// }
//
// // Add the following code if you want the name of the file appear on select
// $(".custom-file-input").on("change", function() {
//     var fileName = $(this).val().split("\\").pop();
//     $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
// });
//
// //check otp
// function checkOtp(otp)
// {
//
//     if(otp.length > 0)
//     {
//         document.getElementById('spinner').style.display = '';
//         document.getElementById('otpRes').style.display = '';
//     }
//     else
//     {
//         document.getElementById('spinner').style.display = 'none';
//         document.getElementById('otpRes').style.display = 'none';
//     }
//
//     if (window.XMLHttpRequest)
//     {
//         xmlhttp = new XMLHttpRequest();
//     }
//     else
//     {
//         xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
//     }
//
//     xmlhttp.onreadystatechange = function ()
//     {
//         if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
//         {
//             var result = xmlhttp.responseText;
//             console.log(result);
//             document.getElementById('otpRes').innerHTML = result;
//
//             if(result === 'OTP MATCH')
//             {
//                 location.reload();
//             }
//
//         }
//     }
//
//     xmlhttp.open('GET', 'config/proc/form_process.php?token=<?php echo session_id() ?>&checkOtp&otp=' + otp, true);
//     xmlhttp.send();
// }
//
// //make query set
// function customQuery(action) {
//     if(action === 'on')
//     {
//         document.getElementById('querySet').style.display = '';
//         document.getElementById('qRes').style.display = '';
//     }
//     else
//     {
//         document.getElementById('querySet').style.display = 'none';
//         document.getElementById('qRes').style.display = 'none';
//     }
// }
//
// // test query
// function testQuery(val)
// {
//     const query_x = document.getElementById('querySet').value;
//     if (window.XMLHttpRequest)
//     {
//         xmlhttp = new XMLHttpRequest();
//     }
//     else
//     {
//         xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
//     }
//
//     xmlhttp.onreadystatechange = function ()
//     {
//         if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
//         {
//             const result = xmlhttp.responseText;
//             document.getElementById('qRes').innerHTML = result;
//             console.log(result);
//
//         }
//     }
//
//     xmlhttp.open('GET', 'config/proc/form_process.php?token=<?php echo session_id() ?>&testQuery&q=' + val, true);
//     xmlhttp.send();
// }
//
// // VALIDATE SMS
// function validateSms(msg)
// {
//
//     const message_len = msg.length;
//     const valid_len = 20 - message_len;
//     if(message_len >= 20 && contacts > 0)
//     {
//         document.getElementById('senSms').disabled = false;
//     }
// }
//
// function br(str)
// {
//
//     var year = new Date().getFullYear();
//     var month = new Date().getMonth();
//     var day = new Date().getUTCDay();
//     var d = year + ':' + month + ':' + day;
//
//     var result = d + ' ' +  str.toString() + '<br>';
//     return result;
// }
//
// // AJAX FUNCTION
// function ajax(url,variable,result_type,result_box)
// {
//     var msgbox = document.getElementById(result_box); // message box
//
//     if (window.XMLHttpRequest)
//     {
//         variable = new XMLHttpRequest(); // make XMLHttp Object for chromium browsers
//     }
//     else
//     {
//         variable = new ActiveXObject('Microsoft.XMLHTTP'); // make XMLHttp Object for Microsoft Pains LOL
//     }
//
//     variable.onreadystatechange = function ()
//     {
//         if (variable.readyState === 4 && variable.status === 200)
//         {
//             const result = variable.responseText; // Ajax Response
//
//             switch(result_type) // make a switch case
//             {
//                 case 'msgbox': // display result in a message box provided
//                     msgbox.innerHTML =  result + msgbox.innerHTML;
//                     break;
//                 case 'console': // log result in console
//                     console.log(result);
//                     break;
//                 default: // no display set. just console log this for debugging
//                     console.log('no result type for ajax to work with')
//             }
//
//             console.log(result);
//
//         }
//     }
//
//     variable.open('GET', url, true);
//     variable.send();
//
// }
//
// //send sms
// function sendSms()
// {
//     var selectedCustomers = document.getElementById('selectedCustomers'); // Customers Frame
//     var execTerminal = document.getElementById('execTerminal'); // Terminal Frame
//     var msgbox = document.getElementById('terminalMessage');
//
//     //hide customers and show terminal
//     selectedCustomers.style.display = 'none';
//     execTerminal.style.display = '';
//
//     // initializing terminal
//     msgbox.innerHTML =  '<?php br("Initializing"); ?>' + msgbox.innerHTML;
//     msgbox.innerHTML =  '<?php br($n . " Contacts to Validate"); ?>' + msgbox.innerHTML;
//
//     var max = 3;
//
//     for (let i = 0; i < max; i++) {
//         if(i === 0)
//         {
//             //check session
//             ajax('config/proc/task.php?token=<?php echo session_id() ?>&sendSms&s=check_session','check_session','msgbox','terminalMessage');
//         }
//         else if(i === 1)
//         {
//             // validate contacts
//             ajax('config/proc/task.php?token=<?php echo session_id() ?>&sendSms&s=validate_numbers','validate_numbers','msgbox','terminalMessage');
//         }
//         else if (i === 2)
//         {
//             // send sms
//         }
//     }
//
//
//
//
//
//
//
//
//
// }
//
//
// function disable(id)
// {
//     document.getElementById(id).disabled = true;
// }

function query(specs) {

    var query_console = document.getElementById('queryConsole');
    var sql = 'none';


    switch (specs){
        case 'all_customer':
            // all customers
            sql = "SELECT * FROM `customers`";
            break;
        case 'updated_customers':
            // customers updated
            sql = "SELECT * FROM `customers` where `v2` = 1";
            break;
        case 'pending_v2':
            // customers pending update
            sql = "SELECT * FROM `customers` where `v2` < 1";
            break;
        default :
            sql = "Type a query";
    }

    query_console.innerHTML = sql;
    testQuery(sql);
}

$('form#process_customer').on('submit', function () {

    $('#loader').modal('show')
    document.getElementById('errplace').innerHTML = '';

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
            switch (response)
            {
                case "LS00" :
                    // done
                    location.reload()
                    break;
                case "LS13":
                    // age age less
                    document.getElementById('errplace').innerHTML = '<small class="text-danger">Age must be > 17</small>';
                    break;
                case "LS11":
                    // phone number exist
                    $('#loader').modal('hide')
                    document.getElementById('errplace').innerHTML = '<small class="text-danger">Number Taken</small>';

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
                    document.getElementById('errplace').innerHTML = '<small class="text-primary">Validating....</small>';
                    setTimeout(() => {
                        document.getElementById('errplace').innerHTML = '<small class="text-danger">Wrong Code</small>';
                    }, 5000)
                    break;
                case "LX3":
                    // Card used
                    document.getElementById('errplace').innerHTML = '<small class="text-primary">Validating....</small>';
                    setTimeout(() => {
                        document.getElementById('errplace').innerHTML = '<small class="text-danger">Card Not Available</small>';
                    }, 1000)
                    break;
                case 'MSG01' :
                    // message group set
                    location.reload();
                    break;

                case 'MSGERR':
                    // failed adding new message group or failed updating
                    alert("There is an error. You can't proceed. Please contact system admin")

                default :
                    document.getElementById('errplace').innerHTML = response;
                    setTimeout(() => {
                        $('#loader').modal('hide')
                    }, 2000)
                    console.log("No Option)")
            }

            if(response === 'LS11')
            {

            }
            else if(response === 'LS00')
            {

            }
        }
    })



    return false;
})

// UPLOAD FILE WITH POST
$('form#formWithFiles').on('submit', function (){

    $('#loader').modal('show');

    var formData = new FormData($('#formWithFiles')[0]); // The form with the file inputs.
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
            console.log(response);
            if(response.toString() === 'UP01')
            {
                location.reload();
            }
            else if(response.toString() === 'UP02')
            {
                document.getElementById('uploadInfo').innerHTML = '<small class="text-info">Error Uploading file</small>';
            }
            else if(response.toString() === 'UPS0')
            {
                document.getElementById('uploadInfo').innerHTML = '<small class="text-info">Select a file</small>'
            }
        }
    });

    return false;
});

function search_customer(query,token)
{

    if(query.toString().length > 0)
    {
        $.ajax({
            url:"/config/proc/form_process.php",
            type: 'POST',
            data: {
                'search_customer':'',
                'token': token,
                'query': query
            },
            success: function (response)
            {
                console.log(response);
                document.getElementById('searchResult').innerHTML = response.toString();
                // var res_split = response.split('%%');
                // if(typeof res_split[1] != undefined)
                // {
                //     document.getElementById('searchResult').innerHTML = res_split[1];
                // }
            }
        });
    }
    else
    {
        document.getElementById('searchResult').innerHTML = "" +
            "<div class='w-100 h-100 d-flex flex-wrap align-content-center justify-content-center'>" +
            "<p class='enc'>EMPTY QUERY</p>" +
            "</div>";
    }


}

function set_session(token, data)
{
    console.log(token)
    document.body.style.cursor = 'progress';
    $.ajax({
        url: "/config/proc/form_process.php",
        type: 'POST',
        data: {
            'token': token,
            'set_session':'',
            'data': data
        },
        success: function (response)
        {
            location.reload()
        }
    });
}

function find_img(token)
{

    setInterval(function() {
        $.ajax(
            {
                url: "/config/proc/form_process.php",
                type: 'POST',
                data: {
                    'token': token,
                    'get_uploaded_id': '',
                },
                success: function (response)
                {

                    var s = response.split('%%');
                    var code = s[0];
                    if(code == 'Y')
                    {
                        var image = s[1];
                        document.getElementById('image_display').src='/assets/customers/'+image.toString();

                        document.getElementById('img_ckeck').style.display='none';
                        document.getElementById('next').disabled = false;
                    }
                    else
                    {
                        document.getElementById('img_ckeck').innerHTML = '<span class="spinner-border spinner-border-sm"></span>\n' +
                            '                                            Upload id using LIP from phone';
                        document.getElementById('next').disabled = true;
                    }
                }
            }
        );
    },  3000)
}

