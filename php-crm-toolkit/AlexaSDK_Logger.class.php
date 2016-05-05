<?php

/**
 * AlexaSDK_Logger.php
 * 
 * This file defines AlexaSDK_AlexaSDK_Logger and AlexaSDK_Log class 
 * that used to log the errors and debug infromation into log file
 * 
 * @author alexacrm.com
 * @version 1.0
 * @package AlexaSDK
 */


/**
 * Logs the messages into the log file with the KLogger
 */
class AlexaSDK_Logger extends AlexaSDK_Abstract{

	/**
	 * Log the message or full exception stack into log file
	 * 
	 * @param string $message The message to log if AlexaSDK_Abstract::$debugMode is enabled
	 * @param Exception $exception the object of Exception class to write trace into log file
	 * 
	 */
	public static function log($message, $exception = NULL){
			if (self::$enableLogs){

				$l = AlexaSDK_Log::instance();

				if ($exception){
					$l->LogError((string)$exception);
				}else if (self::$debugMode){
					$l->LogDebug($message);
				}
			}
	}
}

/**
 * Singletone instance of the KLogger class
 */
class AlexaSDK_Log{
	
	/**
	 * @var Klogger $_instance;
	 */
	protected static $_instance;
	
	/**
	 * Main KLogger Instance
	 *
	 * Ensures only one instance of KLogger is loaded or can be loaded.
	 * 
	 * @static
	 * @return KLogger class object
	 */
	public static function instance() {
		
		if (is_null(self::$_instance)) {
			self::$_instance = new KLogger(self::getLogPath(), KLogger::DEBUG);
		}
		return self::$_instance;
	}
	
	private static function getLogPath(){
		$dir = trailingslashit( dirname(__FILE__)).trailingslashit("logs");
		
		if (!file_exists($dir)){
			self::createLogDir($dir);
		}
		
		$htaccess_file = $dir . '.htaccess';
		if ( !file_exists( $htaccess_file ) ) {
			self::createHtaccess($htaccess_file);
		}
		
		$log = $dir."log.txt";
		if (!file_exists($log)){
			self::createLogFile($dir, $log);
		}
		return $log;
	}
	
	
	
	private static function createLogFile($log){
		// Create the log file with fopen function
		if ( $handle = @fopen( $log, 'w' ) ) {
			fclose( $handle );
		}
	}
	
	private static function createHtaccess($htaccess_file){
		// Check the .htaccess file in the log dir
		if ( $handle = @fopen( $htaccess_file, 'w' ) ) {
			fwrite( $handle, "Deny from all\n" );
			fclose( $handle );
		}
	}
	
	private static function createLogDir($target){
		// From php.net/mkdir user contributed notes.
		$target = str_replace( '//', '/', $target );
		/*
		 * Safe mode fails with a trailing slash under certain PHP versions.
		 * Use rtrim() instead of untrailingslashit to avoid formatting.php dependency.
		 */
		$target = rtrim($target, '/');
		if ( empty($target) )
			$target = '/';

		if ( file_exists( $target ) )
			return @is_dir( $target );

		// We need to find the permissions of the parent folder that exists and inherit that.
		$target_parent = dirname( $target );
		while ( '.' != $target_parent && ! is_dir( $target_parent ) ) {
			$target_parent = dirname( $target_parent );
		}

		// Get the permission bits.
		if ( $stat = @stat( $target_parent ) ) {
			$dir_perms = $stat['mode'] & 0007777;
		} else {
			$dir_perms = 0777;
		}

		if ( @mkdir( $target, $dir_perms, true ) ) {
			/*
			 * If a umask is set that modifies $dir_perms, we'll have to re-set
			 * the $dir_perms correctly with chmod()
			 */
			if ( $dir_perms != ( $dir_perms & ~umask() ) ) {
				$folder_parts = explode( '/', substr( $target, strlen( $target_parent ) + 1 ) );
				for ( $i = 1, $c = count( $folder_parts ); $i <= $c; $i++ ) {
					@chmod( $target_parent . '/' . implode( '/', array_slice( $folder_parts, 0, $i ) ), $dir_perms );
				}
			}

			return true;
		}

		return false;
	}

}