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
      <a class="navbar-brand" href="../index.php"><img src="../images/TwitterLogo.png"></a>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav">
        <li><a href="../index.php">Home</a></li>
        <li><a href="keywordSearch.php">Keyword</a></li>
        <li class="active" name="top"><a href="myTweetsSearch.php">My Tweets</a></li>
      </ul>
    </div>
  </div>
</nav>
<!--
<div class="jumbotron">
  <div class="container text-center">
	<h1>Keyword Info</h1>
    <h1>Twitter Account Info</h1>      
  </div>
</div>
-->

<?php        

if(isset($_POST["account"])){
	//echo "<h1>Twitter Handle ". $_POST["account"] ."</h1>";
	$user = $_POST["account"];
}else{
	$user = "UWWhitewater";
}

require_once('TwitterAPIExchange.php');
/** Set access tokens here - see: https://dev.twitter.com/apps/ **/
require_once('cred.php');

$url = "https://api.twitter.com/1.1/statuses/user_timeline.json";
$requestMethod = "GET";

//if (isset($_GET['user']))  {$user = $_GET['user'];}  else {$user  = "UWWhitewater";}
//if (isset($_GET['count'])) {$count = $_GET['count'];} else {$count = 48;}

$count = 30;

$getfield = "?screen_name=$user&count=$count";
$twitter = new TwitterAPIExchange($settings);
$string = json_decode($twitter->setGetfield($getfield)
->buildOauth($url, $requestMethod)
->performRequest(),$assoc = TRUE);	

$profileError = 1;
$errorType = "";
$counter = 0;

if(count($string) > 1){
	foreach($string as $items)
	{
		if(!isset($items['user']['screen_name'])){
			$profileError = 5;
			$errorType = "private";
		}
	}
}else{
	$profileError = 5;
	$errorType = "nonexistant";
}

if($profileError == 5){
	$url = "https://api.twitter.com/1.1/statuses/user_timeline.json";
	$requestMethod = "GET";

	$getfield = "?screen_name=TheHackersNews&count=$count";
	$twitter = new TwitterAPIExchange($settings);
	$string = json_decode($twitter->setGetfield($getfield)
	->buildOauth($url, $requestMethod)
	->performRequest(),$assoc = TRUE);	
}
/*
echo $string[0]['user']['profile_image_url_https'];
echo "<img src='".$string[0]['user']['profile_image_url_https']."'>";
echo $string[0]['user']['name'];
echo $string[0]['user']['screen_name'];
echo $string[0]['user']['location'];
echo $string[0]['user']['description'];
echo $string[0]['user']['followers_count'];
echo $string[0]['user']['friends_count'];
echo $string[0]['user']['verified'];
*/

echo "<div class='jumbotron'>";
echo "<div class='container'>";    
echo "<div class='row'>";
echo "<div class='col-sm-4' style='background-color:lavender; padding: 16px;'>";
echo "<img src='".$string[0]['user']['profile_image_url_https']."'>";
echo "</div>";
echo "<div class='col-sm-4' style='background-color:lavenderblush; padding: 16px;'>";
echo "<img src='".$string[0]['user']['profile_image_url_https']."'>";
echo "</div>";
echo "<div class='col-sm-4' style='background-color:lavender; padding: 16px;'>";
echo "<img src='".$string[0]['user']['profile_image_url_https']."'>";
echo "</div>";
echo "</div>";
echo "</div>";
echo "</div>";

if($errorType == "private"){
	echo "<h3>&nbsp;&nbsp;The Twitter user is private. Showing Tweets for: The Hackers News</h3><br>";
}else if($errorType == "nonexistant"){
	echo "<h3>&nbsp;&nbsp;The Twitter user does not exist. Showing Tweets for: The Hackers News</h3><br>";
}
	
foreach($string as $items)
{
	$counter = $counter + 1;
	if($counter % 3 == 1){
		echo "<div class='container'>";    
		echo "<div class='row'>";
	}
	echo "<div class='col-sm-4'>";
	echo "<div class='panel panel-primary'>";
	if(isset($items['user']['screen_name'])){
		echo "<div class='panel-heading'><a href='https://twitter.com/".$items['user']['screen_name']."' target='_blank' style='color: white; text-decoration: none;'>@".$items['user']['screen_name']."</a></div>";
	}else{
		echo "<div class='panel-heading'>ERROR</div>";
	}
	if(isset($items['created_at'])){
		echo "<div class='panel-heading' id='tweetDateHeader'>".$items['created_at']."</div>";
	}else{
		echo "<div class='panel-heading' id='tweetDateHeader'>ERROR</div>";
	}
	if(isset($items['text'])){
		echo "<div class='panel-body'>".$items['text']."</div>";
	}else{
		echo "<div class='panel-body'>ERROR</div>";
	}
	echo "<div class='panel-footer'><p><b>Sentiment Score: <span style='float:right;'>0.2</p></b></div>";
	echo "</div>";
	echo "</div>";
	if($counter % 3 == 0){
		echo "</div>";
		echo "</div><br>";
	}
}
if($counter % 3 != 0){
	echo "</div>";
	echo "</div><br>";		
}


?>

<footer class="container-fluid text-center">
  <p><a href="#top">Go to Top</p>  
</footer>

</body>
</html>
