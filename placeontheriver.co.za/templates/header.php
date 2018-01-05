	<!--    
    /**
     * Module name:	header.php
     *
     * Client:			Lewis Coleman, Place on the River - info@placeontheriver.co.za
     * Project:		Availability calendar
     * Author:			Rob Crothall - rob@crothall.co.za - +27 83 678 5055
     * Date Written:	2016-03-13
     * Date changed:
     * Copyright:		Creative Commons (CCO) license
     *
     * Purpose:		Configures HTML pages and displays header
     */  -->

<!DOCTYPE html>

<html>

    <head>
        <meta charset="utf-8"/>
        
        <link href="css/bootstrap.min.css" rel="stylesheet"/>
        <link href="css/bootstrap-theme.min.css" rel="stylesheet"/>
        <link href="css/styles.css" rel="stylesheet"/>

        <?php if (isset($title)): ?>
            <title>Place on the River: <?= htmlspecialchars($title) ?></title>
        <?php else: ?>
            <title>Place on the River</title>
        <?php endif ?>

        <script src="js/jquery-1.10.2.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/scripts.js"></script>

    </head>

    <body>

        <div class="container">

            <div id="top">
                <a href="/"><img alt="Place on the River" src="img/topbanner.jpg"/></a>
            </div>
				<div align="center">
		        <?php if (isset($_SESSION["username"])): ?>
   	         <?= "Welcome, " . htmlspecialchars($_SESSION["username"]) . "!" ?> &nbsp;&nbsp;&nbsp;
				  	<a href="availability.php">Availability</a> &nbsp;&nbsp;&nbsp;
              <!-- <a href="history.php">History</a> &nbsp;&nbsp;&nbsp; -->
               <a href="password.php">Password</a> &nbsp;&nbsp;&nbsp;
               <a href="register.php">Register</a> &nbsp;&nbsp;&nbsp;
               <a href="logout.php"><strong>Log out</strong></a>
      	  <?php else: ?>
         	   <a href="login.php">Login for Administration</a><br>
    	    <?php endif ?>
				</div>
            <div id="middle">
