<!DOCTYPE html>
<html lang="en">
<head>
  <title>Keyword Results</title>
  <link rel="icon" href="../images/TwitterLogo.png">
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
				<li class="dropdown active">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" name="top">Keyword<b class="caret"></b></a>
					<ul class="dropdown-menu" style='background-color: black; color: white;'>
						<li>
							<p>&nbsp;Create a New Search</p>
						</li>
						<li><form action="keywordSearch.php">
							<button type="submit" class="btn btn-success btn-block" style="background-color: #00aced;">New Search</button>
						</form></li>
						<br>
						<li>
							<p>&nbsp;Quickly Search Here:</p>
						</li>
						  <form method="POST" action="keywordResults.php">
                              <div class="form-group">
								<li>
                                  <input style='background-color: #FFFACD;' type="text" class="form-control" value="" required="" title="Please enter a keyword!" name="keyword" placeholder="Technology">
                                </li>
								<span class="help-block"></span>
                              </div>
							  <li>
								<button type="submit" class="btn btn-success btn-block" style="background-color: #00aced;">Find Keyword</button>
							  </li>
						  </form>
					</ul>
				</li>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">My Tweets<b class="caret"></b></a>
					<ul class="dropdown-menu" style='background-color: black; color: white;'>
						<li>
							<p>&nbsp;Create a New Search</p>
						</li>
						<li><form action="myTweetsSearch.php">
							<button type="submit" class="btn btn-success btn-block" style="background-color: #00aced;">New Search</button>
						</form></li>
						<br>
						<li>
							<p>&nbsp;Quickly Search Here:</p>
						</li>
						  <form method="POST" action="myTweetsResults.php">
                              <div class="form-group">
								<li>
                                  <input style='background-color: #FFFACD;' type="text" class="form-control" value="" required="" title="Please enter a keyword!" name="account" placeholder="UWWhitewater">
                                </li>
								<span class="help-block"></span>
                              </div>
							  <li>
								<button type="submit" class="btn btn-success btn-block" style="background-color: #00aced;">Find My Tweets</button>
							  </li>
						  </form>
					</ul>
				</li>
			</ul>
        </div>
      </div>
    </nav>
<?php        

// Get the keyword if its set, otherwise show the results for technology
if(isset($_POST["keyword"])){
	$keyword = $_POST["keyword"];
}else{
	$keyword = "Technology";
}

// Getting external files to connect to the twitter API 
require_once('TwitterAPIExchange.php');
require_once('cred.php');

// Calling the Twitter API 
$url = "https://api.twitter.com/1.1/search/tweets.json";
$requestMethod = "GET";
$count = 30;
$getfield = "?q=$keyword&result_type=recent&count=$count";
$twitter = new TwitterAPIExchange($settings);
$string = json_decode($twitter->setGetfield($getfield)
->buildOauth($url, $requestMethod)
->performRequest(),$assoc = TRUE);
$string = $string['statuses'];	

// Setting the variables 
$counter = 0;
$error = 1;

// If there were no tweets with the keyword, show technology results
if(count($string) == 0){
	$url = "https://api.twitter.com/1.1/search/tweets.json";
	$requestMethod = "GET";
	$keyword = "Technology";
	$getfield = "?q=$keyword&result_type=recent&count=$count";
	$twitter = new TwitterAPIExchange($settings);
	$string = json_decode($twitter->setGetfield($getfield)
	->buildOauth($url, $requestMethod)
	->performRequest(),$assoc = TRUE);	
	$string = $string['statuses'];
	$error = 5;
}

// Setting more variables
$negCounter = 0;
$neuCounter = 0;
$posCounter = 0;
$divider = count($string);
$totalSentiment = 0;
$userInfo = $string[0];
$tweetDiv = "";

// If there was an error, let the user know
if($error == 5){
	$tweetDiv .= "<h3>&nbsp;&nbsp;No results found. Showing Tweets for: Technology</h3><br>";
}

// Importing files for sentiment analysis
require_once __DIR__ . '/Sent/autoload.php';
$sentiment = new \PHPInsight\Sentiment();

foreach($string as $items)
{
	$counter = $counter + 1;
	// Loop through all the tweets 
	if($counter % 3 == 1){
		$tweetDiv .= "<div class='container'>";    
		$tweetDiv .= "<div class='row'>";
	}
	$tweetDiv .= "<div class='col-sm-4'>";
	$tweetDiv .= "<div class='panel panel-primary'>";
	if(isset($items['user']['screen_name'])){
		$tweetDiv .= "<div class='panel-heading'><a href='https://twitter.com/".$items['user']['screen_name']."' target='_blank' style='color: white;'>@".$items['user']['screen_name']."</a></div>"; #text-decoration: none;
	}else{
		$tweetDiv .= "<div class='panel-heading'>ERROR</div>";
	}
	if(isset($items['created_at'])){
		$tweetDiv .= "<div class='panel-heading' id='tweetDateHeader'>".$items['created_at']."</div>";
	}else{
		$tweetDiv .= "<div class='panel-heading' id='tweetDateHeader'>ERROR</div>";
	}
	if(isset($items['text'])){
		$tweetDiv .= "<div class='panel-body'>".$items['text']."</div>";
	}else{
		$tweetDiv .= "<div class='panel-body'>ERROR</div>";
	}
	$tweetDiv .= "<div class='panel-footer'><p><b>Sentiment: <span style='float:right;'>";
	
	# Getting the sentiment
	$string = $items['text'];
	$scores = $sentiment->score($string);
	$class = $sentiment->categorise($string);
	$tweetDiv .= $class.": ".$scores[$class];
	
	if($class == 'neg'){
		$negCounter += 1;
		$temp = -1 * $scores[$class];
		$totalSentiment += $temp;
	}else if($class == 'pos'){
		$posCounter += 1;
		$temp = $scores[$class];
		$totalSentiment += $temp;
	}else if($class == 'neu'){
		$neuCounter += 1;
	}
	
	$tweetDiv .= "</p></b></div>";
	$tweetDiv .= "</div>";
	$tweetDiv .= "</div>";
	if($counter % 3 == 0){
		$tweetDiv .= "</div>";
		$tweetDiv .= "</div><br>";
	}
}
if($counter % 3 != 0){
	$tweetDiv .= "</div>";
	$tweetDiv .= "</div><br>";		
}

// Outputting the top info
echo "<div class='jumbotron' style='background-color: #00aced;'>";
echo "<div class='container' style='color: white;'>";
echo "<div class='row'>";
echo "<div class='col-sm-4'>";
echo "<div id='profilePic'>";
include('simple_html_dom.php');
$search_keyword=str_replace(' ','+',$keyword);
$newhtml =file_get_html("https://www.google.com/search?q=".$search_keyword."&tbm=isch&gws_rd=cr&ei=16E0WMGSKYmisAHmp6b4Ag");
$result_image_source = $newhtml->find('img', 0)->src;
echo "<img src='".$result_image_source."' height='100%' width='100%' style='border-radius: 50%;'>";
echo "</div>";
echo "<div class='row'>";
echo "<div class='col-sm-12'><p style='text-align: center;'>Keyword: ".$keyword."</p></div>";
echo "</div>";
echo "</div>";
echo "<div class='col-sm-4'>";
echo "<div>";
echo "<p>Keyword: ".$keyword."</p>";
echo "<p>Google Link: <a href='https://www.google.com/search?q=".$keyword."' target='_blank' style='color: white;'>Click Me!</a></p>";
echo "<p>Positive Tweets: ".$posCounter."</p>";
echo "<p>Neutral Tweets: ".$neuCounter."</p>";
echo "<p>Negative Tweets: ".$negCounter."</p>";
echo "</div>";
echo "</div>";
echo "<div class='col-sm-4'>";
echo "<div id='emoji'>";

// Assigning a emoji based on the sentiments
$imgSrc = "";
if(max($posCounter, $neuCounter, $negCounter) == $posCounter){
	if($negCounter/$divider <= .2){
		// Super happy
		$imgSrc = "../images/emoji/positive.png";
	}else{
		// Mild happy
		$imgSrc = "../images/emoji/positive2.png";
	}
}else if(max($posCounter, $neuCounter, $negCounter) == $negCounter){
	if($posCounter/$divider <= .2){
		// Super sad
		$imgSrc = "../images/emoji/negative.png";
	}else{
		// Mild sad
		$imgSrc = "../images/emoji/negative2.png";
	}
}else{
	if($posCounter > $negCounter){
		$ratio = $posCounter/($divider-$neuCounter);
		if($ratio > .65){
			// Mild happy
			$imgSrc = "../images/emoji/positive2.png";
		}else{
			// Neutral
			$imgSrc = "../images/emoji/neutral.png";
		}
	}else{
		$ratio = $negCounter/($divider-$neuCounter);
		if($ratio > .65){
			// Mild sad
			$imgSrc = "../images/emoji/negative2.png";
		}else{
			// Neutral
			$imgSrc = "../images/emoji/neutral.png";
		}
	}
}

echo "<img src='".$imgSrc."' height='80%' width='80%'>";
echo "</div>";
echo "<div class='row'>";
echo "<div class='col-sm-12'>";
echo "<p style='text-align: center;'>".round($totalSentiment/$divider, 3)."</p>";
echo "</div>";
echo "</div>";
echo "</div>";
echo "</div>";
echo "</div>";
echo "</div>";
echo $tweetDiv;
?>

<footer class="container-fluid text-center">
  <p><a href="#top">Go to Top</p>  
</footer>
</body>
</html>
