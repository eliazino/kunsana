// JavaScript Document
var isLanding = false;
function cart(a,b,c){
	if(arguments.length === 3){
		a.innerHTML = "<i class='fa fa-spinner fa-spin'></i> working";
		a.disabled = true;
		params = {art_id:c, user_id:b};
		$.post('dep/server/miscpages.php',params,function(data){
			r = data.split('#mkShfT');
			if(r[0] == 'true'){
				a.style.background = 'green';
				a.innerHTML = '<i class="fa fa-check"></i> Added';
				a.style.color = "#fff";
				a.style.border= "none";
			}
			else{
				a.disabled = false;
				a.innerHTML = '<i class="fa fa-shopping-bag"></i> add to cart';
			}
			document.getElementById("stow").style.display = "block";
			document.getElementById("cartCount").innerHTML = r[2];
			$("#stow").html(r[1]);
			clearMessage();
		});
	}else{
		custom = new customMessage();
		mCra(custom.warning("To add an artwork to your cart, Please sign in and try again."));
	}
}
function mCra(message){
	$(document).ready(function(){
		document.getElementById("stow").style.display = "block";
		$("#stow").html(message);
		clearMessage();
	});
}
delay = 4;
function clearMessage(){
	if(delay === 0){
		clearTimeout(timer);
		delay = 4;
		$("#stow").fadeOut(2000, function(){
			$("#stow").html('');
		});
	}
	else{
		delay--;
		timer = setTimeout("clearMessage()",1000);
	}
}
function RemoveItem(a,b,c){
	a.innerHTML = "<i class='fa fa-spinner fa-spin'></i> Removing";
	params = {art_id:c, sever :true};
		$.post('dep/server/miscpages.php',params,function(data){
			r = data.split('#mkShfT');
			if(r[0] == 'true'){
				$("#total").html(r[1]);
				$("#amount").html(r[2]);
				$("#item"+c).remove();
			}
			else{
				a.innerHTML = "<i class=\"icon-cancel\"></i><span >remove</span>";
			}
			document.getElementById("cartCount").innerHTML = r[4];
			mCra(r[3]);
		});
}
$(document).ready(function(){
  $("#scart").click(function(){
	$("#scart").attr('class', 'active');
	$("#ptr").attr('class', '');
	$("#utr").attr('class', '');
	htmlDat = "<div align='center' style='padding-top:100px'><i class='fa fa-spinner fa-2x fa-spin'></i> <br/> Please wait...</div>";
	$("#ctabl").html(htmlDat);
	params = {req:'scart'};
		$.post('dep/server/transfile.php',params,function(data){
			$("#ctabl").attr('class', 'cart col-sm-8 col-md-9');
			$("body").attr('class', 'for_artcart');
			$("#ctabl").html(data);
		});
  });
});
$(document).ready(function(){
  $("#utr").click(function(){
	$("#scart").attr('class', '');
	$("#ptr").attr('class', '');
	$("#utr").attr('class', 'active');
	htmlDat = "<div align='center' style='padding-top:100px'><i class='fa fa-spinner fa-2x fa-spin'></i> <br/> Please wait...</div>";
	$("#ctabl").html(htmlDat);
	params = {req:'utr'};
		$.post('dep/server/transfile.php',params,function(data){
			$("#ctabl").attr('class', 'unsettled col-sm-8 col-md-9');
			$("body").attr('class', 'for_uncompleted_transaction');
			$("#ctabl").html(data);
		});
  });
});
$(document).ready(function(){
  $("#ptr").click(function(){
	$("#scart").attr('class', '');
	$("#ptr").attr('class', 'active');
	$("#utr").attr('class', '');
	htmlDat = "<div align='center' style='padding-top:200px'><i class='fa fa-spinner fa-2x fa-spin'></i> <br/> Please wait...</div>";
	$("#ctabl").html(htmlDat);
	params = {req:'ptr'};
		$.post('dep/server/transfile.php',params,function(data){
			$("#ctabl").attr('class', 'past_trans col-sm-8 col-md-9');
			$("body").attr('class', 'for_past_transaction');
			$("#ctabl").html(data);
		});
  });
});
function inflate(src){
	document.getElementById("maincover").style.display = "block";
	document.getElementById("pixcover").style.display = "block";
	ima = "<img src="+src+" style='vertical-align:middle;'/>";
	document.getElementById("pixcover").innerHTML = ima;
}
function re(a,b){
	document.getElementById("pixcover").style.display = "none";
	document.getElementById("maincover").style.display = "none";
}
function reSearch(obj, Id){
	$(document).ready(function(){
		iD = (Id)? Id : '';
		searchName = document.getElementById("search_name");
		document.getElementById("coverGlass").style.display = "block";
		q = document.getElementById("searchP").value;
		cats = document.getElementsByName("category");
		sortBy = document.getElementsByName("sortBy");
		newIt = document.getElementsByName("newIt");
		loc = document.getElementsByName("location");
		vall = (obj.name == 'viewAll')? true : false;
		if(obj.name == 'sortBy' && newIt.item(0).checked){
			newIt.item(0).click();
		}
		params = {searchQ:'', cats:'', sortBy:'', newIt:'', loc:'', viewAll: vall, next:iD};
		start = 0;
		carti = [];
		while(start < cats.length){
			if(cats[start].checked){ carti.push(cats[start].value) } else{}
			start++;
		}
		//alert(start);
		sortby = sortBy.item(0).selectedIndex;
		newIte = newIt.item(0).checked;
		locat = loc.item(0).options[loc.item(0).selectedIndex].value;
		params.searchQ = q;
		params.cats = carti;
		params.sortBy = sortby;
		params.newIt = newIte;
		params.loc = locat;
		//alert(newIte);
		if(q.length > 0 && q != ' '){searchName.innerHTML= '"'+q+'" - kunsana.com'; }else{ 'kunsana.com - search' }
		//alert(carti);
		$.post('dep/server/t.php',params,function(data){
			document.getElementById("coverGlass").style.display = "none";
			!(Id)? $("#cardCont").html(data) : obj.innerHTML = data;
			isLanding = (Id)? false : isLanding;
			document.getElementById("product_no").innerHTML = (document.getElementById("resCount").value)? document.getElementById("resCount").value+" Product" : document.getElementById("product_no").innerHTML;
			//alert(data);
		});
	})
	return false;
}
$(document).ready(function(){
	$(window).scroll(function(){
		if(($(window).scrollTop() + $(window).height()) > ($(document).height() - 500)){
			if(!isLanding){
				if(document.getElementById("nextValue") && document.getElementById("nextValue").value != 0){
					reSearch(document.getElementById("newItem"+document.getElementById("nextValue").value),document.getElementById("nextValue").value);
					isLanding = true;
					//alert('ta');
				}
			}
			//alert('almost');
		}
		});
	})

function matchList(id){
	vat = document.getElementById(id).options[document.getElementById(id).selectedIndex].value;
	fmange = document.getElementById("manga");
	fmange.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Fetching matched cities';
	params = {m:vat};
	$.post('dep/server/statesys.php',params,function(data){
			fmange.innerHTML = data
		});
}
function activateD(x){
	if (confirm('Do you really want to remove this art ?')){
		document.getElementById(x).submit();
	}
}
$(document).ready(function(){
	$(".mobileAuth").click(function(){
		$(".searchSection").slideToggle("slow");
	});
});
function customMessage(){
	this.warning = function warning(message){
		return '<div style=\'padding:12px; opacity:.92\'><div style=\'color:#F48622; font-size:16px; background:#FBE9BD; border-left:#F48622 thick solid; width:50%; padding:15px; font-family:Helvetica Neue,Helvetica,Arial,sans-serif;\'><i class=\'fa fa-exclamation-circle\'></i> '+message+'</div></div>';
	}
	this.error = function(message){
		return '<div style=\'padding:12px;\'><div align=\'left\' style=\'color:#ED050B; opacity:.92;width:50%; font-size:15px; background:#F9B4B0; border-left:#ED050B thick solid; padding:15px; font-family:Helvetica Neue,Helvetica,Arial,sans-serif; \' ><i class=\'fa fa-warning\'></i> '+message+'</div></div>';
	}
	this.success = function(message){
		return '<div style=\'padding:12px;\'><div style=\'color:#2B8E11; width:50%; opacity:.92; font-size:16px; background:#BCF8AD; border-left:#2B8E11 thick solid; padding:15px; font-family:Helvetica Neue,Helvetica,Arial,sans-serif;\'><i class=\'fa fa-check-square-o\'></i> '+message+'</div></div>';
	}
}