    <!--
    /**
     * Module name:	apology.php
     *
     * Client:			Lewis Coleman, Place on the River - info@placeontheriver.co.za
     * Project:		Availability calendar
     * Author:			Rob Crothall - rob@crothall.co.za - +27 83 678 5055
     * Date Written:	2016-03-13
     * Date changed:
     * Copyright:		Creative Commons (CCO) license
     *
     * Purpose:		Displays error messages
     */ -->

<p class="lead text-danger">
    Sorry!
</p>
<p class="text-danger">
    <?= htmlspecialchars($message) ?>
</p>

<a href="javascript:history.go(-1);">Back</a>
