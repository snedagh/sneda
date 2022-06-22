<?php
    include 'backend/includes/core.php';
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Dashboard</title>

    <link
      rel="stylesheet"
      href="css/bootstrap.min.css"
    />



    <link
      href="https://fonts.googleapis.com/css?family=Montserrat"
      rel="stylesheet"
    />

    <link
      rel="stylesheet"
      href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"
    />
    <link rel="stylesheet" href="css/style.css" />

      <script src="js/jquery.min.js"></script>
      <script src="js/popper.min.js"></script>
      <script src="js/bootstrap.js"></script>
      <script src="js/anton.js"></script>
      <script src="js/query.js"></script>
  </head>

  <body>
    <div id="wrapper">
      <div class="content-area">
        <div class="container-fluid">
          <div class="modal fade" id="trans_modal">
            <div class="modal-dialog">
                <div class="modal-content bg-secondary text-light">
                    <div class="modal-header">
                        <strong class="modal-title" id="trans_hd"></strong>
                    </div>
                    <div class="modal-body">
                        <div id="trans_body">
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered table-striped">
                                    <thead>
                                        <tr><th>Location</th><th>Value</th></tr>
                                    </thead>
                                    <tbody id="modal_tb">
                                        <tr><td>SPINTEX</td><td>0.00</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
          </div>

          <div class="main">
            <div class="row sparkboxes mt-4">
              <div onclick="viewTransactions('grossSales')" class="col-md-3 mb-3">
                <div class="box box1 h-100px">
                  <div class="details">
                      <h4>Gross</h4>
                      <h3 id="gross">0</h3>
                  </div>
                </div>
              </div>


              <div class="col-md-3 mb-3">
                <div onclick="viewTransactions('deduct')" class="box box2 h-100px">
                  <div class="details">
                      <h4>Deductons</h4>
                      <h3 id="deducts">0</h3>
                  </div>
                </div>
              </div>

              <div class="col-md-3 mb-3">
                <div onclick="viewTransactions('net')" class="box box1 h-100px">
                  <div class="details">
                      <h4>Net</h4>
                      <h3 id="net_sales">0</h3>
                  </div>
                </div>
              </div>

              <div class="col-md-3 mb-3 h-100px">
                <div onclick="viewTransactions('tax')" class="box box3 h-100">
                  <div class="details">
                      <h4>Tax</h4>
                      <h3 id="tax">0</h3>
                  </div>
                </div>
              </div>

              <div class="col-md-3 mb-3">
                <div onclick="viewTransactions('total_sales')" class="box box4 h-100px">
                  <div class="details">
                      <h3>Total Sales</h3>
                      <h3 id="total_sales">0</h3>
                  </div>
                </div>
              </div>
            </div>

            <div class="row mt-4">

                <div class="col-md-12">
                    <div class="box shadow mt-4">
                      <div id="line-adwords" class=""></div>
                    </div>
                  </div>
              
            </div>


            <!--LIVE SALES-->
            <div class="box shadow mt-4 w-100" style="height: 50vh;">
                <header>
                    <strong>Live Transactions</strong>
                </header>
                <article class="overflow-hidden">
                    <div class="table table-responsive h-100 overflow-auto">
                        <!-- NO whatever -->
                        <div id="no_transaction" style="display: none !important" class='w-100 h-100 d-flex flex-wrap align-content-center justify-content-center'>
                            <h3 class="text-muted">NO TRANSACTION</h3>
                        </div>
                        <table id="transactions" style="display: none" class="table table-sm table-bordered">
                            <thead>
                                <tr><th>Location</th><th>Bill#</th><th>Casher</th><th>Mech N<u>0</u></th><th>Item</td><th>Quantity</th><td>Un. Price</td><th>Amount</th></tr>
                            </thead>
                            <tbody id="bill_trans_tr">
                                <tr>
                                    <td>None</td>
                                    <td>01</td>
                                    <td>Anton</td>
                                    <td>Item</td>
                                    <td>Mango Juice</td>
                                    <td>1</td>
                                    <td>12</td>
                                    <td>150</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </article>
            </div> 

            <div class="row mt-4">

<!--              <div class="col-md-5">-->
<!--                <div class="box shadow mt-4">-->
<!--                  <div id="barchart"></div>-->
<!--                </div>-->
<!--              </div>-->
<!--                -->
<!--                -->
<!--              <div class="col-md-7">-->
<!--                <div class="box shadow mt-4">-->
<!--                  <div id="areachart"></div>-->
<!--                </div>-->
<!--              </div>-->

            </div>
          </div>
        </div>
      </div>
    </div>



    <script src="js/apexcharts.js"></script>
    <script src="js/script.js"></script>

    <script src="js/mycom.js"></script>

  </body>
</html>