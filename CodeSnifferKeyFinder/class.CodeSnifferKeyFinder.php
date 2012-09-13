<?php
/**
 *
 * PHP CodeSniffer keys are build up like this:
 *
 *     <standard_folder>.<sniff_subfolder>.<sniff_file_without_Sniff_suffix>.<error_name>
 *
 * This class will look for all '*.sniff.php' files in a given directory,
 * and retrieve all the keys that have been registered in those files.
 *
 */
class CodeSnifferKeyFinder
{
	/**
	 * Find all PHP CodeSnifferKeys in a given directory.
	 *
	 * @param RecursiveDirectoryIterator $p_sFolder
	 *
	 * @return array
	 */
	public function findKeysInFolder(RecursiveDirectoryIterator $p_sFolder)
	{
		$aKeys = array();

		return $aKeys;
	}
}

#EOF