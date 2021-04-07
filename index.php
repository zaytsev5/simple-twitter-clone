<?php
	// if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
	// 	$uri = 'https://';
	// } else {
	// 	$uri = 'http://';
	// }
	// $uri .= $_SERVER['HTTP_HOST'];
	// header('Location: '.$uri.'/home.php');
	// exit;

	$status = 0;

	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "twitter";
	// $status = 0;
	// $user = "1"


		// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);

		// Check connection
	if ($conn->connect_error) {
		 die("Connection failed: " . $conn->connect_error);
	}


	function doFollow($conn,$fid){
		$query = "insert into followers values (1,".$fid.")";
		if (!mysqli_query($conn, $query)) {
					echo "Error: " . $query . "" . mysqli_error($conn);
		}
		$uri = 'http://';
		$uri .= $_SERVER['HTTP_HOST'];
		header('Location: '.$uri.'/index.php');
	}
	function doUnFollow($conn, $fid){

		$query = "delete from followers where uid=1 and follower=$fid";
		if (!mysqli_query($conn, $query)) {
					echo "Error: " . $query . "" . mysqli_error($conn);
		}
		$uri = 'http://';
		$uri .= $_SERVER['HTTP_HOST'];
		header('Location: '.$uri.'/index.php');
	}
	function doTweet($conn, $content){
		$uid= rand(2,10);
		$today = date("Y-m-d H:i:s");
		$query = "insert into tweets values ($uid,null,'$content','$today')";
		if (!mysqli_query($conn, $query)) {
					echo "Error: " . $query . "" . mysqli_error($conn);
		}

	}
	if(!empty($_POST)){
		$content = $_POST['content'];
		doTweet($conn, $content);
	}
	if(!empty($_GET)){
		if(!empty($_GET['fid'])){
			$fid=$_GET['fid'];
			$status = 1;
			// echo '<script type="text/javascript">alert("Did follow")</script>';
			doFollow($conn,$fid);

		}else{
			$ufid=$_GET['ufid'];
			$status = 2;
			// echo '<script type="text/javascript">alert("Did unfollow")</script>';
			doUnFollow($conn,$ufid);

		}
	}
	echo 'User IP Address - '.$_SERVER['REMOTE_ADDR']."\n";
	print '<div style="margin-left:50px">';
	print "<h4>hi, 127.0.0.1</h4>";
	print <<<EOF
		<form class="m-10" method="post" action="index.php">
			<input type="text" name="content" />
			<button type="submit" class="btn btn-primary">tweet</button>
		</form>
	EOF;
	$sql = "select * from tweets";
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
	  // output data of each row
	print '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">';
	print "<table class='table-bordered'>";
	print "<tr><td>uid</td><td>tid</td><td>content</td><td>createdAt</td></tr>";
	  while($row = $result->fetch_assoc()) {
	  	$uid = $row['uid'];
	  	$tid = $row['tid'];
			$content = $row['content'];
			$createdAt = $row['createdAt'];

	  print <<<EOF
		<tr><td>$uid</td><td>$tid</td><td>$content</td><td>$createdAt</td>
		EOF;
		if($conn->query("select * from followers where uid = 1 and follower = $uid")->num_rows == 0){
			print <<<EOF
			<td><a href="index.php?fid=$uid">follow</a></td></tr>
			EOF;
		}else {
			print <<<EOF
			<td><a href="index.php?ufid=$uid">unfollow</a></td></tr>
			EOF;
		}


	  }
	print "</table></div>";
	} else {
	  echo "0 results";
	}
	print "<div>$status</div>";

		


	$conn->close();
	// $conn = pg_connect("host=localhost port=5432 dbname=test");
	// $result = pg_query($conn, "select * users");
	// var_dump(pg_fetch_all($result));
	
	




?>
