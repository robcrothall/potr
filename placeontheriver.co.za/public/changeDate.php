
	<?php
    /**
     * Module name:	changeDate.php
     *
     * Client:			Lewis Coleman, Place on the River - info@placeontheriver.co.za
     * Project:		Availability calendar
     * Author:			Rob Crothall - rob@crothall.co.za - +27 83 678 5055
     * Date Written:	2016-04-23
     * Date changed:
     * Copyright:		Creative Commons (CCO) license
     *
     * Purpose:		Change the clicked date
     */ 

    // configuration
    require("../includes/config.php");
     if (!empty($_GET["id"])) {
			$cmd = "select * from bookings where room_date = ?";
			$rows = query($cmd, $_GET['id']);	 
			if (count($rows) > 0) {
				if ($rows[0]["room_status"] = 1) {
					$cmd = "delete from bookings where room_date = ?";
					$rows = query($cmd, $_GET['id']);
					$cmd = "insert into history (user_id, room_date, action) values (?, ?, ?)";
					$rows = query($cmd, $_SESSION["id"], $_GET["id"], "free");
					print "free";
					return false;
				}			
			} 
			else {
				$cmd = "insert into bookings values(?, 1);";
				$rows = query($cmd, $_GET['id']);   
				$cmd = "insert into history (user_id, room_date, action) values (?, ?, ?)";
				$rows = query($cmd, $_SESSION["id"], $_GET["id"], "booked");
				print "booked";
				return true;
     		}
		return;     	
     	} 
     	return;
?> 
<!--	</body>
</html>
-->