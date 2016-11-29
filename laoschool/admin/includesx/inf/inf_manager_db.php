<?php
include("../../config.php");
$type = $_REQUEST['type'];
$time = time();

$headers = array();
$auth_key = $_SESSION[$config_session]['auth_key'];
$headers[] = "auth_key: $auth_key";
$headers[] = "api_key: TEST_API_KEY";
$userdata = callapi($headers,'','','api/users/myprofile');
$userdatas = explode("\n",$userdata['output']);
$myprofile = json_decode($userdatas[14]);

$infdata = callapi($headers,'','',"non-secure/schools/$myprofile->school_id");
$infdatas = explode("\n",$infdata['output']);
$schooldata = json_decode($infdatas[14]);
//print_r($schooldata);

$prvdata = callapi($headers,'','',"api/sys/sys_province");
$prvdatas = explode("\n",$prvdata['output']);
$provincedata = json_decode($prvdatas[14]);

$dgrdata = callapi($headers,'','',"api/sys/sys_degree");
$dgrdatas = explode("\n",$dgrdata['output']);
$degreedata = json_decode($dgrdatas[14]);
//print_r($degreedata);

foreach($degreedata->messageObject->list as $list){
	if($list->id == $schooldata->degree){
		$schooldata->degreetext = $list->sval;
		break;
	}
}

foreach($provincedata->messageObject->list as $list){
	if($list->id == $schooldata->prov_city){
		$schooldata->prov_citytext = $list->sval;
		break;
	}
}			
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
						<span class="skills">ອຳ​ນວຍ​ການ: <?php echo $schooldata->principal ?></span>
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
                    <p><b>ອຳ​ນວຍ​ການ:</b> <?php echo $schooldata->principal ?></p>
					<p><b>Degree:</b> <?php echo $schooldata->degreetext ?></p>
                    <p><b>Found Date:</b> <?php echo $schooldata->found_dt ?></p>
                    <p><b>Website:</b> <?php echo $schooldata->url ?></p>
                    <p><b>Phone:</b> <?php echo $schooldata->phone ?></p>
                </div>
                <div class="col-lg-4">
					<p><b>ທີ່​ຢູ່ 1:</b> <?php echo $schooldata->addr1 ?></p>
					<p><b>ທີ່​ຢູ່ 2:</b> <?php echo $schooldata->addr2 ?></p>
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
