<?php

require('log.php');

error_reporting(E_ALL);

require_once("oauth/OAuth.php"); // From http://oauth.googlecode.com/svn/code/php/

// ===== CUSTOM SYSTEM FOR LINKING/STORING _YOUR_ USER ID WITH THE URBAN API TOKEN, YOU WILL NEED TO MODIFY IT =====

// Using session as a "store" for the simplicity of the demo
//session_start();

//function remote_get_contents($uri)
//{
//	$content = @file_get_contents($uri);
//	if( !$content && extension_loaded('curl') )
//	{
//		$c = curl_init();
//        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
//        curl_setopt($c, CURLOPT_URL, $uri);
//        $content = curl_exec($c);
//        curl_close($c);
//	}
//	return $content;
//}
//
//function saveOAuthToken($user_id, $token_key, $token_secret, $type_of_token = 'request')
//{
//	// I'm saving in a temp session, you'll certainly insert/update in your DB to link your user with the urban api token.
//	$urban_api_token = array(
//		'key' => $token_key,
//		'secret' => $token_secret,
//		'type' => $type_of_token
//		);
//
//	$_SESSION['urban_api_token'] = $urban_api_token;
//
//	return $urban_api_token;
//}
//
//// Get the current oauth token save for a user
//function getOAuthToken($user_id)
//{
//	// I'm storing in a temp session (that's why I'm not using a user_id), but you might want to do a database lookup here
//	if ( array_key_exists('urban_api_token', $_SESSION) ) return $_SESSION['urban_api_token'];
//	else return false;
//}
//
//// ===== END OF CUSTOM SYSTEM =====
//
///**
//* ApiRequest
//*/
//class ApiRequest
//{
//	private $apiURL;
//	private $oauthConsumer;
//	private $oauthAccessToken;
//	private $sigMethod;
//	private $apiCalls = array();
//
//	function __construct($apiURL, $oauthConsumer, $oauthAccessToken, $sigMethod)
//	{
//		$this->apiURL = $apiURL;
//		$this->oauthConsumer = $oauthConsumer;
//		$this->oauthAccessToken = $oauthAccessToken;
//		$this->sigMethod = $sigMethod;
//	}
//
//	public function addApiCall($callName, $callParams = array())
//	{
//		$this->apiCalls[] = array(
//			'call' => $callName,
//			'params' =>	$callParams
//			);
//	}
//
//	public function reset()
//	{
//		$this->apiCalls = array();
//	}
//
//	public function execute($reset = false)
//	{
//		if ( !$this->apiCalls )
//		{
//			die("Can't execute request as there are no apiCalls");
//		}
//
//		$params = array('request' => json_encode($this->apiCalls));
//
//		// ... pass the json encoded api request as the "request" parameter of the OAuth request
//		// Use the consumer (your app/site) and the access token (the user authorized token) to build the actual signed http request
//		$api_req = OAuthRequest::from_consumer_and_token($this->oauthConsumer, $this->oauthAccessToken, "GET", $this->apiURL, $params);
//		$api_req->sign_request($this->sigMethod, $this->oauthConsumer, $this->oauthAccessToken);
//
//		$apiResponse = json_decode(remote_get_contents($api_req->__toString()), true);
//
//		if ( $reset ) $this->reset();
//
//		return $apiResponse;
//	}
//}
//
//
//// Your api key/secret - callback url is your site url where you want the user to go after authorizing your app (basically, _this_ php script but maybe you want to build a more complicated process)
//$api_key = '018a3a0fdaa01ca1250243b1ee2c5f4f059c842a1';
//$api_secret = '8c20d7fe1310787024520455b57faf1f';
//$callback_url = "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
//
//// OAuth parameters (from http://www.urban-rivals.com/api)
//$request_token_url = "http://www.urban-rivals.com/api/auth/request_token.php";
//$authorize_token_url = "http://www.urban-rivals.com/api/auth/authorize.php";
//$access_token_url = "http://www.urban-rivals.com/api/auth/access_token.php";
//$api_url = "http://www.urban-rivals.com/api/";
//
//// User id in the external site - should be something real in your case
//// You'll then link this user id in your site with the api token
//$user_id = 0;
//
//
//if ( !$api_key || !$api_secret ) die("You must set your Urban API key & secret - go get one: http://www.urban-rivals.com/api/ !");
//
//$oauthConsumer = new OAuthConsumer($api_key, $api_secret);
//$sigMethod = new OAuthSignatureMethod_HMAC_SHA1();
//
//$urban_api_token = getOAuthToken($user_id);
//
//// No token at all - request one
//if ( !$urban_api_token )
//{
//	// Request a token using your consumer
//	$req_req = OAuthRequest::from_consumer_and_token($oauthConsumer, NULL, "GET", $request_token_url, NULL);
//  	$req_req->sign_request($sigMethod, $oauthConsumer, NULL);
//
//	// Get the request token
//	$request_token_str = remote_get_contents($req_req->__toString());
//	// Parse the response (it will create 2 globals variables "oauth_token" & "oauth_token_secret")
//	parse_str($request_token_str, $output);
//
//
//	$oauth_token = $output['oauth_token'];
//	$oauth_token_secret = $output['oauth_token_secret'];
//
//	if ( !$oauth_token || !$oauth_token_secret ) die("Error while getting the request token: ".$request_token_str);
//
//	// Save them, linked to the current user id on your site
//	saveOAuthToken( $user_id, $oauth_token, $oauth_token_secret, 'request');
//
//	// Redirect the user's browser to the authorization page on Urban Rivals website
//	Header("Location: ".$authorize_token_url."?oauth_token=".$oauth_token."&oauth_callback=".urlencode($callback_url));
//}
//// Already got a request token - hopefully we come back from authorization, use the authorized request token to get an access token
//else if ( $urban_api_token['type'] == 'request' )
//{
//	$oauthRequestToken = new OAuthToken($urban_api_token['key'], $urban_api_token['secret']);
//
//	// Request an access token using your consumer (representing you) and the authorized request token (representing the user)
//  	$acc_req = OAuthRequest::from_consumer_and_token($oauthConsumer, $oauthRequestToken, "GET", $access_token_url, NULL);
//  	$acc_req->sign_request($sigMethod, $oauthConsumer, $oauthRequestToken);
//
//	// Get the access token
//	$access_token_str = remote_get_contents($acc_req->__toString());
//	// Parse the response (it will create 2 globals variables "oauth_token" & "oauth_token_secret")
//	parse_str($access_token_str);
//
//	if ( !$oauth_token || !$oauth_token_secret ) die("Error while getting the access token: ".$request_token_str);
//
//	// Save them, linked to the current user id on your site
//	$urban_api_token = saveOAuthToken( $user_id, $oauth_token, $oauth_token_secret, 'access');
//
//	// Now you have a usable access token for the user, you can start having fun with the api!
//}


// Got an access token! Make calls to the api
if ( $urban_api_token['type'] == 'access' )
{
	$oauthAccessToken = new OAuthToken($urban_api_token['key'], $urban_api_token['secret']);

	$apiRequest = new ApiRequest($api_url, $oauthConsumer, $oauthAccessToken, $sigMethod);

        /*$apiRequest->addApiCall('collections.getCharacterVariations', array(
            "characterID" => "399"
        ));*/
	/*$apiRequest->addApiCall('forum.createmessage', array(
            'subjectId' => '2683173',
            'message' => 'ça marche'
        ));*/
	// Uncomment if you have "user" access
	/*
	$apiRequest->addApiCall('collections.getDeck', array('imageSize' => 'medium'));
	$apiRequest->addApiCall('players.getFeed');
	*/
	
        
        

	echo '<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"/></head><body>';
	echo "<script>window.localStorage['connecte'] = true;</script>";

	// Uncomment the bloc if you have "user" access
	
	// Let's display the deck nicely (only the first 10 to avoid a loooong page)
	$apiRequest->addApiCall('collections.getClanSummary', array('ownedOnly' => true));
	$apiResponse = $apiRequest->execute(true);
	$characters = $apiResponse['collections.getClanSummary']['items'];

	

	// Get the price
	$totalPrice = 0;
	for($i = 0; $i < count($characters); ++$i)
	{
		$character = $characters[$i];
		if (intval($character['minMarketPrice']) > 0)
		{
				echo '<img src="'.$character['characterNewPictUrl'].'" align="left" height="128" width="94/>"';
				echo '<b>'.$character['name'].'</b>';
				echo '<br/>';
				echo '<img src="'.$character['clanPictUrl'].'"/>';
				echo ' '.$character['clan_name'];
				echo '<br/>';
				$nbr = $character['totalOwnedCharacters'];
				echo ' '.number_format($nbr, 0, '.',' ').' x '.number_format(intval($character['minMarketPrice']), 0, '.', ' ').' =';
				echo '<br/>';
				$price = $character['minMarketPrice'] * $nbr;
				echo ' '.number_format($price, 0, '.', ' ').' Clintz';
			
			$totalPrice += intval($price);

			echo '<br clear="all"/>';

			echo '<hr size="1" noshade />';
		}
	}
	echo '<h1>Current Collection Price on the Market: '.number_format( $totalPrice, 0, '.', ' ').' Clintz</h1>';
	
	echo '<br/><br/>';
	/*if( is_array($apiResponse) )
		{
			// Cycle and display all methods calls responses (so you can understand how it works)
			foreach($apiResponse as $callName => $callResponse)
			{
				echo '<h2>'.$callName.':</h2>';

				$items = $callResponse['items'];
				$context = $callResponse['context'];
                        
                print_r($apiResponse);

                        

			}
		}*/
	
        /*echo '<br><br><br>';
        foreach($items as $key => $value){
            echo $value['id']. " ";
            echo $value['name']. " ";
        }*/
        
	echo '</body></html>';        
}
?>