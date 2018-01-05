	<!--    
    /**
     * Module name:	password_confirm_form.php
     *
     * Client:			Lewis Coleman, Place on the River - info@placeontheriver.co.za
     * Project:		Availability calendar
     * Author:			Rob Crothall - rob@crothall.co.za - +27 83 678 5055
     * Date Written:	2016-03-13
     * Date changed:
     * Copyright:		Creative Commons (CCO) license
     *
     * Purpose:		Confirm password.
     */  -->
<div class="container">
<?php
    //require("../templates/menu.php");
?>
<form action="availability.php" method="post">
    <fieldset>
        <div class="form-group">
            <?= htmlspecialchars($message) ?>
        </div>
    </fieldset>
</form>
</div>
