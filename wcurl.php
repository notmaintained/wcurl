<?php


	class WcurlException extends Exception { }

	function wcurl($method, $url, $query='', $payload='', $request_headers=array(), &$response_headers=array(), $curl_opts=array())
	{
		$ch = curl_init(wcurl_request_uri($url, $query));
		wcurl_setopts($ch, $method, $payload, $request_headers, $curl_opts);
		$res = curl_exec($ch);
		$errno = curl_errno($ch);
		$error = curl_error($ch);
		curl_close($ch);

		if ($errno) throw new WcurlException($error, $errno);

		list($msg_headers, $msg_body) = preg_split("/\r\n\r\n|\n\n|\r\r/", $res, 2);
		$response_headers = wcurl_response_headers($msg_headers);

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
				CURLOPT_USERAGENT => 'HAC',
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
				if (!empty($request_headers)) $default_curl_opts[CURLOPT_HTTPHEADER] = $request_headers;
				if (!empty($payload))
				{
					if (is_array($payload)) $payload = http_build_query($payload);
					$default_curl_opts[CURLOPT_POSTFIELDS] = $payload;
				}
			}

			$overriden_opts = $curl_opts + $default_curl_opts;
			foreach ($overriden_opts as $curl_opt=>$value) curl_setopt($ch, $curl_opt, $value);
		}

		function wcurl_response_headers($msg_headers)
		{
			$header_lines = preg_split("/\r\n|\n|\r/", $msg_headers);
			$headers = array();
			list(, $headers['http_status_code'], $headers['http_status_message']) = explode(' ', trim(array_shift($header_lines)), 3);
			foreach ($header_lines as $header_line)
			{
				list($name, $value) = explode(':', $header_line, 2);
				$headers[strtolower($name)] = trim($value);
			}

			return $headers;
		}

?>