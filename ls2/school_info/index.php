<?php
function callapi($headers,$post,$get,$api){
	if(is_array($get)){
		$GETVARS = http_build_query($get);
		//echo $GETVARS;
	}
	
	//$url = "https://192.168.0.202:9443/laoschoolws/$api?$GETVARS";
	$url = "https://222.255.29.25:8443/laoschoolws/$api?$GETVARS";

	$ch = curl_init();
	
	curl_setopt($ch, CURLOPT_URL,$url);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

	//curl_setopt($ch, CURLOPT_SSLVERSION,5);
	
	if(is_array($headers)){	
		curl_setopt($ch, CURLOPT_HEADER, 1); 
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	}

	if($post!=''){
		//if(is_array($post)) $POSTVARS = http_build_query($post);
		//else $POSTVARS = $post;
		
		$POSTVARS = $post;
		//echo $POSTVARS;
		curl_setopt($ch, CURLOPT_POST,1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$POSTVARS);
	}
	
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			
	$output = curl_exec($ch);

	if(!curl_errno($ch)){
	 $info = curl_getinfo($ch);
	}else echo 'Erreur Curl : ' . curl_error($ch);

	list($headers, $response) = explode("\r\n\r\n", $output, 2);
	$headers = explode("\n", $headers);

	$auth_key = '';
	foreach($headers as $header){
		if (stripos($header, 'auth_key:') !== false) $auth_key = str_replace('auth_key: ','',$header); 
	}
	curl_close ($ch); 
	$data['http_code'] = $info['http_code'];
	$data['auth_key'] = $auth_key;
	$data['headers'] = $headers;
	$data['output'] = $output;
	return $data;
}


$type = $_REQUEST['type'];
$time = time();

$headers = array();
$headers[] = "api_key: WEB";

$school_id = $_REQUEST['school_id'];

$infdata = callapi($headers,'','',"non-secure/schools/$school_id");
$infdatas = end(explode("\n",$infdata['output']));
$schooldata = json_decode($infdatas);

//echo "non-secure/schools/$school_id";

//print_r($infdata);
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo $schooldata->title ?></title>

    <!-- Bootstrap Core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Theme CSS -->
    <link href="css/freelancer.min.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>


<body id="page-top" class="index">

    <!-- Header -->
    <header>
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
					<img class="img-responsive" src="img/profile.png" alt="">
                    <div class="intro-text">
                        <span class="name"><?php echo $schooldata->title ?></span>
                        <hr class="star-light">
						<span class="skills">Principal: <?php echo $schooldata->principal ?></span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- About Section -->
    <section class="success">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2>About</h2>
                    <hr class="star-light">
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-lg-offset-2">
                    <p><b>Principal:</b> <?php echo $schooldata->principal ?></p>
					<p><b>Degree:</b> <?php echo $schooldata->degreetext ?></p>
                    <p><b>Found Date:</b> <?php echo $schooldata->found_dt ?></p>
                    <p><b>Website:</b> <?php echo $schooldata->url ?></p>
                    <p><b>Phone:</b> <?php echo $schooldata->phone ?></p>
                </div>
                <div class="col-lg-4">
					<p><b>Address 1:</b> <?php echo $schooldata->addr1 ?></p>
					<p><b>Address 2:</b> <?php echo $schooldata->addr2 ?></p>
					<p><b>Pronvice:</b> <?php echo $schooldata->prov_citytext ?></p>
					<p><b>Ext:</b> <?php echo $schooldata->ext ?></p>
                    <p><b>Fax:</b> <?php echo $schooldata->fax ?></p>
                </div>
            </div>
        </div>
    </section>


  

    <!-- Scroll to Top Button (Only visible on small and extra-small screen sizes) -->
    <div class="scroll-top page-scroll hidden-sm hidden-xs hidden-lg hidden-md">
        <a class="btn btn-primary" href="#page-top">
            <i class="fa fa-chevron-up"></i>
        </a>
    </div>

  
    <!-- jQuery -->
    <script src="vendor/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Plugin JavaScript -->
    <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>

    <!-- Contact Form JavaScript -->
    <script src="js/jqBootstrapValidation.js"></script>
    <script src="js/contact_me.js"></script>

    <!-- Theme JavaScript -->
    <script src="js/freelancer.min.js"></script>

</body>

</html>
