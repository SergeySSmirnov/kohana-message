<?php defined('SYSPATH') or die('No direct script access.'); 
/**
 * Message is a class that lets you easily send messages of different types in same time in your application (aka Flash Messages).
 * @package Message
 * @author Sergey S. Smirnov 
 * @author Dave Widmer
 * @see https://github.com/SergeySSmirnov/kohana-message
 * @see http://github.com/daveWid/message
 * @copyright  2010-2011 © Dave Widmer
 * @copyright  2014-2016 © Sergey S. Smirnov
 */
class Message_Core
{
	/**
	 * The default view if one isn't passed into display/render.
	 * @var string
	 */
	public static $default = "message/basic";

	
	/**
	 * Type of message: ERROR.
	 * @var string
	 */
	const ERROR = 'error';
	/**
	 * Type of message: NOTICE.
	 * @var string
	 */
	const NOTICE = 'notice';
	/**
	 * Type of message: SUCCESS.
	 * @var string
	 */
	const SUCCESS = 'success';
	/**
	 * Type of message: WARN.
	 * @var string
	 */
	const WARN = 'warn';

	
	/**
	 * The messages to display.
	 * @var array
	 */
	public $message = array();
	/**
	 * The type of message.
	 * @var string
	 */
	public $type;

	
	/**
	 * Creates a new Message instance.
	 * @param string Type of message.
	 * @param string|array The message(s) to display.
	 */
	public function __construct($type, $message) {
		$this->type = $type;
		$this->message = (array)$message;
	}

	
	/**
	 * Clears the message from the session.
	 */
	public static function clear() {
		Session::instance()->delete('flash_message');
	}
	/**
	 * Displays the message.
	 * @param string Name of the view.
	 * @return string Message(s) to string (HTML).
	 */
	public static function display($view = null) {
		$_html = "";
		if($_msg = self::get()) {
			self::clear();
			$_html = View::factory(Text::getVal($view, self::$default))->set('messages', $_msg)->render();
		}
		return $_html;
	}
	/**
	 * The same as display - used to mold to Kohana standards.
	 * @param string Name of the view.
	 * @return string Message(s) to string (HTML).
	 */
	public static function render($view = null) {
		return self::display($view);
	}
	/**
	 * Gets all messages (if not defined param) or the messages of defined in param type. 
	 * @param string $type The type of message or get messages of all types.
	 * @return Message|array The message or array of messages.
	 */
	public static function get($type = null) {
		$_selfMess = Session::instance()->get('flash_message', array());
		return (!empty($type) && isset($_selfMess[$type])) ? $_selfMess[$type] : $_selfMess;
	}
	/**
	 * Sets a message.
	 * @param string Type of message.
	 * @param string|array String or array of string for the message.
	 */
	public static function set($type, $message) {
		$_messages = self::get();
		if ( ! (isset($_messages[$type]) && ($_messages[$type] instanceof Message)))
			$_messages[$type] = new Message($type, $message);
		elseif (is_array($message))
			$_messages[$type]->message = array_merge($_messages[$type]->message, $message);
		else
			$_messages[$type]->message[] = $message;
		Session::instance()->set('flash_message', $_messages);
	}
	/**
	 * Sets an error message.
	 * @param string|array String or array of strings for the message(s).
	 */
	public static function error($message) {
		self::set(Message::ERROR, $message);
	}
	/**
	 * Sets a notice.
	 * @param string|array String or array of strings for the message(s).
	 */
	public static function notice($message) {
		self::set(Message::NOTICE, $message);
	}
	/**
	 * Sets a success message.
	 * @param string|array String or array of strings for the message(s).
	 */
	public static function success($message) {
		self::set(Message::SUCCESS, $message);
	}
	/**
	 * Sets a warning message.
	 * @param string|array String or array of strings for the message(s).
	 */
	public static function warn($message) {
		self::set(Message::WARN, $message);
	}

}
