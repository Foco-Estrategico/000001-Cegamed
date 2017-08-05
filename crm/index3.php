<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Charts | Nifty - Responsive admin template.</title>


    <!--STYLESHEET-->
    <!--=================================================-->



    <!--Bootstrap Stylesheet [ REQUIRED ]-->
    <link href="../css/bootstrap.min.css" rel="stylesheet">


    <!--Nifty Stylesheet [ REQUIRED ]-->
    <link href="../css/nifty.min.css" rel="stylesheet">

    <!--Nifty Premium Icon [ DEMO ]-->
    <link href="../css/demo/nifty-demo-icons.min.css" rel="stylesheet">

    
    <!--Font Awesome [ OPTIONAL ]-->
    <link href="../plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">


    <!--Switchery [ OPTIONAL ]-->
    <link href="../plugins/switchery/switchery.min.css" rel="stylesheet">


    <!--Bootstrap Select [ OPTIONAL ]-->
    <link href="../plugins/bootstrap-select/bootstrap-select.min.css" rel="stylesheet">


    <!--Morris.js [ OPTIONAL ]-->
    <link href="../plugins/morris-js/morris.min.css" rel="stylesheet">


    <!--Demo [ DEMONSTRATION ]-->
    <link href="../css/demo/nifty-demo.min.css" rel="stylesheet">




    <!--SCRIPT-->
    <!--=================================================-->

    <!--Page Load Progress Bar [ OPTIONAL ]-->
    <link href="../plugins/pace/pace.min.css" rel="stylesheet">
    <script src="../plugins/pace/pace.min.js"></script>


    
    <!--

    REQUIRED
    You must include this in your project.

    RECOMMENDED
    This category must be included but you may modify which plugins or components which should be included in your project.

    OPTIONAL
    Optional plugins. You may choose whether to include it in your project or not.

    DEMONSTRATION
    This is to be removed, used for demonstration purposes only. This category must not be included in your project.

    SAMPLE
    Some script samples which explain how to initialize plugins or components. This category should not be included in your project.


    Detailed information and more samples can be found in the document.

    -->
        
</head>

<!--TIPS-->
<!--You may remove all ID or Class names which contain "demo-", they are only used for demonstration. -->

<body>
    <div id="container" class="effect mainnav-sm">
        
        <!--NAVBAR-->
        <!--===================================================-->
        <header id="navbar">
            <div id="navbar-container" class="boxed">

                <!--Brand logo & name-->
                <!--================================-->
                <div class="navbar-header">
                    <a href="index.html" class="navbar-brand">
                        <img src="img/logo.png" alt="Nifty Logo" class="brand-icon">
                        <div class="brand-title">
                            <span class="brand-text">Nifty</span>
                        </div>
                    </a>
                </div>
                <!--================================-->
                <!--End brand logo & name-->


                <!--Navbar Dropdown-->
                <!--================================-->
                <div class="navbar-content clearfix">
                    <ul class="nav navbar-top-links pull-left">

                        <!--Navigation toogle button-->
                        <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                        <li class="tgl-menu-btn">
                            <a class="mainnav-toggle" href="#">
                                <i class="pli-view-list"></i>
                            </a>
                        </li>
                        <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                        <!--End Navigation toogle button-->



                        <!--Notification dropdown-->
                        <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                        <li class="dropdown">
                            <a href="#" data-toggle="dropdown" class="dropdown-toggle">
                                <i class="pli-bell"></i>
                                <span class="badge badge-header badge-danger"></span>
                            </a>

                            <!--Notification dropdown menu-->
                            <div class="dropdown-menu dropdown-menu-md">
                                <div class="pad-all bord-btm">
                                    <p class="text-lg text-semibold mar-no">You have 9 notifications.</p>
                                </div>
                                <div class="nano scrollable">
                                    <div class="nano-content">
                                        <ul class="head-list">

                                            <!-- Dropdown list-->
                                            <li>
                                                <a href="#">
                                                    <div class="clearfix">
                                                        <p class="pull-left">Database Repair</p>
                                                        <p class="pull-right">70%</p>
                                                    </div>
                                                    <div class="progress progress-sm">
                                                        <div style="width: 70%;" class="progress-bar">
                                                            <span class="sr-only">70% Complete</span>
                                                        </div>
                                                    </div>
                                                </a>
                                            </li>

                                            <!-- Dropdown list-->
                                            <li>
                                                <a href="#">
                                                    <div class="clearfix">
                                                        <p class="pull-left">Upgrade Progress</p>
                                                        <p class="pull-right">10%</p>
                                                    </div>
                                                    <div class="progress progress-sm">
                                                        <div style="width: 10%;" class="progress-bar progress-bar-warning">
                                                            <span class="sr-only">10% Complete</span>
                                                        </div>
                                                    </div>
                                                </a>
                                            </li>

                                            <!-- Dropdown list-->
                                            <li>
                                                <a class="media" href="#">
                                            <span class="badge badge-success pull-right">90%</span>
                                                    <div class="media-left">
                                                        <i class="pli-data-settings icon-2x"></i>
                                                    </div>
                                                    <div class="media-body">
                                                        <div class="text-nowrap">HDD is full</div>
                                                        <small class="text-muted">50 minutes ago</small>
                                                    </div>
                                                </a>
                                            </li>

                                            <!-- Dropdown list-->
                                            <li>
                                                <a class="media" href="#">
                                                    <div class="media-left">
                                                        <i class="pli-file-edit icon-2x"></i>
                                                    </div>
                                                    <div class="media-body">
                                                        <div class="text-nowrap">Write a news article</div>
                                                        <small class="text-muted">Last Update 8 hours ago</small>
                                                    </div>
                                                </a>
                                            </li>

                                            <!-- Dropdown list-->
                                            <li>
                                                <a class="media" href="#">
                                            <span class="label label-danger pull-right">New</span>
                                                    <div class="media-left">
                                                        <i class="pli-speech-bubble-7 icon-2x"></i>
                                                    </div>
                                                    <div class="media-body">
                                                        <div class="text-nowrap">Comment Sorting</div>
                                                        <small class="text-muted">Last Update 8 hours ago</small>
                                                    </div>
                                                </a>
                                            </li>

                                            <!-- Dropdown list-->
                                            <li>
                                                <a class="media" href="#">
                                                    <div class="media-left">
                                                        <i class="pli-add-user-plus-star icon-2x"></i>
                                                    </div>
                                                    <div class="media-body">
                                                        <div class="text-nowrap">New User Registered</div>
                                                        <small class="text-muted">4 minutes ago</small>
                                                    </div>
                                                </a>
                                            </li>
                                            <?php/*
                                                print message_dropdown("av4.png", "Lucy", "30 minutes ago");
                                                print message_dropdown("av3.png", "Jackson", "40 minutes ago");
                                                print notification_dropdown('icon-circle bg-danger', 'fa-hdd-o', 'HDD is full', '50 minutes ago', 'badge badge-success', '90%');
                                                print notification_dropdown('bg-info', 'fa-file-word-o', 'Write a news article', 'Last Update 8 hours ago');
                                                print notification_dropdown('bg-purple', 'fa-comment', 'Comment Sorting', 'Last Update 8 hours ago', 'label label-danger', 'New');
                                                print notification_dropdown('bg-success', 'fa-user', 'New User Registered', '4 minutes ago');
                                                print message_dropdown("av3.png", "Jackson", "Yesterday");
                                            */?>
                                            <!-- Dropdown list-->
                                            <li class="bg-gray">
                                                <a class="media" href="#">
                                                    <div class="media-left">
                                                        <img class="img-circle img-sm" alt="Profile Picture" src="img/av4.png">
                                                    </div>
                                                    <div class="media-body">
                                                        <div class="text-nowrap">Lucy sent you a message</div>
                                                        <small class="text-muted">30 minutes ago</small>
                                                    </div>
                                                </a>
                                            </li>

                                            <!-- Dropdown list-->
                                            <li class="bg-gray">
                                                <a class="media" href="#">
                                                    <div class="media-left">
                                                        <img class="img-circle img-sm" alt="Profile Picture" src="img/av3.png">
                                                    </div>
                                                    <div class="media-body">
                                                        <div class="text-nowrap">Jackson sent you a message</div>
                                                        <small class="text-muted">40 minutes ago</small>
                                                    </div>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <!--Dropdown footer-->
                                <div class="pad-all bord-top">
                                    <a href="#" class="btn-link text-dark box-block">
                                        <i class="fa fa-angle-right fa-lg pull-right"></i>Show All Notifications
                                    </a>
                                </div>
                            </div>
                        </li>
                        <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                        <!--End notifications dropdown-->



                        <!--Mega dropdown-->
                        <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                        <li class="mega-dropdown">
                            <a href="#" class="mega-dropdown-toggle">
                                <i class="pli-layout-grid"></i>
                            </a>
                            <div class="dropdown-menu mega-dropdown-menu">
                                <div class="clearfix">
                                    <div class="col-sm-12 col-md-3">

                                        <!--Mega menu widget-->
                                        <div class="text-center bg-purple pad-all">
                                            <h3 class="text-thin mar-no">Weekend shopping</h3>
                                            <div class="pad-ver box-inline">
                                                <span class="icon-wrap icon-wrap-lg icon-circle bg-trans-light">
                                                    <i class="psi-car-coins fa-4x"></i>
                                                </span>
                                            </div>
                                            <p class="pad-btm">
                                                Members get <span class="text-lg text-bold">50%</span> more points. Lorem ipsum dolor sit amet!
                                            </p>
                                            <a href="#" class="btn btn-purple">Learn More...</a>
                                        </div>

                                    </div>
                                    <div class="col-sm-4 col-md-3">

                                        <!--Mega menu list-->
                                        <ul class="list-unstyled">
									        <li class="dropdown-header">Pages</li>
									        <li><a href="#">Profile</a></li>
									        <li><a href="#">Search Result</a></li>
									        <li><a href="#">FAQ</a></li>
									        <li><a href="#">Sreen Lock</a></li>
									        <li><a href="#" class="disabled">Disabled</a></li>
									        <li class="divider"></li>
									        <li class="dropdown-header">Icons</li>
									        <li><a href="#"><span class="pull-right badge badge-purple">479</span> Font Awesome</a></li>
									        <li><a href="#">Skycons</a></li>
                                        </ul>

                                    </div>
                                    <div class="col-sm-4 col-md-3">

                                        <!--Mega menu list-->
                                        <ul class="list-unstyled">
									        <li class="dropdown-header">Mailbox</li>
									        <li><a href="#"><span class="pull-right label label-danger">Hot</span>Indox</a></li>
									        <li><a href="#">Read Message</a></li>
									        <li><a href="#">Compose</a></li>
									        <li class="divider"></li>
									        <li class="dropdown-header">Featured</li>
									        <li><a href="#">Smart navigation</a></li>
									        <li><a href="#"><span class="pull-right badge badge-success">6</span>Exclusive plugins</a></li>
									        <li><a href="#">Lot of themes</a></li>
									        <li><a href="#">Transition effects</a></li>
                                        </ul>

                                    </div>
                                    <div class="col-sm-4 col-md-3">

                                        <!--Mega menu list-->
                                        <ul class="list-unstyled">
									        <li class="dropdown-header">Components</li>
									        <li><a href="#">Tables</a></li>
									        <li><a href="#">Charts</a></li>
									        <li><a href="#">Forms</a></li>
									        <li class="divider"></li>
                                            <li>
                                                <form role="form" class="form">
                                                    <div class="form-group">
                                                        <label class="dropdown-header" for="demo-megamenu-input">Newsletter</label>
                                                        <input id="demo-megamenu-input" type="email" placeholder="Enter email" class="form-control">
                                                    </div>
                                                    <button class="btn btn-primary btn-block" type="submit">Submit</button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                        <!--End mega dropdown-->

                    </ul>
                    <ul class="nav navbar-top-links pull-right">

                        <!--Language selector-->
                        <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                        <li class="dropdown">
                            <a id="demo-lang-switch" class="lang-selector dropdown-toggle" href="#" data-toggle="dropdown">
                                <span class="lang-selected">
                                    <img class="lang-flag" src="img/flags/united-kingdom.png" alt="English">
                                </span>
                            </a>

                            <!--Language selector menu-->
                            <ul class="head-list dropdown-menu">
						        <li>
						            <!--English-->
						            <a href="#" class="active">
						                <img class="lang-flag" src="img/flags/united-kingdom.png" alt="English">
						                <span class="lang-id">EN</span>
						                <span class="lang-name">English</span>
						            </a>
						        </li>
						        <li>
						            <!--France-->
						            <a href="#">
						                <img class="lang-flag" src="img/flags/france.png" alt="France">
						                <span class="lang-id">FR</span>
						                <span class="lang-name">Fran&ccedil;ais</span>
						            </a>
						        </li>
						        <li>
						            <!--Germany-->
						            <a href="#">
						                <img class="lang-flag" src="img/flags/germany.png" alt="Germany">
						                <span class="lang-id">DE</span>
						                <span class="lang-name">Deutsch</span>
						            </a>
						        </li>
						        <li>
						            <!--Italy-->
						            <a href="#">
						                <img class="lang-flag" src="img/flags/italy.png" alt="Italy">
						                <span class="lang-id">IT</span>
						                <span class="lang-name">Italiano</span>
						            </a>
						        </li>
						        <li>
						            <!--Spain-->
						            <a href="#">
						                <img class="lang-flag" src="img/flags/spain.png" alt="Spain">
						                <span class="lang-id">ES</span>
						                <span class="lang-name">Espa&ntilde;ol</span>
						            </a>
						        </li>
                            </ul>
                        </li>
                        <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                        <!--End language selector-->



                        <!--User dropdown-->
                        <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                        <li id="dropdown-user" class="dropdown">
                            <a href="#" data-toggle="dropdown" class="dropdown-toggle text-right">
                                <span class="pull-right">
                                    <img class="img-circle img-user media-object" src="img/av1.png" alt="Profile Picture">
                                </span>
                                <div class="username hidden-xs">John Doe</div>
                            </a>


                            <div class="dropdown-menu dropdown-menu-md dropdown-menu-right panel-default">

                                <!-- Dropdown heading  -->
                                <div class="pad-all bord-btm">
                                    <p class="text-lg text-semibold mar-btm">750Gb of 1,000Gb Used</p>
                                    <div class="progress progress-sm">
                                        <div class="progress-bar" style="width: 70%;">
                                            <span class="sr-only">70%</span>
                                        </div>
                                    </div>
                                </div>


                                <!-- User dropdown menu -->
                                <ul class="head-list">
                                    <li>
                                        <a href="#">
                                            <i class="pli-male icon-lg icon-fw"></i> Profile
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <span class="badge badge-danger pull-right">9</span>
                                            <i class="pli-mail icon-lg icon-fw"></i> Messages
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <span class="label label-success pull-right">New</span>
                                            <i class="pli-gear icon-lg icon-fw"></i> Settings
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <i class="pli-information icon-lg icon-fw"></i> Help
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <i class="pli-computer-secure icon-lg icon-fw"></i> Lock screen
                                        </a>
                                    </li>
                                </ul>

                                <!-- Dropdown footer -->
                                <div class="pad-all text-right">
                                    <a href="pages-login.html" class="btn btn-primary">
                                        <i class="pli-unlock"></i> Logout
                                    </a>
                                </div>
                            </div>
                        </li>
                        <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                        <!--End user dropdown-->

                    </ul>
                </div>
                <!--================================-->
                <!--End Navbar Dropdown-->

            </div>
        </header>
        <!--===================================================-->
        <!--END NAVBAR-->

        <div class="boxed">

            <!--CONTENT CONTAINER-->
            <!--===================================================-->
            <div id="content-container">
                
                <!--Page Title-->
                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                <div id="page-title">
                    <h1 class="page-header text-overflow">Charts</h1>

                    <!--Searchbox-->
                    <div class="searchbox">
                        <div class="input-group custom-search-form">
                            <input type="text" class="form-control" placeholder="Search..">
                            <span class="input-group-btn">
                                <button class="text-muted" type="button"><i class="fa fa-search"></i></button>
                            </span>
                        </div>
                    </div>
                </div>
                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                <!--End page title-->

                <!--Page content-->
                <!--===================================================-->
                <div id="page-content">
                    
					<div class="row">
						<div class="eq-height">
							<div class="col-md-12 col-lg-4 eq-box-lg">
								<div class="panel">
									<div class="panel-heading">
										<div class="panel-control panel-control-component">
											<small class="text-muted">Auto update</small>
											<input type="checkbox" data-size="switchery-md" id="demo-auto-gauge">
										</div>
										<h3 class="panel-title">Gauge</h3>
									</div>
					
									<!-- Gauge JS -->
									<!--===================================================-->
									<div class="panel-body">
										<div class="row">
                        <div class="col-sm-6 col-lg-3">
                    
                            <!--Sparkline Area Chart-->
                            <div class="panel panel-success panel-colorful text-center">
                                <div class="panel-body">
                    
                                    <!--Placeholder-->
                                    <div id="demo-sparkline-area"></div>
                    
                                </div>
                                <div class="bg-light pad-ver">
                                    <h4 class="text-thin">Sparkline</h4> Area Chart
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                    
                            <!--Sparkline Line Chart-->
                            <div class="panel panel-info panel-colorful text-center">
                                <div class="panel-body">
                    
                                    <!--Placeholder-->
                                    <div id="demo-sparkline-line"></div>
                    
                                </div>
                                <div class="bg-light pad-ver">
                                    <h4 class="text-thin">Sparkline</h4> Line Chart
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                    
                            <!--Sparkline Bar Chart-->
                            <div class="panel panel-purple panel-colorful text-center">
                                <div class="panel-body">
                    
                                    <!--Placeholder-->
                                    <div id="demo-sparkline-bar" class="box-inline"></div>
                    
                                </div>
                                <div class="bg-light pad-ver">
                                    <h4 class="text-thin">Sparkline</h4> Bar Chart
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                    
                            <!--Sparkline Pie Chart-->
                            <div class="panel panel-mint panel-colorful text-center">
                                <div class="panel-body">
                    
                                    <!--Placeholder-->
                                    <div id="demo-sparkline-pie" class="box-inline "></div>
                                </div>
                                <div class="bg-light pad-ver">
                                    <h4 class="text-thin">Sparkline</h4> Pie chart
                                </div>
                            </div>
                        </div>
                    </div>
									</div>
									<!--===================================================-->
									<!-- End Gauge JS -->
					
								</div>
							</div>
						</div>
					</div>
					<br>
					<h3 class="text-thin">Sparkline</h3>
					<hr>
					
					<br>
					<hr>
					
					<!--Morris Charts -->
					<!--===================================================-->
				
				
					<!--===================================================-->
					<!-- End Morris Charts -->
					
					
					<!-- Flot Charts -->
					
					<!--===================================================-->
					<!-- End Flot Charts -->
					
					
					
                </div>
                <!--===================================================-->
                <!--End page content-->


            </div>
            <!--===================================================-->
            <!--END CONTENT CONTAINER-->


            
            <!--MAIN NAVIGATION-->
            <!--===================================================-->
            <nav id="mainnav-container">
                <div id="mainnav">

                    <!--Shortcut buttons-->
                    <!--================================-->
                    <div id="mainnav-shortcut">
                        <ul class="list-unstyled">
                
                            <li class="col-xs-4" data-content="Page Alerts">
                               
                            </li>
                        </ul>
                    </div>
                    <!--================================-->
                    <!--End shortcut buttons-->


                    <!--Menu-->
                    <!--================================-->
                    <div id="mainnav-menu-wrap">
                        <div class="nano">
                            <div class="nano-content">
                                <ul id="mainnav-menu" class="list-group">
                        
                                    <!--Category name-->
                                    <li class="list-header">Menú Principal</li>
                        
                                    <!--Menu list item-->
                               
                        
                                    <!--Menu list item-->
                                    
                                     <li>
                                        <a href="#" class="active-link">
                                            <i class="psi-phone-2" ></i>
                                            <span class="menu-title">CRM</span>
                                            <i class="arrow"></i>
                                        </a>
                        
                                        <!--Submenu-->
                                        <ul class="collapse in">
                                             <li class="active-link"><a href="index.php">Ventana Dial</a></li>
                        
                                        </ul>
                                    </li>


                            </div>
                        </div>
                    </div>
                    <!--================================-->
                    <!--End menu-->

                </div>
            </nav>
            <!--===================================================-->
            <!--END MAIN NAVIGATION-->
            
            <!--ASIDE-->
            <!--===================================================-->
            <aside id="aside-container">
                <div id="aside">
                    <div class="nano">
                        <div class="nano-content">
                            
                            <!--Nav tabs-->
                            <!--================================-->
                            <ul class="nav nav-tabs nav-justified">
                                <li class="active">
                                    <a href="#demo-asd-tab-1" data-toggle="tab">
                                        <i class="pli-speech-bubble-7"></i>
                                        <span class="badge badge-purple">7</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#demo-asd-tab-2" data-toggle="tab">
                                        <i class="pli-information"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="#demo-asd-tab-3" data-toggle="tab">
                                        <i class="pli-wrench"></i>
                                    </a>
                                </li>
                            </ul>
                            <!--================================-->
                            <!--End nav tabs-->



                            <!-- Tabs Content -->
                            <!--================================-->
                            <div class="tab-content">

                                <!--First tab (Contact list)-->
                                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                                <div class="tab-pane fade in active" id="demo-asd-tab-1">
                                    <h4 class="pad-all">
                                        <span class="pull-right badge badge-warning">3</span> Family
                                    </h4>

                                    <!--Family-->
                                    <div class="list-group bg-trans">
							            <a href="#" class="list-group-item">
							                <div class="media-left">
							                    <img class="img-circle img-xs" src="img/av2.png" alt="Profile Picture">
							                </div>
							                <div class="media-body">
							                    <p class="mar-no">Stephen Tran</p>
							                    <small class="text-muted">Availabe</small>
							                </div>
							            </a>
							            <a href="#" class="list-group-item">
							                <div class="media-left">
							                    <img class="img-circle img-xs" src="img/av4.png" alt="Profile Picture">
							                </div>
							                <div class="media-body">
							                    <p class="mar-no">Brittany Meyer</p>
							                    <small class="text-muted">I think so</small>
							                </div>
							            </a>
							            <a href="#" class="list-group-item">
							                <div class="media-left">
							                    <img class="img-circle img-xs" src="img/av3.png" alt="Profile Picture">
							                </div>
							                <div class="media-body">
							                    <p class="mar-no">Donald Brown</p>
							                    <small class="text-muted">Lorem ipsum dolor sit amet.</small>
							                </div>
							            </a>
							            <a href="#" class="list-group-item">
							                <div class="media-left">
							                    <img class="img-circle img-xs" src="img/av5.png" alt="Profile Picture">
							                </div>
							                <div class="media-body">
							                    <p class="mar-no">Betty Murphy</p>
							                    <small class="text-muted">Bye</small>
							                </div>
							            </a>
                                    </div>

                                    <hr>
                                    <h4 class="pad-hor">
                                        <span class="pull-right badge badge-success">Offline</span> Friends
                                    </h4>

                                    <!--Works-->
                                    <div class="list-group bg-trans">
                                        <a href="#" class="list-group-item">
                                            <span class="badge badge-purple badge-icon badge-fw pull-left"></span> Joey K. Greyson
                                        </a>
                                        <a href="#" class="list-group-item">
                                            <span class="badge badge-info badge-icon badge-fw pull-left"></span> Andrea Branden
                                        </a>
                                        <a href="#" class="list-group-item">
                                            <span class="badge badge-success badge-icon badge-fw pull-left"></span> Johny Juan
                                        </a>
                                        <a href="#" class="list-group-item">
                                            <span class="badge badge-danger badge-icon badge-fw pull-left"></span> Susan Sun
                                        </a>
                                    </div>


                                    <hr>
                                    <h4 class="pad-hor">News</h4>

                                    <div class="pad-hor">
                                        <p class="text-muted">Lorem ipsum dolor sit amet, consectetuer
                                            <a data-title="45%" class="add-tooltip text-semibold" href="#">adipiscing elit</a>, sed diam nonummy nibh. Lorem ipsum dolor sit amet.
                                        </p>
                                        <small class="text-muted"><em>Last Update : Des 12, 2014</em></small>
                                    </div>


                                </div>
                                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                                <!--End first tab (Contact list)-->


                                <!--Second tab (Custom layout)-->
                                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                                <div class="tab-pane fade" id="demo-asd-tab-2">

                                    <!--Monthly billing-->
                                    <div class="pad-all">
                                        <h4>Billing &amp; reports</h4>
                                        <p class="text-muted">Get <strong>$5.00</strong> off your next bill by making sure your full payment reaches us before August 5, 2016.</p>
                                        <hr>
                                        <span class="text-semibold">Amount Due On</span>
                                        <p>August 17, 2016</p>
                                        <p class="text-2x text-thin">$83.09</p>
                                        <button class="btn btn-block btn-success mar-top">Pay Now</button>
                                    </div>


                                    <hr>

                                    <h4 class="pad-hor">Additional Actions</h4>

                                    <!--Simple Menu-->
                                    <div class="list-group bg-trans">
                                        <a href="#" class="list-group-item"><i class="pli-information icon-lg icon-fw"></i> Service Information</a>
                                        <a href="#" class="list-group-item"><i class="pli-mine icon-lg icon-fw"></i> Usage Profile</a>
                                        <a href="#" class="list-group-item"><span class="label label-info pull-right">New</span><i class="pli-credit-card-2 icon-lg icon-fw"></i> Payment Options</a>
                                        <a href="#" class="list-group-item"><i class="pli-support icon-lg icon-fw"></i> Message Center</a>
                                    </div>


                                    <hr>

                                    <div class="text-center">
                                        <div><i class="pli-old-telephone icon-3x"></i></div>
                                        Questions?
                                        <p class="text-lg text-semibold"> (415) 234-53454 </p>
                                        <small><em>We are here 24/7</em></small>
                                    </div>
                                </div>
                                <!--End second tab (Custom layout)-->
                                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->


                                <!--Third tab (Settings)-->
                                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                                <div class="tab-pane fade" id="demo-asd-tab-3">
                                    <ul class="list-group bg-trans">
                                        <li class="pad-top list-header">
                                            <h4>Account Settings</h4>
                                        </li>
                                        <li class="list-group-item">
                                            <div class="pull-right">
                                                <input class="demo-switch" type="checkbox" checked>
                                            </div>
                                            <p>Show my personal status</p>
                                            <small class="text-muted">Lorem ipsum dolor sit amet, consectetuer adipiscing elit.</small>
                                        </li>
                                        <li class="list-group-item">
                                            <div class="pull-right">
                                                <input class="demo-switch" type="checkbox" checked>
                                            </div>
                                            <p>Show offline contact</p>
                                            <small class="text-muted">Lorem ipsum dolor sit amet, consectetuer adipiscing elit.</small>
                                        </li>
                                        <li class="list-group-item">
                                            <div class="pull-right">
                                                <input class="demo-switch" type="checkbox">
                                            </div>
                                            <p>Invisible mode </p>
                                            <small class="text-muted">Lorem ipsum dolor sit amet, consectetuer adipiscing elit.</small>
                                        </li>
                                    </ul>


                                    <hr>

                                    <ul class="list-group pad-btm bg-trans">
                                        <li class="list-header"><h4>Public Settings</h4></li>
                                        <li class="list-group-item">
                                            <div class="pull-right">
                                                <input class="demo-switch" type="checkbox" checked>
                                            </div>
                                            Online status
                                        </li>
                                        <li class="list-group-item">
                                            <div class="pull-right">
                                                <input class="demo-switch" type="checkbox" checked>
                                            </div>
                                            Show offline contact
                                        </li>
                                        <li class="list-group-item">
                                            <div class="pull-right">
                                                <input class="demo-switch" type="checkbox">
                                            </div>
                                            Show my device icon
                                        </li>
                                    </ul>



                                    <hr>

                                    <h4 class="pad-hor">Task Progress</h4>
                                    <div class="pad-all">
                                        <p>Upgrade Progress</p>
                                        <div class="progress progress-sm">
                                            <div class="progress-bar progress-bar-success" style="width: 15%;"><span class="sr-only">15%</span></div>
                                        </div>
                                        <small class="text-muted">15% Completed</small>
                                    </div>
                                    <div class="pad-hor">
                                        <p>Database</p>
                                        <div class="progress progress-sm">
                                            <div class="progress-bar progress-bar-danger" style="width: 75%;"><span class="sr-only">75%</span></div>
                                        </div>
                                        <small class="text-muted">17/23 Database</small>
                                    </div>

                                </div>
                                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                                <!--Third tab (Settings)-->

                            </div>
                        </div>
                    </div>
                </div>
            </aside>
            <!--===================================================-->
            <!--END ASIDE-->

        </div>

        

        <!-- FOOTER -->
        <!--===================================================-->
        <footer id="footer">

            <!-- Visible when footer positions are fixed -->
            <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
            <div class="show-fixed pull-right">
                <ul class="footer-list list-inline">
                    <li>
                        <p class="text-sm">SEO Proggres</p>
                        <div class="progress progress-sm progress-light-base">
                            <div style="width: 80%" class="progress-bar progress-bar-danger"></div>
                        </div>
                    </li>

                    <li>
                        <p class="text-sm">Online Tutorial</p>
                        <div class="progress progress-sm progress-light-base">
                            <div style="width: 80%" class="progress-bar progress-bar-primary"></div>
                        </div>
                    </li>
                    <li>
                        <button class="btn btn-sm btn-dark btn-active-success">Checkout</button>
                    </li>
                </ul>
            </div>



            <!-- Visible when footer positions are static -->
            <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
            <div class="hide-fixed pull-right pad-rgt">Currently v2.4.1</div>



            <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
            <!-- Remove the class name "show-fixed" and "hide-fixed" to make the content always appears. -->
            <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->

            <p class="pad-lft">&#0169; 2016 Your Company</p>



        </footer>
        <!--===================================================-->
        <!-- END FOOTER -->


        <!-- SCROLL TOP BUTTON -->
        <!--===================================================-->
        <button id="scroll-top" class="btn"><i class="fa fa-chevron-up"></i></button>
        <!--===================================================-->



    </div>
    <!--===================================================-->
    <!-- END OF CONTAINER -->


    
    
    <!-- SETTINGS - DEMO PURPOSE ONLY -->
    <!--===================================================-->
    <div id="demo-set" class="demo-set">
        <div class="demo-set-body bg-light">
            <div id="demo-set-alert"></div>
            <div class="nano" style="height:520px">
                <div class="nano-content">
                    <div class="panel mar-no">
                        <!--Panel heading-->
                        <div class="panel-heading bg-dark">
                            <div class="panel-control pull-left">
                                <ul class="nav nav-tabs text-lg">
                                    <li class="active"><a data-toggle="tab" href="#demo-settings-box-1" aria-expanded="true">Basic Layout</a></li>
                                    <li class=""><a data-toggle="tab" href="#demo-settings-box-2" aria-expanded="false">Sidebars</a></li>
                                    <li class=""><a data-toggle="tab" href="#demo-settings-box-3" aria-expanded="false">Header &amp; Footer</a></li>
                                    <li class=""><a data-toggle="tab" href="#demo-settings-box-4" aria-expanded="false">Color Schemes</a></li>
                                </ul>
                            </div>
                            <h3 class="panel-title">&nbsp;</h3>
                        </div>

                        <!--Panel body-->
                        <div class="panel-body">
                            <div class="tab-content">
                                <div id="demo-settings-box-1" class="tab-pane fade active in">
                                    <div class="row">
                                        <div class="col-xs-4 text-center">
                                            <img src="img/settings/animation.png">
                                        </div>
                                        <div class="col-xs-8">
                                            <table class="table mar-no">
                                                <thead>
                                                    <tr>
                                                        <th colspan="2"><p class="text-lg text-semibold text-primary">Animations</p></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td><p>Enable Animations</p></td>
                                                        <td class="text-right">
                                                            <div id="demo-anim">
                                                                <label class="form-checkbox form-icon active">
                                                                    <input type="checkbox" checked="">
                                                                </label>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <p>Transition effects</p>
                                                        </td>
                                                        <td class="text-right">
                                                            <select id="demo-ease">
                                                                <option value="effect" selected>ease (Default)</option>
                                                                <option value="easeInQuart">easeInQuart</option>
                                                                <option value="easeOutQuart">easeOutQuart</option>
                                                                <option value="easeInBack">easeInBack</option>
                                                                <option value="easeOutBack">easeOutBack</option>
                                                                <option value="easeInOutBack">easeInOutBack</option>
                                                                <option value="steps">Steps</option>
                                                                <option value="jumping">Jumping</option>
                                                                <option value="rubber">Rubber</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-4 text-center">
                                            <img src="img/settings/box-layout.png">
                                        </div>
                                        <div class="col-xs-8">
                                            <table class="table mar-no">
                                                <thead>
                                                    <tr>
                                                        <th colspan="2"><p class="text-lg text-semibold text-primary">Layout</p></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td><p>Boxed Layout</p></td>
                                                        <td class="text-right">
                                                            <label id="demo-box-lay" class="form-checkbox form-icon active">
                                                                <input type="checkbox">
                                                            </label>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div id="demo-settings-box-2" class="tab-pane fade">
                                    <div class="row">
                                        <div class="col-xs-4 text-center">
                                            <img src="img/settings/navigation.png">
                                        </div>
                                        <div class="col-xs-8">
                                            <table class="table mar-no">
                                                <thead>
                                                    <tr>
                                                        <th colspan="2"><p class="text-lg text-semibold text-primary">Navigation</p></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td><p>Fixed</p></td>
                                                        <td class="text-right">
                                                            <label id="demo-nav-fixed" class="form-checkbox form-icon mar-btm">
                                                                <input type="checkbox">
                                                            </label>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><p>Collapsed</p></td>
                                                        <td class="text-right">
                                                            <label id="demo-nav-coll" class="form-checkbox form-icon mar-btm">
                                                                <input type="checkbox">
                                                            </label>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><p>Off-Canvas Mode</p></td>
                                                        <td class="text-right">
                                                            <select id="demo-nav-offcanvas">
                                                                <option value="none" selected disabled="disabled">-- Select Mode --</option>
                                                                <option value="push">Push</option>
                                                                <option value="slide">Slide in on top</option>
                                                                <option value="reveal">Reveal</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-4 text-center">
                                            <img src="img/settings/aside.png">
                                        </div>
                                        <div class="col-xs-8">
                                            <table class="table mar-no">
                                                <thead>
                                                    <tr>
                                                        <th colspan="2"><p class="text-lg text-semibold text-primary">Aside</p></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td><p>Visible</p></td>
                                                        <td class="text-right">
                                                            <label id="demo-asd-vis" class="form-checkbox form-icon">
                                                                <input type="checkbox">
                                                            </label>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><p>Fixed</p></td>
                                                        <td class="text-right">
                                                            <label id="demo-asd-fixed" class="form-checkbox form-icon">
                                                                <input type="checkbox">
                                                            </label>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><p>Aside on the left side</p></td>
                                                        <td class="text-right">
                                                            <label id="demo-asd-align" class="form-checkbox form-icon">
                                                                <input type="checkbox">
                                                            </label>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><p>Bright Mode</p></td>
                                                        <td class="text-right">
                                                            <label id="demo-asd-themes" class="form-checkbox form-icon">
                                                                <input type="checkbox">
                                                            </label>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div id="demo-settings-box-3" class="tab-pane fade">
                                    <div class="row">
                                        <div class="col-xs-4 text-center">
                                            <img src="img/settings/header_and_footer.png">
                                        </div>
                                        <div class="col-xs-8">
                                            <table class="table mar-no">
                                                <thead>
                                                    <tr>
                                                        <th colspan="2"><p class="text-lg text-semibold text-primary">Header &amp; Footer</p></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td><p>Fixed Header</p></td>
                                                        <td class="text-right">
                                                            <label id="demo-navbar-fixed" class="form-checkbox form-icon">
                                                                <input type="checkbox">
                                                            </label>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><p>Fixed Footer</p></td>
                                                        <td class="text-right">
                                                            <label id="demo-footer-fixed" class="form-checkbox form-icon">
                                                                <input type="checkbox">
                                                            </label>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div id="demo-settings-box-4" class="tab-pane fade">
                                    <div id="demo-theme">
                                        <div class="row">
                                            <div class="col-xs-4 text-center">
                                                <img src="img/settings/scheme_a.png">
                                            </div>
                                            <div class="col-xs-8">
                                                <div class="demo-theme-btn">
                                                    <a href="#" class="demo-theme demo-a-light add-tooltip" data-theme="theme-light" data-type="a" data-title="(A). Light">
                                                        <div class="demo-theme-brand"></div>
                                                        <div class="demo-theme-head"></div>
                                                        <div class="demo-theme-nav"></div>
                                                    </a>
                                                    <a href="#" class="demo-theme demo-a-navy add-tooltip" data-theme="theme-navy" data-type="a" data-title="(A). Navy Blue">
                                                        <div class="demo-theme-brand"></div>
                                                        <div class="demo-theme-head"></div>
                                                        <div class="demo-theme-nav"></div>
                                                    </a>
                                                    <a href="#" class="demo-theme demo-a-ocean add-tooltip" data-theme="theme-ocean" data-type="a" data-title="(A). Ocean">
                                                        <div class="demo-theme-brand"></div>
                                                        <div class="demo-theme-head"></div>
                                                        <div class="demo-theme-nav"></div>
                                                    </a>
                                                    <a href="#" class="demo-theme demo-a-lime add-tooltip" data-theme="theme-lime" data-type="a" data-title="(A). Lime">
                                                        <div class="demo-theme-brand"></div>
                                                        <div class="demo-theme-head"></div>
                                                        <div class="demo-theme-nav"></div>
                                                    </a>
                                                    <a href="#" class="demo-theme demo-a-purple add-tooltip" data-theme="theme-purple" data-type="a" data-title="(A). Purple">
                                                        <div class="demo-theme-brand"></div>
                                                        <div class="demo-theme-head"></div>
                                                        <div class="demo-theme-nav"></div>
                                                    </a>
                                                    <a href="#" class="demo-theme demo-a-dust add-tooltip" data-theme="theme-dust" data-type="a" data-title="(A). Dust">
                                                        <div class="demo-theme-brand"></div>
                                                        <div class="demo-theme-head"></div>
                                                        <div class="demo-theme-nav"></div>
                                                    </a>
                                                    <a href="#" class="demo-theme demo-a-mint add-tooltip" data-theme="theme-mint" data-type="a" data-title="(A). Mint">
                                                        <div class="demo-theme-brand"></div>
                                                        <div class="demo-theme-head"></div>
                                                        <div class="demo-theme-nav"></div>
                                                    </a>
                                                    <a href="#" class="demo-theme demo-a-yellow add-tooltip" data-theme="theme-yellow" data-type="a" data-title="(A). Yellow">
                                                        <div class="demo-theme-brand"></div>
                                                        <div class="demo-theme-head"></div>
                                                        <div class="demo-theme-nav"></div>
                                                    </a>
                                                    <a href="#" class="demo-theme demo-a-well-red add-tooltip" data-theme="theme-well-red" data-type="a" data-title="(A). Well Red">
                                                        <div class="demo-theme-brand"></div>
                                                        <div class="demo-theme-head"></div>
                                                        <div class="demo-theme-nav"></div>
                                                    </a>
                                                    <a href="#" class="demo-theme demo-a-coffee add-tooltip" data-theme="theme-coffee" data-type="a" data-title="(A). Coffee">
                                                        <div class="demo-theme-brand"></div>
                                                        <div class="demo-theme-head"></div>
                                                        <div class="demo-theme-nav"></div>
                                                    </a>
                                                    <a href="#" class="demo-theme demo-a-prickly-pear add-tooltip" data-theme="theme-prickly-pear" data-type="a" data-title="(A). Prickly pear">
                                                        <div class="demo-theme-brand"></div>
                                                        <div class="demo-theme-head"></div>
                                                        <div class="demo-theme-nav"></div>
                                                    </a>
                                                    <a href="#" class="demo-theme demo-a-dark add-tooltip" data-theme="theme-dark" data-type="a" data-title="(A). Dark">
                                                        <div class="demo-theme-brand"></div>
                                                        <div class="demo-theme-head"></div>
                                                        <div class="demo-theme-nav"></div>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-4 text-center">
                                                <img src="img/settings/scheme_b.png">
                                            </div>
                                            <div class="col-xs-8">
                                                <div class="demo-theme-btn">
                                                    <a href="#" class="demo-theme demo-b-light add-tooltip" data-theme="theme-light" data-type="b" data-title="(B). Light">
                                                        <div class="demo-theme-brand"></div>
                                                        <div class="demo-theme-head"></div>
                                                        <div class="demo-theme-nav"></div>
                                                    </a>
                                                    <a href="#" class="demo-theme demo-b-navy add-tooltip" data-theme="theme-navy" data-type="b" data-title="(B). Navy Blue">
                                                        <div class="demo-theme-brand"></div>
                                                        <div class="demo-theme-head"></div>
                                                        <div class="demo-theme-nav"></div>
                                                    </a>
                                                    <a href="#" class="demo-theme demo-b-ocean add-tooltip" data-theme="theme-ocean" data-type="b" data-title="(B). Ocean">
                                                        <div class="demo-theme-brand"></div>
                                                        <div class="demo-theme-head"></div>
                                                        <div class="demo-theme-nav"></div>
                                                    </a>
                                                    <a href="#" class="demo-theme demo-b-lime add-tooltip" data-theme="theme-lime" data-type="b" data-title="(B). Lime">
                                                        <div class="demo-theme-brand"></div>
                                                        <div class="demo-theme-head"></div>
                                                        <div class="demo-theme-nav"></div>
                                                    </a>
                                                    <a href="#" class="demo-theme demo-b-purple add-tooltip" data-theme="theme-purple" data-type="b" data-title="(B). Purple">
                                                        <div class="demo-theme-brand"></div>
                                                        <div class="demo-theme-head"></div>
                                                        <div class="demo-theme-nav"></div>
                                                    </a>
                                                    <a href="#" class="demo-theme demo-b-dust add-tooltip" data-theme="theme-dust" data-type="b" data-title="(B). Dust">
                                                        <div class="demo-theme-brand"></div>
                                                        <div class="demo-theme-head"></div>
                                                        <div class="demo-theme-nav"></div>
                                                    </a>
                                                    <a href="#" class="demo-theme demo-b-mint add-tooltip" data-theme="theme-mint" data-type="b" data-title="(B). Mint">
                                                        <div class="demo-theme-brand"></div>
                                                        <div class="demo-theme-head"></div>
                                                        <div class="demo-theme-nav"></div>
                                                    </a>
                                                    <a href="#" class="demo-theme demo-b-yellow add-tooltip" data-theme="theme-yellow" data-type="b" data-title="(B). Yellow">
                                                        <div class="demo-theme-brand"></div>
                                                        <div class="demo-theme-head"></div>
                                                        <div class="demo-theme-nav"></div>
                                                    </a>
                                                    <a href="#" class="demo-theme demo-b-well-red add-tooltip" data-theme="theme-well-red" data-type="b" data-title="(B). Well red">
                                                        <div class="demo-theme-brand"></div>
                                                        <div class="demo-theme-head"></div>
                                                        <div class="demo-theme-nav"></div>
                                                    </a>
                                                    <a href="#" class="demo-theme demo-b-coffee add-tooltip" data-theme="theme-coffee" data-type="b" data-title="(B). Coofee">
                                                        <div class="demo-theme-brand"></div>
                                                        <div class="demo-theme-head"></div>
                                                        <div class="demo-theme-nav"></div>
                                                    </a>
                                                    <a href="#" class="demo-theme demo-b-prickly-pear add-tooltip" data-theme="theme-prickly-pear" data-type="b" data-title="(B). Prickly pear">
                                                        <div class="demo-theme-brand"></div>
                                                        <div class="demo-theme-head"></div>
                                                        <div class="demo-theme-nav"></div>
                                                    </a>
                                                    <a href="#" class="demo-theme demo-b-dark add-tooltip" data-theme="theme-dark" data-type="b" data-title="(B). Dark">
                                                        <div class="demo-theme-brand"></div>
                                                        <div class="demo-theme-head"></div>
                                                        <div class="demo-theme-nav"></div>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-4 text-center">
                                                <img src="img/settings/scheme_c.png">
                                            </div>
                                            <div class="col-xs-8">
                                                <div class="demo-theme-btn">
                                                    <a href="#" class="demo-theme demo-c-light add-tooltip" data-theme="theme-light" data-type="c" data-title="(C). Light">
                                                        <div class="demo-theme-brand"></div>
                                                        <div class="demo-theme-head"></div>
                                                        <div class="demo-theme-nav"></div>
                                                    </a>
                                                    <a href="#" class="demo-theme demo-c-navy add-tooltip" data-theme="theme-navy" data-type="c" data-title="(C). Navy Blue">
                                                        <div class="demo-theme-brand"></div>
                                                        <div class="demo-theme-head"></div>
                                                        <div class="demo-theme-nav"></div>
                                                    </a>
                                                    <a href="#" class="demo-theme demo-c-ocean add-tooltip" data-theme="theme-ocean" data-type="c" data-title="(C). Ocean">
                                                        <div class="demo-theme-brand"></div>
                                                        <div class="demo-theme-head"></div>
                                                        <div class="demo-theme-nav"></div>
                                                    </a>
                                                    <a href="#" class="demo-theme demo-c-lime add-tooltip" data-theme="theme-lime" data-type="c" data-title="(C). Lime">
                                                        <div class="demo-theme-brand"></div>
                                                        <div class="demo-theme-head"></div>
                                                        <div class="demo-theme-nav"></div>
                                                    </a>
                                                    <a href="#" class="demo-theme demo-c-purple add-tooltip" data-theme="theme-purple" data-type="c" data-title="(C). Purple">
                                                        <div class="demo-theme-brand"></div>
                                                        <div class="demo-theme-head"></div>
                                                        <div class="demo-theme-nav"></div>
                                                    </a>
                                                    <a href="#" class="demo-theme demo-c-dust add-tooltip" data-theme="theme-dust" data-type="c" data-title="(C). Dust">
                                                        <div class="demo-theme-brand"></div>
                                                        <div class="demo-theme-head"></div>
                                                        <div class="demo-theme-nav"></div>
                                                    </a>
                                                    <a href="#" class="demo-theme demo-c-mint add-tooltip" data-theme="theme-mint" data-type="c" data-title="(C). Mint">
                                                        <div class="demo-theme-brand"></div>
                                                        <div class="demo-theme-head"></div>
                                                        <div class="demo-theme-nav"></div>
                                                    </a>
                                                    <a href="#" class="demo-theme demo-c-yellow add-tooltip" data-theme="theme-yellow" data-type="c" data-title="(C). Yellow">
                                                        <div class="demo-theme-brand"></div>
                                                        <div class="demo-theme-head"></div>
                                                        <div class="demo-theme-nav"></div>
                                                    </a>
                                                    <a href="#" class="demo-theme demo-c-well-red add-tooltip" data-theme="theme-well-red" data-type="c" data-title="(C). Well Red">
                                                        <div class="demo-theme-brand"></div>
                                                        <div class="demo-theme-head"></div>
                                                        <div class="demo-theme-nav"></div>
                                                    </a>
                                                    <a href="#" class="demo-theme demo-c-coffee add-tooltip" data-theme="theme-coffee" data-type="c" data-title="(C). Coffee">
                                                        <div class="demo-theme-brand"></div>
                                                        <div class="demo-theme-head"></div>
                                                        <div class="demo-theme-nav"></div>
                                                    </a>
                                                    <a href="#" class="demo-theme demo-c-prickly-pear add-tooltip" data-theme="theme-prickly-pear" data-type="c" data-title="(C). Prickly pear">
                                                        <div class="demo-theme-brand"></div>
                                                        <div class="demo-theme-head"></div>
                                                        <div class="demo-theme-nav"></div>
                                                    </a>
                                                    <a href="#" class="demo-theme demo-c-dark add-tooltip" data-theme="theme-dark" data-type="c" data-title="(C). Dark">
                                                        <div class="demo-theme-brand"></div>
                                                        <div class="demo-theme-head"></div>
                                                        <div class="demo-theme-nav"></div>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="pad-top pad-hor bord-top clearfix">
                <div class="pull-right">
                    <button id="demo-reset-settings" class="btn btn-primary mar-btm">Reset Settings</button>
                </div>
                <p class="text-lg text-semibold">Theme Options</p>
                <p class="text-muted text-sm">* All settings will be saved automatically.</p>
            </div>
        </div>
        <button id="demo-set-btn" class="btn btn-sm"><i class="fa fa-cog fa-2x"></i></button>
    </div>
    <!--===================================================-->
    <!-- END SETTINGS -->

    
    <!--JAVASCRIPT-->
    <!--=================================================-->

    <!--jQuery [ REQUIRED ]-->
    <script src="../js/jquery-2.2.1.min.js"></script>


    <!--BootstrapJS [ RECOMMENDED ]-->
    <script src="../js/bootstrap.min.js"></script>


    <!--Fast Click [ OPTIONAL ]-->
    <script src="../plugins/fast-click/fastclick.min.js"></script>

    
    <!--Nifty Admin [ RECOMMENDED ]-->
    <script src="../js/nifty.min.js"></script>


    <!--Switchery [ OPTIONAL ]-->
    <script src="../plugins/switchery/switchery.min.js"></script>


    <!--Bootstrap Select [ OPTIONAL ]-->
    <script src="../plugins/bootstrap-select/bootstrap-select.min.js"></script>


    <!--Morris.js [ OPTIONAL ]-->
    <script src="../plugins/morris-js/morris.min.js"></script>
	<script src="../plugins/morris-js/raphael-js/raphael.min.js"></script>


    <!--Sparkline [ OPTIONAL ]-->
    <script src="../plugins/sparkline/jquery.sparkline.min.js"></script>


    <!--Flot Chart [ OPTIONAL ]-->
    <script src="../plugins/flot-charts/jquery.flot.min.js"></script>
	<script src="../plugins/flot-charts/jquery.flot.resize.min.js"></script>


    <!--Flot Pie Chart [ OPTIONAL ]-->
    <script src="../plugins/flot-charts/jquery.flot.pie.min.js"></script>


    <!--Gauge js [ OPTIONAL ]-->
    <script src="../plugins/gauge-js/gauge.min.js"></script>


    <!--Easy Pie Chart [ OPTIONAL ]-->
    <script src="../plugins/easy-pie-chart/jquery.easypiechart.min.js"></script>


    <!--Demo script [ DEMONSTRATION ]-->
    <script src="../js/demo/nifty-demo.min.js"></script>


    <!--Charts [ SAMPLE ]-->
    <script src="../js/demo/charts.js"></script>

    
    <!--

    REQUIRED
    You must include this in your project.

    RECOMMENDED
    This category must be included but you may modify which plugins or components which should be included in your project.

    OPTIONAL
    Optional plugins. You may choose whether to include it in your project or not.

    DEMONSTRATION
    This is to be removed, used for demonstration purposes only. This category must not be included in your project.

    SAMPLE
    Some script samples which explain how to initialize plugins or components. This category should not be included in your project.


    Detailed information and more samples can be found in the document.

    -->
        

</body>
</html>
