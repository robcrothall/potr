	<!--
    /**
     * Module name:	calendar_form.php
     *
     * Client:			Lewis Coleman, Place on the River - info@placeontheriver.co.za
     * Project:		Availability calendar
     * Author:			Rob Crothall - rob@crothall.co.za - +27 83 678 5055
     * Date Written:	2016-03-13
     * Date changed:
     * Copyright:		Creative Commons (CCO) license
     *
     * Purpose:		Display the availability calendar
     */ -->
<div class="container">
<h2>Availability Calendar</h2>
<font size=+2 color="silver"><a href="http://www.placeontheriver.co.za">Return to Place on the River</a><font size=-1 color="black"><br>
Dates that are blocked in <strong style="background-color:red;">red</strong> are not available.
    <script type="text/javascript">
    function clked(clickedId)
    {
		var parm = "id=" + clickedId;

    	$.ajax({
    		url:'http://www.placeontheriver.co.za/public/changeDate.php?' + parm,
    		complete: function (response) {
    			$('#output').html(response.responseText);
    			var it = response.responseText.indexOf("booked");
    			var it2 = response.responseText;
    			if (it > 0) {
    				document.getElementById(clickedId).style.backgroundColor = "#F00";
    			} else {
    				it = response.responseText.indexOf("free");
    			   it2 = response.responseText;
    				if (it > 0) {
    					document.getElementById(clickedId).style.backgroundColor = "#DDD";
    				} else {
    					document.getElementById(clickedId).style.backgroundColor = "#00F";
    				}
    			}
    		},
    		error: function() {
    			alert("Error setting status for " + parm);
    			document.getElementById(clickedId).style.backgroundColor = "#DDD";
    		}
    	});  
    }
    </script>
<?php
    include '../includes/calendar.php';
    echo '	<table border="0" cellspacing="0" cellpadding="5" align=center>	';
    for ($i = 0; $i < 9; $i++) {
    	echo '<tr valign=top>	<td> ';
		$calendar = new Calendar();
    	echo $calendar->show();
    	echo '</td> <td> ';
    	$_SESSION["month"] = $_SESSION["month"] + 1;
    	if($_SESSION["month"] > 12) {
    		$_SESSION["month"] = 1;
    		$_SESSION["year"] = $_SESSION["year"] + 1;
    	}
		$calendar = new Calendar();
    	echo $calendar->show();
    	echo '</td> ';
    	$_SESSION["month"] = $_SESSION["month"] + 1;
    	if($_SESSION["month"] > 12) {
    		$_SESSION["month"] = 1;
    		$_SESSION["year"] = $_SESSION["year"] + 1;
    	}
    	echo '</tr> ';
    }
    
?>
</div>
