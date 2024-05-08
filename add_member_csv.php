<?php

include_once('../common.php');

if (!isset($generalobjAdmin)) {
    require_once(TPATH_CLASS . "class.general_admin.php");
    $generalobjAdmin = new General_admin();
}

if (!$userObj->hasPermission('view-corporate-member')) {
    $userObj->redirect();
}

$iAdminId = $_SESSION['sess_iAdminUserId'];

// Fetch Corporate Admin Data.
$corp_id_sql = "SELECT 
    lcp.iCorporateAdminId, car.corporate_name, car.corporate_nameAr, 
    lcp.corporate_planname 
FROM `loyalty_corporate_planname` lcp, corporate_admin_registration car 
WHERE car.status = 'active' AND car.iCorporateAdminId = lcp.iCorporateAdminId 
AND iAdminId = '".$iAdminId ."'";
$corp_id_db = $obj->MySQLSelect($corp_id_sql);
$iCorporateAdminId = $corp_id_db[0]['iCorporateAdminId'];
$corporate_name = ($corp_id_db[0]['iCorporateAdminId'] != '') ? $corp_id_db[0]['corporate_name'] : $corp_id_db[0]['corporate_nameAr'];
$corporate_planname = $corp_id_db[0]['corporate_planname'];

$script ="Upload csvfile";

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title><?= $SITE_NAME ?> | Loyalty Member</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <style>
      .container {
        background:#b1dcf2;
      }
    </style>
<head>
</head>
<body>
	<div class="container p-3 my-3 border" >
		<div>
			<h2>CSV UPLOAD</h2>
		</div>
		<div class="row p-3">
			<div class="col-sm-6">
				<a href="../templates/loyalty/add_member_template_csv.csv">
					<button type="button" id="download_btn"class="btn btn-info">Download Template</button>
				</a>
			</div>
			<div class="col-sm-6">
				<form action="#" id="upload_file" method="post" enctype="multipart/form-data">
				  <div class="custom-file">
                    <input type="file" name="csvfile" required="required" class="custom-file-input" id="customFile">
                    <label class="custom-file-label" for="customFile">Choose file</label>
                    <div align="right" class="p-3">
				      <input class="btn btn-info" type="submit" value="upload" />                  
                    </div>
                  </div>
				</form>
			</div>
		<div class="p-3 bg-danger text-white" id="note" style="display:none;">
		    <label>Note:</label>
		    <p>Please Enter the following data in the respective fields<br/>
		    Loyalty Type as <strong>'classic', 'gold', 'platinum'</strong><br />
		    Date Of Brith format as <strong>'DD-MM-YYYY'</strong><br />
		    IDExpiryDate format as <strong>'DD-MM-YYYY'</strong><br />
		    </p>
		</div>
		<div class=" mx-auto mt-3 p-3 border" id="success-data" style="display:none; align:center; width:500px; background:white;">
		  <div class="p-1  mb-2 bg-success text-white" style="border-radius:5px;" id="success_msg"></div>
		  <div class="p-1  mb-2 bg-danger text-white" style="border-radius:5px;" id="failure_msg"></div>
		  <div class="p-1  mb-2 bg-danger text-white" style="border-radius:5px; display:none;" id="error_msg"></div>
		</div>
		</div>
		<div class="d-flex justify-content-between p-3">
		    <a href="loyalty_members.php?"  class="btn btn-outline-secondary">Back</a>
		    <a href="loyalty_members.php?" align="right" class="btn btn-outline-secondary">Done</a>
		</div>
	</div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

    <script>

    $(document).ready(function() {

       $('#upload_file').on('submit', function() {

           $.ajax({
              url: "loyalty_member_upload_csv.php",
              type: "POST",
              data:new FormData(this),
              dataType:'json',
              contentType:false,
              cache:false,
              processData:false,
              success: function(response) {
                  $('#success-data').show();
                  $('#success_msg').html(response.success_count + " Records uploaded successfully");
                  $('#failure_msg').html(response.failure_count + " Records failed to upload");
                  if (response.error_message) {
                    $('#error_msg').show();
                    $('#error_msg').html(response.error_message);    
                  }
               }
           });
          return false;
       });
    });

    // Show details on click.
    $('#download_btn').on('click', function() {
       $('#note').show(); 
    });

    // Add the following code if you want the name of the file appear on select
    $(".custom-file-input").on("change", function() {
      var fileName = $(this).val().split("\\").pop();
      $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });
    </script>

</body>
</html>