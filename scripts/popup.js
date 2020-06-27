
$(document).ready(function() {

	$(".IAL").click(function(){
		$(".bg-modal").css("display", "flex");			
	})

	$(".close").click(function(){
		$(".bg-modal").css("display", "none");			
		$("#ZS").css("display", "none");			
		$("#ZCR").css("display", "none");			
		$("#AO").css("display", "none");			
		$("#CAR").css("display", "none");			
	})

	$(".ISO").click(function(){
		$("#ZS").css("display", "flex");			
	})
	$(".ICR").click(function(){
		$("#ZCR").css("display", "flex");			
	})
	$(".IAO").click(function(){
		$("#AO").css("display", "flex");			
	})
	$(".ICAR").click(function(){
		$("#CAR").css("display", "flex");			
	})
});