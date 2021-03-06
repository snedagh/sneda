<?php
    include 'backend/includes/ini.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipe Maker</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/main.css">
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/popper.min.js"></script>

    <script src="js/recipe.js"></script>
    <script src="js/query.js"></script>
    <script src="js/j_query_supplies.js"></script>
    <script src="js/session.js"></script>
    <script src="js/db_trans.js"></script>
    <script src="js/anton.js"></script>



    <script src="js/sweetalert2@11"></script>
    <link rel="stylesheet" href="css/sweetalert.min.css">

</head>
<body class="bg-light">

    <div style="height: 100vh!important; display: block" id="loader" class="w-100 h-100">
        <div class="h-100 w-100 d-flex flex-wrap align-content-center justify-content-center">
            <div style="height: 50px !important; width: 50px !important">
                <img src="res/gif/menu_items.gif" alt="Loading..." class="ing-fluid">
            </div>
        </div>
    </div>

    <main id="page_content" style="display: none !important" class="container-fluid h-100 overflow-hidden">
        <div class="row d-flex flex-wrap align-content-center p-2 h-100 overflow-auto">
            <div class="col-sm-8 h-100 overflow-hidden d-flex align-content-start flex-wrap">
                <div style="height: 10vh; overflow: hidden" class="w-100 d-flex flex-wrap">
                    <div class="w-75">
                        <input type="search" autocomplete="off" id="myInput" placeholder="Find and item" class="form-control rounded-0">
                    </div>
                    <div class="w-25 d-flex flex-wrap justify-content-end">
                        <kbd style="height: fit-content"><span id="done">0</span> / <span id="all">0</span></kbd>
                    </div>
                </div>
                <div style="height: 90vh; overflow: auto" class="w-100 d-flex justify-content-between flex-wrap" id="myDIV">

                </div>
            </div>

            <div id="recipe_card" class="col-sm-4 p-0 shadow bg-dark h-100 d-flex flex-wrap">
                <div class="card rounded-0 h-100 w-100">
                    <div class="card-header">
                        <div class="w-100 h-100 d-flex flex-wrap justify-content-between align-content-center">
                            <div class="h-100 w-100 d-flex flex-wrap align-content-center justify-content-between">
                                <p class="m-0 card-title" id="item_name">Select And Item</p>
                                <button onclick="$('#rec_form').fadeIn(1000)" class="btn btn-sm btn-success">NEW ITEM</button>
                            </div>

                        </div>
                    </div>

                    <div class="card-body p-0">
                        <ul id="rec_form" style="display: none" class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <input type="hidden" id="item_parent" class="form-control form-control-sm">
                                <input type="text" name="item_name_x" id="item_name_x" placeholder="Item Name" autocomplete="off" class="form-control form-control-sm rounded-0">
                                <input type="number" name="qty" class="ml-1 form-control form-control-sm rounded-0 w-50" placeholder="qty" id="qty">
                                <select name="unit" class="form-control form-control-sm rounded-0 ml-1 w-50" id="unit">
                                    <option value="g">g</option>
                                    <option value="PCS">PCS</option>
                                    <option value="PCS">KG</option>

                                    <option value="PCS">LIT</option>
                                </select>
                                <button onclick="new_recipe_item()" class="btn btn-sm btn-success ml-1">ADD</button>
                            </li>
                        </ul>

                        <ul id="rec_list" class="list-group list-group-flush">


                        </ul>
                    </div>

                </div>
            </div>

        </div>
    </main>
</body>
</html>


<script>
    $(document).ready(function(){
        $("#myInput").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#myDIV *").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });

    // load items for page
    loadMenuItems();





</script>