	<!--
    /**
     * Module name:	history_form.php
     *
     * Client:			Lewis Coleman, Place on the River - info@placeontheriver.co.za
     * Project:		Availability calendar
     * Author:			Rob Crothall - rob@crothall.co.za - +27 83 678 5055
     * Date Written:	2016-03-13
     * Date changed:
     * Copyright:		Creative Commons (CCO) license
     *
     * Purpose:		Will become the availability calendar
     */ -->
<div class="container">
<?php
    //require("../templates/menu.php");
?>
<!--input autofocus name="search" placeholder="Search symbol" type="text"/>
<button type="submit" class="btn btn-default">Search</button-->
<h2>Transaction history (descending date) for User "<?= $form_user ?>"</h2>
<table class="table table-striped">
    <THEAD>
        <tr>
            <th>Date</th>
            <th>Action</th>
            <th>Symbol</th>
            <th>Price</th>
            <th>No of shares</th>
            <th>Value</th>
        </tr>
    </THEAD>
    <tbody>
    <tr>
        <td></td>
        <td></td>
        <td>Cash</td>
        <td colspan="2"></td>
        <td><?= $cash_value ?></td>
    </tr>
    <?php foreach ($positions as $position): ?>
        
        <tr>
            <td><?= $position["form_date"] ?></td>
            <td><?= $position["form_action"] ?></td>
            <td><?= $position["form_symbol"] ?></td>
            <td><?= $position["form_price"] ?></td>
            <td><?= $position["form_shares"] ?></td>
            <td><?= $position["form_value"] ?></td>
        </tr>
        
    <?php endforeach ?>
    </tbody>
</table>
</div>
