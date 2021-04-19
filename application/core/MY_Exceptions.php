<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @link https://stackoverflow.com/a/30083294
 */
class MY_Exceptions extends CI_Exceptions
{
	private $stati = [
		100	=> 'Continue',
		101	=> 'Switching Protocols',

		200	=> 'OK',
		201	=> 'Created',
		202	=> 'Accepted',
		203	=> 'Non-Authoritative Information',
		204	=> 'No Content',
		205	=> 'Reset Content',
		206	=> 'Partial Content',

		300	=> 'Multiple Choices',
		301	=> 'Moved Permanently',
		302	=> 'Found',
		303	=> 'See Other',
		304	=> 'Not Modified',
		305	=> 'Use Proxy',
		307	=> 'Temporary Redirect',

		400	=> 'Bad Request',
		401	=> 'Unauthorized',
		402	=> 'Payment Required',
		403	=> 'Forbidden',
		404	=> 'Not Found',
		405	=> 'Method Not Allowed',
		406	=> 'Not Acceptable',
		407	=> 'Proxy Authentication Required',
		408	=> 'Request Timeout',
		409	=> 'Conflict',
		410	=> 'Gone',
		411	=> 'Length Required',
		412	=> 'Precondition Failed',
		413	=> 'Request Entity Too Large',
		414	=> 'Request-URI Too Long',
		415	=> 'Unsupported Media Type',
		416	=> 'Requested Range Not Satisfiable',
		417	=> 'Expectation Failed',
		422	=> 'Unprocessable Entity',
		426	=> 'Upgrade Required',
		428	=> 'Precondition Required',
		429	=> 'Too Many Requests',
		431	=> 'Request Header Fields Too Large',

		500	=> 'Internal Server Error',
		501	=> 'Not Implemented',
		502	=> 'Bad Gateway',
		503	=> 'Service Unavailable',
		504	=> 'Gateway Timeout',
		505	=> 'HTTP Version Not Supported',
		511	=> 'Network Authentication Required',
	];

	public function show_error($heading, $message, $template = 'error_general', $status_code = 500)
	{
		if (!class_exists('CI_Controller')) {
			return parent::show_error($heading, $message, 'error_general', 500);
		}

		if ($template == 'error_general') {
			$template = 'custom_error_general';
		}

		$CI =& get_instance();
		$templates_path = config_item('error_views_path');

		if (empty($templates_path)) {
			$templates_path = VIEWPATH.'errors'.DIRECTORY_SEPARATOR;
		}

		if (is_cli()) {
			$message = "\t".(is_array($message) ? implode("\n\t", $message) : $message);
			$template = 'cli'.DIRECTORY_SEPARATOR.$template;
		} else {
			set_status_header($status_code);
			$message = '<p>'.(is_array($message) ? implode('</p><p>', $message) : $message).'</p>';
			$template = 'html'.'/'.$template;

			$view = $CI->load->view('layouts/main', [
				'data' => [
					'heading' => def($this->stati, $status_code, $heading),
					'message' => $message,
					'status_code' => $status_code,
				],
				'title' => $status_code .' '. def($this->stati, $status_code),
				'view' => 'errors/'.$template
			], true);
		}

		if (ob_get_level() > $this->ob_level + 1) {
			ob_end_flush();
		}

		ob_start();
		echo $view;
		$buffer = ob_get_contents();
		ob_end_clean();
		return $buffer;
	}
	
}
