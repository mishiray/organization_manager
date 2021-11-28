$(document).ready(function() {
	$('[data-toggle="tooltip"]').tooltip();//Enable tooltip
	//password and confirm password checking
	$(document).on('change keyup','#dataConfPass, #dataNewPass, #cpassword, #password', function(){
		// var dataPass = document.getElementById("dataUsername");
		let dataPass=$('#dataNewPass, #password').val();
		let dataCPass=$('#dataConfPass, #cpassword').val();
		if(dataPass!='' || dataCPass!=''){
			$(".matches").html(( dataPass==dataCPass? '<i class="text-primary fa fa-check-circle-o"> password matched</i>':
				'<i class="text-danger fa fa-times-circle-o"> password does not match confirm password</i>'));
		}else{
			$(".matches").empty();
		}
	});

	// load and transform upload 1 image display style
	$(document).on('change','#dataID', function(){
		$("#imgSrc").css('display','block');
		$("#imgSrc").css('width','220px');
		$("#imgSrc").css('height','220px');
		// $("#imgSrc").css('padding','10');
		$("#imgSrc").css('margin-left','2pc');
		readURL(this,"#imgSrc");
	});

	//fetch countr's state to datalist
	$(document).on('change','#dataCountry', function(){
		let dataCountry=$(this).val();
		$.post("http://localhost/ftmpcis/site/inc/general.php",{dataCountry:dataCountry, evt:'fetchAddr'}, (res, err)=>{
			const obj=JSON.parse(res);
			// console.log(obj);
			if(obj && obj.length>0){
				$("#states").empty();
				for(objects of obj){
					$("#states").append(`<option value="${objects.name}">${objects.name}`);
				}
			}
		});
	});
	//fetch state's city to datalist
	$(document).on('change','#dataState', function(){
		let dataCountry=$('#dataCountry').val();
		let dataState=$(this).val();
		$.post("http://localhost/ftmpcis/site/inc/general.php",{dataState:dataState, dataCountry:dataCountry, evt:'fetchAddr'}, (res, err)=>{
			const obj=JSON.parse(res);
			// console.log(obj);
			if(obj && obj.length>0){
				$("#cities").empty();
				for(objects of obj){
					$("#cities").append(`<option value="${objects.name}">${objects.name}`);
				}
			}
		});
	});
});

//generate token
let getToken=function(length) {
    //http://us1.php.net/manual/en/function.openssl-random-pseudo-bytes.php#104322
    let token = "";
    let codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+=@#$)(%-_/\\";
    for (let i = 0; i < length; i++) {
        token += codeAlphabet[Math.floor((Math.random() * 73) + 1)];
    }
    return token;
}

// image upload function
let readURL= (input, tagPointer, buttonClass=null)=> {
	let targetValue=null;
    if (input.files && input.files[0]) {
        let reader = new FileReader();
        console.log(reader);
        reader.onload = (e) =>{
        	// console.log(e.target);
        	targetValue=e.target.result;
        	if(buttonClass!=null){
	            $(buttonClass).removeClass("hidden");
	        }
            $(tagPointer).attr('src', targetValue);
        };

        reader.readAsDataURL(input.files[0]);
    }
    return targetValue;
}

//export to ms-excel
let exportToExcel=(printData)=>{
	//Get the HTML of selector
	let selector=null;
	if( document.getElementById(printData) ){
		selector =document.getElementById(printData);
	}else if( document.getElementByClassName(printData) ){
		selector =document.getElementByClassName(printData);
	}else{
		selector =document.querySelector(printData);
	}
    let divElements = selector.innerHTML;

    //open in MS Excel
    let file = new Blob([divElements], {type:"application/vnd.ms-excel"});
	let url = URL.createObjectURL(file);
	let a = $("<a/>", {
	href: url,  download: "filename.xls"}).appendTo("body").get(0).click();
}

//print data
let printData= (printData, title=null)=> {
	//Get the HTML of selector
	let selector=null;
	if( document.getElementById(printData) ){
		selector =document.getElementById(printData);
	}else if( document.getElementByClassName(printData) ){
		selector =document.getElementByClassName(printData);
	}else{
		selector =document.querySelector(printData);
	}
	title=(title==null?'City Hoppers':title);
    let divElements = selector.innerHTML;
    //Get the HTML of whole page
    let oldPage = document.body.innerHTML;
    //Reset the page's HTML with div's HTML only
    document.body.innerHTML = `<html>
	      <head>
	      	<title>${title}</title>
	      	<link rel="stylesheet" href="http://scog.ajahcity.com.ng/lib/common/bootstrap/css/bootstrap.min.css"/>
			<link rel="stylesheet" href="http://scog.ajahcity.com.ng/lib/common/css/style.css" type="text/css"/>
			<link rel="stylesheet" href="http://scog.ajahcity.com.ng/lib/common/font-awesome/css/font-awesome.min.css">
			<style>
			</style>
	      </head>
      	  <body>
      	  	${divElements}
			<script src="http://scog.ajahcity.com.ng/lib/common/js/jquery-3.3.1.js"></script>
			<script src="http://scog.ajahcity.com.ng/lib/common/bootstrap/js/bootstrap.min.js"></script>
			<script src="http://scog.ajahcity.com.ng/lib/common/js/script.js"></script>
      	  </body>
      </html>`;
      // console.log(document.body.innerHTML);
    //Print Page
    window.print();
    //Restore orignal HTML
    document.body.innerHTML = oldPage;
}

function formatCurrency(number, currency={lang: 'en-US', code: 'USD'}){
	return number.toLocaleString(currency.lang, { style: 'currency', currency: currency.code });
}