<h1>Checkout Example Page</h1>
<h2>This Page for testing purpose</h2>
<h3>You can modifiy this template for your development purposes</h3>
<body>
    <form id="myCCForm" action="{{url('/checkout')}}" method="post">
    {{ csrf_field() }}
      <input id="token" name="token" type="hidden" />
      <div>
        <label>
          <span>Card Number(Test Value)</span>
          <input id="ccNo" type="text" value="4222222222222220" autocomplete="off" required />
        </label>
      </div>
      <div>
        <label>
          <span>Expiration Date (MM/YYYY) (Test Value)</span>
          <input id="expMonth" type="text" size="2" required value="12" />
        </label>
        <span> / </span>
        <input id="expYear" type="text" size="2" required value="22" />
      </div>
      <div>
        <label>
          <span>CVV/CVC (Test Value)</span>
          <input id="cvv" type="text" value="123" size="4" autocomplete="off" required />
        </label>
      </div>
      <input type="submit" value="Submit Payment" />
    </form>
</body>
<script type="text/javascript" src="https://www.2checkout.com/checkout/api/2co.min.js"></script>
<script type="text/javascript">    
    var cForm = document.forms[0];
    var ccNo = document.getElementById('ccNo');
    var ccv = document.getElementById('cvv');
    var expMonth = document.getElementById('expMonth');
    var expYear = document.getElementById('expYear');

    
    
    var successCb = function(data){
        document.getElementById('token').value = data.response.token.token;
        cForm.submit();
    };

    
    var errorCb = function(data){
         if (data.errorCode === 200) {
            alert("200 Error");
            // This error code indicates that the ajax call failed. We recommend that you retry the token request.
        } else {
          alert(data.errorMsg);
        }
    };
    function tokenRequest(args){


        TCO.loadPubKey('{{ TwoCheckout::config()->env }}', function(){
            TCO.requestToken(successCb, errorCb, args);
        });
    }

    cForm.addEventListener("submit", function(e){
        e.preventDefault();
        
        var args = {
            sellerId: '{{TwoCheckout::config()->seller_id }}',
            publishableKey: '{{TwoCheckout::config()->publishable_key }}',
            ccNo: ccNo.value,
            cvv: ccv.value,
            expMonth: expMonth.value,
            expYear: expYear.value
        };
        
        tokenRequest(args);
        
    });
</script>