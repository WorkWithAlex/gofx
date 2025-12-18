<!doctype html>
<html>
<head><meta charset="utf-8"></head>
<body style="font-family:Arial,Helvetica,sans-serif;color:#111;">
  <div>
    <h2>Thank you for your purchase</h2>
    <p>Hi {{ $receipt['name'] }},</p>
    <p>Thanks for purchasing <strong>{{ $receipt['course'] }}</strong>. Your payment was successful.</p>
    <p>Transaction ID: <strong>{{ $receipt['txnid'] }}</strong></p>
    <p>Amount: <strong>{{ $receipt['amount'] }}</strong></p>
    <p>You can download your receipt using the attached PDF.</p>
    <p>— GOFX Team</p>
  </div>
</body>
</html>
