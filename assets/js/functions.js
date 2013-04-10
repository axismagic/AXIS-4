// JavaScript Document
$(window).load(function(){

 $("[rel=tooltip]").tooltip({});
 $("[rel=tooltipRight]").tooltip({
 	placement: 'right'
 });
 $("[rel=tooltipLeft]").tooltip({
 	placement: 'left'
 });
 $("[rel=tooltipBottom]").tooltip({
 	placement: 'bottom'
 });
 
 $("[rel=popover]").popover({
	placement: 'left',
	trigger: 'hover'
 });
	
 $(window).scroll(function() {
		var topo = $(this).scrollTop();
		if(topo > 200) {
			$('.minilogo').stop(true, false).animate({
				width: 65
			});
		} else {
			$('.minilogo').stop(true, false).animate({
				width: 0
			});
		}
 });
 
 
 $('#termsAndStuff').click(function() {
	 $('#termsAndConditions').modal('toggle');
 });
 
 $('#iWantToSeeItAll').click(function() {
	$('#someTos').slideUp(function(){
		 $('#allTos').slideDown();
	});
	$('#iWantToSeeItAll').fadeOut(function() {
		$('#notSoMuchPlease').fadeIn();
	});
 });
 
 $('#notSoMuchPlease').click(function() {
	$('#allTos').slideUp(function(){
		 $('#someTos').slideDown();
	});
	$('#notSoMuchPlease').fadeOut(function(){
		$('#iWantToSeeItAll').fadeIn();
	});
 });
 
 $('#accept').click(function() {
	 $('#acceptModal').modal('toggle');
 });
 
 $('#goodToGo').click(function() {
	if (!$('#acceptModal #checkIt').is(':checked')) {
		$('#noTick').fadeIn();
	} else {
		 
		 var values = "id=" + $('html').attr('class');
		 
		 $.ajax({
			type: "POST",
			url: "assets/save.php",
			data: values,
			success: function(result){
				if (result) {
					
					$('#acceptBox').slideUp(function() {
						$('#acceptedBox').slideDown();
						$('#noCanDo').fadeOut();
						$('#goodToGo').fadeOut();
						$('#closeAccept').fadeIn();
					}).delay(15000).hide(function() {
						$('#acceptModal').modal('hide');
						location.reload();
					});
					
				} else {
					
					
				}
			}
		});
		
	}
 });
 
 
 $('#questions').click(function() {
	 $('#getInTouch').modal('toggle');
 });
 
 $('#getInTouch .stepone ul li a').click(function() {
	 $(this).parent().addClass("active");
	 $(this).parent().siblings().slideUp();
	 $('#backToOne').fadeIn();
	 var target = $(this).attr('data-target');
	 $('#getInTouch').find(target).slideDown();
	 $('#sendItIn').fadeIn();
	 $('#closeContacts').fadeOut();
 });
 
 $('#backToOne').click(function() {
	 resetAll();
 });
 
 $('#sendItIn').click(function() {
	$('.contextual').each(function() {
		if($(this).is(':visible')) {
			validate($(this).find('form'));
		}
	});
 });
 
});

function resetAll() {
	$('#getInTouch .stepone ul li').show(function() {
		$(this).removeClass("active");
	});
	 $('.contextual').slideUp();
	 $('#backToOne').fadeOut();
	 $('#sendItIn').fadeOut();
	 $('#closeContacts').fadeIn();
}

function success() {
	$('#getInTouch .stepone ul li').hide(function() {
		$(this).removeClass("active");
	});
	 $('.contextual').slideUp();
	 $('#backToOne').fadeOut();
	 $('#sendItIn').fadeOut();
	 $('#closeContacts').fadeIn();
	 $('#welldone').fadeIn().delay(10000).show(function() {
		 $('#getInTouch').modal('hide');
	 });;
}

function error() {
	$('#getInTouch .stepone ul li').hide(function() {
		$(this).removeClass("active");
	});
	 $('.contextual').slideUp();
	 $('#backToOne').fadeOut();
	 $('#sendItIn').fadeOut();
	 $('#closeContacts').fadeIn();
	 $('#notcool').fadeIn().delay(30000).show(function() {
		 $('#getInTouch').modal('hide');
	 });
}


function validate(formId) {
	
	var errors = 0;
	
	var values = formId.serialize();
	
	$.ajax({
		type: "POST",
		url: "assets/submit.php",
		data: values,
		success: function(result){
			if (result) {
				
				success();
				
			} else {
				
				error();
				
			}
		}
	});
	
}