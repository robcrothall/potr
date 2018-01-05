	<!--    
    /**
     * Module name:	register.php
     *
     * Client:			Lewis Coleman, Place on the River - info@placeontheriver.co.za
     * Project:		Availability calendar
     * Author:			Rob Crothall - rob@crothall.co.za - +27 83 678 5055
     * Date Written:	2016-03-13
     * Date changed:
     * Copyright:		Creative Commons (CCO) license
     *
     * Purpose:		Create a new User.
     */  -->
<?php

    // configuration
    require("../includes/config.php");
    
    // if form was submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // validate submission
        if (empty($_POST["username"]))
        {
            apologize("You must provide your username.");
        }
        else if (empty($_POST["password"]))
        {
            apologize("You must provide your password.");
        }
        else if (empty($_POST["confirmation"]))
        {
            apologize("You must provide your confirmation password.");
        }
        if ($_POST["password"] !== $_POST["confirmation"])
        {
            apologize("Your password and confirmation password are not identical.");
        }

        // query database for user
        $rows = query("SELECT * FROM users WHERE username = ?", $_POST["username"]);

        // if we found user, tell him he is already registered
        if (count($rows) == 1)
        {
            apologize("Your username has already been registered - please choose another.");
        }
        // insert the new user into the users table
        $rows = query("INSERT INTO users (username, hash, status) VALUES(?, ?, ?)", 
            $_POST["username"], crypt($_POST["password"],$_POST["username"]), "active");
        if ($rows === false)
        {
            apologize("Unable to register your user name - please contact info@placeontheriver.co.za");
        }
        $rows = query("SELECT LAST_INSERT_ID() AS id");
        $id = $rows[0]["id"];

        // remember that user's now logged in by storing user's ID in session
        $_SESSION["id"] = $id;

        // redirect to availability
        redirect("availability.php");
    }
    else
    {
        render("register_form.php", ["title" => "Register"]);
    }
?>
