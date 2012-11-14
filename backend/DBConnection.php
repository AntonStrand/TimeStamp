<?php
	
	/**
	*
	* Gateway for ProjectTimer.
	*
	*author Anton Strand.
	*/
	
	class DBConnection{
	
		public function __construct()
		{
			//Kopplingen till databasen sker i driver.php istället för att det är
			//mindre chans att någon lyckas hacka kopplingen på det sätten.
		
				include('../../driver.php');
		}
	

		/* -------- LOGIN --------- */
		/**
		* Try to create a new user. Checks if username already exist.
		* @returns true or false
		*/
		function createUser($username, $password)
		{

			$sql = "SELECT * FROM pt_users WHERE username='".$username."'";
			$query = mysql_query($sql);

			// Check if the username already exists
			$username_counter = mysql_num_rows($query);

		
			// If username_counter is greater than 0 it means the username is taken.
			if ($username_counter > 0) {
			 
				return false;
			}
			else 
			{
				// Otherwise create a new user.
				mysql_query("INSERT INTO  pt_users (id ,username ,password)VALUES (NULL ,  '".$username."',  '".$password."')");
				return true;
			}
		}	

		/**
		* Try to login. 
		* @returns if successfull, user id else -1
		**/
		function login($username, $password)
		{
			// Looking for correct username and password
			$sql = "SELECT * FROM  pt_users WHERE  username LIKE  '".$username."'AND  password LIKE '".$password."'"; 
			$query = mysql_query($sql);

			// If the user is found fetch user id
			while($data = mysql_fetch_array($query)){
				$user_id = $data["id"];	
			}

			// If there was no user with that username and password return -1.
			if( $user_id == null ){
				$user_id = -1;
			}

			return $user_id;
		}



		/* -------- PROJECT --------- */
		/**
		* Has no restriction on unique name.
		*@returns true
		*/
		function addProject($user_id, $projectname, $client, $startdate, $deadline, $emolument)
		{
			mysql_query("INSERT INTO pt_projects (project_id, user_id, projectname, client, startdate, deadline, emolument) 
				VALUES (NULL, '".$user_id."', '".$projectname."', '".$client."', '".$startdate."', '".$deadline."', '".$emolument."')");
			return true;
		}

		/**
		* Delete project and times connected to project.
		* Send in project_id 
		*/
		function deleteProject($project_id)
		{
			mysql_query("DELETE FROM pt_projects WHERE project_id =".$project_id);
			// Remember to delete from pt_timesheet
			return true;
		}

		/**
		* Send in user_id 
		* @returns all of the users project and project data as a MultiArray
		*/
		function getAllProjects($user_id)
		{
			// Get all information of all the project connected to the user_id
			$sql = mysql_query("SELECT * FROM pt_projects WHERE user_id = ".$user_id);
			
			// save all the information exept user_id in a  MutliArray. 1 row = 1 project
			while ($row = mysql_fetch_array($sql)) {
				
				$projects[] = array($row['project_id'] , $row['projectname'], $row['client'], $row['startdate'], $row['deadline'], $row['emolument']);
				
			}
			
			// return an array with project data.
			return $projects;
		}



		/* -------- TIME SHEET --------- */
		/**
		*Save workhours in the projects time sheet
		*@returns true
		*/
		function addTimeStamp($user_id, $project_id, $task, $date, $hours, $minutes)
		{
			mysql_query("INSERT INTO pt_timesheet (time_id, user_id, project_id, task, minutes, hours, date) 
				VALUES (NULL, '".$user_id."', '".$project_id."', '".$task."', '".$minutes."', '".$hours."', '".$date."')");
			return true;
		}
		
		/*
		Get all workhours concerning a project
		@returns MultiArray or null (if there's no timestamps)
		*/
		function getProjectTimeStamps($user_id, $project_id)
		{
			$sql = mysql_query("SELECT * FROM  pt_timesheet WHERE  user_id =".$user_id." AND  project_id =".$project_id);
			
			// save all the information exept user_id in a  MutliArray. 1 row = 1 project
			while ($row = mysql_fetch_array($sql)) {
				
				$timeStamps[] = array($row['time_id'], $row['project_id'] , $row['task'], $row['date'], $row['hours'], $row['minutes']);
				
			}
			
			// return an array with project data.
			return $timeStamps;
		}
	}
?>