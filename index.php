<?php
header( 'Content-Type: text/html; charset=utf-8' );
ini_set( 'max_execution_time', 0 );
ini_set( 'display_errors', 1 );
ini_set( 'error_reporting', 2047 );


$fp       = file_get_contents( 'links.txt' );
$link_key = preg_split( '/\n|\r\n?/', $fp );


foreach ( $link_key as $key => $val ) {
	$name     = $val;
	$dsa      = str_replace( " ", "+", $val );

	$location = $dsa;
	$ch = curl_init( 'https://www.schmalz.com/en/'.$location );
	curl_setopt( $ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.0; rv:20.0) Gecko/20100101 Firefox/20.0' );
	curl_setopt( $ch, CURLOPT_HEADER, true );
	curl_setopt($ch, CURLOPT_HTTPHEADER, array("Cookie: _ga=GA1.2.1062105868.1567538491;_gat_UA-42003922-1=1;_gid=GA1.2.1423749803.1567884659;GTM_CURID=247217;GTM_LOGIN=1;PHPSESSID=grlopigq3ng4nmr7gj94mrep06;_cid=GSnv89xOHOWtYO9D;cgi=1;cookielaw=1;countryCluster=RU;currentStoreId=2;external_no_cache=1;frontend=o5f7rr193lufc8a5rep7dk1m95;frontend_cid=y56Fhp4cWDEQ38qS;reference_pl_id=WEB-RU-EUR;schmalz_current_currency=EUR;schmalz_current_currency_symbol=%E2%82%AC;gtm_internal=false;"));
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
	curl_setopt($ch, CURLOPT_VERBOSE, true);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,30);
	curl_setopt($ch, CURLOPT_TIMEOUT,30);
	$data      = curl_exec( $ch );
	$http_code = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
	curl_close( $ch );
	$content_data = $data;

	$regex        = '|<div class="h1 modal-title">(.*?)</div>|is';
	preg_match_all( $regex, $content_data, $out );
	$name = $out[1][0];
	print '<pre>';
	var_dump($name);
	print '</pre>';

	$sku = $location;
	print '<pre>';
	var_dump($sku);
	print '</pre>';

	$regex        = '|<div class="product-description">(.*?)</div>|is';
	preg_match_all( $regex, $content_data, $out );
	$description = str_replace('  ', '', $out[1][0]);
	$description = str_replace("\n", "", $description);
	print '<pre>';
	var_dump($description);
	print '</pre>';


	$regex        = '|{"reference_pl_id":"WEB-RU-EUR","start_date":"2013-01-01","end_date":"2999-12-31","value":"(.*?)"}|is';
	preg_match_all( $regex, $content_data, $out );
	$price = str_replace('  ', '', $out[1][0]);
	print '<pre>';
	var_dump($price);
	print '</pre>';


	$re = '`/Artikelbild/(.*?).jpg`';
	preg_match_all($re, $content_data, $matches, PREG_SET_ORDER, 0);
	$img = $matches[0][1];
	$to = 'C:\os\OSPanel\domains\shmalz\img/' .$img. '.jpg';
	file_put_contents( $to, file_get_contents( 'https://pimmedia.schmalz.com/assets/Artikelbild/' .$img.'.jpg' ) );

	print '<pre>';
	echo '<img src="img/'.$img.'.jpg">';
	print '</pre>';
	$ar_img = $img.'.jpg';
	$end = array();
	array_push($end, $name, $sku, $price, $description, $ar_img);
	print'<pre>';
	var_dump($end);
	print '</pre>';


	$fp = fopen('file.csv', 'a');
	fputcsv($fp, $end, ';');
	fclose($fp);

	sleep(1);
}
?>