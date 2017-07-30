var categories = [];
$( document ).ready(function() {

	$('#newRecordDate').val((new Date).toISOString().split('T')[0]);
	$('#dateTo').val((new Date).toISOString().split('T')[0]);
	$('#dateFrom').val((new Date((new Date).setMonth((new Date).getMonth()-1))).toISOString().split('T')[0]);

  	$('.overviewButton').on('click', function(event){
		$('.charts').addClass('hidden');
		$('.categories').addClass('hidden');
		$('#filter').removeClass('hidden');		
		$('.overview').removeClass('hidden');
		$('ul.nav').children('li').removeClass('active');
		$(this).parent().addClass('active');
		$('#sectionHeading').text('Expenses overview');
	});

	$('.reportsButton').on('click', function(event){
		$('.overview').addClass('hidden');
		$('.categories').addClass('hidden');
		$('#filter').removeClass('hidden');		
		$('.charts').removeClass('hidden');
		$('ul.nav').children('li').removeClass('active');
		$(this).parent().addClass('active');
		$('#sectionHeading').text('Expenses charts');
	});

	$('.categoriesButton').on('click', function(event){
		$('.overview').addClass('hidden');
		$('.charts').addClass('hidden');
		$('#filter').addClass('hidden');		
		$('.categories').removeClass('hidden');		
		$('ul.nav').children('li').removeClass('active');
		$(this).parent().addClass('active');
		$('#sectionHeading').text('Expense categories');
	});

	$( "#logout" ).click(function( event ) {
		event.preventDefault();
		$.ajax({
			url: "include/functions.php",
			method: "POST",
			data:{ "logout":true },
			dataType:"json",
			statusCode: {
			200: function() {
							window.location = "login.php";
						}
			}
		})
	});
	
	getAllCategories = function(){		
		$("#categoriesSelectFilter").empty();
		$("#addRecordCategorySelector").empty();
		$("#modifiedRecordCategorySelector").empty();
		$.ajax({
			url: "include/functions.php?categories&all=true",
			method: "GET",
			dataType:"json",
			statusCode: {
				200: function(data) {
					categories = data.categories;
					$("#categoriesSelectFilter").append('<option id="-1">All categories</option>');   	
					$.each(data.categories, function(key,value) {   				    
						var selectorData = '<option id="'+ value.id +'" value="' + value.categoryName + '">' + value.categoryName + '</option>';
						$("#categoriesSelectFilter").append(selectorData);    
						$("#addRecordCategorySelector").append(selectorData);    
						$("#modifiedRecordCategorySelector").append(selectorData);    
						
					});
				}
			},
		})		
	}

	getCategories = function(offset, limit){
		$("#categoryTable tbody").empty();
		offset = typeof offset !== 'undefined' ? offset : 0 ;
		limit = typeof limit !== 'undefined' ?  limit : $('.limitPerPageCategories option:selected').val();

		$.ajax({
			url: "include/functions.php?categories&offset="+offset+"&limit="+limit,
			method: "GET",
			dataType:"json",
			statusCode: {
				200: function(data) {
					$('.current-position-categories').html(data.pagination.currentPage + " / " + data.pagination.pageCount);

					if(data.pagination.currentPage == data.pagination.pageCount){
						$('#paginateForwardCategories').attr("disabled", true);
					}
					else {
						$('#paginateForwardCategories').attr("disabled", false);
					}
					if(data.pagination.currentPage <=1){
						$('#paginateBackCategories').attr("disabled", true);
					}
					else {
						$('#paginateBackCategories').attr("disabled", false);
					}			
		
					$.each(data.categories, function(key,value) {   				    
						$("#categoryTable tbody").append(
											'<tr><td>' + value.categoryName +
											'</td><td>' + '<button type="button" class="btn btn-default btn-xs modifyCategory"  data-toggle="modal" data-target="#modifyCategory"  data-categoryname = "'+ value.categoryName +'" data-categoryid="'+value.id+'"><span class="glyphicon glyphicon-pencil"></span></button>&nbsp;<button type="button" class="btn btn-default btn-xs deleteCategory" data-toggle="modal" data-target="#confirmCategoryDeletion" data-categoryId="'+value.id+'"><span class="glyphicon glyphicon-trash"></span></button>'+
											'</td></tr>'
										); 
					});
				}
			},
		})
	};

	$( "#paginateBack" ).click(function( event ) {
		event.preventDefault();
		var category = $("#categoriesSelectFilter option:selected").attr("id");
		var limit = $('.limitPerPage option:selected').val();
		var currentPage = parseInt($('.current-position').html().split('/')[0]);
		var offset = (currentPage - 2) * limit;
		getRecords(category, offset, limit);
	});


	$( "#paginateForward" ).click(function( event ) {
		event.preventDefault();
		var category = $("#categoriesSelectFilter option:selected").attr("id");
		var limit = $('.limitPerPage option:selected').val();
		var currentPage = parseInt($('.current-position').html().split('/')[0]);
		var offset = (currentPage) * limit;
		getRecords(category, offset, limit);
	});	

	$( ".limitPerPage" ).change(function( event ) {
		event.preventDefault();
		var category = $("#categoriesSelectFilter option:selected").attr("id");
		var limit = $('.limitPerPage option:selected').val();
		var currentPage = 1;
		var offset = 0;
		getRecords(category, offset, limit);
	});	


	$( "#paginateBackCategories" ).click(function( event ) {
		event.preventDefault();
		var limit = $('.limitPerPageCategories option:selected').val();
		var currentPage = parseInt($('.current-position-categories').html().split('/')[0]);
		var offset = (currentPage - 2) * limit;
		getCategories(offset, limit);
	});


	$( "#paginateForwardCategories" ).click(function( event ) {
		event.preventDefault();
		var limit = $('.limitPerPageCategories option:selected').val();
		var currentPage = parseInt($('.current-position-categories').html().split('/')[0]);
		var offset = (currentPage) * limit;
		getCategories(offset, limit);
	});	

	$( ".limitPerPageCategories" ).change(function( event ) {
		event.preventDefault();
		var limit = $('.limitPerPageCategories option:selected').val();
		var currentPage = 1;
		var offset = 0;
		getCategories(offset, limit);
	});	

	getRecords = function(category, offset, limit){

		category = typeof category !== 'undefined' ? parseInt(category) : -1 ;
		dateFrom = $('#dateFrom').val();
		dateTo = $('#dateTo').val();
		
		offset = typeof offset !== 'undefined' ? offset : 0 ;
		limit = typeof limit !== 'undefined' ?  limit : $('.limitPerPage option:selected').val();

		$("#recordTable tbody").empty();
		$.ajax({
		  url: "include/functions.php?records&category="+category+"&dateFrom="+dateFrom+"&dateTo="+dateTo+"&offset="+offset+"&limit="+limit,
		  method: "GET",
		  dataType:"json",
		  statusCode: {
            200: function(response) {
				$('.current-position').html(response.pagination.currentPage + " / " + response.pagination.pageCount);

				if(response.pagination.currentPage == response.pagination.pageCount){
					$('#paginateForward').attr("disabled", true);
				}
				else {
					$('#paginateForward').attr("disabled", false);
				}
				if(response.pagination.currentPage <= 1){
					$('#paginateBack').attr("disabled", true);
				}
				else {
					$('#paginateBack').attr("disabled", false);
				}				

				$.each(response.records, function(key,value) {  
					var date = value.recordDate.split("-") 
					var preformattedDate = date[2]+'/'+date[1]+'/'+date[0];		    
					$("#recordTable tbody").append(
						'<tr><td>' + value.categoryName +
						'</td><td>' + value.recordName + 
						'</td><td>' + value.recordAmount + 
						'</td><td>' + preformattedDate +
						'</td><td>' + '<button type="button" class="btn btn-default btn-xs modify" data-toggle="modal" data-target="#modifyRecord" data-recordInfo=\''+JSON.stringify(value).replace(/'/g, "\\'")+'\'><span class="glyphicon glyphicon-pencil"></span></button>&nbsp;<button type="button"  data-toggle="modal" data-target="#confirmRecordDeletion" class="btn btn-default btn-xs delete" data-recordId="'+value.recordId+'"><span class="glyphicon glyphicon-trash"></span></button>'+
						'</td></tr>'
					);    
				});
				drawRecords(response);
				if (category === -1){
					drawCategoryShare(response);
				}
				else{
					drawRecordsInCategoryShare(response);
				}
			}
		  },
	  });
		return true;
	};

	drawRecords = function(data){
		var dates = [];
		var seriesData = [];
		
		$.each(categories, function(categoryKey,categoryValue) { 
			var recordData = [];
			$.each(data.summedRecords, function(recordKey,recordValue) { 
				if(categoryValue.categoryName === recordValue.categoryName){
					var date = recordValue.recordDate.split("-");
					var convertedDate = Date.UTC(date[0], date[1]-1, date[2]);
					var amount = parseFloat(recordValue.recordAmount);
					recordData.push([convertedDate, amount])
				}
			});
			seriesData.push({'name':categoryValue.categoryName, 'data':recordData});
		});		
		$('.chart').highcharts({
			chart:{
				type: 'line',
				width: $('.main .row').width()
			},
			title: {
				text: 'Expenses'
			},
			xAxis: {
				type: 'datetime',
				title: {
					text: 'Date'
				}
			},
			yAxis: {
				title: {
					text: 'Amount'
				}
			},
			series: seriesData
		});
	};


	drawRecordsInCategoryShare = function(data){
		var seriesData = [];
		
		$.each(data.records, function(recordKey,recordValue) { 
			seriesData.push({'name':recordValue.recordName, 'y':parseFloat(recordValue.recordAmount)});		
		});

		$('.categoryShareChart').highcharts({
				chart:{
						type: 'pie',
						width: $('.main .row').width()
					},
			title: {
				text: 'Record amount shares in category'
			},
					
			plotOptions: {
				pie: {
					allowPointSelect: true,
					cursor: 'pointer',
					dataLabels: {
						enabled: true,
						format: '<b>{point.name}</b>: {point.percentage:.1f} %',
						style: {
							color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
						}
					}
				}
			},
			xAxis: {	
					title: {
					text: 'Date'
				}
			},
			yAxis: {
				title: {
					text: 'Amount'
				}
			},
			series:[{name:"Amount", data:seriesData}] 
		});		
	};

	drawCategoryShare = function(data){
		var seriesData = [];
		
		$.each(data.summedCategories, function(categoryKey,categoryValue) { 
			seriesData.push({'name':categoryValue.categoryName, 'y':parseFloat(categoryValue.recordAmount)});		
		});

    $('.categoryShareChart').highcharts({
			  chart:{
					type: 'pie',
					width: $('.main .row').width()
				},
        title: {
            text: 'Expense category shares'
        },
				
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                    style: {
                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                    }
                }
            }
        },
        xAxis: {	
				title: {
                text: 'Date'
            }
        },
        yAxis: {
            title: {
                text: 'Amount'
            }
        },
        series:[{name: "Amount", data:seriesData}] 
    });		
	}

	$('#addCategoryModal').on('show.bs.modal', function (event) {
		$( "#addCategory" ).on('click', function( event ) {
			event.preventDefault();
			event.stopImmediatePropagation();
			if ($('#categoryName').val()!==''){
				$.ajax({
					url: "include/functions.php?categories",
					method: "POST",
					data:{"categoryName":$('#categoryName').val()},
					dataType:"json",
					statusCode: {
					200: function() {
									getCategories();	
									getAllCategories();	
									$('#addCategoryModal').modal('hide');
									$('#categoryName').val('');
								}
					},
				})
				$('#addCategory').off();
			}
		});
	});

	$('#addRecordModal').on('show.bs.modal', function (event){
		if(categories.length){
			$("#addRecordModal").find('.alert').addClass('hidden');
			$("#addRecord").attr('disabled', false);
		}
		else{
			$("#addRecordModal").find('.alert').removeClass('hidden');
			$("#addRecord").attr('disabled', true);
		}

		$("#addRecord").on('click', function( event ) {
			event.preventDefault(); 
			event.stopImmediatePropagation();
			if ($('#newRecordName').val()!=='' && $('#newRecordAmount').val()!=='' ){
				$.ajax({
					url: "include/functions.php?records",
					method: "POST",
					data:{
						"categoryId":$("#addRecordCategorySelector option:selected").attr("id"),
						"newRecordName": $('#newRecordName').val(), 
						"newRecordAmount": $('#newRecordAmount').val(), 
						"newRecordDate": $('#newRecordDate').val(),
					},
					dataType:"json",
					statusCode: {
					200: function() {
									getRecords(-1);
									$('#addRecordModal').modal('hide');
								}
					},
				})
				$('#addRecord').off();
			}
		});		
	});
	
	$('#modifyRecord').on('show.bs.modal', function (event) {
    	var button = $(event.relatedTarget);
		var data = button.data('recordinfo');
		var recordId = data.recordId;
		var categoryId = data.categoryId;
		$('#modifiedRecordName').val(data.recordName);
		$('#modifiedRecordAmount').val(data.recordAmount);
		$('#modifiedRecordDate').val(data.recordDate);
		$('#modifiedRecordCategorySelector').val(data.categoryName)
    	$('#modifyRecordButton').on('click', function(){
			$.ajax({
				url: "include/functions.php?records",
				method: "POST",
				data:{
					"categoryId": $("#modifiedRecordCategorySelector option:selected").attr("id"),
					"modifiedRecordAmount": $('#modifiedRecordAmount').val(),
					"modifiedRecordDate" : $('#modifiedRecordDate').val(),
					"modifiedRecordName" : $('#modifiedRecordName').val(),
					"recordId": recordId, 
				},
				dataType:"json",
				statusCode: {
				200: function() {
								getRecords();
								$('#modifyRecord').modal('hide');
							}
				},
			});		
			$('#modifyRecordButton').off();	
		});
	});

	$('#confirmRecordDeletion').on('show.bs.modal', function (event) {
		var button = $(event.relatedTarget);
		$('#deleteRecordButton').on('click', function(){
			var recordId = button.data('recordid');
			$.ajax({
				url: "include/functions.php?records",
				method: "POST",
				data:{
					"deleteRecord": true,
					"recordId": recordId, 
				},
				dataType:"json",
				statusCode: {
				200: function() {
								getRecords();
								$('#confirmRecordDeletion').modal('hide');
							}
				},
			});		
			$('#deleteRecordButton').off();	
		});
	});

	$('#modifyCategory').on('show.bs.modal', function (event) {
		var button = $(event.relatedTarget);
		var categoryName = button.data('categoryname');
		var categoryId = button.data('categoryid');
		$('#modifiedCategoryName').val(categoryName);
		$('#modifyCategoryButton').on('click', function(){
			$.ajax({
				url: "include/functions.php?categories",
				method: "POST",
				data:{
					"categoryName": $('#modifiedCategoryName').val(),
					"categoryId": categoryId, 
				},
				dataType:"json",
				statusCode: {
				200: function() {
								getCategories();
								getAllCategories();
								getRecords();
								$('#modifyCategory').modal('hide');
							}
				},
			});				
			$('#modifyCategoryButton').off();
		});
	});

	$('#confirmCategoryDeletion').on('show.bs.modal', function (event) {
		var button = $(event.relatedTarget);
		$('#deleteCategoryButton').on('click', function(){
			var categoryId = button.data('categoryid');
			$.ajax({
				url: "include/functions.php?categories",
				method: "POST",
				data:{
					"deleteCategory": true,
					"categoryId": categoryId, 
				},
				dataType:"json",
				statusCode: {
				200: function() {
								getRecords(-1);
								getCategories();
								getAllCategories();
								$('#confirmCategoryDeletion').modal('hide');
							}
				},
			});			
			$('#deleteCategoryButton').off();
		});
	});

	$( "#applyFilter" ).on('click', function( event ) {
		event.preventDefault();
		var category = $("#categoriesSelectFilter option:selected").attr("id");
		getRecords(category);
	});

	getCategories();
	getAllCategories();
	getRecords();
		
});