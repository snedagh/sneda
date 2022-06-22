function mssql_rows(table,condition = 'none',limit=0)
{
    if(limit < 1)
    {
        var lim = '';
    }
    else
    {
        var lim = "TOP("+limit+")";
    }

    if(condition === 'none')
    {
        var cond = '';
    } else
    {
        cond =  " WHERE " + condition.toString()
    }

    var query = "SELECT "+lim+" * FROM " + table.toString() + " " + cond.toString()
    var result = 0;


    var form_data = {
        'function':'row_count',
        'query' : query
    }

    $.ajax({
        url: 'backend/process/js_helper.php',
        'async': false,
        'type': "POST",
        'global': false,
        'dataType': 'html',
        data:form_data,
        success: function (response) {
            result = response;
        }
    });

    return parseInt(result);


}

function get_row(table,condition = 'none',limit = 0) {
    var form_data = {
        'function':'get_row',
        'table':table,
        'condition':condition,
        'limit' : limit
    }


    var result = 0;

    $.ajax(
        {
            url:'backend/process/js_helper.php',
            'async': false,
            'type': "POST",
            'global': false,
            'dataType': 'html',
            data:form_data,
            success: function (response)
            {
                result = response;
                // echo("GET_ROW QUERY : SELECT * FROM " + table + " WHERE " + condition.toString())

            }
        }
    );

    return result;
}
