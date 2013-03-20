<HTML>
  <BODY>
			
<?php
	while(true) # acts like a thread
	{
    #connect statement to mysql
  	$con = mysqli_connect(your_servername,your_username,your_password,your_dbname);
  	if (mysqli_connect_errno($con))
  	{
  		echo "Failed to connect to MySQL: " . mysqli_connect_error();
  	}
    
    
  	$result = mysqli_query($con,"SELECT * FROM users;");
  	while($row = mysqli_fetch_array($result))
  	{
  		echo $row['phone'] . " " . $row['name']."<br>";
  		$sub = mysqli_query($con,"SELECT * FROM subscription where phone = ".$row['phone'].";");
  		while($tweetid = mysqli_fetch_array($sub))
  		{
  			try
  			{
  			echo $tweetid['screen_name']." ".$tweetid['latest']."<BR>";
  			$latest = $tweetid['latest'];
  			$twid = $tweetid['screen_name'];	

        #implements twitter api
        #returns last 'count' tweets of the 'twid'
  			$tweets_result=file_get_contents("https://api.twitter.com/1/statuses/user_timeline.json?include_entities=true&include_rts=true&screen_name=".$twid."&count=3&exclude_replies=true&since_id=12345");
  			#$tweets_result=file_get_contents("https://api.twitter.com/1/statuses/user_timeline.json?include_entities=true&include_rts=true&screen_name=".$twid."&count=3&exclude_replies=true");
  			
  			
  			$data=json_decode($tweets_result);
  			$tweet=array_reverse($data);
        #tweet variable has an array of tweets in it
  			foreach ($tweet as &$tw)
  			{
  				foreach($tw as $paramName => $paramValue)
  				{		
  					if($paramName == "text")
  					{		
  				  		echo $paramValue."<br>";
  					  	setupString($twid.":".$paramValue,$row['phone']);
  					}
  					if($paramName == "id")
  				  	{
  						$a=sprintf('%0.0f',$paramValue);
  						print $a."<BR>";
  						mysqli_query($con,"UPDATE subscription SET latest = '".$a."' WHERE phone = '".$row['phone']."' AND screen_name='".$twid."'");
  						
  					}
  				}
  				echo "<br />";
  			}
  			}
  			catch(Exception $e)
  			{
  				print "EXCEPTION ".$e;
  			}
  		}
  	}
  	mysqli_close($con);
  	function setupString($text,$phonenum)
  	{
  		require_once("Way2SMS.php");
  		$client = new Way2SMS(your_way2sms_api_key_goes_here);
  		$response = $client->sendSms($text,$phonenum);
  		var_dump($response);
  		print "<BR><BR>";
  	}
	}
?>
	</BODY>
</HTML>
