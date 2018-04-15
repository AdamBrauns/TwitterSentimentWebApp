<!DOCTYPE html>
<html lang="en">
<head>
  <title>Twitter User Results</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="../css/template.css">
</head>
<body>

<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
	  <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>                        
      </button>
      <a class="navbar-brand" href="#" name="top"><img src="../images/TwitterLogo.png"></a>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav">
        <li><a href="../index.html">Home</a></li>
        <li><a href="#">Keyword</a></li>
        <li class="active"><a href="#">My Tweets</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="jumbotron">
  <div class="container text-center">
	<h1>Keyword Info</h1>
    <h1>Twitter Account Info</h1>      
  </div>
</div>

<?php        

if(isset($_POST["account"])){
	//echo "<h1>Twitter Handle ". $_POST["account"] ."</h1>";
	$user = $_POST["account"];
}

require_once('TwitterAPIExchange.php');
/** Set access tokens here - see: https://dev.twitter.com/apps/ **/
$settings = array(
	'oauth_access_token' => "2684005000-GdAxuzbGdt0H7EMxlXbcMKZmbDlkwrWpiaxBIOU",
	'oauth_access_token_secret' => "pdnHIVjIj8spYauMiEHCKq3mAC6tvUy62vrcmBxImyYzY",
	'consumer_key' => "P7BVLVo3JvwI0pIgqJ7PUmniC",
	'consumer_secret' => "SjimBJjGcrM7X5L3lI0Qc3y0H8r4mRhcBjbWW70tA0e1Yi06OH"
);

$url = "https://api.twitter.com/1.1/statuses/user_timeline.json";
$requestMethod = "GET";

if (isset($_GET['user']))  {$user = $_GET['user'];}  else {$user  = "UWWhitewater";}
//if (isset($_GET['count'])) {$count = $_GET['count'];} else {$count = 48;}

$count = 30;

$getfield = "?screen_name=$user&count=$count";
$twitter = new TwitterAPIExchange($settings);
$string = json_decode($twitter->setGetfield($getfield)
->buildOauth($url, $requestMethod)
->performRequest(),$assoc = TRUE);
/*
foreach($string as $items)
    {
        echo "Time and Date of Tweet: ".$items['created_at']."<br />";
        echo "Tweet: ". $items['text']."<br />";
        echo "Tweeted by: ". $items['user']['name']."<br />";
        echo "Screen name: ". $items['user']['screen_name']."<br />";
        echo "Followers: ". $items['user']['followers_count']."<br />";
        echo "Friends: ". $items['user']['friends_count']."<br />";
        echo "Listed: ". $items['user']['listed_count']."<br /><hr />"; 
    }
*/	
	
$counter = 0;
foreach($string as $items)
    {
		$counter = $counter + 1;
		if($counter % 3 == 1){
			echo "<div class='container'>";    
			echo "<div class='row'>";
		}
		echo "<div class='col-sm-4'>";
		echo "<div class='panel panel-primary'>";
        echo "<div class='panel-heading'>@".$items['user']['screen_name']."</div>";
		echo "<div class='panel-heading' id='tweetDateHeader'>".$items['created_at']."</div>";
        echo "<div class='panel-body'>".$items['full_text']."</div>";
        echo "<div class='panel-footer'><p><b>Sentiment Score: <span style='float:right;'>0.2</p></b></div>";
        echo "</div>";
        echo "</div>";
		if($counter % 3 == 0){
			echo "</div>";
			echo "</div><br>";
		}
    }
?>

<footer class="container-fluid text-center">
  <p><a href="#top">Go to Top</p>  
</footer>

</body>
</html>
