function otpMove(id) {
    var split_id = id.split('_');
    var id_num = split_id[1];
    var next_id_number = parseInt(id_num) + 1;
    var next_id = "#otp_" + next_id_number.toString();
    $(next_id).val('')
    $(next_id).focus()

    if(id === 'otp_6')
    {
        // submit form
        console.log('submit');
        $('#regular_form').submit()
    }

}

function validateCard(cardNumber) {
    console.log(cardNumber)

    if(cardNumber.length === 10)
    {
        var form_date = {
            'token': token,
            'card':cardNumber,
            'validate_card':''
        }

        $.ajax({

            url:'config/proc/form/loyalty.php',
            type:'POST',
            data: form_date,
            success: function (response) {
                console.log(response)
                if(response === 'card_available')
                {

                    // enable save button
                    document.getElementById('save_button').disabled = false;
                }
                else
                {
                    // disable save button
                    document.getElementById('save_button').disabled = true;
                }
            }
        })
    }

}

function rowCount(table,condition)
{
    let form_data = {
        'token':token,
        'function':'row_count',
        'table':table,
        'condition':condition
    }

    var tmp = null;

    $.ajax({
        async:false,
        url: 'config/proc/form/loyalty.php',
        type: 'POST',
        data: form_data,
        success: function (response){
            tmp = response;
        }
    });
    return tmp;
}

function c_nav(direction) {
    console.log(direction)
    var form_date = {'function':'customerNavigator','direction':direction}
    $.ajax({
            async:false,
            url: 'config/proc/form/loyalty.php',
            type: 'POST',
            data:{'function':'customerNavigator','direction':direction,'token':token},
            success: function (response) {
                console.log(response)
                var spl = response.split('^');
                if(spl.length === 8)
                {
                    //console.log(spl.length);
                    let card_id,next,previ,sess_data,card_no
                    $('#firstName').text(spl[0]);
                    $('#lastName').text(spl[1]);
                    $('#emailAddress').text(spl[3]);
                    $('#phoneNumber').text(spl[2])
                    $('#ownerX').text(spl[4])
                    $('#location').text(spl[5]);
                    $('#card_no').text(spl[7])

                    card_id = spl[6]

                    sess_data = 'customer='+card_id.toString();
                    console.log(sess_data)

                    // check next
                    next = parseInt(rowCount('customers',"`id` > " + card_id.toString()))
                    if(next > 0)
                    {

                        document.getElementById('nextCustomer').disabled = false;
                    }
                    else
                    {
                        document.getElementById('nextCustomer').disabled = true;
                    }

                    // check previous
                    previ = parseInt(rowCount('customers',"`id` < " + card_id.toString()))
                    if(previ > 0)
                    {
                        // enable next
                        document.getElementById('previCustomer').disabled = false
                    }
                    else
                    {
                        document.getElementById('previCustomer').disabled = true;
                    }

                    console.log('hello')

                    sess_data = 'customer='+card_id.toString();
                    console.log(sess_data)
                    set_session('customer='+card_id.toString(),token,0)



                }
            }
        })
}

function selectCustomer(id) {
    //console.log(id)
    var x_data = {
        'token':token,'function':'activate_customer','customer':id
    }

    //console.log(x_data)

    $.ajax({
        async:false,
        url: 'config/proc/form/loyalty.php',
        type: 'POST',
        data:x_data,
        success: function (response) {
            console.log(response)
            var spl = response.split('^');
            if(spl.length === 8)
            {
                //console.log(spl.length);
                let card_id,next,previ,sess_data,card_no
                $('#firstName').text(spl[0]);
                $('#lastName').text(spl[1]);
                $('#emailAddress').text(spl[3]);
                $('#phoneNumber').text(spl[2])
                $('#ownerX').text(spl[4])
                $('#location').text(spl[5]);
                $('#card_no').text(spl[7])

                card_id = spl[6]

                sess_data = 'customer='+card_id.toString();
                console.log(sess_data)

                // check next
                next = parseInt(rowCount('customers',"`id` > " + card_id.toString()))
                if(next > 0)
                {

                    document.getElementById('nextCustomer').disabled = false;
                }
                else
                {
                    document.getElementById('nextCustomer').disabled = true;
                }

                // check previous
                previ = parseInt(rowCount('customers',"`id` < " + card_id.toString()))
                if(previ > 0)
                {
                    // enable next
                    document.getElementById('previCustomer').disabled = false
                }
                else
                {
                    document.getElementById('previCustomer').disabled = true;
                }

                console.log('hello')

                sess_data = 'customer='+card_id.toString();
                console.log(sess_data)
                set_session('customer='+card_id.toString(),token,0)

                $('#searchCustomer').modal('hide');



            }

        }
    })
}


function search(q) {

}

$('#customerSearchInput').on('input',function(e){
    var query = $('#customerSearchInput').val();

    var form_date = {
        'token':token,'function':'search_customer','query':query
    }

    $.ajax({
        async:false,
        url: 'config/proc/form/loyalty.php',
        type: 'POST',
        data:form_date,
        success: function (response) {
            //console.log(response)
            if(response.split('%%').length > 0)
            {
                var res = response.split('%%')[1];
                $('#cusRes').html(res)
                console.log(res);
            }

        }
    })

});

function updateCustomer(token) {
    let form_date = {
        'function':'update_customer',
        'token':token
    }

    $.ajax({
        url:'config/proc/form/loyalty.php',
        type:'POST',
        data: form_date,
        success: function (response)
        {
            console.log(response)
        }
    });
}