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
    *return html markup to display the calendar and events
    */ 
    public function buildCalendar() 
    { 
    	$event_info = NULL;
        /* 
         * Determine the calendar month and create an array of 
         * weekday abbreviations to label the calendar columns 
         */ 
        $cal_month = date('F Y', strtotime($this->_useDate)); 
        $weekdays = array('Sun', 'Mon', 'Tue', 
                'Wed', 'Thu', 'Fri', 'Sat'); 
        /* 
         * Add a header to the calendar markup 
                  */ 
        $html = "\n\t<h2>$cal_month</h2>"; 
        for ( $d=0, $labels=NULL; $d<7; ++$d ) 
        { 
            $labels .= "\n\t\t<li>" . $weekdays[$d] . "</li>"; 
        } 
        $html .= "\n\t<ul class=\"weekdays\">" 
            . $labels . "\n\t</ul>"; 
        /* 
         * Load events data 
         */ 
        $events = $this->_createEventObj(); 
        /* 
         * Create the calendar markup 
         */ 
        $html .= "\n\t<ul>"; // Start a new unordered list 
        for ( $i=1, $c=1, $t=date('j'), $m=date('m'), $y=date('Y'); 
                $c<=$this->_daysInMonth; ++$i ) 
        { 
            /* 
             * Apply a "fill" class to the boxes occurring before 
             * the first of the month 
             */ 
            $class = $i<=$this->_startDay ? "fill" : NULL; 
            /* 
             * Add a "today" class if the current date matches 
             * the current date 
             */ 
            if ( $c+1==$t && $m==$this->_m && $y==$this->_y ) 
            { 
                $class = "today"; 
            } 
            /* 
             * Build the opening and closing list item tags 
             */ 
            $ls = sprintf("\n\t\t<li class=\"%s\">", $class); 
            $le = "\n\t\t</li>"; 
            /* 
             * Add the day of the month to identify the calendar box 
             */ 
            if ( $this->_startDay<$i && $this->_daysInMonth>=$c) 
            { 
                /* 
                 * Format events data 
                 */ 
                $event_info = NULL; // clear the variable
                if(isset($events[$c]) ) 
                { 
                    foreach ( $events[$c] as $event ) 
                    { 
                        $link = '<a href="view.php?event_id=' 
                                . $event->id . '">' . $event->title 
                                . '</a>'; 
                        $event_info .= "\n\t\t\t$link"; 
                    } 
                } 
                $date = sprintf("\n\t\t\t<strong>%02d</strong>",$c++); 
            } 
            else { $date="&nbsp;"; } 
            /* 
             * If the current day is a Saturday, wrap to the next row 
             */ 
            $wrap = $i!=0 && $i%7==0 ? "\n\t</ul>\n\t<ul>" : NULL; 
            /* 
             * Assemble the pieces into a finished item 
             */ 
            $html .= $ls . $date . $event_info . $le . $wrap; 
        } 
        /* 
         * Add filler to finish out the last week 
         */ 
        while ( $i%7!=1 ) 
        { 
            $html .= "\n\t\t<li class=\"fill\">&nbsp;</li>"; 
            ++$i; 
        } 
        /* 
         * Close the final unordered list 
         */ 
        $html .= "\n\t</ul>\n\n"; 
        /* 
         * Return the markup for output 
         */ 
        return $html; 
    } //build calendar ends

        	public function displayEvent($id){
    		/**
    		*check if empty return null
    		*/
    		if(empty($id)){
    			return NULL;
    		}
    		/**
    		*Make sure the id is integer
    		*/
    		$id = preg_replace('/[^0-9]/', '', $id);

    		/**
    		*Load the event data
    		*/
    		$event = $this->_loadEventById($id);

    		/**
    		*Generate string for the date start and end time
    		*/
    		$ts = strtotime($event->start);
    		$date = date('F, d, Y', $ts);
    		$start = date('g:ia', $ts);
    		$end = date('g:ia', strtotime($event->end));

    		/**
    		*Generate and return the markeup
    		*/

    		$html = "<h2>$event->title</h2>";
    		$html .= "\n\t<p class=\"dates\"> $date, $start &dash;$end</p>";
    		$html .= "\n\t<p>$event->description</p>";
    		return $html;

    	} //display event ends

        /** 
     * Generates a form to edit or create events 
     * 
     * @return string the HTML markup for the editing form 
     */ 
    public function displayForm() 
    { 
        /* 
         * Check if an ID was passed 
         */ 
        if ( isset($_POST['event_id']) ) 
        { 
            $id = (int) $_POST['event_id']; 
                // Force integer type to sanitize data 
        } 
        else 
        { 
            $id = NULL; 
        } 
        /* 
         * Instantiate the headline/submit button text 
         */ 
        $submit = "Create a New Event"; 
        /* 
         * If an ID is passed, loads the associated event 
         */ 
        if ( !empty($id) ) 
        { 
            $event = $this->_loadEventById($id); 
            /* 
             * If no object is returned, return NULL 
             */ 
            if ( !is_object($event) ) { return NULL; } 
            $submit = "Edit This Event"; 
        }
     	/* 
         * Build the markup 
         */ 
        return <<<FORM_MARKUP 
     		<form action="assets/common/process.inc.php" method="post">
     		<fieldset>
     			<legend>$submit</legend>
     			<label for="event_title">Event Title</label>
     			<input type="text" name="event_title" id="event_title" value="$event->title" />
     			<label for="event_start">Start Time</label>
     			<input type="text" name="event_start" id="event_start" value="$event->start" />
     			<label for="event_end"></label>
     			<input type="text" name="event_end" id="event_end" value="$event->end" />
     			<label for="event_description"></label>
     			<textarea name="event_description" id="event_description" value="$event->description"></textarea>
     			<input type="hidden" name="event_id" value="$event->id" />
     			<input type="hidden" name="token" value="$_SESSION[token]" />
     			<input type="submit" name="action" value="event_edit" />
     			<input type="submit" name="submit" value="$submit" />
     			or <a href="./">Cancel</a>
     			</fieldset>
     			</form>


     		</fieldset>
     		</form>

     		FORM_MARKUP;
     	
     }//display form func ends


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
    *returns a single event object
    *
    *@param int $id an event id
    *@return object the event object
    */
    private function _loadEventById($id)
    {
    	/**
    	*if no id is passed
    	*/
    	if(empty($id))
    	{
    		return NULL;
    	}
    	$event = $this->_loadEventData($id);
    		/**
    		*return and event object
    		*/
    		if(isset($event[0])){
    			return new Event($event[0]);
    		}
    		else{
    			return NULL;
    		}
    	}

    	/**
    	*Displays a given event's description
    	*@param int $id the event ID
    	*@return string basic markup to display 
    	*/

     }

?>