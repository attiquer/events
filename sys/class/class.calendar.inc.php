<?php
/**
*class Calendar 
*builds and events calendar
*
*@author A Rehman
*@license http://www.opensource.org/licenses/mit-license.html 
*@copyright 2016 All rights reserved
*
*/

	class Calendar extends DB_Connect
	{

	/**
	*The date from which calendar is built
	*@var string the date to use for calendar
	*/
	private $_useDate;

	/**
	*The month for which calendar is built
	*@var int the month being used
	*/
	private $_m;

	/**
	*The year for which calendar is built
	*@var int the year to use
	*/
	private $_y;

	/**
	*The number of days in selected month
	*@var int the number of days 
	*/
	private $_daysInMonth;

	/**
	*The index of the day of the week the month starts on (0-6)
	*@var int the day of the week the month starts on
	*/
	private $_startDay;
	
     /**
     *Creates a database object and stores relevant data 
     * Upon instantiation, this class accepts a database object 
     * that, if not null, is stored in the object's private $_db 
     * property. If null, a new PDO object is created and stored 
     * instead. 
     * 
     * Additional info is gathered and stored in this method, 
     * including the month from which the calendar is to be built, 
     * how many days are in said month, what day the month starts 
     * on, and what day it is currently. 
     */

     /**
     *@param object $dbo a database object
     *@param string $useDate the date to use to build the calendar
     *@return void
     */

     public function __construct($dbo=NULL, $useDate=NULL){

     	/**
     	*call the parent constructor to check for a database connection
     	*/
     	parent::__construct($dbo);

     	/**
     	*Gather and store data relevant to the month
     	*/
     	if(isset($useDate))
     	{
     		$this->_useDate = $useDate;
     	}
     	else
     	{
     		$this->_useDate = date("Y-m-d H:i:s");
     	}

     	/**
     	*convert to timestamp then breakdown to 
     	*month and year
     	*/
     	$ts = strtotime($this->_useDate);
     	$this->_m = date("m", $ts);
     	$this->_y = date("Y", $ts);

     	/**
     	*Determin how many days are in a month
     	*/
     	$this->_daysInMonth = cal_days_in_month(CAL_GREGORIAN, $this->_m, $this->_y);

     	/**
     	*Determine what weekday the month starts on
     	*/
     	$ts = mktime(0, 0, 0, $this->_m, 1, $this->_y);
     	$this->_startDay = date("w", $ts);

     }

     /** 
     * Loads event(s) info into an array 
     * 
     * @param int $id an optional event ID to filter results 
     * @return array an array of events from the database 
     */ 
    private function _loadEventData($id=NULL) 
    { 
        $sql = "SELECT 
                    `event_id`, `event_title`, `event_desc`, 
                    `event_start`, `event_end` 
                FROM `events`"; 
        /* 
         * If an event ID is supplied, add a WHERE clause 
         * so only that event is returned 
         */ 
        if( !empty($id) ) 
        { 
            $sql .= "WHERE `event_id`=:id LIMIT 1"; 
        } 
        /* 
         * Otherwise, load all events for the month in use 
         */ 
        else 
        { 
            /* 
             * Find the first and last days of the month 
             */ 
            $start_ts = mktime(0, 0, 0, $this->_m, 1, $this->_y); 
            $end_ts = mktime(23, 59, 59, $this->_m+1, 0, $this->_y); 
            $start_date = date('Y-m-d H:i:s', $start_ts); 
            $end_date = date('Y-m-d H:i:s', $end_ts); 
            /* 
             * Filter events to only those happening in the 
             * currently selected month 
             */ 
            $sql .= "WHERE `event_start` 
                        BETWEEN '$start_date' 
                        AND '$end_date' 
                    ORDER BY `event_start`"; 
        } 
        try 
        { 
            $stmt = $this->db->prepare($sql); 
            /* 
             * Bind the parameter if an ID was passed 
             */ 
            if ( !empty($id) ) 
            { 
                $stmt->bindParam(":id", $id, PDO::PARAM_INT); 
            } 
            $stmt->execute(); 
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC); 
            $stmt->closeCursor(); 
            return $results; 
        } 
        catch ( Exception $e ) 
        { 
            die ( $e->getMessage() ); 
        } 
    }

    /**
    *Loads all events for the month into an array
    *
    *@return array events info
    */
    private function _createEventObj()
    {
    	/**
    	*load the events array
    	*/
    	$arr = $this->_loadEventData();

    	/**
    	*Create a new array and organise events by the day of month
    	*/
    	$events = array();
    	foreach ($arr as $event){
    		$day = date('j', strtotime($event['event_start']));
    		try
    		{
    			$events[$day][] = new Event($event);
    		}
    		catch(Exception $e){
    			di($e->getMessage());
    		}
    		return $events;
    	}
    }

    /**
    *return html markup to display the calendar and events
    */
    public function buildCalendar()
    {
    	/**
    	*Determine the calendar month and create an array of 
    	*/
    	$cal_month = date('F y', strtotime($this->_useDate));
    	$weekdays = array('Sun', 'Mon', 'Tue', 'Wed', 'Thurs', 'Fri', 'Sat');
    	$html = "<h2>". $cal_month. "</h2>";
    	for ($d = 0; $labels = NULL, $d<7; $d++){
    		$labels .= "\n\t<li>".$weekdays[$d]."</li>";
    	}
    	$html .= "\n\t<ul class=\"weekdays\">".$labels."\n\t</ul>";
    	return $html;

    }
    
  } 

?>