<?php
 
require("dbconn.php");

session_start();
/* check if all required fields for new user are present */
if (isset($_POST['email']) && isset($_POST['password']) && isset($_POST['firstName']) && isset($_POST['lastName'])){
	$email = stripslashes(trim($_POST['email']));
	$password = stripslashes(trim($_POST['password']));
	$firstName = stripslashes(trim($_POST['firstName']));
	$lastName = stripslashes(trim($_POST['lastName']));	
	$status = registerUser($email, $password, $firstName, $lastName);
	
	if($status) loginUser($email, $password);
	
	header('Content-Type: application/json');
	if ($status){
		header("HTTP 200 OK", true, 200);
		echo '{"message":"OK","target":"dashboard.php"}';
	}
	else{
		header("HTTP 422 Unprocessable Entity", true, 422);
		echo '{"message":"User with that Email already exists!"}';		
	}
}

/* check if all required fields for login are present */
if (isset($_POST['email']) && isset($_POST['password']) && !isset($_POST['firstName']) && !isset($_POST['lastName'])){
	$email = stripslashes(trim($_POST['email']));
	$password = stripslashes(trim($_POST['password']));
	$status = loginUser($email, $password);
	
	header('Content-Type: application/json');
	if ($status){
		header("HTTP 200 OK", true, 200);
		echo '{"message":"Successfully logged in!","target":"dashboard.php"}';
	}
	else{
		header("HTTP 422 Unprocessable Entity", true, 422);
		echo '{"message":"Check Username or Password"}';		
	}
}

if(isset($_GET['categories']) && !isset($_POST['categoryName']) && !isset($_POST['deleteCategory']) && !isset($_POST['categoryId'])){
	$userId = getLoggedUserId();
	$status = true;

	$limit = isset($_GET['limit']) ? $_GET['limit'] : 10;
	$offset = isset($_GET['offset']) ? $_GET['offset'] : 0;

	$allCategories = isset($_GET['all']);

	if(!$userId) {
		$status = false;
	}
   	else{
		$categories = getUserCategories($userId, $offset, $limit, $allCategories);
	}

	header('Content-Type: application/json');
	if ($status){
		header("HTTP 200 OK", true, 200);
		echo $categories;
	}
	else{
		header("HTTP 422 Unprocessable Entity", true, 422);
		echo '{"message":"Not logged in"}';		
	}

}

if(isset($_GET['categories']) && isset($_POST['categoryName']) && !isset($_POST['categoryId'])){
	$userId = getLoggedUserId();
	$categoryName = htmlspecialchars(stripslashes(trim($_POST['categoryName'])));
	$status = true;

	if(!$userId) {
		$status = false;
	}
   	else{
		newCategory($userId, $categoryName);
	}


	header('Content-Type: application/json');
	if ($status){
		header("HTTP 200 OK", true, 200);
		echo '{"message":"Category added"}';	
	}
	else{
		header("HTTP 422 Unprocessable Entity", true, 422);
		echo '{"message":"Not logged in"}';		
	}

}

//getUserRecords
if(isset($_GET['records']) && !isset($_POST['categoryName']) && isset($_GET['category'])){
	$userId = getLoggedUserId();

	$limit = isset($_GET['limit']) ? $_GET['limit'] : 10;
	$offset = isset($_GET['offset']) ? $_GET['offset'] : 0;

	$dateFrom = isset($_GET['dateFrom']) ? $_GET['dateFrom'] : null;
	$dateTo = isset($_GET['dateTo']) ? $_GET['dateTo'] : null;
	

	$category = htmlspecialchars(stripslashes(trim($_GET['category'])));

	if ($category === '-1') $category = null; 

	$status = true;

	if(!$userId) {
		$status = false;
	}
   	else{
		$records = getUserRecords($userId, $category, $dateFrom, $dateTo, $limit, $offset);
	}

	header('Content-Type: application/json');
	if ($status){
		header("HTTP 200 OK", true, 200);
		echo $records;
	}
	else{
		header("HTTP 422 Unprocessable Entity", true, 422);
		echo '{"message":"Not logged in"}';		
	}

}

//POST NEW RECORD
if(isset($_GET['records']) && isset($_POST['categoryId']) && isset($_POST['newRecordAmount']) && isset($_POST['newRecordDate']) && isset($_POST['newRecordName']) && !isset($_POST['currentRecordId']) && isUserLoggedIn()){
	$userId = getLoggedUserId();
	$categoryId = htmlspecialchars(trim($_POST['categoryId']));
	$newRecordAmount =  htmlspecialchars(trim($_POST['newRecordAmount']));
	$newRecordDate = htmlspecialchars(trim($_POST['newRecordDate']));
	$newRecordName = htmlspecialchars(trim($_POST['newRecordName']));
	 
	$status =  newUserRecord($userId,$categoryId, $newRecordName, $newRecordAmount, $newRecordDate);

	header('Content-Type: application/json');
	if ($status){
		header("HTTP 200 OK", true, 200);
		echo '{"message":"OK"}';	
	}
	else{
		header("HTTP 422 Unprocessable Entity", true, 422);
		echo '{"message":"Error saving record"}';		
	}

}



//modify record
if(isset($_GET['records']) && isset($_POST['categoryId']) && isset($_POST['modifiedRecordAmount']) && isset($_POST['modifiedRecordDate']) && isset($_POST['modifiedRecordName']) && isset($_POST['recordId']) && isUserLoggedIn()){
	$userId = getLoggedUserId();
	$categoryId = htmlspecialchars(trim($_POST['categoryId']));
	$modifiedRecordAmount =  htmlspecialchars(trim($_POST['modifiedRecordAmount']));
	$modifiedRecordDate = htmlspecialchars(trim($_POST['modifiedRecordDate']));
	$modifiedRecordName = htmlspecialchars(trim($_POST['modifiedRecordName']));	
	$recordId = htmlspecialchars(trim($_POST['recordId']));

	$status = modifyUserRecord($userId,$categoryId, $modifiedRecordName, $modifiedRecordAmount, $modifiedRecordDate, $recordId);
	header('Content-Type: application/json');
	if ($status){
		header("HTTP 200 OK", true, 200);
		echo '{"message":"Record modified"}';	
	}
	else{
		header("HTTP 422 Unprocessable Entity", true, 422);
		echo '{"message":"Error modifying record"}';		
	}
}

//delete record
if(isset($_GET['records']) && isset($_POST['deleteRecord']) && isset($_POST['recordId']) && isUserLoggedIn()){
	$userId = getLoggedUserId();
	$recordId = htmlspecialchars(trim($_POST['recordId']));

	$status = deleteUserRecord($userId, $recordId);
	header('Content-Type: application/json');
	if ($status){
		header("HTTP 200 OK", true, 200);
		echo '{"message":"Record deleted"}';	
	}
	else{
		header("HTTP 422 Unprocessable Entity", true, 422);
		echo '{"message":"Error deleting record"}';		
	}
}

//modify category
if(isset($_GET['categories']) && isset($_POST['categoryName']) && isset($_POST['categoryId']) && isUserLoggedIn()){
	$userId = getLoggedUserId();
	$categoryName = htmlspecialchars(stripslashes(trim($_POST['categoryName'])));
	$categoryId = htmlspecialchars(stripslashes(trim($_POST['categoryId'])));
	$status = true;

	if(!$userId) {
		$status = false;
	}
   	else{
		modifyCategory($userId, $categoryId, $categoryName);
	}


	header('Content-Type: application/json');
	if ($status){
		header("HTTP 200 OK", true, 200);
		echo '{"message":"Category modified"}';	
	}
	else{
		header("HTTP 422 Unprocessable Entity", true, 422);
		echo '{"message":"Not logged in"}';		
	}
}

//delete category
if(isset($_GET['categories']) && isset($_POST['deleteCategory']) && isset($_POST['categoryId']) && isUserLoggedIn()){
	$userId = getLoggedUserId();
	$categoryId = htmlspecialchars(trim($_POST['categoryId']));

	$status = deleteCategory($userId, $categoryId);
	header('Content-Type: application/json');
	if ($status){
		header("HTTP 200 OK", true, 200);
		echo '{"message":"Category deleted"}';	
	}
	else{
		header("HTTP 422 Unprocessable Entity", true, 422);
		echo '{"message":"Error deleting category"}';		
	}
}

/*register new user with given user account info*/
function registerUser($email, $password, $firstName, $lastName){
	$link=connect();
	
	$firstName = mysqli_real_escape_string($link, $firstName);
	$lastName = mysqli_real_escape_string($link, $lastName);
	$email = mysqli_real_escape_string($link, $email);
	$password = mysqli_real_escape_string($link,$password);
	$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
	
	$stmt = mysqli_prepare($link, "SELECT `ID` from `users`  WHERE Email = ? ");
	if ($stmt){
		mysqli_stmt_bind_param($stmt, "s", $email);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_bind_result($stmt, $userExists);
		mysqli_stmt_fetch($stmt);
		mysqli_stmt_close($stmt);
	}

	if($userExists){
		return 0;
	}
	else {
		$stmt = mysqli_prepare($link, "INSERT INTO users  (ID, FirstName, LastName, Email, Password) VALUES ( null,?,?,?,?);");
		
		if ($stmt){
			mysqli_stmt_bind_param($stmt, "ssss", $firstName, $lastName, $email, $hashedPassword);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_close($stmt);
		}	
		return 1;	
	}

	disconnect($link);
}

//send sendEmail

if(isset($_POST['email']) && isset($_POST['name']) && isset($_POST['subject']) && isset($_POST['text'])){
	$email = htmlspecialchars(trim($_POST['email']));
	$name = htmlspecialchars(trim($_POST['name']));
	$subject = htmlspecialchars(trim($_POST['subject']));
	$text = htmlspecialchars(trim($_POST['text']));

	$status = sendEmail($name, $email, $subject, $text);

	header('Content-Type: application/json');
	if ($status){
		header("HTTP 200 OK", true, 200);
		echo '{"message":"Mail sent"}';	
	}
	else{
		header("HTTP 422 Unprocessable Entity", true, 422);
		echo '{"message":"Mail not sent :("}';		
	}
}

/*login with given credentials*/
function loginUser($email, $password){
	$link = connect();
	
	$email = mysqli_real_escape_string($link, $email);
	$password = mysqli_real_escape_string($link,$password);
	$stmt = mysqli_prepare($link, "SELECT `ID`,`Password` from `users`  WHERE Email = ? ");
	if ($stmt){
		mysqli_stmt_bind_param($stmt, "s", $email);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_bind_result($stmt, $userId, $storedHashedPassword);
		mysqli_stmt_fetch($stmt);
		mysqli_stmt_close($stmt);
	}
	if (password_verify($password,$storedHashedPassword) && $userId){
		$_SESSION['userId'] = $userId;
		return 1;		
	}
	else {
		return 0;
	}

	disconnect($link);
}


/*check is user logged in*/
function isUserLoggedIn(){
	return isset($_SESSION['userId']) ? true : false;
}

/*logout actions*/
if(isset($_POST['logout'])){
	echo logout();
}

function logout(){
	session_destroy();
	header('Content-Type: application/json');
	header("HTTP 200 OK", true, 200);
	return '{"message":"Successfully logged out!"}';
}

//get logged in user id

function getLoggedUserId(){
	return isset($_SESSION['userId']) ? $_SESSION['userId'] : null;
}


function getUserCategories($userId, $offset, $limit, $returnAllCategories){

	$link=connect();

	$stmt = mysqli_prepare($link, "SELECT COUNT(*) from `categories` WHERE ( OwnerId = ? );");
	if ($stmt){
		mysqli_stmt_bind_param($stmt, "i", $userId);
		mysqli_stmt_bind_result($stmt, $rowCount);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_store_result($stmt);		
	}	

	$rows=mysqli_stmt_num_rows($stmt);

	if ($rows){
		while (mysqli_stmt_fetch($stmt)) {
			if(!$rowCount) {
				return json_encode(array('pagination' => array('pageCount' => 0, 'currentPage' => 0), 'categories' => array()));
			}
			else {
				$pages = ceil($rowCount / $limit);
			}
		}
	} 
	else{
		return json_encode(array('pagination' => array('pageCount' => 0, 'currentPage' => 0), 'categories' => array()));
	}

	if ($returnAllCategories) $limit = $rowCount;

	$stmt = mysqli_prepare($link,"SELECT `ID`,`CategoryName` from `categories`  WHERE ( OwnerID = ? ) ORDER BY `CategoryName` ASC LIMIT ?, ?;");
	if ($stmt){
		mysqli_stmt_bind_param($stmt, "iii", $userId, $offset, $limit);
		mysqli_stmt_bind_result($stmt, $id, $categoryName);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_store_result($stmt);	
	}		

	$rows=mysqli_stmt_num_rows($stmt);
	$categories=[];
	if($rows){
		while (mysqli_stmt_fetch($stmt)) {
			$category = array(
				'id'=> $id,
				'categoryName' => $categoryName
			);
			$categories[]=$category;
		}
	}
	
    mysqli_stmt_close($stmt);   
	disconnect($link);	


	$currentPage = $offset == 0 ? 1 : ceil(($offset)/$limit)+1;

	return json_encode(array('pagination' => array('pageCount' => $pages, 'currentPage' => $currentPage), 'categories' => $categories));
	
}

function getUserRecords($userId,$category=null, $dateFrom, $dateTo, $limit, $offset){
	
	$link=connect();

	$dateSQL = "";

	if ($dateFrom && $dateTo){
		$dateSQL = "AND `Date` >= ? AND `Date` <= ?";
	}

	if ($category){
		$stmt = mysqli_prepare($link,"SELECT COUNT(*) from `records` INNER JOIN `categories` ON records.CategoryID = categories.ID WHERE ( UserId = ? AND records.CategoryID = ? " . $dateSQL . " )");
		if ($stmt){
			mysqli_stmt_bind_param($stmt, "iiss", $userId, $category, $dateFrom, $dateTo);
			mysqli_stmt_bind_result($stmt, $rowCount);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_store_result($stmt);	
		}
	}
	else{
		$stmt = mysqli_prepare($link,"SELECT COUNT(*) from `records` INNER JOIN `categories` ON records.CategoryID = categories.ID WHERE ( UserId = ? " . $dateSQL . " )");
		if ($stmt){
			mysqli_stmt_bind_param($stmt, "iss", $userId, $dateFrom, $dateTo);
			mysqli_stmt_bind_result($stmt, $rowCount);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_store_result($stmt);			
		}	
	}

	$rows=mysqli_stmt_num_rows($stmt);

	if ($rows){
		while (mysqli_stmt_fetch($stmt)) {
			if(!$rowCount) {
				return json_encode(array('pagination' => array('pageCount' => 0, 'currentPage' => 0),'records' => array(), 'summedRecords' => array(), 'summedCategories' => array()));
			}
			else {
				$pages = ceil($rowCount / $limit);
			}
		}
	} 
	else{
		return json_encode(array('pagination' => array('pageCount' => 0, 'currentPage' => 0),'records' => array(), 'summedRecords' => array(), 'summedCategories' => array()));
	}

	if ($category){
		$stmt = mysqli_prepare($link,"SELECT `ExpenseID`, `CategoryName`,`Name`,`Amount`,`Date`, `CategoryID` from `records` INNER JOIN `categories` ON records.CategoryID = categories.ID WHERE ( UserId = ? AND records.CategoryID = ? " . $dateSQL . " ) ORDER BY `Date` DESC, `Name` ASC, `ExpenseID` DESC LIMIT ?, ? ;");
		if ($stmt){
			mysqli_stmt_bind_param($stmt, "iissii", $userId, $category, $dateFrom, $dateTo, $offset, $limit);
			mysqli_stmt_bind_result($stmt, $expenseId, $categoryName, $recordName, $recordAmount, $recordDate, $categoryId);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_store_result($stmt);	
		}
	}
	else{
		$stmt = mysqli_prepare($link,"SELECT `ExpenseID`, `CategoryName`,`Name`,`Amount`,`Date`, `CategoryID` from `records` INNER JOIN `categories` ON records.CategoryID = categories.ID WHERE ( UserId = ? " . $dateSQL . ") ORDER BY `Date` DESC, `Name` ASC, `ExpenseID` DESC LIMIT ?, ?;");
		if ($stmt){
			mysqli_stmt_bind_param($stmt, "issii", $userId, $dateFrom, $dateTo, $offset, $limit);
			mysqli_stmt_bind_result($stmt, $expenseId, $categoryName, $recordName, $recordAmount, $recordDate, $categoryId);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_store_result($stmt);			
		}	
	}

	$rows=mysqli_stmt_num_rows($stmt);
	$records = [];
	if($rows){
		while (mysqli_stmt_fetch($stmt)) {
			$record = array(
				'categoryName'=> $categoryName,
				'recordName' => $recordName,
				'recordAmount' => $recordAmount,
				'recordDate' => $recordDate,
				'recordId' => $expenseId,
				'categoryId' => $categoryId,
			);
			$records[]=$record;
		}
	}

	if ($category){
		$stmt = mysqli_prepare($link,"SELECT `CategoryName`, SUM(`Amount`), `Date` from `records` INNER JOIN `categories` ON records.CategoryID = categories.ID WHERE ( UserId = ? AND records.CategoryID = ? " . $dateSQL . ") GROUP BY `CategoryName`, `Date` ORDER BY `Date` ASC;");
		if ($stmt){
			mysqli_stmt_bind_param($stmt, "iiss", $userId,$category, $dateFrom, $dateTo);
			mysqli_stmt_bind_result($stmt, $categoryName, $recordAmount, $recordDate);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_store_result($stmt);	
		}
	}
	else{
		$stmt = mysqli_prepare($link,"SELECT `CategoryName`, SUM(`Amount`), `Date` from `records` INNER JOIN `categories` ON records.CategoryID = categories.ID WHERE ( UserId = ? " . $dateSQL . ") GROUP BY  `CategoryName`, `Date` ORDER BY `Date` ASC;");
		if ($stmt){
			mysqli_stmt_bind_param($stmt, "iss", $userId, $dateFrom, $dateTo);
			mysqli_stmt_bind_result($stmt, $categoryName, $recordAmount, $recordDate);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_store_result($stmt);			
		}	
	}

	$rows=mysqli_stmt_num_rows($stmt);
	$summedRecords = [];
	if($rows){
		while (mysqli_stmt_fetch($stmt)) {
			$summedRecord = array(
				'categoryName'=> $categoryName,
				'recordAmount' => $recordAmount,
				'recordDate' => $recordDate
			);
			$summedRecords[]=$summedRecord;
		}
	}

	if ($category){
		$stmt = mysqli_prepare($link,"SELECT `CategoryName`, SUM(`Amount`) from `records` INNER JOIN `categories` ON records.CategoryID = categories.ID WHERE ( UserId = ? AND records.CategoryID = ? " . $dateSQL . ") GROUP BY `CategoryName`;");
		if ($stmt){
			mysqli_stmt_bind_param($stmt, "iiss", $userId,$category, $dateFrom, $dateTo);
			mysqli_stmt_bind_result($stmt, $categoryName, $recordAmount);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_store_result($stmt);	
		}
	}
	else{
		$stmt = mysqli_prepare($link,"SELECT `CategoryName`, SUM(`Amount`) from `records` INNER JOIN `categories` ON records.CategoryID = categories.ID WHERE ( UserId = ? " . $dateSQL . ") GROUP BY  `CategoryName`;");
		if ($stmt){
			mysqli_stmt_bind_param($stmt, "iss", $userId, $dateFrom, $dateTo);
			mysqli_stmt_bind_result($stmt, $categoryName, $recordAmount);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_store_result($stmt);			
		}	
	}

	$rows=mysqli_stmt_num_rows($stmt);
	$summedCategories = [];
	if($rows){
		while (mysqli_stmt_fetch($stmt)) {
			$summedCategory = array(
				'categoryName'=> $categoryName,
				'recordAmount' => $recordAmount,
			);
			$summedCategories[]=$summedCategory;
		}
	}

	$currentPage = $offset == 0 ? 1 : ceil(($offset)/$limit)+1;
	return json_encode(array('pagination' => array('pageCount' => $pages, 'currentPage' => $currentPage),'records' => $records, 'summedRecords' => $summedRecords, 'summedCategories' => $summedCategories));

    mysqli_stmt_close($stmt);   
	disconnect($link);	
}

function newUserRecord($userId,$categoryId, $name, $amount, $date){
	$link=connect();
	$name=mysqli_real_escape_string($link, $name);	
	$stmt = mysqli_prepare($link, "INSERT INTO records (ExpenseID, UserID, CategoryID, Name, Amount, Date) VALUES ( null, ?, ?, ?, ?, ?);");	
	if ($stmt){
		mysqli_stmt_bind_param($stmt, "iisss", $userId, $categoryId, $name, $amount, $date);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
	}		
	return true;
	disconnect($link);	
}

function modifyUserRecord($userId, $categoryId, $name, $amount, $date, $recordId){
	$link=connect();
	$name=mysqli_real_escape_string($link, $name);	
	$stmt = mysqli_prepare($link, "UPDATE `records` SET `CategoryID` = ?, `Name` = ?, `Amount` = ?, `Date` = ? WHERE `records`.`ExpenseID` = ?");	
	if ($stmt){
		mysqli_stmt_bind_param($stmt, "isssi", $categoryId, $name, $amount, $date, $recordId);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
	}		
	return true;
	disconnect($link);	
}

function deleteUserRecord($userId, $recordId){
	$link=connect();
	$stmt = mysqli_prepare($link, "DELETE FROM `records` WHERE `records`.`ExpenseID` = ? AND `records`.`UserID` = ?");	
	if ($stmt){
		mysqli_stmt_bind_param($stmt, "ii", $recordId, $userId);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
	}		
	return true;
	disconnect($link);	
}

function newCategory($userId,$categoryName){
	$link=connect();
	$categoryName=mysqli_real_escape_string($link, $categoryName);	
	$stmt = mysqli_prepare($link, "INSERT INTO categories  (ID, OwnerID, CategoryName) VALUES ( null, ?,?);");	
	if ($stmt){
		mysqli_stmt_bind_param($stmt, "is", $userId, $categoryName);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
	}		
	disconnect($link);
}

function modifyCategory($userId, $categoryId, $name){
	$link=connect();
	$name=mysqli_real_escape_string($link, $name);	
	$stmt = mysqli_prepare($link, "UPDATE `categories` SET `CategoryName` = ? WHERE `categories`.`ID` = ? AND `categories`.`OwnerID` = ?");	
	if ($stmt){
		mysqli_stmt_bind_param($stmt, "sii", $name, $categoryId, $userId);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
	}		
	return true;
	disconnect($link);	
}

function deleteCategory($userId, $categoryId){
	$link=connect();
	//first delete records, then category
	$stmt = mysqli_prepare($link, "DELETE FROM `records` WHERE `records`.`UserID` = ? AND `records`.`CategoryID` = ?");	
	if ($stmt){
		mysqli_stmt_bind_param($stmt, "ii", $userId, $categoryId);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
	}	

	$stmt = mysqli_prepare($link, "DELETE FROM `categories` WHERE `categories`.`ID` = ? AND `categories`.`OwnerId` = ?");	
	if ($stmt){
		mysqli_stmt_bind_param($stmt, "ii", $categoryId, $userId);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
	}		
	return true;
	disconnect($link);	
}
//kontakt
function sendEmail($name, $email, $subject, $text){
	
	$to      = 'mmiljevic013@gmail.com';
	$subject = 'Kontakt forma: ' . $subject;

	$message = "Email by " . $name . "\n" ."Email address: ". $email ."\n" . "Message: " . "\n" . $text;
	$message = wordwrap($message, 68);
	$message = nl2br($message);

	$headers = 'From: mmiljevic013@gmail.com' . "\r\n" .
		'Reply-To: '. $email . "\r\n" .
		'Content-Type: text/html; charset=UTF-8' . "\r\n" .
		'X-Mailer: PHP/' . phpversion();
	$status=mail($to, $subject, $message, $headers);
	return $status;
}