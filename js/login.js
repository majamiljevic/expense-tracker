var getUrlParameter = function getUrlParameter(sParam) {
    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : sParameterName[1];
        }
    }
};

$( document ).ready(function() {
	if(getUrlParameter('logged') === 'false'){
		$( "#loginAlert" ).removeClass("hidden");	
		$( "#loginAlert" ).addClass("alert-danger");	
		$( "#loginAlert" ).text("Please log-in before accessing Dashboard!");	
	}

	$( ".form-signup-heading" ).on( "click", function() {
	  $( ".signin" ).removeClass("active");	 
		$( "#signup" ).removeClass("hidden");
		$( "#inputFirstName" ).removeClass("hidden");
		$( "#inputLastName" ).removeClass("hidden");
			  
	  $( ".signup" ).addClass("active");	  
		$( "#signin" ).addClass("hidden");	
		
		$("#loginAlert").addClass("hidden");  
	});

	  $('#inputPassword').keyup(function(event){
    if(event.keyCode == 13){
        $('#signin').click();
    }
  });
	
	$( ".form-signin-heading" ).on( "click", function() {
	  $( ".signup" ).removeClass("active");	 
		$( "#signin" ).removeClass("hidden");
			 
	  $( ".signin" ).addClass("active");	  
		$( "#signup" ).addClass("hidden");	 	  
	  $( "#inputFirstName" ).addClass("hidden");
		$( "#inputLastName" ).addClass("hidden");
	
		$("#loginAlert").addClass("hidden");  
	});
	
	$( "#signin" ).on( "click", function() {
		
		$("#loginAlert").addClass("hidden");
		$("#loginAlert").removeClass("alert-danger");

		if($("#inputEmail").val()=="" || $("#inputPassword").val() == ""){
			$("#loginAlert").addClass("alert-danger");
			$("#loginAlert").removeClass("hidden");
			$("#loginAlert").text("Please populate all required fields");
			return;
		}
		
		$.ajax({
			url: "include/functions.php",
			method: "POST",
			data:{"email":$("#inputEmail").val(), "password":$("#inputPassword").val()},
			dataType:"json",
			statusCode: {
						200: function(response) {
							$("#loginAlert").addClass("alert-success");
							$("#loginAlert").removeClass("hidden");
							$("#loginAlert").text(response.message);
							window.setTimeout (function(){window.location = response.target;}, 1000);
						},
						422: function(response) {
							$("#loginAlert").addClass("alert-danger");
							$("#loginAlert").removeClass("hidden");
							$("#loginAlert").text(response.responseJSON.message);
						},
			},
		})
  });

	 $("#inputLastName").keyup(function(event){
		 if(event.keyCode == 13){
		 $("#signup").click();
	 	}
   });

	
	$( "#signup" ).on( "click", function() {
		
		$("#loginAlert").addClass("hidden");
		$("#loginAlert").removeClass("alert-danger");

		if($("#inputEmail").val()=="" || $("#inputPassword").val() == "" || $("#inputFirstName").val() == "" || $("#inputLastName").val() == ""){
			$("#loginAlert").addClass("alert-danger");
			$("#loginAlert").removeClass("hidden");
			$("#loginAlert").text("Please populate all required fields");
			return;
		}
		
	  $.ajax({
		  url: "include/functions.php",
		  method: "POST",
		  data:{"firstName":$("#inputFirstName").val(),"lastName":$("#inputLastName").val(),"email":$("#inputEmail").val(), "password":$("#inputPassword").val()},
		  dataType:"json",
			statusCode: {
						200: function(response) {
							$("#loginAlert").addClass("alert-success");
							$("#loginAlert").removeClass("hidden");
							$("#loginAlert").text(response.message);
							window.setTimeout (function(){window.location = response.target;}, 1000);
						},
						422: function(response) {
							$("#loginAlert").addClass("alert-danger");
							$("#loginAlert").removeClass("hidden");
							$("#loginAlert").text(response.responseJSON.message);
						},
			},
	  })
  });
});
