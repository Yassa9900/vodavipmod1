<?php
  $number = $_GET["phone"];
  $password = $_GET["password"];
  $url5="https://mobile.vodafone.com.eg/services/security/oauth/oauth/token";
  $data5="username=".$number."&password=".$password."&client_secret=secret&grant_type=password&client_id=my-trusted-client";
  $headers5[]='Accept: application/json, text/plain, */*';
  $headers5[]='api-host: token';
  $headers5[]='Connection: keep-alive';
  $headers5[]='Content-Type: application/x-www-form-urlencoded';
  $headers5[]='Content-Length: '.strlen($data5).'';
  $headers5[]='Host: mobile.vodafone.com.eg';
  $headers5[]='User-Agent: okhttp/3.12.1';
  $ch77 = curl_init();
  curl_setopt($ch77, CURLOPT_URL,$url5);
  curl_setopt($ch77, CURLOPT_POST, 1);
  curl_setopt($ch77, CURLOPT_POSTFIELDS,$data5);
  curl_setopt($ch77, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch77, CURLOPT_HTTPHEADER,$headers5);
  curl_setopt($ch77, CURLOPT_SSL_VERIFYPEER, false);
  $output66= curl_exec ($ch77);
  curl_close($ch77);
  $json = json_decode($output66,true);
  if (isset($json["access_token"]))
  {
  $a=$json['access_token'];
  $nodes = array();
  $data='{"channel":{"name":"MobileApp"},"orderItem":[{"action":"add","product":{"characteristic":[{"name":"ExecutionType","value":"Sync"},{"name":"LangId","value":"en"}],"relatedParty":[{"id":"'.$number.'","name":"MSISDN","role":"Subscriber"}],"id":"Vodafone_Plus_WatchIt_Monthly","@type":"MI"}}],"@type":"MIProfile"}';
  $headers[]='api-host: ProductOrderingManagementHost';
  $headers[]='useCase: MIProfile';
  $headers[]='Accept: application/json';
  $headers[]='access-token:'.$a;
  $headers[]='msisdn:'.$number;
  $headers[]='Accept-Language: ar';
  $headers[]='Content-Type: application/json; charset=UTF-8';
  $headers[]='Content-Length:'.strlen($data).'';
  $headers[]='Host: mobile.vodafone.com.eg';
  $headers[]='Connection: Keep-Alive';
  $headers[]='User-Agent: okhttp/3.12.1';
  for ($x =0;$x<10;$x++){
    array_push($nodes,"https://mobile.vodafone.com.eg/services/dxl/pom/productOrder");
  }
  $node_count = count($nodes);
  $curl_arr = array();
  $master = curl_multi_init();
  for($i = 0; $i < $node_count; $i++)
  {
    $url =$nodes[$i];
    $curl_arr[$i] = curl_init($url);
    curl_setopt($curl_arr[$i], CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl_arr[$i], CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl_arr[$i], CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl_arr[$i],CURLOPT_POST,true);
    curl_setopt($curl_arr[$i], CURLOPT_POSTFIELDS,$data);
    curl_setopt($curl_arr[$i], CURLOPT_HTTPHEADER,$headers);
    curl_multi_add_handle($master, $curl_arr[$i]);
  }

  do {
    curl_multi_exec($master,$running);
  } while($running > 0);
  for($i = 0; $i < $node_count; $i++)
  {
    $results = curl_multi_getcontent( $curl_arr[$i]);
  }
echo $results;
    echo "Completed";
  }
  else{
    echo "error";
  }
?>