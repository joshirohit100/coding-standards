<?php

/**
 * \RohitJoshi\CodingStandards\DrupalLibraryVersionPatternSniff\Sniffs\DrupalLibraryVersionPatternSniff.
 *
 * @category PHP
 * @package  PHP_CodeSniffer
 * @link     http://pear.php.net/package/PHP_CodeSniffer
 */

namespace RohitJoshi\CodingStandards\DrupalLibraryVersionPatternSniff\Sniffs;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

class DrupalLibraryVersionPatternSniff implements Sniff
{
    public function register(): array
    {
        return [T_INLINE_HTML];
    }

    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        $fileExtension = strtolower(substr($phpcsFile->getFilename(), -14));
        if ($fileExtension !== '.libraries.yml') {
            return ($phpcsFile->numTokens + 1);
        }

        // If "version" key exists in the library definition.
        if (preg_match('/^\s*version:\s*([^\s]+)/m', $tokens[$stackPtr]['content'], $matches) === 1
            && isset($tokens[($stackPtr - 1)]) === true
        ) {
            // If version is not proper like "1.0.0" or "1.0.1".
            // Version value should only contain number and ".".
            if (!empty($matches[1]) && !preg_match("/^[0-9.]+$/i", $matches[1])) {
                $phpcsFile->addWarning('Library version is not correct. It should be like 1.0.0', $stackPtr, 'InvalidLibraryVersion');
            }
        }
    }

}
