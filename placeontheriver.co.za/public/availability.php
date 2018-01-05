	<!--    
    /**
     * Module name:	index.php
     *
     * Client:			Lewis Coleman, Place on the River - info@placeontheriver.co.za
     * Project:		Availability calendar
     * Author:			Rob Crothall - rob@crothall.co.za - +27 83 678 5055
     * Date Written:	2016-03-13
     * Date changed:
     * Copyright:		Creative Commons (CCO) license
     *
     * Purpose:		Display availability calendar.
     */  -->
<?php

    // configuration
    //error_log("Entered index.php", 3, "/tmp/potr-errors.log");
    require("../includes/config.php"); 

    // get User details
	 if (isset($_SESSION["id"])): 
    	$user_rows = query("SELECT * FROM users WHERE id = ?", $_SESSION["id"]);
    endif;
    date_default_timezone_set("Africa/Johannesburg");
	 $_SESSION["month"] = date("m",time());
	 $_SESSION["year"] = date("Y",time());
    render("calendar_form.php");
    
?>
