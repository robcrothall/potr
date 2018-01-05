<?php

    /**
     * Module name:	functions.php
     *
     * Client:			Lewis Coleman, Place on the River - info@placeontheriver.co.za
     * Project:		Availability calendar
     * Author:			Rob Crothall - rob@crothall.co.za - +27 83 678 5055
     * Date Written:	2016-03-13
     * Date changed:
     * Copyright:		Creative Commons (CCO) license
     *
     * Purpose:		Helper functions.
     */

    require_once("constants.php");

    /**
     * Apologizes to user with message.
     */
    function apologize($message)
    {
        render("apology.php", ["message" => $message]);
        exit;
    }

    /**
     * Facilitates debugging by dumping contents of variable
     * to browser.
     */
    function dump($variable)
    {
        require("../templates/dump.php");
        exit;
    }

    /**
     * Logs out current user, if any.  Based on Example #1 at
     * http://us.php.net/manual/en/function.session-destroy.php.
     */
    function logout()
    {
        // unset any session variables
        $_SESSION = [];

        // expire cookie
        if (!empty($_COOKIE[session_name()]))
        {
            setcookie(session_name(), "", time() - 42000);
        }

        // destroy session
        session_destroy();
    }

    /**
     * Returns a stock by symbol (case-insensitively) else false if not found.
     */
    function lookup($symbol)
    {
        // reject symbols that start with ^
        if (preg_match("/^\^/", $symbol))
        {
            return false;
        }

        // reject symbols that contain commas
        if (preg_match("/,/", $symbol))
        {
            return false;
        }

        // open connection to Yahoo
        $handle = @fopen("http://download.finance.yahoo.com/d/quotes.csv?f=snl1&s=$symbol", "r");
        if ($handle === false)
        {
            // trigger (big, orange) error
            trigger_error("Could not connect to Yahoo!", E_USER_ERROR);
            exit;
        }

        // download first line of CSV file
        $data = fgetcsv($handle);
        if ($data === false || count($data) == 1)
        {
            return false;
        }

        // close connection to Yahoo
        fclose($handle);

        // ensure symbol was found
        if ($data[2] === "0.00")
        {
            return false;
        }

        // return stock as an associative array
        return [
            "symbol" => $data[0],
            "name" => $data[1],
            "price" => $data[2],
        ];
    }

    /**
     * Executes SQL statement, possibly with parameters, returning
     * an array of all rows in result set or false on (non-fatal) error.
     */
    function query(/* $sql [, ... ] */)
    {
        // SQL statement
        $sql = func_get_arg(0);
    
        // parameters, if any
        $parameters = array_slice(func_get_args(), 1);

        // try to connect to database
        static $handle;
        if (!isset($handle))
        {
            try
            {
                // connect to database
                $handle = new PDO("mysql:dbname=" . DATABASE . ";host=" . SERVER, USERNAME, PASSWORD);

                // ensure that PDO::prepare returns false when passed invalid SQL
                $handle->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); 
            }
            //catch (Exception $e)
            catch (PDOException $e)
            {
                // trigger (big, orange) error
                trigger_error($e->getMessage(), E_USER_ERROR);
                exit;
            }
        }

        // prepare SQL statement
        $statement = $handle->prepare($sql);
        if ($statement === false)
        {
            // trigger (big, orange) error
            trigger_error($handle->errorInfo()[2], E_USER_ERROR);
            exit;
        }

        // execute SQL statement
        $results = $statement->execute($parameters);

        // return result set's rows, if any
        if ($results !== false)
        {
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }
        else
        {
            return false;
        }
    }

    /**
     * Redirects user to destination, which can be
     * a URL or a relative path on the local host.
     *
     * Because this function outputs an HTTP header, it
     * must be called before caller outputs any HTML.
     */
    function redirect($destination)
    {
        // handle URL
        if (preg_match("/^https?:\/\//", $destination))
        {
            header("Location: " . $destination);
        }

        // handle absolute path
        else if (preg_match("/^\//", $destination))
        {
            $protocol = (isset($_SERVER["HTTPS"])) ? "https" : "http";
            $host = $_SERVER["HTTP_HOST"];
            header("Location: $protocol://$host$destination");
        }

        // handle relative path
        else
        {
            // adapted from http://www.php.net/header
            $protocol = (isset($_SERVER["HTTPS"])) ? "https" : "http";
            $host = $_SERVER["HTTP_HOST"];
            $path = rtrim(dirname($_SERVER["PHP_SELF"]), "/\\");
            header("Location: $protocol://$host$path/$destination");
        }

        // exit immediately since we're redirecting anyway
        exit;
    }

    /**
     * Renders template, passing in values.
     */
    function render($template, $values = [])
    {
        // if template exists, render it
        if (file_exists("../templates/$template"))
        {
            // extract variables into local scope
            extract($values);

            // render header
            require("../templates/header.php");

            // render template
            require("../templates/$template");

            // render footer
            require("../templates/footer.php");
        }

        // else err
        else
        {
            trigger_error("Invalid template: $template", E_USER_ERROR);
        }
    }
    function logit($cmd, $module)
    {
        $mycmd = str_replace("'", "`", $cmd);
        $sql = "insert into sql_log (cmd, module) values ('" . $mycmd . "', '" . $module . "')";
        $result = query("$sql");
        return $result;
    }
    function write_history($values = [])
    {
        $cmd1 = "insert into history (";
        $cmd2 = ") values (";
        $cmd3 = ")";
        $user_rows = query("SELECT * FROM users WHERE id = ?", $_SESSION["id"]);

        $cmd1 = $cmd1 . "username";
        $cmd2 = $cmd2 . "'" . $user_rows[0]["username"] . "'";
        
        $cmd1 = $cmd1 . ", user_id";
        $cmd2 = $cmd2 . ", " . $_SESSION["id"];
        if (!empty($values["symbol"]))
        {        
            $cmd1 = $cmd1 . ", symbol";
            $cmd2 = $cmd2 . ", '" . $values["symbol"] . "'";
        }
        
        if (!empty($values["action"]))
        {
            $cmd1 = $cmd1 . ", action";
            $cmd2 = $cmd2 . ", '" . $values["action"] . "'";
        }
        
        if (!empty($values["shares"]))
        {        
            $cmd1 = $cmd1 . ", shares";
            $cmd2 = $cmd2 . ", " . $values["shares"];
        }
        
        if (!empty($values["price"]))
        {        
            $cmd1 = $cmd1 . ", price";
            $cmd2 = $cmd2 . ", " . $values["price"];
        }
        
        if (!empty($values["value"]))
        {        
            $cmd1 = $cmd1 . ", value";
            $cmd2 = $cmd2 . ", " . $values["value"];
        }
        
        if (!empty($values["msg"]))
        {
            $cmd1 = $cmd1 . ", msg";
            $cmd2 = $cmd2 . ", '" . $values["msg"] . "'";
        }
        
        $cmd = $cmd1 . $cmd2 . $cmd3;
        $result = query("$cmd");
        return $result;
    }
	/* draws a calendar */
	function draw_calendar($month,$year){

		/* draw table */
		$calendar = '<table cellpadding="0" cellspacing="0" class="calendar">';

		/* table headings */
		/*$headings = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'); */
		$headings = array('Sun&nbsp;&nbsp;&nbsp;','Mon&nbsp;&nbsp;&nbsp;','Tue&nbsp;&nbsp;&nbsp;','Wed&nbsp;&nbsp;&nbsp;','Thu&nbsp;&nbsp;&nbsp;','Fri&nbsp;&nbsp;&nbsp;','Sat&nbsp;&nbsp;&nbsp;');
		$calendar.= '<tr class="calendar-row"><td class="calendar-day-head">'.implode('</td><td class="calendar-day-head">',$headings).'</td></tr>';
	
		/* days and weeks vars now ... */
		date_default_timezone_set('Africa/Johannesburg');
		$running_day = date('w',mktime(0,0,0,$month,1,$year));
		$days_in_month = date('t',mktime(0,0,0,$month,1,$year));
		$days_in_this_week = 1;
		$day_counter = 0;
		$dates_array = array();

		/* row for week one */
		$calendar.= '<tr class="calendar-row">';

		/* print "blank" days until the first of the current week */
		for($x = 0; $x < $running_day; $x++):
			$calendar.= '<td class="calendar-day-np"> </td>';
			$days_in_this_week++;
		endfor;
	
		/* keep going with days.... */
		for($list_day = 1; $list_day <= $days_in_month; $list_day++):
			$calendar.= '<td class="calendar-day">';
				/* add in the day number */
				$calendar.= '<div class="day-number">'.$list_day.'</div>';
	
				/** QUERY THE DATABASE FOR AN ENTRY FOR THIS DAY !!  IF MATCHES FOUND, PRINT THEM !! **/
				$calendar.= str_repeat('<p> </p>',2);
				
			$calendar.= '</td>';
			if($running_day == 6):
				$calendar.= '</tr>';
				if(($day_counter+1) != $days_in_month):
					$calendar.= '<tr class="calendar-row">';
				endif;
				$running_day = -1;
				$days_in_this_week = 0;
			endif;
			$days_in_this_week++; $running_day++; $day_counter++;
		endfor;
	
		/* finish the rest of the days in the week */
		if($days_in_this_week < 8):
			for($x = 1; $x <= (8 - $days_in_this_week); $x++):
				$calendar.= '<td class="calendar-day-np"> </td>';
			endfor;
		endif;
	
		/* final row */
		$calendar.= '</tr>';
	
		/* end the table */
		$calendar.= '</table>';
		
		/* all done, return result */
		return $calendar;
	}

/*	function chgeDate($myDate) {
	     if (isset($myDate)) {
			$cmd = "select * from bookings where room_date = ?";
			$rows = query($cmd, $myDate);	 
			if (count($rows) > 0) {
				if ($rows[0]["room_status"] = 1) {
					$cmd = "delete from bookings where room_date = ?";
					$rows = query($cmd, $myDate);
					return false;
				}			
			} 
			else {
				$cmd = "insert into bookings values(?, 1);";
				$rows = query($cmd, myDate);   
     		}
		  }
		  return false;
	} */
?>

