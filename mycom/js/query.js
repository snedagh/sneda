function row_count(table,condition = 'none') {
    var form_data = {
        'function':'row_count',
        'table':table,
        'condition':condition
    }

    // echo("SELECT * FROM "+table+" WHERE "+condition)

    var result = 0;

    $.ajax(
        {
            url:'backend/process/ajax_tools.php',
            'async': false,
            'type': "POST",
            'global': false,
            'dataType': 'html',
            data:form_data,
            success: function (response)
            {
                result = response;


            }
        }
    );

    return parseInt(result);

}

// execute query
function exec(query = 'none')
{
    if(query !== 'none')
    {
        // prepare
        form_data = {
            'function':'query','query':query
        }
        $.ajax(
            {
                url: 'backend/process/ajax_tools.php',type: 'POST',data:form_data,success: function (respose) {
                    echo(respose)
                }
            }
        );
    }
}

// fetch rows with condition
function get_row(table,condition) {
    var form_data = {
        'function':'get_row',
        'table':table,
        'condition':condition
    }

    var result = 0;

    $.ajax(
        {
            url:'backend/process/ajax_tools.php',
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

// insert data into table
function insert(table,data) {

    let cols = data['cols']
    let vars = data['vars']

    if(cols.length > vars.length)
    {
        swal_error("Columns are more than values \n Columns : " + cols + "\n Values : "+ vars)
    } else if (vars.length > cols.length)
    {
        swal_error("Values are more than columns \n Columns : " + cols + "\n Values : "+ vars)
    } else {
        // prepare to execute
        let columns = '';
        for (let i = 0; i < cols.length; i++) {

            if(cols.length - i > 1)
            {
                columns += "`"+cols[i]+"`,"
            } else {
                columns += "`"+cols[i]+"`"
            }

        }

        let values = '';
        for (let i = 0; i < vars.length; i++) {

            if (cols.length - i > 1) {
                values += '"'+ vars[i] + '",'
            } else {
                values += '"' + vars[i] + '"'
            }

        }

        let query = "INSERT INTO "+ table + " (" + columns + ") values ("+values+")";
        // echo(query)

        // prepare ajax submission
        var form_data = {
            'function':'insert',
            'query':query
        }
        var result = 0;

        $.ajax(
            {
                url:'backend/process/ajax_tools.php',
                'async': false,
                'type': "POST",
                'global': false,
                'dataType': 'html',
                data:form_data,
                success: function (response)
                {
                    result = response;
                    // echo(result)

                }
            }
        );

        return result;


        //echo(values)
    }





    //echo("INSERT INTO " + table + " (" + col +") values ("+data+")")
}

// fetch rows from query
function rows(query)
{
    form_data = {
        'function':'query_rows',
        'query' : query
    }

    var result = 0;

    $.ajax(
        {
            url:'backend/process/ajax_tools.php',
            'async': false,
            'type': "POST",
            'global': false,
            'dataType': 'html',
            data:form_data,
            success: function (response)
            {
                result = response;
                //echo("GET_ROW QUERY : " + query.toString())

            }
        }
    );

    return result;
}

// sum of a row
function row_sum(table, column,condition='none')
{
    var form_data = {
        'function':'row_sum',
        'column':column,
        'table':table,
        'condition':condition
    }

    var sum = 0;

    $.ajax(
        {
            url:'backend/process/ajax_tools.php',
            'async': false,
            'type': "POST",
            'global': false,
            'dataType': 'html',
            data:form_data,
            success: function (response)
            {
                sum = response;
                let query = "SELECT SUM(" + column + ") FROM " + table + " WHERE " + condition
                echo("GET_ROW_SUM : " + query.toString())

            }
        }
    );

    return sum;

}