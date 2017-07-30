$( document ).ready(function(){

	$("#text").keyup(function(event){
			if(event.keyCode == 13){
					$("#sendEmail").click();
			}
	});

	$( "#sendEmail" ).click(function( event ) {
		event.preventDefault();
		if($("#name").val()=="" || $("#inputEmail").val() == "" || $("#subject").val() == "" || $("#text").val() == ""){
			$("#contactAlert").addClass("alert-danger");
			$("#contactAlert").removeClass("hidden");
			$("#contactAlert").text("Please populate all required fields");
			return;
		}
		
		$.ajax({
		  url: "include/functions.php",
		  method: "POST",
		  data:{
				"name":$("#name").val(), 
				"email":$("#inputEmail").val(), 
				"subject":$("#subject").val(),
				"text":$("#text").val()
			},
		  dataType:"json",
			statusCode: {
				200: function(response) {
					$("#contactAlert").removeClass("alert-danger");
					$("#contactAlert").addClass("alert-success");
					$("#contactAlert").removeClass("hidden");
					$("#contactAlert").text(response.message);
					$("#name").val(""); 
					$("#inputEmail").val(""); 
					$("#subject").val("");
					$("#text").val("");
				},
				422: function(response) {
					$("#contactAlert").addClass("alert-danger");
					$("#contactAlert").removeClass("hidden");
					$("#contactAlert").text(response.responseJSON.message);
				},
			},
		});
		return false;
    });
});

