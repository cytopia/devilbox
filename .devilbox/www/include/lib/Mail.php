<?php
namespace devilbox;

/**
 * @requires Pear::Mail_Mbox (http://pear.php.net/package/Mail_Mbox)
 * @requires Pear::Mail_mimeDecode (http://pear.php.net/package/Mail_mime)
 */
class Mail
{
	/**
	 * Mbox object.
	 * @var object
	 */
	private $_Mbox;


	/**
	 * Default options for Mime decoding.
	 * @var array
	 */
	private $_defaultMimeParams = array(
		'include_bodies'	=> true,
		'decode_bodies'		=> true,
		'decode_headers'	=> true,
		'crlf'				=> "\r\n"
	);


	/**
	 * Contstructor
	 *
	 * @param string $mboxPath Path to mbox file.
	 */
	function __construct($mboxPath)
	{
		$this->_Mbox = new \Mail_Mbox($mboxPath);
		$this->_Mbox->open();
	}


	/**
	 * Destructor
	 */
	function __destruct()
	{
		$this->_Mbox->close();
	}


	/**
	 * Set/Overwrite Mime dedocing options.
	 *
	 * @param mixed[] $mimeParams Options for mime decoding.
	 */
	public function setMimeParams($mimeParams)
	{
		$this->_defaultMimeParams = array_merge($this->_defaultMimeParams, $mimeParams);
	}


	/**
	 * Retrieve emails.
	 *
	 * @param  mixed[] $sort array($sort => $order) Array
	 * @return mixed[] Array of emails
	 */
	public function get($sort = null)
	{
		// Stores all messages
		$messages = array();


		// Total number of emails in mbox file
		$total	= $this->_Mbox->size() - 1;

		// Get messages in reverse order (last entry first)
		for ($n = $total; $n >= 0; --$n) {
			$message	= $this->_Mbox->get($n);
			$Decoder	= new \Mail_mimeDecode($message, "\r\n");
			$messages[]	= array(
				'num'		=> $n + 1,
				'raw'		=> $message,
				'decoded'	=> $Decoder->decode($this->_defaultMimeParams)
			);
		}

		// Optionally sort messages
		if (is_array($sort) && (array_values($sort)[0] == 'ASC' || array_values($sort)[0] == 'DESC')) {
			$key	= array_keys($sort)[0];
			$order	= array_values($sort)[0];

			$sorter = function ($a, $b) use ($key, $order) {
				$val1 = $a['decoded']->headers[$key];
				$val2 = $b['decoded']->headers[$key];

				// Convert date strings to timestamps for comparison
				if (strtotime($val1) !== false && strtotime($val2) !== false) {
					$val1 = strtotime($val1);
					$val2 = strtotime($val2);
				}

				if ($order === 'ASC') {
					return (strcmp($val1, $val2) > 0);
				} else {
					return (strcmp($val1, $val2) <= 0);
				}
			};
			usort($messages, $sorter);
		}

		return $messages;
	}
}
