<!DOCTYPE html>
<html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Vending Machine</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
  </head>

  <body class="bg-light">

    <div class="container">
      <div class="py-5 text-center">
        <h2>Vending Machine</h2>
      </div>
      <div class="row">
        <div class="col-md-4 order-md-2 mb-4">
          <h4 class="d-flex justify-content-between align-items-center mb-3">
            <span class="text-muted">Total Inserted Coins</span>
          </h4>
          <ul id="" class="list-group mb-3">
            <li class="list-group-item d-flex justify-content-between lh-condensed">
              <div>
                <h6 class="my-0">TOTAL</h6>
              </div>
              <span id="totalCoinsInserted" class="text-muted"></span>
            </li>
            <li class="list-group-item justify-content-between lh-condensed">
              <div>
                <button type="button" class="btn btn-sm btn-block btn-danger ml-auto" onclick="returnCoins()"> Return </button>
              </div>
            </li>
          </ul>
          <h4 class="d-flex justify-content-between align-items-center mb-3">
            <span class="text-muted">Insert Coins</span>
          </h4>
          <ul id="coins" class="list-group mb-3"></ul>
        </div>
        <div class="col-md-4 order-md-2 mb-4">
          <h4 class="d-flex justify-content-between align-items-center mb-3">
            <span class="text-muted">Select Product</span>
          </h4>
          <ul id="products" class="list-group mb-3"></ul>
          <h4 class="d-flex justify-content-between align-items-center mb-3">
            <span class="text-muted">Result</span>
          </h4>
          <ul id="result" class="list-group mb-3">
            <li class="list-unstyled alert alert-secondary" role="alert"> </li>
          </ul>
        </div>
        <div class="col-md-4 order-md-2 mb-4">
          <h4 class="d-flex justify-content-between align-items-center mb-3">
            <span class="text-muted">Status Machine</span>
          </h4>
          <ul class="list-group mb-3">
            <li class="list-group-item d-flex justify-content-between lh-condensed">
              <div>
                <h6  class="my-0">Products Available</h6>
                <div id ="products_available"></div>
              </div>
            </li>
            <li class="list-group-item d-flex justify-content-between lh-condensed">
              <div>
                <h6 class="my-0">Coins Available</h6>
                <div id ="coins_available"></div>
              </div>
            </li>
            <li class="list-group-item d-flex justify-content-between lh-condensed">
              <div>
                <h6 class="my-0">Inserted Coins</h6>
                <div id ="inserted_coins"></div>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </div>

    <script>
    $(function() {
      // Set new order
      $.ajax({
        type: 'POST',
        data: {"status":"New"},
        url: "http://localhost:8000/api/order",
      })
      .done(function( result ) {
        idOrder = result.idOrder;
        // Display updated machine values
        updateMachineStatus(result.statusMachine);
      });
    });

    function insertCoin(element) {
      insertedCoin = $(element).text();
      $.ajax({
        type: 'POST',
        data: {"coin":insertedCoin},
        url: "http://localhost:8000/api/ordercoins/"+idOrder,
      })
      .done(function( result ) {
        // Display updated machine values
        updateMachineStatus(result.statusMachine);
      });
    }

    function returnCoins() {
      $.ajax({
        type: 'GET',
        url: "http://localhost:8000/api/ordercoins/"+idOrder+"/delete",
      })
      .done(function( result ) {
        let orderCoinsReturn = result.orderCoinsReturn;
        $("#result li").empty();
        $("#result li").append('<small class="text-muted">Return: </small>');
        $.each(orderCoinsReturn, function(index, value) {
          let orderCoinReturn = orderCoinsReturn[index];
          // Set coins inserted status machine
          $("#result li").append('<small class="text-muted"><b> ' + orderCoinReturn.coin + ' </b></small>');
        });

        // Display updated machine values
        updateMachineStatus(result.statusMachine);
      });
    }

    function makePayment(element) {
      productName = $(element).text();
      $.ajax({
        type: 'POST',
        data: {"productName":productName},
        url: "http://localhost:8000/api/order/"+idOrder+"/payment",
      })
      .done(function( result ) {
        let changeCoins = result.changeCoins;
        $("#result").empty();
        if(result.status === 'Success'){
          idOrder = result.idOrder;
          $("#result").html('<li class="list-unstyled alert alert-success"><b>' + result.message + '</b> Sold. Change: <b>'+ result.change +'</b></li>');

          if(changeCoins){
            $("#result").append('<li id="changeCoins" class="list-unstyled alert alert-secondary"></li>');
            $("#changeCoins").append('<small class="text-muted"> Return coins: </small>');
          }
          $.each(changeCoins, function(index, value) {
            let changeCoin = changeCoins[index];
            let coin = changeCoin.coin+' ';
            //RETURN-COIN
            let coins = coin.repeat(changeCoin.coinsNumber);
            // Set coins inserted status machine
            $("#changeCoins").append('<small class="text-muted"><b> '+ coins +' </b></small>');
          });
        }else{
          $("#result").html('<li class="list-unstyled alert alert-danger">' + result.message + '</li>');
        }
        // Display updated machine values
        updateMachineStatus(result.statusMachine);
      });
    }


    function updateMachineStatus(statusMachine){
       let products   = statusMachine.products;
       let coins      = statusMachine.coins;
       let orderCoins = statusMachine.orderCoins;

       var totalCoinsInserted = 0;
       $("#products_available").empty();
       $("#coins_available").empty();
       $("#inserted_coins").empty();
       $("#products").empty();
       $("#coins").empty();

      // Set products
      $.each(products, function(index, value) {
        let product = products[index];
        $("#products").append('<li class="list-group-item d-flex justify-content-between lh-condensed"> \
                                <div> \
                                  <button type="button" class="btn btn-sm  btn-block btn-info" onclick="makePayment(this)">' + product.name + '</button> \
                                </div> \
                                <span class="text-muted">$' + product.price + '</span> \
                              </li>');

        $("#products_available").append('<small class="text-muted">' + product.name + ' <span class="text-muted boldb"> <strong>' + product.items + '</strong></span></small><br />');
      });

      // Set coins available
      $.each(coins, function(index, value) {
        let coin = coins[index];
        // Set coins for insert
        $("#coins").append('<li class="list-group-item  justify-content-between lh-condensed"> \
                                <div> \
                                  <button type="button" class="btn btn-sm  btn-block btn-primary" onclick="insertCoin(this)">' + coin.coin + '</button> \
                                </div> \
                              </li>');
        // Set coins status machine
        $("#coins_available").append('<small class="text-muted">' + coin.coin + ' <span class="text-muted boldb"> <strong>' + coin.items + '</strong></span></small><br />');
      });

      // Set inserted coins
      $.each(orderCoins, function(index, value) {
        let orderCoin = orderCoins[index];
        totalCoinsInserted = parseFloat(totalCoinsInserted) + parseFloat(orderCoin.coin);
        // Set coins inserted status machine
        $("#inserted_coins").append('<small class="text-muted">' + orderCoin.coin + '</small><br />');
      });

      $("#totalCoinsInserted").text(totalCoinsInserted.toFixed(2));

    }

    </script>
</body></html>
