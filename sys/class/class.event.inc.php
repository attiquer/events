<?php
/**
*Class Event
*Stores event information
*
*@author A Rehman
*@license http://www.opensource.org/licenses/mit-license.html 
*@copyright 2016 All rights reserved
*/

class Event
{
	/**
	*The Event ID
	*@var int $id
	*/
	public $id;

	/**
	*@var string
	*/
	public $title;

	/**
	*Event description
	*@var string $description
	*/
	public $description;

	/**
	*Event start time
	*@var string $start
	*/
	public $start;

	/**
	*Event end time
	*@var string $end
	*/
	public $end;

	/**
	*Accepts an array of data and stores it
	*@param array $event associative array of event data
	*@return void
	*/
	public function __construct($event)
	{
		if(is_array($event)){
			$this->id = $event['event_id'];
			$this->title = $event['event_title'];
			$this->description = $event['event_desc'];
			$this->start = $event['event_start'];
			$this->end = $event['event_end'];
		}
		else {
			throw new exception("No event found");
		}
	}


}

?>