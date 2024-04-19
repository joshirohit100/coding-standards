<?php

/**
 * \RohitJoshi\CodingStandards\DrupalHookInstallLast\Sniffs\DrupalHookInstallLastSniff.
 *
 * @category PHP
 * @package  PHP_CodeSniffer
 * @link     http://pear.php.net/package/PHP_CodeSniffer
 */

namespace RohitJoshi\CodingStandards\DrupalHookUpdateNComment\Sniffs;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

class DrupalHookInstallLastSniff implements Sniff{

    public function register()
    {
        return [
            T_FUNCTION,
        ];
    }

    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        $fileExtension = strtolower(substr($phpcsFile->getFilename(), -8));
        if ($fileExtension !== '.install') {
            return ($phpcsFile->numTokens + 1);
        }

        $fileName = explode('.', basename($phpcsFile->getFilename()))[0];

        // If this is hook_install() function.
        $pointer_location = $stackPtr + 2;
        if ($tokens[$pointer_location]['content'] === $fileName . '_install') {
            // To check if there is any function hook_update_N() after this hook_install().
            $pointer_location = $pointer_location + 1;
            while ($pointer_location = $phpcsFile->findNext(T_FUNCTION, $pointer_location)) {
                $func_name = $tokens[$pointer_location + 2]['content'];
                if (str_starts_with($func_name, $fileName . '_update_')) {
                    $pattern = "/^{$fileName}_update_([0-9]*)$/";
                    if (preg_match($pattern, $func_name)) {
                        $phpcsFile->addWarning('HOOK_install() should be defined after all the update hooks.', $stackPtr, 'DrupalHookInstallLast');
                        break;
                    }
                }

                $pointer_location = $pointer_location + 1;
            }
        }
    }

}
