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
	public function __construct($mboxPath)
	{
		$this->_Mbox = new \Mail_Mbox($mboxPath);
		$this->_Mbox->open();
	}


	/**
	 * Destructor
	 */
	public function __destruct()
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
	 * Deletes an emails
	 *
	 * Note: messages start with 0.
	 *
	 * @param int $message The number of the message to remove, or array of message ids to remove
	 */
	public function delete($message) {
		$this->_Mbox->remove($message);
	}


	/**
	 * Returns a single message
	 * 
	 * @param int $messageIndex The zero-based index of the message to return.
	 */
	public function getMessage($messageIndex){
		$message	= $this->_Mbox->get($messageIndex);
		$Decoder	= new \Mail_mimeDecode($message, "\r\n");
		return array(
			'num'		=> $messageIndex + 1,
			'raw'		=> $message,
			'decoded'	=> $Decoder->decode($this->_defaultMimeParams)
		);
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
			$messages[]	= $this->getMessage($n);
		}

		// Optionally sort messages
		if (is_array($sort)) {
			$array_values = array_values($sort);
			$array_value = $array_values[0];
			if ($array_value == 'ASC' || $array_value == 'DESC') {
				$key	= array_keys($sort);
				$key	= $key[0];
				$order	= array_values($sort);
				$order	= $order[0];

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
		}

		return $messages;
	}
}
