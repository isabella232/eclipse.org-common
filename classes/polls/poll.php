<?php
require_once("/home/data/httpd/eclipse-php-classes/system/dbconnection_polls_rw.class.php");

/*******************************************************************************
 * Copyright (c) 2006 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Denis Roy (Eclipse Foundation)- initial API and implementation
 *******************************************************************************/

#*****************************************************************************
#
# poll.php
#
# Author: 		Denis Roy
# Date:			2006-02-02
#
# Description: Class to use polls  
#
#****************************************************************************



class Poll {
	var $poll_id		= 0;  #PK
	var $url			= "";
	var $poll_index		= 0;
	var $poll_title		= "";
	var $poll_action	= "";
	var $total_votes 	= 0;
	var $show_graph		= true;
	
	var $COOKIE_NAME = "ECLIPSE_INSTA_POLLS";
	
	
	var $poll_options	= array(); # Array of PollOption objects	
	
	
	/**
	 * Default constructor
	 * @param int $poll_index  to identify the poll index on a page (for multiple polls)
	 * @param string $poll_title
	 * 
	 */
	function Poll($_poll_index, $_poll_title) {
		$this->url			= $_SERVER['REQUEST_URI'];
		$this->poll_index	= $_poll_index;
		$this->poll_title	= $_poll_title;
		$this->selectInsertPollID();  ## We need this before going on!
		
		
		if(isset($_POST['poll_action' . $_poll_index])) {
			$this->poll_action 	= $_POST['poll_action' . $_poll_index];
		}
		
		# Clean incoming
		$this->url = str_replace("..", "", $this->url);
		$this->url = str_replace(";", "", $this->url);
		
		if(!is_numeric($this->poll_index)) {
			$this->poll_index = 1;
		}
			
		# Determine the action to take.
		if($this->poll_action == "vote" && $this->isVoteable()) {
			# Save poll options!
			$this->updatePollCount();
		}
		
	}


	/**
	 * fetch poll counts from the database
	 * 
	 */
	function selectPollCounts() {
		# Get poll counts for poll id
		
		if($this->isValidPollID()) {
		 
			$App = new App();
			
			$this->total_votes = 0;
			
		    $sql = "SELECT 
						option_id,
						answer_count
		        	FROM
						poll_options
					WHERE poll_id = " . $this->poll_id;
					
		    $dbc = new DBConnectionPollsRW();
		    $dbh = $dbc->connect();
	
		    $result = mysql_query($sql, $dbh);
	
			# find the right poll option, and increment the count
		    while($myrow = mysql_fetch_array($result)) {
				for($i = 0; $i < count($this->poll_options); $i++) {
					$PollOption = $this->poll_options[$i];
					
					if($PollOption->option_id == $myrow['option_id']) {
						$PollOption->answer_count 	= $myrow['answer_count'];
						$this->total_votes 			+= $myrow['answer_count'];
						$this->poll_options[$i] 	= $PollOption;
						break;
					}
				}
		    }
	
		    $dbc->disconnect();
		    $dbh 	= null;
		    $dbc 	= null;
		    $result = null;
		    $myrow	= null;
		}		   
	}



	/**
	 * get poll id from db, or create if not found
	 */
	function selectInsertPollID() {
		
		if($this->isValidPollID()) {
			# Get poll ID from the database, or create one if it doesn't exist.
			$App = new App();
			
		    $sql = "SELECT 
						POL.poll_id
		        	FROM
						polls AS POL
					WHERE POL.url = " . $App->returnQuotedString($this->url) . "
						AND POL.poll_index = " . $this->poll_index;
					
		    $dbc = new DBConnectionPollsRW();
		    $dbh = $dbc->connect();
	
		    $result = mysql_query($sql, $dbh);
	
		    if($myrow = mysql_fetch_array($result)) {
		            $this->poll_id  = $myrow["poll_id"];
		    }
		    else {
		    	$sql = "INSERT INTO polls (
							poll_id,
							url,
							poll_index,
							first_seen)
						VALUES (
							NULL,  
						" . $App->returnQuotedString($this->url) . ",
						" . $this->poll_index . ",
							CURDATE())";
						
				mysql_query($sql, $dbh);
				$this->poll_id = mysql_insert_id($dbh);
				
				# Perform house cleaning when creating new polls
				# Pass database handle to recycle the connection
				$this->flushOldPolls($dbh);
		    }
	    	
	    	$dbc->disconnect();
	    	$dbh 	= null;
	    	$dbc 	= null;
	    	$result = null;
	    	$myrow	= null;
		}
	}
	
	
	/**
	 * update poll count in db as a result of voting
	 */
	function updatePollCount() {

		if($this->isValidPollID()) {
			$App = new App();
	    
		    # we have the poll id, add a count
		    $poll_option = $_POST['polloption'];
			if(!is_numeric($poll_option)) {
				$poll_option = 1;
			}
		    
		    
	    	$sql = "UPDATE poll_options SET
						answer_count = answer_count + 1
					WHERE
						poll_id = " . $this->poll_id . "
						AND option_id = " . $poll_option;  
	
	
		    $dbc = new DBConnectionPollsRW();
		    $dbh = $dbc->connect();
			mysql_query($sql, $dbh);
			
			if(mysql_affected_rows() == 0) {
				# Update failed.  Issue insert statement
				$sql = "INSERT INTO poll_options (
							poll_id,
							option_id, 
							answer_count) VALUES (
						" . $this->poll_id . ",
						" . $poll_option . ",
						1)";  
							  
				mysql_query($sql, $dbh);
			}
			
			# put cookie on the browser to indicate user has voted
			$this->setCookie(); 
	
		    $dbc->disconnect();
		    $dbh 	= null;
		    $dbc 	= null;
		    $result = null;
		    $myrow	= null;
		}
	}
	
	
	/**
	 * set cookie on the browser to avoid ballot stuffers
	 */
	function setCookie() {
		/*
		 * Cookie is called ECLIPSE_INSTA_POLLS
		 * We put a csv list of poll id's the user has voted on
		 * all in one cookie.  Makes for fewer cookies on the browser
		 * 
		 */
		
		$insta_polls = array();
		if(isset($_COOKIE[$this->COOKIE_NAME])) {
			$insta_polls = explode(",", $_COOKIE[$this->COOKIE_NAME]);
		}
		
		if(!is_numeric(array_search($this->poll_id, $insta_polls))) {
			$insta_polls[count($insta_polls)] = $this->poll_id;
		} 
		
		setcookie($this->COOKIE_NAME, implode(",", $insta_polls), time()+604800, "/");
	}

	/**
	 * determine if user can vote
	 * @return bool
	 */
	function isVoteable() {
		
		/*
		 * isVoteable - used to determine if we display a form or just get the actual results
		 */
		
		$rValue = false;
				
		$insta_polls = array();
		if(isset($_COOKIE[$this->COOKIE_NAME])) {
			$insta_polls = explode(",", $_COOKIE[$this->COOKIE_NAME]);
		}
		
		if(!is_numeric(array_search($this->poll_id, $insta_polls))) {
			$rValue = true;
		}
		return $rValue;
	}
	
	
	/**
	 * Add a poll option to the object's array
	 * @param string $option_id a 12-character alphanumeric key to represent the option
	 * @param string $option_text
	 */
	function addOption($_option_id, $_option_text) {		
		$PollOption = new PollOption();
		$PollOption->poll_id = $this->poll_id;
		$PollOption->option_id = $_option_id;
		$PollOption->option_text = str_replace("\"", "'", $_option_text);	
		
		$this->poll_options[count($this->poll_options)] = $PollOption;
		
	}
	
	/**
	 * disable graph display
	 */
	function noGraph() {
		$this->show_graph = false;
	}

	/**
	 * generate HTML required for the poll
	 * @return string
	 */
	function getHTML() {
		
		$rValue = "";
		
		if($this->poll_action == "" && $this->isVoteable()) {
			
			# display poll options
		
			$rValue = "<p><b>" . $this->poll_title . "</b><form method=\"post\">";
			$rValue .= "<input type=\"hidden\" name=\"poll_index\" value=\"" . $this->poll_index . "\" />";
			$rValue .= "<input type=\"hidden\" name=\"poll_action" . $this->poll_index . "\" value=\"vote\" />";
			for($i = 0; $i < count($this->poll_options); $i++) {
				$PollOption = $this->poll_options[$i];
				
				$rValue .= "<input type=\"radio\" name=\"polloption\" value=\"" . $PollOption->option_id . "\" />" . $PollOption->option_text . "<br />";
			}
			
			$rValue .= "<input type=\"submit\" value=\"Vote\" /></form></p>";
		}

		if($this->poll_action == "vote" || !($this->isVoteable())) {
			
			# display poll options
			
			$this->selectPollCounts();
		
			$rValue = "<p><b>Results: " . $this->poll_title . "</b></p>";
			
			for($i = 0; $i < count($this->poll_options); $i++) {
				$PollOption = $this->poll_options[$i];
				
				$thisPercent = 0;
				
				if($this->total_votes > 0) {
					$thisPercent = round($PollOption->answer_count / $this->total_votes * 100);
				}				
				$rValue .= $PollOption->option_text . ": " . $thisPercent . " %<br />";
				
				if($thisPercent > 0 && $this->show_graph) {
					$rValue .= "<table height=\"9\" cellpadding=\"0\" cellspacing=\"0\"><td class=\"poll_start\" width=\"3\"></td><td width=\"$thisPercent\" class=\"poll_bar\"></td><td width=\"3\" class=\"poll_end\" style=\"font-size: 2pt;\">&nbsp;</td></tr></table><br />";
				}
				else {
					$rValue .= "<br />";
				}

			}
			
			
			$rValue .= "<p><b>Total votes: </b>" . $this->total_votes . "</p>";
		}

		return $rValue;
	}
	
	
	/**
	 * determine if poll_id is valid
	 * @return bool
	 */
	function isValidPollID() {
		if(is_numeric($this->poll_id)) {
			return true;
		}
		else {
			return false;
		}
	}
	
	/**
	 * flush old polls and results.  Polls can still be used, this is simply for house cleaning.
	 * This code should be run automatically when a new poll is created.
	 * @param dbh $_dbh a writable database connection
	 * 
	 */
	function flushOldPolls($_dbh) {

		$sql = "DELETE FROM 
					poll_options, polls 
				USING 
					poll_options, polls 
				WHERE 
					poll_options.poll_id = polls.poll_id 
					AND polls.first_seen <=  DATE_SUB(CURDATE(), INTERVAL 3 MONTH)";

		mysql_query($sql, $_dbh);
	
	    $_dbh 	= null;
	}
}

class PollOption {
	var $poll_id		= 0;  #PK
	var $option_id		= 0; #PK
	var $option_text	= "";
	var $answer_count	= 0;
}


?>