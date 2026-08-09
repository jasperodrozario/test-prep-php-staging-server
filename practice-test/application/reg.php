 <?php include 'includes/header.php'; ?>
 <!-- <!DOCTYPE html>
<html>
	<head>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1"> 
	<title>Confirm Password validation using jQuery</title>
	
	</head>
	<body>  -->
		<div class="container">
			<div class="row">
				<h2>Confirm Password validation using jQuery!</h2>
				<hr />
				<div class="form-group">
					<form action="" id="form" method="post">
						
						<div class="form-group">
							<label for="desc">Password:</label> 
							<input type="password" class="form-control" name="pass" id="pass" required>
						</div>
						<div class="form-group">
							<label for="desc">Confirm Password:</label> 
							<input type="password" class="form-control" name="confpass" id="confpass" required>
						</div>
						<div class="form-group">
							<span class="error" style="color:red"></span><br />
						</div>
						<button type="submit" name="submit" class="btn btn-default">Submit</button>
					</form>
				</div>
			</div>
		</div>
	<?php include 'includes/footer.php'; ?>