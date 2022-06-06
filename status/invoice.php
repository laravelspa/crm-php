<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <style>
        html {
            background-color:  #5D92BA;
            margin:0;
            padding: 0;
        }
        body{
            background-color:  #5D92BA;
            box-sizing: border-box;
            letter-spacing: 0px;
         }

        .up {
            text-transform: uppercase;
        }
        .fb {
        font-weight: bold;
        }
        .fb-20 {
            font-size: 20px;
            font-weight: bold;
        }
         button.invoice_print, button.invoice_product {
            width: 10rem;
            position: fixed;
            bottom: 3%;
            right: 3%;
            margin-left: 22px; 
            padding: 15px 30px;
            color: #fff;
            font-size: 20px;
            display: flex;
            justify-content: space-around;
            background-color: #39357b;
            border: 1px solid #39357b;
            border-radius: 5px;
            cursor: pointer;
         }
         button.invoice_product {
            bottom: 27%;
        }   

         button.invoice_pdf {
            width: 10rem;
            position: fixed;
            bottom: 15%;
            right: 3%;
            margin-left: 22px; 
            padding: 15px 30px;
            color: #fff;
            font-size: 20px;
            display: flex;
            justify-content: space-around;
            background-color: #39357b;
            border: 1px solid #39357b;
            border-radius: 5px;
            cursor: pointer;
         }
        .container {
            background-color: #ffffff;
            color:#000000;
            /*margin: 10px auto;*/
            padding:22px;
            width: 60%;
        }
        .section_one {
            display: flex;
            height: 100px;
        }
        .section_two, .section_three{
            display: flex;
            height: 85px;	
        }
        .section_four, .section_five {
            display: flex;
            flex-direction: column;
            height: 200px;
            border: 2px solid #000;
        }

        .section_sex {
            display: flex;
            flex-direction: column;
            height: 240px;
            border: 2px solid #000;
        }

        .invoice_footer {
            display: flex;
            flex-direction: column;
            height: 60px;
            border: 2px solid #000;
        }

        .invoice_company {
            padding-top:10px;
            text-align: center;
            border: 2px solid #000;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .invoice_company span {
            flex: 1;
        }

        .invoice_barcode {
            padding-top:10px;
            text-align: center;
            border: 2px solid #000;
            flex: 3;
        }


        .invoice_destination {
            padding-top:10px;
            text-align: center;
            border: 2px solid #000;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .invoice_destination span {
            flex: 1;
        }


        .invoice_origin {
            text-align: center;
            padding-top: 10px;
            border: 2px solid #000;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .invoice_origin span {
            flex: 1;
        }

        .invoice_date {
            padding-top: 10px;
            padding-left: 10px;
            border: 2px solid #000;
            flex: 3;
            display: flex;
            flex-direction: column;
        }

        .invoice_date .date_flex, 
        .invoice_date .foreign_ref_flex, 
        .invoice_date .ref_flex, 
        .invoice_customer .customer_flex,
        .invoice_customer .order_flex, 
        .invoice_weight .weight_flex,
        .invoice_weight .chargeable_flex,
        .invoice_weight .codes_flex,
        .invoice_price .pieces_flex,
        .invoice_price .product_name_flex,
        .invoice_price .price_flex,
        .invoice_accounting .account_flex,
        .invoice_accounting .account_destination,
        .invoice_accounting .customers_services_flex,
        .eg_flex,
        .invoice_customer_details .customer_name_flex,
        .invoice_customer_details .customer_address_flex,
        .invoice_customer_details .customer_phone_flex,
        .invoice_footer .shipper_ref_flex,
        .invoice_footer .consignee_ref_flex {
            display: flex;
            flex: 1;
        }

        .invoice_date .date_flex span ,
        .invoice_date .foreign_ref_flex span,
        .invoice_date .ref_flex span,
        .invoice_customer .customer_flex span,
        .invoice_customer .order_flex span,
        .invoice_weight .weight_flex span, 
        .invoice_weight .chargeable_flex span,
        .invoice_weight .codes_flex span,
        .invoice_price .pieces_flex span,
        .invoice_price .product_name_flex span,
        .invoice_price .price_flex span,
        .invoice_accounting .account_flex span,
        .invoice_footer .shipper_ref_flex span,
        .invoice_footer .consignee_ref_flex span {
            flex: 4;
        }

        .invoice_accounting .customers_services_flex span,
        .invoice_customer_details .customer_name_flex span,
        .invoice_customer_details .customer_address_flex span,
        .invoice_customer_details .customer_phone_flex span {
            flex: 3
        }

        .eg_flex {
            justify-content: flex-end;
            padding-right: 30px;
            font-size: 27px;
            font-weight: bold;
        }
        .invoice_accounting .customers_services_flex span:first-child {
            flex: 1.5;
        }
        .invoice_date .date_flex span:first-child, 
        .invoice_date .foreign_ref_flex span:first-child,
        .invoice_date .ref_flex span:first-child,
        .invoice_customer .customer_flex span:first-child,
        .invoice_customer .order_flex span:first-child,
        .invoice_weight .weight_flex span:first-child,
        .invoice_weight .chargeable_flex span:first-child,
        .invoice_weight .codes_flex span:first-child,
        .invoice_price .pieces_flex span:first-child,
        .invoice_price .product_name_flex span:first-child,
        .invoice_price .price_flex span:first-child,
        .invoice_accounting .account_flex span:first-child,
        .invoice_customer_details .customer_name_flex span:first-child,
        .invoice_customer_details .customer_address_flex span:first-child,
        .invoice_customer_details .customer_phone_flex span:first-child,
        .invoice_footer .shipper_ref_flex span:first-child,
        .invoice_footer .consignee_ref_flex span:first-child {
            flex: 1;
        }

        .invoice_dom, .invoice_onp {
            align-items: center;
            border: 2px solid #000;
            flex: 1;
            display: flex;
            justify-content: center;
        }

        .invoice_dom span:first-child, .invoice_onp span:first-child {
            font-size: 20px;
            font-weight: bold;
        }

        .invoice_onp {
            border: 2px solid #000;
            flex: 1;
        }

        .invoice_customer {
            padding-top: 10px;
            padding-left: 10px;
            border: 2px solid #000;
            flex: 4;
            display: flex;
            flex-direction: column;
        }


        .invoice_weight {
            padding-top: 10px;
            padding-left: 10px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .section_four h4 {
            margin-bottom: 5px;
            margin-top: 5px;
            background-color: #000;
            color: #ccc;
            padding:10px;
        }

        .invoice_price {
            padding-top: 10px;
            padding-left: 10px;
            flex: 1;
        }

        .invoice_accounting, .invoice_customer_details {
            height: 200px;
            padding-top: 10px;
            padding-left: 50px;
            display: flex;
            flex-direction: column;
            font-size: 20px;
        }

        .invoice_footer {
            padding-top: 10px;
            padding-left: 50px;
            display: flex;
            flex-direction: column;
        }

        .editContainer {
            position: absolute;
            background-color: #fff;
            padding: 35px;
            border-radius: 5px;
            box-shadow: 1px 1px 10px #52525269;
            border: 1px solid #eee;
            left: 36%;
            z-index: 9999999;
            display: none;
        }

        .inputs-container {
            margin-bottom: 20px;
        }
        .input {
            display: block;
            width: 349px;
            height: 30px;
            margin-top: 5px;
            padding-left: 9px;
            border-radius: 3px;
            border: 1px solid #a5a5a5;
        }
        .input-button {
            width: 8rem;
            padding: 10px 10px;
            color: #fff;
            font-size: 16px;
            background-color: #39357b;
            border: 1px solid #39357b;
            border-radius: 5px;
            text-transform: capitalize;
            cursor: pointer;
        }
        p.DateOfReceipt {
            text-align: end;
            padding-right: 30px;
            font-weight: bold;
            font-size: 20px;
        }
        .formProduct {
            border-bottom: 1px solid #bfbfbf;
            margin-bottom: 20px;
        }

    </style>
</head>
<body>

<!-- Start Edit Form -->

<div class='editContainer' id='editInvoice'>
    <form class='editForm'>
        <input type='hidden' name='orderId' id='orderId' value='<?php echo $_GET['orderId']; ?>'>
        <div class='inputs-container'>
            <label for="InvoiceDate">Invoice Date</label>
            <input class='input' type='date' name='InvoiceDate' id='InvoiceDate' placeholder='Invoice Date' value='<?php if (isset($_GET['date'])) {echo $_GET['date'];} else {echo date("Y-m-d");} ?>'>
        </div>
        <div class='inputs-container'>
            <label for="CustomerName">Customer Name</label>
            <input class='input' type='text' name='CustomerName' id='CustomerName' placeholder='Customer Name' value='<?php echo $_GET['customer']; ?>'>
        </div>
        <div class='inputs-container'>
            <label for="Pieces">Pieces</label>
            <input class='input' type='number' name='Pieces' id='Pieces' placeholder='Pieces' value='<?php echo $_GET['pieces']; ?>'>
        </div>
        <div class='inputs-container'>
            <label for="ProductName">Product Name</label>
            <input class='input' type='text' name='ProductName' id='ProductName' placeholder='Product Name' value='<?php echo $_GET['prod']; ?>'>
        </div>
        <div class='inputs-container'>
            <label for="Price">Price</label>
            <input class='input' type='text' name='Price' id='Price' placeholder='Price' value='<?php echo $_GET['price']; ?>'>
        </div>
        <div class='inputs-container'>
            <label for="CustomersServices">Customers Services</label>
            <input class='input' type='text' name='CustomersServices' id='CustomersServices' placeholder='Customers Services' value='<?php if (isset($_GET['customerServicesNumbers'])) {echo $_GET['customerServicesNumbers'];} elseif ($_GET['customerServicesNumbers'] == '') {echo "01101034235  &nbsp;&nbsp;&nbsp;01150124316  &nbsp;&nbsp;&nbsp;01101034237";} else {echo "01101034235  &nbsp;&nbsp;&nbsp;01150124316  &nbsp;&nbsp;&nbsp;01101034237";} ?>'>
        </div>
        <div class='inputs-container'>
            <label for="CustomerAddress">Customer Address</label>
            <input class='input' type='text' name='CustomerAddress' id='CustomerAddress' placeholder='Customer Address' value='<?php echo $_GET['address'] ?>'>
        </div>
        <div class='inputs-container'>
            <label for="CustomerPhone">Customer Phone</label>
            <input class='input' type='text' name='CustomerPhone' id='CustomerPhone' placeholder='Customer Phone' value='<?php echo $_GET['phone'] ?>'>
        </div>
        <div class='inputs-container'>
            <label for="DateOfReceipt">Date Of Receipt</label>
            <input class='input' type='text' name='DateOfReceipt' id='DateOfReceipt' placeholder='Date Of Receipt' value='<?php if (isset($_GET['DateOfReceipt'])) {echo $_GET['DateOfReceipt'];} ?>'>
        </div>
        <div class="inputs-container">
            <button class='input-button' type='submit'>apply Edits</button>
            <button class='input-button cancelAction' type='button'>Cancel</button>
        </div>
    </form>
</div>

<div class='editContainer' id='addProduct'>
    <form class='addProductForm' method='get'>
        <button class='input-button addProductInput' type='button' style='width: 100%;margin-bottom: 20px;'>Add Products</button>
        <div class='productContainerForm'>
            <?php if (!isset($_GET['ProductName']) || !isset($_GET['ProductPieces'])) { ?> 
                <div class='formProduct'>
                    <div class='inputs-container'>
                        <label for="ProductName">Product Name</label>
                        <input class='input' type='text' name='ProductName[]' id='ProductName' placeholder='Product Name' value='<?php echo $_GET['prod']; ?>'>
                    </div>
                    <div class='inputs-container'>
                        <label for="ProductPieces">Product Pieces</label>
                        <input class='input' type='text' name='ProductPieces[]' id='ProductPieces' placeholder='Product Pieces' value='<?php echo $_GET['pieces']; ?>'>
                    </div>
                    <button type='button' class='input-button removeProductContainer' style='margin-top: 20px;margin-bottom: 10px; width: 100%;'>Remove Product</button>
                </div>
            <?php } else { ?> 
                <?php $pieces = $_GET['ProductPieces']; foreach($_GET['ProductName'] as $index => $prods) { ?> 
                        <div class='formProduct'>
                            <div class='inputs-container'>
                                <label for="ProductName">Product Name</label>
                                <input class='input' type='text' name='ProductName[]' id='ProductName' placeholder='Product Name' value='<?php echo $prods; ?>'>
                            </div>
                            <div class='inputs-container'>
                                <label for="ProductPieces">Product Pieces</label>
                                <input class='input' type='text' name='ProductPieces[]' id='ProductPieces' placeholder='Product Pieces' value='<?php echo $pieces[$index]; ?>'>
                            </div>
                            <button type='button' class='input-button removeProductContainer' style='margin-top: 20px;margin-bottom: 10px; width: 100%;'>Remove Product</button>
                        </div>
                <?php } ?>
            <?php } ?>
        </div>
        <div class="inputs-container">
            <button class='input-button' type='submit'>Add Products</button>
            <button class='input-button cancelActionOnAddProduct' type='button'>Cancel</button>
        </div>
    </form>
</div>

<!-- End Edit Form -->
    
    	<button id="invoice_print" class="invoice_print">Image</button>
		<button id="invoice_pdf" class="invoice_pdf editButton">Edit</button>
		<button id="invoice_product" class="invoice_product addProduct">Add Products</button>
<div class="container" id="container">
	<div class="section_one">
		<div class="invoice_company">
		<span class="fb">Company:</span>
		<span class="fb-20 up" id="company_value"><strong>FLY POST</strong></span>
		</div>
		<div class="invoice_barcode">
			<svg id="barcode_value" width="121px" height="82px" x="0px" y="0px" viewBox="0 0 121 82" xmlns="http://www.w3.org/2000/svg" version="1.1" style="transform: translate(0px, 0px);"><rect x="0" y="0" width="121" height="82" style="fill:#ffffff;"></rect><g transform="translate(10, 10)" style="fill:#000000;"><rect x="0" y="0" width="2" height="40"></rect><rect x="3" y="0" width="1" height="40"></rect><rect x="6" y="0" width="3" height="40"></rect><rect x="11" y="0" width="1" height="40"></rect><rect x="13" y="0" width="3" height="40"></rect><rect x="17" y="0" width="2" height="40"></rect><rect x="22" y="0" width="1" height="40"></rect><rect x="24" y="0" width="1" height="40"></rect><rect x="29" y="0" width="2" height="40"></rect><rect x="33" y="0" width="3" height="40"></rect><rect x="39" y="0" width="1" height="40"></rect><rect x="41" y="0" width="2" height="40"></rect><rect x="44" y="0" width="1" height="40"></rect><rect x="46" y="0" width="3" height="40"></rect><rect x="51" y="0" width="2" height="40"></rect><rect x="55" y="0" width="3" height="40"></rect><rect x="59" y="0" width="1" height="40"></rect><rect x="61" y="0" width="4" height="40"></rect><rect x="66" y="0" width="2" height="40"></rect><rect x="70" y="0" width="3" height="40"></rect><rect x="74" y="0" width="1" height="40"></rect><rect x="77" y="0" width="2" height="40"></rect><rect x="81" y="0" width="1" height="40"></rect><rect x="85" y="0" width="1" height="40"></rect><rect x="88" y="0" width="2" height="40"></rect><rect x="93" y="0" width="3" height="40"></rect><rect x="97" y="0" width="1" height="40"></rect><rect x="99" y="0" width="2" height="40"></rect><text style="font: 20px monospace" text-anchor="middle" x="50.5" y="62"><?php echo rand(0, 10000000000); ?></text></g></svg>
		</div>
	</div>

	<div class="section_two">
		<div class="invoice_destination">
			<span class="fb">Destination:</span>
			<span class="fb-20 up" id="destination_value"></span>
		</div>
		<div class="invoice_origin">
			<span class="fb">Origin:</span>
			<span class="fb-20 up" id="origin_value"></span>
		</div>
		<div class="invoice_date">
			<div class="date_flex">
				<span class="fb" style='margin-right: -77px;'>Date:</span>
				<span class="fb-20 up"><strong><?php if (isset($_GET['date'])) {echo $_GET['date'];} else {echo date("Y-m-d");} ?></strong></span>
			</div>
			<div class="foreign_ref_flex">
				<span class="fb" style='margin-right: -19px;'>Foreign Ref:</span>
				<span class="fb-20 up">Null</span>
			</div>
			<div class="ref_flex">
				<span class="fb" style='margin-right: -90px;'>Ref:</span>
				<span class="fb-20 up">Null</span>
			</div>
		</div>
	</div>

	<div class="section_three">
		<div class="invoice_dom">
			<span>DOM</span>
		</div>
		<div class="invoice_onp">
			<span>ONP</span>
		</div>
		<div class="invoice_customer">
			<div class="customer_flex">
				<span class="fb" style='margin-right: -46px;'>Customer:</span>
				<span class="fb-20 up"><strong><?php echo $_GET['customer']; ?></strong></span>
			</div>
			<div class="order_flex">
				<span class="fb" style='margin-right: -50px;'>Order ID:</span>
				<span class="fb-20 up"><strong><?php echo $_GET['orderId']; ?></strong></span>
			</div>
		</div>
	</div>

	<div class="section_four">
		<div class="invoice_weight">
			<div class="weight_flex">
				<span class="fb" style='margin-right: -140px;'>Weight:</span>
				<span class="fb-20"><strong>.5 k.g</strong></span>
			</div>
			<div class="chargeable_flex">
				<span class="fb" style='margin-right: -113px;'>Chargeable:</span>
				<span class="fb-20 up">Null</span>
			</div>
			<div class="codes_flex">
				<span class="fb-20 up" style='margin-right: -83px;'>Services:</span>
				<span class="fb-20 up">CODS</span>
			</div>			
		</div>
		<h4>Services Code</h4>
		<div class="invoice_price">
			<div class="pieces_flex">
				<span class="fb" style='margin-right: -154px;'>Pieces:</span>
				<span class="fb-20 up">
                    <strong><?php if (!isset($_GET['ProductName']) || !isset($_GET['ProductPieces'])) {echo $_GET['pieces'];} else {foreach($_GET['ProductPieces'] AS $pieces) {echo $pieces ." &nbsp;&nbsp;&nbsp;";} } ?></strong>
                </span>
			</div>
			<div class="product_name_flex">
				<span class="fb" style='margin-right: -87px;'>Product Name:</span>
				<span class="fb-20 up">
                <strong><?php if (!isset($_GET['ProductName']) || !isset($_GET['ProductPieces'])) {echo $_GET['prod'];} else {foreach($_GET['ProductName'] AS $prods) {echo $prods . "&nbsp;&nbsp;&nbsp;";} } ?></strong>
                </span>
			</div>
			<div class="price_flex">
				<span class="fb" style='margin-right: -169px;'>Price:</span>
				<span class="fb-20 up"><strong><?php echo $_GET['price']; ?></strong></span>
			</div>			
		</div>		
	</div>

	<div class="section_five">
		<div class="invoice_accounting">
			<div class="account_flex">
				<span class="fb" style='margin-right: -111px;'>Account:</span>
				<span class="fb-20 up"><strong><?php echo $_GET['price']; ?></strong> L.E</span>
			</div>
			<div class="account_destination">
				<span class="fb-20 up"></span>
			</div>
			<div class="customers_services_flex">
				<span class="fb" style='margin-right: -148px;'>Customers Services:</span>
				<span class="fb-20 up"><strong><?php if (isset($_GET['customerServicesNumbers'])) {echo $_GET['customerServicesNumbers'];} elseif ($_GET['customerServicesNumbers'] == '') {echo "01101034235 &nbsp;&nbsp;&nbsp;01150124316 &nbsp;&nbsp;&nbsp;01101034237";} else {echo "01101034235 &nbsp;&nbsp;&nbsp;01150124316 &nbsp;&nbsp;&nbsp;01101034237";} ?></strong></span>
			</div>
			<div class="eg_flex">
				<span class="fb">EG</span>
			</div>
		</div>
	</div>

	<div class="section_sex">
		<div class="invoice_customer_details">
			<div class="customer_name_flex">
				<span class="fb" style='margin-right: -78px;'>Customer Name:</span>
				<span class="fb-20 up" style='margin-right: -53px;'><strong><?php echo $_GET['customer']; ?></strong></span>
			</div>
			<div class="customer_address_flex">
				<span class="fb" style='margin-right: -53px;'>Customer Address:</span>
				<span class="fb-20 up"><strong><?php echo $_GET['address']; ?></strong></span>
			</div>
			<div class="customer_phone_flex">
				<span class="fb" style='margin-right: -76px;'>Customer Phone:</span>
				<span class="fb-20 up"><strong><?php echo $_GET['phone']; ?></strong></span>
			</div>
			<div class="eg_flex">
				<span class="fb">EG</span>
			</div>	
            <p class='DateOfReceipt'><strong><?php if (isset($_GET['DateOfReceipt'])) {echo $_GET['DateOfReceipt'];} ?></strong></p>

		</div>
	</div>
	<div class="section_seven">
		<div class="invoice_footer">
			<div class="shipper_ref_flex">
				<span class="fb" style='margin-right: -96px;'>Shipper Ref:</span>
				<span class="fb">Null</span>
			</div>
			<div class="consignee_ref_flex">
				<span class="fb" style='margin-right: -78px;'>Consignee Ref:</span>
				<span class="fb">Null</span>
			</div>
		</div>
	</div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
/*! dom-to-image 10-06-2017 */
!function(a){"use strict";function b(a,b){function c(a){return b.bgcolor&&(a.style.backgroundColor=b.bgcolor),b.width&&(a.style.width=b.width+"px"),b.height&&(a.style.height=b.height+"px"),b.style&&Object.keys(b.style).forEach(function(c){a.style[c]=b.style[c]}),a}return b=b||{},g(b),Promise.resolve(a).then(function(a){return i(a,b.filter,!0)}).then(j).then(k).then(c).then(function(c){return l(c,b.width||q.width(a),b.height||q.height(a))})}function c(a,b){return h(a,b||{}).then(function(b){return b.getContext("2d").getImageData(0,0,q.width(a),q.height(a)).data})}function d(a,b){return h(a,b||{}).then(function(a){return a.toDataURL()})}function e(a,b){return b=b||{},h(a,b).then(function(a){return a.toDataURL("image/jpeg",b.quality||1)})}function f(a,b){return h(a,b||{}).then(q.canvasToBlob)}function g(a){"undefined"==typeof a.imagePlaceholder?v.impl.options.imagePlaceholder=u.imagePlaceholder:v.impl.options.imagePlaceholder=a.imagePlaceholder,"undefined"==typeof a.cacheBust?v.impl.options.cacheBust=u.cacheBust:v.impl.options.cacheBust=a.cacheBust}function h(a,c){function d(a){var b=document.createElement("canvas");if(b.width=c.width||q.width(a),b.height=c.height||q.height(a),c.bgcolor){var d=b.getContext("2d");d.fillStyle=c.bgcolor,d.fillRect(0,0,b.width,b.height)}return b}return b(a,c).then(q.makeImage).then(q.delay(100)).then(function(b){var c=d(a);return c.getContext("2d").drawImage(b,0,0),c})}function i(a,b,c){function d(a){return a instanceof HTMLCanvasElement?q.makeImage(a.toDataURL()):a.cloneNode(!1)}function e(a,b,c){function d(a,b,c){var d=Promise.resolve();return b.forEach(function(b){d=d.then(function(){return i(b,c)}).then(function(b){b&&a.appendChild(b)})}),d}var e=a.childNodes;return 0===e.length?Promise.resolve(b):d(b,q.asArray(e),c).then(function(){return b})}function f(a,b){function c(){function c(a,b){function c(a,b){q.asArray(a).forEach(function(c){b.setProperty(c,a.getPropertyValue(c),a.getPropertyPriority(c))})}a.cssText?b.cssText=a.cssText:c(a,b)}c(window.getComputedStyle(a),b.style)}function d(){function c(c){function d(a,b,c){function d(a){var b=a.getPropertyValue("content");return a.cssText+" content: "+b+";"}function e(a){function b(b){return b+": "+a.getPropertyValue(b)+(a.getPropertyPriority(b)?" !important":"")}return q.asArray(a).map(b).join("; ")+";"}var f="."+a+":"+b,g=c.cssText?d(c):e(c);return document.createTextNode(f+"{"+g+"}")}var e=window.getComputedStyle(a,c),f=e.getPropertyValue("content");if(""!==f&&"none"!==f){var g=q.uid();b.className=b.className+" "+g;var h=document.createElement("style");h.appendChild(d(g,c,e)),b.appendChild(h)}}[":before",":after"].forEach(function(a){c(a)})}function e(){a instanceof HTMLTextAreaElement&&(b.innerHTML=a.value),a instanceof HTMLInputElement&&b.setAttribute("value",a.value)}function f(){b instanceof SVGElement&&(b.setAttribute("xmlns","http://www.w3.org/2000/svg"),b instanceof SVGRectElement&&["width","height"].forEach(function(a){var c=b.getAttribute(a);c&&b.style.setProperty(a,c)}))}return b instanceof Element?Promise.resolve().then(c).then(d).then(e).then(f).then(function(){return b}):b}return c||!b||b(a)?Promise.resolve(a).then(d).then(function(c){return e(a,c,b)}).then(function(b){return f(a,b)}):Promise.resolve()}function j(a){return s.resolveAll().then(function(b){var c=document.createElement("style");return a.appendChild(c),c.appendChild(document.createTextNode(b)),a})}function k(a){return t.inlineAll(a).then(function(){return a})}function l(a,b,c){return Promise.resolve(a).then(function(a){return a.setAttribute("xmlns","http://www.w3.org/1999/xhtml"),(new XMLSerializer).serializeToString(a)}).then(q.escapeXhtml).then(function(a){return'<foreignObject x="0" y="0" width="100%" height="100%">'+a+"</foreignObject>"}).then(function(a){return'<svg xmlns="http://www.w3.org/2000/svg" width="'+b+'" height="'+c+'">'+a+"</svg>"}).then(function(a){return"data:image/svg+xml;charset=utf-8,"+a})}function m(){function a(){var a="application/font-woff",b="image/jpeg";return{woff:a,woff2:a,ttf:"application/font-truetype",eot:"application/vnd.ms-fontobject",png:"image/png",jpg:b,jpeg:b,gif:"image/gif",tiff:"image/tiff",svg:"image/svg+xml"}}function b(a){var b=/\.([^\.\/]*?)$/g.exec(a);return b?b[1]:""}function c(c){var d=b(c).toLowerCase();return a()[d]||""}function d(a){return a.search(/^(data:)/)!==-1}function e(a){return new Promise(function(b){for(var c=window.atob(a.toDataURL().split(",")[1]),d=c.length,e=new Uint8Array(d),f=0;f<d;f++)e[f]=c.charCodeAt(f);b(new Blob([e],{type:"image/png"}))})}function f(a){return a.toBlob?new Promise(function(b){a.toBlob(b)}):e(a)}function g(a,b){var c=document.implementation.createHTMLDocument(),d=c.createElement("base");c.head.appendChild(d);var e=c.createElement("a");return c.body.appendChild(e),d.href=b,e.href=a,e.href}function h(){var a=0;return function(){function b(){return("0000"+(Math.random()*Math.pow(36,4)<<0).toString(36)).slice(-4)}return"u"+b()+a++}}function i(a){return new Promise(function(b,c){var d=new Image;d.onload=function(){b(d)},d.onerror=c,d.src=a})}function j(a){var b=3e4;return v.impl.options.cacheBust&&(a+=(/\?/.test(a)?"&":"?")+(new Date).getTime()),new Promise(function(c){function d(){if(4===g.readyState){if(200!==g.status)return void(h?c(h):f("cannot fetch resource: "+a+", status: "+g.status));var b=new FileReader;b.onloadend=function(){var a=b.result.split(/,/)[1];c(a)},b.readAsDataURL(g.response)}}function e(){h?c(h):f("timeout of "+b+"ms occured while fetching resource: "+a)}function f(a){console.error(a),c("")}var g=new XMLHttpRequest;g.onreadystatechange=d,g.ontimeout=e,g.responseType="blob",g.timeout=b,g.open("GET",a,!0),g.send();var h;if(v.impl.options.imagePlaceholder){var i=v.impl.options.imagePlaceholder.split(/,/);i&&i[1]&&(h=i[1])}})}function k(a,b){return"data:"+b+";base64,"+a}function l(a){return a.replace(/([.*+?^${}()|\[\]\/\\])/g,"\\$1")}function m(a){return function(b){return new Promise(function(c){setTimeout(function(){c(b)},a)})}}function n(a){for(var b=[],c=a.length,d=0;d<c;d++)b.push(a[d]);return b}function o(a){return a.replace(/#/g,"%23").replace(/\n/g,"%0A")}function p(a){var b=r(a,"border-left-width"),c=r(a,"border-right-width");return a.scrollWidth+b+c}function q(a){var b=r(a,"border-top-width"),c=r(a,"border-bottom-width");return a.scrollHeight+b+c}function r(a,b){var c=window.getComputedStyle(a).getPropertyValue(b);return parseFloat(c.replace("px",""))}return{escape:l,parseExtension:b,mimeType:c,dataAsUrl:k,isDataUrl:d,canvasToBlob:f,resolveUrl:g,getAndEncode:j,uid:h(),delay:m,asArray:n,escapeXhtml:o,makeImage:i,width:p,height:q}}function n(){function a(a){return a.search(e)!==-1}function b(a){for(var b,c=[];null!==(b=e.exec(a));)c.push(b[1]);return c.filter(function(a){return!q.isDataUrl(a)})}function c(a,b,c,d){function e(a){return new RegExp("(url\\(['\"]?)("+q.escape(a)+")(['\"]?\\))","g")}return Promise.resolve(b).then(function(a){return c?q.resolveUrl(a,c):a}).then(d||q.getAndEncode).then(function(a){return q.dataAsUrl(a,q.mimeType(b))}).then(function(c){return a.replace(e(b),"$1"+c+"$3")})}function d(d,e,f){function g(){return!a(d)}return g()?Promise.resolve(d):Promise.resolve(d).then(b).then(function(a){var b=Promise.resolve(d);return a.forEach(function(a){b=b.then(function(b){return c(b,a,e,f)})}),b})}var e=/url\(['"]?([^'"]+?)['"]?\)/g;return{inlineAll:d,shouldProcess:a,impl:{readUrls:b,inline:c}}}function o(){function a(){return b(document).then(function(a){return Promise.all(a.map(function(a){return a.resolve()}))}).then(function(a){return a.join("\n")})}function b(){function a(a){return a.filter(function(a){return a.type===CSSRule.FONT_FACE_RULE}).filter(function(a){return r.shouldProcess(a.style.getPropertyValue("src"))})}function b(a){var b=[];return a.forEach(function(a){try{q.asArray(a.cssRules||[]).forEach(b.push.bind(b))}catch(c){console.log("Error while reading CSS rules from "+a.href,c.toString())}}),b}function c(a){return{resolve:function(){var b=(a.parentStyleSheet||{}).href;return r.inlineAll(a.cssText,b)},src:function(){return a.style.getPropertyValue("src")}}}return Promise.resolve(q.asArray(document.styleSheets)).then(b).then(a).then(function(a){return a.map(c)})}return{resolveAll:a,impl:{readAll:b}}}function p(){function a(a){function b(b){return q.isDataUrl(a.src)?Promise.resolve():Promise.resolve(a.src).then(b||q.getAndEncode).then(function(b){return q.dataAsUrl(b,q.mimeType(a.src))}).then(function(b){return new Promise(function(c,d){a.onload=c,a.onerror=d,a.src=b})})}return{inline:b}}function b(c){function d(a){var b=a.style.getPropertyValue("background");return b?r.inlineAll(b).then(function(b){a.style.setProperty("background",b,a.style.getPropertyPriority("background"))}).then(function(){return a}):Promise.resolve(a)}return c instanceof Element?d(c).then(function(){return c instanceof HTMLImageElement?a(c).inline():Promise.all(q.asArray(c.childNodes).map(function(a){return b(a)}))}):Promise.resolve(c)}return{inlineAll:b,impl:{newImage:a}}}var q=m(),r=n(),s=o(),t=p(),u={imagePlaceholder:void 0,cacheBust:!1},v={toSvg:b,toPng:d,toJpeg:e,toBlob:f,toPixelData:c,impl:{fontFaces:s,images:t,util:q,inliner:r,options:{}}};"undefined"!=typeof module?module.exports=v:a.domtoimage=v}(this);
</script>
<script>
document.getElementById('invoice_print').addEventListener('click', function() {
	var pe = document.getElementById('container');
    var imageName = '<?php echo $_GET['customer']; ?>';
	domtoimage.toJpeg(pe, { quality: 0.95, width:"950" })
	.then(function (dataUrl) {
	    saveAs(dataUrl, imageName+'.jpg');
	});
	function saveAs(url, filename) {
		var link = document.createElement('a');
		if (typeof link.download === 'string') {
			link.href = url;
			link.download = filename;
			document.body.appendChild(link);
			link.click();	
			document.body.removeChild(link);
		}
	}
	
});
</script>

<script>
    $(document).on("click", ".editButton", function() {
        $("#editInvoice").fadeIn();
    });

    $(document).on("click", ".cancelAction", function() {
        $("#editInvoice").fadeOut();
    });
    $(document).on("submit", ".editForm", function(event) {

        event.preventDefault();

        var orderId             = $("#orderId").val();
        var CustomerName        = $("#CustomerName").val();
        var Pieces              = $("#Pieces").val();
        var ProductName         = $("#ProductName").val();
        var Price               = $("#Price").val();
        var CustomersServices   = $("#CustomersServices").val();
        var CustomerAddress     = $("#CustomerAddress").val();
        var CustomerPhone       = $("#CustomerPhone").val();
        var date                = $("#InvoiceDate").val();
        var DateOfReceipt       = $("#DateOfReceipt").val();

        window.location.replace("http://crm.healthy-cure.xyz/status/invoice.php?orderId="+orderId+"&customer="+CustomerName+"&pieces="+Pieces+"&prod="+ProductName+"&price="+Price+"&address="+CustomerAddress+"&phone="+CustomerPhone+"&customerServicesNumbers="+CustomersServices+"&date="+date+"&DateOfReceipt="+DateOfReceipt+"");


    });


</script>

<script>
    $(document).on("click", "#invoice_product", function() {

        $("#addProduct").fadeIn();

    });
    $(document).on("click", ".cancelActionOnAddProduct", function() {

        $("#addProduct").fadeOut();


    });
</script>

<script>

    $(document).on("click", ".addProductInput", function() {

        $(".productContainerForm").append("<div class='formProduct'><div class='inputs-container'><label for='ProductName'>Product Name</label><input class='input' type='text' name='ProductName[]' id='ProductName' placeholder='Product Name'></div><div class='inputs-container'><label for='ProductPieces'>Product Pieces</label><input class='input' type='text' name='ProductPieces[]' id='ProductPieces' placeholder='Product Pieces'></div><button type='button' class='input-button removeProductContainer' style='margin-top: 20px;margin-bottom: 10px; width: 100%;'>Remove Product</button></div>");

    });

    $(document).on("click", ".removeProductContainer", function() {

        $(this).parent().remove();

    });
</script>

<script>
    $(document).on("submit", ".addProductForm", function(event) {

        event.preventDefault();

        

        var orderId             = $(".editForm #orderId").val();
        var CustomerName        = $(".editForm #CustomerName").val();
        //var Pieces              = $(".editForm #Pieces").val();
        //var ProductName         = $(".editForm #ProductName").val();
        var Price               = $(".editForm #Price").val();
        var CustomersServices   = $(".editForm #CustomersServices").val();
        var CustomerAddress     = $(".editForm #CustomerAddress").val();
        var CustomerPhone       = $(".editForm #CustomerPhone").val();
        var date                = $(".editForm #InvoiceDate").val();
        var DateOfReceipt       = $(".editForm #DateOfReceipt").val();

        window.location.replace("http://crm.healthy-cure.xyz/status/invoice.php?orderId="+orderId+"&customer="+CustomerName+"&price="+Price+"&address="+CustomerAddress+"&phone="+CustomerPhone+"&customerServicesNumbers="+CustomersServices+"&date="+date+"&DateOfReceipt="+DateOfReceipt+"&"+$(this).serialize());



    });
</script>

</body>
</html>