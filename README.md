# Php CodeSniffer Key Finder
[![Build Status](https://secure.travis-ci.org/potherca/PhpCodeSnifferKeyFinder.png)][1]

This project consists of a class that has one goal: Get all the available keys
from [PHP_CodeSniffer][2] [Sniffs folders][3]

The `getKeysFromSniffs.php` file adds a rather simple interface to this
functionality from either a web host or command-line.

**Webhostexample**  
`http://your.example.com/?dir=/path/to/code/sniffs/`

**Commandline example**  
`php getKeysFromSniffs.php /path/to/code/sniffs/`

## Dependencies
Requires PHP 5.3 or higher


[1]: http://travis-ci.org/potherca/PhpCodeSnifferKeyFinder
[2]: http://pear.php.net/package/PHP_CodeSniffer
[3]: https://github.com/squizlabs/PHP_CodeSniffer/tree/master/CodeSniffer/Standards