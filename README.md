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

### Use
TODO: Add more examples

```php
<?php

	require 'path/to/wcurl/wcurl.php';
	$response = wcurl('GET', 'http://google.com/');

?>
```