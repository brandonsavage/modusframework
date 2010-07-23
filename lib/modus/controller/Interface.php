<?php

interface Controller_Interface
{

	/**
	 * Establish the controller and take a request.
	 * 
	 * @param RequestI $request The request object for this request.
	 * @return void
	 */
	public function __construct(Request_Interface $request);
	
	/**
	 * Initialize the controller and prepare it to execute the action.
	 * 
	 * @return ActionI
	 */
	public function initialize();
	
	/**
	 * Get the action name from the request.
	 * 
	 * @return string
	 */
	public function getAction();
	
    /**
     * Execute the action defined by the initialize() method.
     * 
     * @return ResponseI
     */
	public function execute();
	
	/**
	 * Determine whether or not the current action can be executed by this user.
	 * 
	 * @return bool
	 */
	public function authenticate();
	
	public function setResponseFormatter(Formatter_Interface $rformat);
	
	public function getResponseFormatter();

}