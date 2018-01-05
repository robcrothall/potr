	<!--    
    /**
     * Module name:	password_form.php
     *
     * Client:			Lewis Coleman, Place on the River - info@placeontheriver.co.za
     * Project:		Availability calendar
     * Author:			Rob Crothall - rob@crothall.co.za - +27 83 678 5055
     * Date Written:	2016-03-13
     * Date changed:
     * Copyright:		Creative Commons (CCO) license
     *
     * Purpose:		Change password.
     */  -->
<div class="container">
<?php
    //require("../templates/menu.php");
?>
<p>
To change your password, you need to enter your current password, then your new password twice.
If your current password is correct, and the two entries of your new password match exactly, 
your password will be changed.
</p>
<form action="password.php" method="post">
    <fieldset>
        <div class="form-group">
            <input autofocus class="form-control" name="old_pwd" placeholder="Current password" type="password"/>
        </div>
        <div class="form-group">
            <input class="form-control" name="new_pwd1" placeholder="New password" type="password"/>
        </div>
        <div class="form-group">
            <input class="form-control" name="new_pwd2" placeholder="New password again" type="password"/>
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-default">Change Password</button>
        </div>
    </fieldset>
</form>
<br/>
<p class="text-danger">
    <?= htmlspecialchars($message) ?>
</p>
</div>
