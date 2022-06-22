// error handler
function error_handler(response)
{
    // split response
    if(response.split('%%').length === 2)
    {
        let response_split = response.split('%%');
        let response_type = response_split[0];
        let response_message = response_split[1];

        // switching response type
        switch (response_type)
        {
            case 'error': // when the response type is an error
                switch (response_message) // switch between error message
                {
                    case "barcode_multiple_not_number": // item quantity is not a number
                        $('#general_input').addClass('bg-danger');
                        setTimeout(function (){$('#general_input').removeClass('bg-danger')},2000)
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Quantity value must be a number',
                        })
                        break;
                    case 'item_does_not_exist':
                        swal_error("Item Not Found");
                        break;
                    case 'bill_recall_does_not_exits': // bill recall does not exist
                        swal_error("Bill does not exist")
                        $('#general_input').val('');
                        break;
                    case 'bill_not_on_hold':
                        swal_error("Bill is not on hold")
                        $('#general_input').val('');
                        break;
                    case 'no_clerk_account':
                        swal_error("Invalid Clerk or Key")
                        break;
                    case 'no_clerk_key':
                        swal_error("Invalid Key")
                        break;
                    default:
                        echo(response_message)
                }
                break;
            case 'done':
                switch (response_message) {
                    case 'bill_added':
                        get_bill();
                        $('#general_input').val('');
                        // clear bill input
                        break;
                    default:
                        pass('lets go')
                }
        }

    }
}