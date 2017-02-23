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
class Message_Core {

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
	 * The default view if one isn't passed into display/render.
	 * @var string
	 */
	public static $default = "message/basic";
	/**
	 * Config of module.
	 * @var Config_Group
	 */
	protected static $config = null;


	/**
	 * Clears the message from the session.
	 */
	public static function clear() {
		self::getSession()->delete(self::$config['session_key']);
	}
	/**
	 * Gets all messages (if not defined param) or the messages of defined in param type.
	 * @param string $type The type of message or get messages of all types.
	 * @return Message|array The message or array of messages.
	 */
	public static function get(string $type = null) {
		$_selfMess = self::getSession()->get(self::$config['session_key'], array());
		return (!empty($type) && isset($_selfMess[$type])) ? $_selfMess[$type] : $_selfMess;
	}
	/**
	 * Sets a message.
	 * @param string Type of message.
	 * @param string|array String or array of string for the message.
	 */
	public static function set(string $type, $message) {
		$_messages = self::get();
		if (!(isset($_messages[$type]) && ($_messages[$type] instanceof Message)))
			$_messages[$type] = new Message($type, $message);
		elseif (is_array($message))
			$_messages[$type]->message = array_merge($_messages[$type]->message, $message);
		else
			$_messages[$type]->message[] = $message;
		self::getSession()->set(self::$config['session_key'], $_messages);
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
	/**
	 * Render the message block.
	 * @param string Name of the view.
	 * @return string Message(s) to string (HTML).
	 */
	public static function render(string $view = null) : string {
		$_html = "";
		if($_msg = self::get()) {
			self::clear();
			$_html = View::factory(Text::getVal($view, self::$default))->set('messages', $_msg)->render();
		}
		return $_html;
	}


	/**
	 * Load current module config and return current user session.
	 * @return Session Current user session.
	 */
	protected static function getSession() : Session {
		if(!isset(self::$config))
			self::$config = Kohana::$config->load('message');
			return Session::instance(self::$config['session_type']);
	}


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
	protected function __construct($type, $message) {
		$this->type = $type;
		$this->message = (array)$message;
	}

}
