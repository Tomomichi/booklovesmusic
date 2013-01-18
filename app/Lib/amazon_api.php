<?php

	function make_Request( $type, $key, $page = null ) {

		//共通変数のセット
		$Service = "Service=AWSECommerceService";
		$AWSAccessKeyId ="&AWSAccessKeyId=AKIAIINQX6GJFBSYBYYQ";
		$AssociateTag = "&AssociateTag=bookmentoring-22";
		$ResponseGroup = "&ResponseGroup=ItemAttributes,Images";
		$Version = "&Version=2011-08-01";
		$SearchIndex = "&SearchIndex=Books";
		$Operation = "&Operation=".$type;
		$Timestamp = "&Timestamp=" . str_replace('GMT','T',gmdate("Y-m-dTH:i:s")) . "Z";
		
		//表示ページ
		if(!$page){
			$page = 1;
		}
		$ItemPage = "&ItemPage=".$page;

		//個別変数の初期化
		$IdType = "";
		$ItemId = "";
		$Keywords = "";
		
		//個別変数のセット
		switch($type) {
			case "ItemLookup": 
				$IdType = "&IdType=ISBN";
				$ItemId = "&ItemId=".$key;
				break;
			case "ItemSearch":
				$Keywords = "&Keywords=".$key;
				break;
		}
		
		//リクエスト文の作成
		$request     = $Service . $AWSAccessKeyId . $AssociateTag . $Version . $Operation . $ResponseGroup . $SearchIndex . $IdType . $ItemId . $Keywords . $ItemPage . $Timestamp ;
		
		return $request;
	}


	function canonical_Str( $request ) {

	   $request = str_replace( ',', '%2C', $request );
	   $request = str_replace( ':', '%3A', $request );
	   $request = str_replace( '%7E', '~', $request );

	   $req_split = array();
	   $req_split = split("&", $request);
	   sort( $req_split );

	   $str_join = implode('&', $req_split );
	   return $str_join;
	}


	function get_Signature( $request, $aws_host ) {

	   $key = "mqZkmTA5vq5f2HpBy58Z7VNBrDEYr86deQuDU04y";

	   $prep01 = 'GET';
	   $prep02 = $aws_host;
	   $prep03 = '/onca/xml';
	   $str_to_sign = $prep01 . "\n" . $prep02 . "\n" . $prep03 . "\n". $request;

	   $algo = "sha256";
	   $hash_out  = hash_hmac( $algo, $str_to_sign, $key, true );
	   $signature = urlencode(base64_encode($hash_out));
	   return $signature;
	}
