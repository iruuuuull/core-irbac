<?php

/**
 * Library atau helper untuk simpen method response
 */
class Response
{
	private $CI;

	public function __construct()
	{
		$this->CI =& get_instance();
	}

	public function api($meta = [], $data = [], $message = '', $status = true, $httpCode = 200)
	{
		return $this->CI->output
	        ->set_content_type('application/json')
	        ->set_status_header($httpCode) // Return status
	        ->set_output(json_encode([
	            'status' => $status,
	            'meta' => $meta,
	            'data' => $data,
	            'message' => $message
	        ]));
	}

	public function apiString($meta = [], $data = [], $message = '', $status = true)
	{
		return json_encode([
            'status' => $status,
            'meta' => $meta,
            'data' => $data,
            'message' => $message
        ]);
	}
}
