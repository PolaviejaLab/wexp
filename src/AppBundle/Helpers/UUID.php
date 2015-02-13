<?php 
	namespace AppBundle\Helpers;
	
	/**
	 * Generates globally unique identifiers
	 * 
	 * A UUID is a 128 bit number represented by 32 lowercase hexadecimal digits
	 * commonly hyphenated as follows:
	 * 
	 * 8-4-4-4-12 (for a total of 36 characters)
	 * 
	 * To use this function use:
	 *  use AppBundle\Helpers\UUID;
	 *  $uuid = UUID::generateUUID();
	 */
	class UUID
	{
		public static function generateUUID($version = 4)
		{
			if($version != 4)
				throw new \Exception("Unsupported UUID version");
			
    		return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
		        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
        		mt_rand( 0, 0xffff ),
		        mt_rand( 0, 0x0fff ) | 0x4000,
		        mt_rand( 0, 0x3fff ) | 0x8000,
		        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
    		);
		}
	}
