<?php


	class WcurlException extends Exception { }

	function wcurl($method, $url, $query='', $payload='', $request_headers=array(), &$response_headers=array(), $curl_opts=array())
	{
		$ch = curl_init(wcurl_request_uri($url, $query));
		wcurl_setopts($ch, $method, $payload, $request_headers, $curl_opts);
		$response = curl_exec($ch);
		$curl_info = curl_getinfo($ch);
		$errno = curl_errno($ch);
		$error = curl_error($ch);
		curl_close($ch);

		if ($errno) throw new WcurlException($error, $errno);

		$header_size = $curl_info["header_size"];
		$msg_header = substr($response, 0, $header_size);
		$msg_body = substr($response, $header_size);

		$response_headers = wcurl_response_headers($msg_header);

		return $msg_body;
	}

		function wcurl_request_uri($url, $query)
		{
			if (empty($query)) return $url;
			if (is_array($query)) return "$url?".http_build_query($query);
			else return "$url?$query";
		}

		function wcurl_setopts($ch, $method, $payload, $request_headers, $curl_opts)
		{
			$default_curl_opts = array
			(
				CURLOPT_HEADER => true,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_MAXREDIRS => 3,
				CURLOPT_SSL_VERIFYPEER => true,
				CURLOPT_SSL_VERIFYHOST => 2,
				CURLOPT_USERAGENT => 'wcurl',
				CURLOPT_CONNECTTIMEOUT => 30,
				CURLOPT_TIMEOUT => 30,
			);

			if ('GET' == $method)
			{
				$default_curl_opts[CURLOPT_HTTPGET] = true;
			}
			else
			{
				$default_curl_opts[CURLOPT_CUSTOMREQUEST] = $method;

				// Disable cURL's default 100-continue expectation
				if ('POST' == $method) array_push($request_headers, 'Expect:');

				if (!empty($payload))
				{
					if (is_array($payload))
					{
						$payload = http_build_query($payload);
						array_push($request_headers, 'Content-Type: application/x-www-form-urlencoded; charset=utf-8');

					}

					$default_curl_opts[CURLOPT_POSTFIELDS] = $payload;
				}
			}

			if (!empty($request_headers)) $default_curl_opts[CURLOPT_HTTPHEADER] = $request_headers;

			$overriden_opts = $curl_opts + $default_curl_opts;
			foreach ($overriden_opts as $curl_opt=>$value) curl_setopt($ch, $curl_opt, $value);
		}

		function wcurl_response_headers($msg_header)
		{

			$multiple_headers = preg_split("/\r\n\r\n|\n\n|\r\r/", trim($msg_header));
			$last_response_header_lines = array_pop($multiple_headers);
			$response_headers = array();

			$header_lines = preg_split("/\r\n|\n|\r/", $last_response_header_lines);
			list(, $response_headers['http_status_code'], $response_headers['http_status_message']) = explode(' ', trim(array_shift($header_lines)), 3);
			foreach ($header_lines as $header_line)
			{
				list($name, $value) = explode(':', $header_line, 2);
				$response_headers[strtolower($name)] = trim($value);
			}

			return $response_headers;
		}

?>