# Php CodeSniffer Key Finder

[![Build Status]][Travis Page] 
[![Project Stage: Experimental]][Project Stage Page] 
[![License Badge]][GPL3+]



This project consists of a class that has one goal: Get all the available keys
from [PHP_CodeSniffer]'s [Sniffs folders].

The `getKeysFromSniffs.php` file adds a rather simple interface to this
functionality from either a web host or command-line.

**Webhostexample**  
`http://your.example.com/?dir=/path/to/code/sniffs/`

**Commandline example**  
`php getKeysFromSniffs.php /path/to/code/sniffs/`

## Dependencies
Requires PHP 5.3 or higher


## License
Licensed under [GPL3+]


[Build Status]: https://img.shields.io/travis/potherca/PhpCodeSnifferKeyFinder.svg?style=flat-square
[Project Stage Page]: http://bl.ocks.org/potherca/a2ae67caa3863a299ba0
[Project Stage: Experimental]: https://img.shields.io/badge/Project%20Stage-Experimental-yellow.svg?style=flat-square
[Travis Page]: https://travis-ci.org/potherca/PhpCodeSnifferKeyFinder

[PHP_CodeSniffer]: https://pear.php.net/package/PHP_CodeSniffer
[Sniffs folders]: https://github.com/squizlabs/PHP_CodeSniffer/tree/master/CodeSniffer/Standards

[GPL3+]: LICENSE.md
[License Badge]: https://img.shields.io/badge/License-GPL3%2B-lightgray.svg?style=flat-square
