# wcurl

A convenience wrapper around PHP's cURL library


## Requirements

* PHP 5+ with [cURL support](http://php.net/manual/en/book.curl.php).


## Download
Download the [latest version of wcurl](https://github.com/sandeepshetty/wcurl/archives/master):

```shell
$ curl -L http://github.com/sandeepshetty/wcurl/tarball/master | tar xvz
$ mv sandeepshetty-wcurl-* wcurl
```


## Use

string __wcurl__( string _$method_ , string _$url_ [, mixed _$query_ [, mixed _$payload_ [, array _$request_headers_ [, array _&$response_headers_ [, array _$curl_opts_ ]]]]] )


```php
<?php

	require 'path/to/wcurl/wcurl.php';

	$body = wcurl('GET', 'http://duckduckgo.com/');

	// Query string examples
	$body = wcurl('GET', 'http://duckduckgo.com/?q=foobar');
	$body = wcurl('GET', 'http://duckduckgo.com/', array('q'=>'foobar'));
	$body = wcurl('GET', 'http://duckduckgo.com/', 'q=foobar');

?>
```