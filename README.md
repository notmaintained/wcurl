# wcurl

A convenience wrapper around PHP's cURL library


## Requirements

* PHP 5+ with [cURL support](http://php.net/manual/en/book.curl.php).


## Download

### Via [Composer](http://getcomposer.org/) (Preferred)

Create a `composer.json` file if you don't already have one in your projects root directory and require wcurl:

```
{
	"require": {
		"sandeepshetty/wcurl": "dev-master"
	}
}
```

Install Composer:
```
$ curl -s http://getcomposer.org/installer | php
```

Run the install command:
```
$ php composer.phar install
```

This will download wcurl into the `vendor/sandeepshetty/wcurl` directory.

To learn more about Composer visit http://getcomposer.org/


### Via an archive
Download the [latest version of wcurl](https://github.com/sandeepshetty/wcurl/archives/master):

```shell
$ curl -L http://github.com/sandeepshetty/wcurl/tarball/master | tar xvz
$ mv sandeepshetty-wcurl-* wcurl
```

## Use


### Description

string __wcurl__( string _$method_ , string _$url_ [, mixed _$query_ [, mixed _$payload_ [, array _$request_headers_ [, array _&$response_headers_ [, array _$curl_opts_ ]]]]] )


### Examples

```php
<?php

	require 'path/to/wcurl/wcurl.php';


	// Basic GET request
	$response_body = wcurl('GET', 'https://api.github.com/gists/public');


	// GET request with query string parameters
	$response_body = wcurl('GET', 'https://api.github.com/gists/public', array('page'=>1, 'per_page'=>2));


	// Basic POST request
	// Parameters you want to skip can be passed as NULL. For example, here the query parameter is passed as NULL.
	$body = wcurl('POST', 'http://duckduckgo.com/', NULL, array('q'=>'42', 'format'=>'json'));


	// POST request with a custom request header (Content-Type) and an overriden cURL opt (CURLOPT_USERAGENT)
	// Also passing in a variable ($response_headers) to get back the response headers
	$response_headers = array();
	$response_body = wcurl
	(
		'POST',
		'https://api.github.com/gists',
		NULL,
		stripslashes(json_encode(array('description'=>'test gist', 'public'=>true, 'files'=>array('42.txt'=>array('content'=>'The Answer to the Ultimate Question of Life, the Universe, and Everything'))))),
		array('Content-Type: application/json; charset=utf-8'),
		$response_headers,	// This variable is filled with the response headers
		array(CURLOPT_USERAGENT=>'MY_APP_NAME')
	);

?>
```