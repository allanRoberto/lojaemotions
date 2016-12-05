<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
 <style>
body {
    font-family: 'Lato', sans-serif;
    font-size: 12px;
}

h1 {
  font-size: 18px;
  margin-bottom: 20px;
}

#header p {
  text-align: center;
  font-size: 10px;
}

.table-details-shipping tr  {
  width: 100% !important;
  float : left;
}

.table-details-shipping tr td {
  margin-right : 0px !important;
  height: 30px;
}


.details-order-total {
  width: 100%;
}
.details-order-total td {
  height : 30px;
}

.border-gray td {
  border-bottom: 2px solid #ccc;
}


.table-details-shipping {
  margin-bottom: 20px;
  width: 100%;
}

.shipping-delivery p {
  margin-bottom: 0px !important;
  font-size: 12px;
}

.comment-address {
  margin-top : 30px;
}

.logo {
  position:absolute;
  top: 60px !important;
  right: 25px !important;
  float: left;
  text-align: center !important;
}


 </style>
</head>
  <body> 
  <div id="header"> 
    <span class="date-header">[[PDFINVOICEDATE]]</span>
    <p>
      Nota de entrega
    </p>
  </div>
   <div class="logo" style="display:inline-block; margin-left: 500px !important;  text-align: center !important;">
        [[PDFLOGO]]
        <p>Obrigado por escolher os nossos produtos.</p>
    </div>
  <div id="shipping-delivery">
    <h1 class="title-shipping-delivery">Destinatário</h1>
    <p class="shipping-address-info">
      [[PDFBILLINGNAMEFIRSTADDRESS]] [[PDFBILLINGNAMELASTADDRESS]]<br/>
      [[PDFSHIPPINGADDRESS]]
    </p>
    <span class="shipping-tel">
      Telefone: [[PDFBILLINGTEL]]<br>
      Celular:
    </span>
    <div class="comment-address">
    <strong><span class="title-comment">Comentários adicionais</span></strong>
    <p>[[PDFORDERNOTES]]</p>
      
    </div>
  </div>
  <div class="notes-shipping">
    <h1>Notas de entrega</h1>
    <span style="height: 3px; width: 100%; background: #000; display: inline-block;"></span>
    <table class="table-details-shipping">
      <tr class="border-gray">
        <td>Número do pedido</td>
        <td>[[PDFORDERENUM]]</td>
      </tr>
      <tr class="border-gray">
        <td>Data do pedido</td>
        <td>[[PDFORDERDATE]]</td>
      </tr>
      <tr class="border-gray">
        <td>Forma de pagamento</td>
        <td>[[PDFINVOICEPAYMENTMETHOD]]</td>
      </tr>
      <tr class="border-gray">
        <td>E-mail</td>
        <td>[[PDFBILLINGEMAIL]]</td>
      </tr>
      <tr class="border-gray">
        <td>Telefone</td>
        <td>[[PDFBILLINGTEL]]</td>
      </tr>
    </table>
    <table class="details-order">
      [[ORDERINFO]]
      [[ORDERINFOHEADER]]
    </table>
    <table class="details-order-total">
      [[PDFORDERTOTALS]]
    </table>
    <span style="height: 3px; width: 100%; background: #000; display: inline-block;"></span>
  

  </div>

</body> 
</html> 
