<?php
  require("include/functions.php");
  if(!isUserLoggedIn()){
    header("Location: login.php?logged=false");
  }
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Money expense tracker features">
    <meta name="author" content="Maja Miljevic">
    <title>Simple expense tracking system!</title>
  	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/dashboard.css">
  </head>
  <body>
	<div class="navbar navbar-inverse navbar-fixed-top">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="index.php">Expense Tracker</a>
          
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
            <li></li>
            <li><span>&nbsp;</span></li>
            <li><button class="btn btn-primary navbar-btn addRecordButton" data-toggle="modal" data-target="#addRecordModal">Add Expense!</button></li>
            <li><button class="btn btn-primary navbar-btn addRecordButton" data-toggle="modal" data-target="#addCategoryModal">Add Category!</button></li>
            <li><a href="#" id="logout">Logout</a></li>
          </ul>
        </div>
      </div>
	</div>

    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
          <ul class="nav nav-sidebar">
            <li class="active"><a href="#" class="overviewButton">Overview</a></li>
            <li><a href="#" class="reportsButton">Reports</a></li>
            <li><a href="#" class="categoriesButton">Categories</a></li>
          </ul>
        </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          <div class="row">
            <h2 class="sub-header" id="sectionHeading">Expenses overview</h2>
          </div>
          <div class="row" id="filter">
            <div class="col-lg-3">
              <div class="row">
                <div class="input-group">
                  <span class="input-group-addon">Category</span>
                  <select id="categoriesSelectFilter" class="form-control"><option id="-1">All categories</option></select>
                </div>           
              </div>
            </div>
            <div class="col-lg-3">
              <div class="row">
                <div class="input-group">
                  <span class="input-group-addon">Date from</span>
                  <input type="date" class="form-control" id="dateFrom" required>
                </div>    
              </div>        
            </div>  
            <div class="col-lg-offset-1 col-lg-3">
              <div class="row">
                <div class="input-group">
                  <span class="input-group-addon">Date to</span>
                  <input type="date" class="form-control" id="dateTo" required>
                </div>       
              </div>         
            </div>    
            <div class="col-lg-1 pull-right"> 
              <div class="row">    
                <div class="input-group">
                  <button type="button" class="btn btn-primary" class="form-control" id="applyFilter">Apply!</button>   
                </div>         
              </div>   
            </div>                                 
          </div>
          <div class="overview">
            <div class="row">
              <div class="table-responsive">
                <table class="table table-striped" id="recordTable">
                  <thead>
                    <tr>
                      <th>Category</th>
                      <th>Expense</th>
                      <th>Amount</th>
                      <th>Date</th>
                      <th><button type="button" class="btn btn-default btn-xs" disabled><span class="glyphicon glyphicon-pencil"></span></button>&nbsp;<button type="button" class="btn btn-default btn-xs" disabled><span class="glyphicon glyphicon-trash"></span></button></th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
                <div class="pagination">
                  </button><button type="button" class="btn btn-default btn-xs" id="paginateBack"><span class="glyphicon glyphicon-arrow-left"></span></button>
                  <span class="current-position"></span>
                  </button><button type="button" class="btn btn-default btn-xs" id="paginateForward"><span class="glyphicon glyphicon-arrow-right"></span></button>
                  <span>Expenses per page</span>
                  <select class="limitPerPage">
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                  </select>
                </div>
              </div>
            </div>
          </div>
          <div class="charts hidden">
            <div class="row">
              <div class="chart"></div>
            </div>
            <div class="row">
              <div class="categoryShareChart"></div>
            </div>            
          </div>
          <div class="categories hidden">
            <div class="row">
              <div class="table-responsive">
                <table class="table table-striped" id="categoryTable">
                  <thead>
                    <tr>
                      <th>Category Name</th>
                      <th><button type="button" class="btn btn-default btn-xs" disabled><span class="glyphicon glyphicon-pencil"></span></button>&nbsp;<button type="button" class="btn btn-default btn-xs" disabled><span class="glyphicon glyphicon-trash"></span></button></th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
                <div class="pagination">
                  </button><button type="button" class="btn btn-default btn-xs" id="paginateBackCategories"><span class="glyphicon glyphicon-arrow-left"></span></button>
                  <span class="current-position-categories"></span>
                  </button><button type="button" class="btn btn-default btn-xs" id="paginateForwardCategories"><span class="glyphicon glyphicon-arrow-right"></span></button>
                  <span>Categories per page</span>
                  <select class="limitPerPageCategories">
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                  </select>
                </div>
              </div>
            </div>
          </div>          
        </div>
      </div>
    </div>

    <div id="addRecordModal" class="modal fade" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Add Record:</h4>
          </div>
          <div class="modal-body">
              <div class="input-group">
                <span class="input-group-addon">Category</span>
                <select class="form-control" id="addRecordCategorySelector"></select>
              </div>   
              <div class="input-group">
                <span class="input-group-addon">Name</span>
                <input type="text" class="form-control"  id="newRecordName" placeholder="Name" required>
              </div>   
              <div class="input-group">
                <span class="input-group-addon">Amount</span>
                <input type="number" class="form-control"  id="newRecordAmount" placeholder="Amount" step="0.01" min="0.01" required>
              </div>   
              <div class="input-group">
                <span class="input-group-addon">Date</span>
                <input type="date" class="form-control"  id="newRecordDate" required>
              </div>   

              <br>
              <span class="alert alert-danger">Add at least one category before adding records!</span>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-success" id="addRecord" disabled>Save</button>
          </div>
        </div>
      </div>
    </div>

    <div id="modifyRecord" class="modal fade" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Modify record data:</h4>
          </div>
          <div class="modal-body">
              <div class="input-group">
                <span class="input-group-addon">Category</span>
                <select class="form-control" id="modifiedRecordCategorySelector"></select>
              </div>   
              <div class="input-group">
                <span class="input-group-addon">Name</span>
                <input type="text" class="form-control"  id="modifiedRecordName" placeholder="Name" required>
              </div>   
              <div class="input-group">
                <span class="input-group-addon">Amount</span>
                <input type="number" class="form-control"  id="modifiedRecordAmount" placeholder="Amount" step="0.01" min="0.01" required>
              </div>   
              <div class="input-group">
                <span class="input-group-addon">Date</span>
                <input type="date" class="form-control"  id="modifiedRecordDate" required>
              </div>            
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-success" id="modifyRecordButton">Save</button>
          </div>
        </div>
      </div>
    </div>

    <div id="confirmRecordDeletion" class="modal fade" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Confirm deletion:</h4>
          </div>
          <div class="modal-body">
            <p>This Expense record will be <strong>deleted</strong> and cannot be recovered.</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-danger" id="deleteRecordButton">Delete</button>
          </div>
        </div>
      </div>
    </div>

    <div id="modifyCategory" class="modal fade" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Modify category name:</h4>
          </div>
          <div class="modal-body">
              <div class="input-group">
                <span class="input-group-addon">Name</span>
                <input type="text" class="form-control" placeholder="Name" id="modifiedCategoryName">
              </div>                  
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-success" id="modifyCategoryButton">Save</button>
          </div>
        </div>
      </div>
    </div>

   <div id="confirmCategoryDeletion" class="modal fade" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Confirm deletion</h4>
          </div>
          <div class="modal-body">
            <p>This Category and ALL related records will be <strong>deleted</strong> and cannot be recovered.</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-danger" id="deleteCategoryButton">Delete</button>
          </div>
        </div>
      </div>
    </div>
    <div id="addCategoryModal" class="modal fade" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Add Category:</h4>
          </div>
          <div class="modal-body">
              <div class="input-group">
                <span class="input-group-addon">Name</span>
                <input type="text" class="form-control" placeholder="Category name" id="categoryName">
              </div>           
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-success" id="addCategory">Add category!</button>
          </div>
        </div>
      </div>
    </div> 
              
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="http://code.highcharts.com/stock/highstock.js"></script>
	  <script src="js/dashboard.js"></script>
  </body>
</html>

