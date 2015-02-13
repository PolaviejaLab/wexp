<?php 
	namespace AppBundle\Helpers;
	
	/**
	 * Wrapper for json_decode
	 */
	class JSON
	{
		protected $data = "";
		protected $errorCode = JSON_ERROR_NONE;
		protected $errorString = "";
		
		
		function __construct($string) {
			$this->errorCode = JSON_ERROR_NONE;
			$this->data = json_decode($string);
			
			if($this->data == NULL) {
				$this->errorCode = json_last_error();
			}
		}
		
		
		public function isValid()
		{
			return $this->errorCode == JSON_ERROR_NONE;
		}
		
		
		public function getErrorString()
		{
			return self::ErrorToString($this->errorCode);
		}
		
		
		public function getErrorCode()
		{
			return $this->errorCode;
		}
		
		
		public function getData()
		{
			return $this->data;
		}
		
		
		
		/**
		 * Converts an error code as returned by json_decode
		 *  into an error string.
		 *  
		 * @param int $error Error number
		 * @return string Description of the error
		 */
		public static function ErrorToString($error) {
			switch($error) {
				case JSON_ERROR_NONE:
					return "No error has occurred";
				case JSON_ERROR_DEPTH:
					return "The maximum stack depth has been exceeded";
				case JSON_ERROR_STATE_MISMATCH:
					return "Invalid or malformed JSON";
				case JSON_ERROR_CTRL_CHAR:
					return "Control character error, possibly incorrectly encoded";
				case JSON_ERROR_SYNTAX:
					return "Syntax error";
				case JSON_ERROR_UTF8:
					return "Malformed UTF-8 characters, possibly incorrectly encoded";
				case JSON_ERROR_RECURSION:
					return "One or more recursive references in the value to be encoded	PHP";
				case JSON_ERROR_INF_OR_NAN:
					return "One or more NAN or INF values in the value to be encoded";
				case JSON_ERROR_UNSUPPORTED_TYPE:
					return "A value of a type that cannot be encoded was given";
				default:
					return "Undefined error: " + str($error);				
			} 
		}
	}
