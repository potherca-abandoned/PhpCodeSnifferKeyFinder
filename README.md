# Php CodeSniffer Key Finder

This project consists of a class that has one goal: Get all the available keys
from [PHP_CodeSniffer][1] [Sniffs folders][2]

The `getKeysFromSniffs.php` file adds a rather simple interface to this
functionality from either a web host or command-line.

*Webhostexample*
`http://your.example.com/?dir=/path/to/code/sniffs/`

*Commandline example*
`php getKeysFromSniffs.php /path/to/code/sniffs/`

## Dependencies
Requires PHP 5.3 or higher

[1]: http://pear.php.net/package/PHP_CodeSniffer
[2]: https://github.com/squizlabs/PHP_CodeSniffer/tree/master/CodeSniffer/Standards