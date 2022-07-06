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
        url: 'backend/process/js_helpa.php',
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
            url:'backend/process/js_helpa.php',
            'async': false,
            'type': "POST",
            'global': false,
            'dataType': 'html',
            data:form_data,
            success: function (response)
            {
                result = response;
                // console.log(response)
                // echo("GET_ROW QUERY : SELECT * FROM " + table + " WHERE " + condition.toString())

            }
        }
    );

    return result;
}

function fetch_return(db,query) {
    var form_data = {
        'function':'fetch',
        'db':db,
        'query':query
    }




    var result = {'none':'none'};

    if(query.length > 0)
    {
        $.ajax(
            {
                url:'backend/process/js_helpa.php',
                'async': false,
                'type': "POST",
                'global': false,
                'dataType': 'html',
                data:form_data,
                success: function (response)
                {
                    result = JSON.parse(response);
                    console.log(query)
                    // echo("GET_ROW QUERY : SELECT * FROM " + table + " WHERE " + condition.toString())

                }
            }
        );
    }



    return result;
}

function query_row(db,query) {
    var form_data = {
        'function':'query_row',
        'query':query,
        'db':db
    }




    var result = {'none':'none'};

    if(query.length > 0)
    {
        $.ajax(
            {
                url:'backend/process/js_helpa.php',
                'async': false,
                'type': "POST",
                'global': false,
                'dataType': 'html',
                data:form_data,
                success: function (response)
                {
                    result = response;
                    console.log(query)
                    // console.log(response)
                    // echo("GET_ROW QUERY : SELECT * FROM " + table + " WHERE " + condition.toString())

                }
            }
        );
    }



    return result;
}

function exec(db,query)
{
    form_data = {
        'function':'exec',
        'query':query,
        'db':db,
    }

    $.ajax(
        {
            url:'backend/process/js_helpa.php',
            'async': false,
            'type': "POST",
            'global': false,
            'dataType': 'html',
            data:form_data,
            success: function (response)
            {
                console.log("Query : " + query)
                let result = response;
                console.log(response)
                // console.log(response)
                // echo("GET_ROW QUERY : SELECT * FROM " + table + " WHERE " + condition.toString())

            }
        }
    )
}
