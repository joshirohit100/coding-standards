<?php

/**
 * \RohitJoshi\CodingStandards\DrupalHookUpdateNComment\Sniffs\DrupalHookUpdateNCommentSniff.
 *
 * @category PHP
 * @package  PHP_CodeSniffer
 * @link     http://pear.php.net/package/PHP_CodeSniffer
 */

namespace RohitJoshi\CodingStandards\DrupalHookUpdateNComment\Sniffs;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

class DrupalHookUpdateNCommentSniff implements Sniff{

    public function register()
    {
        return [
            T_DOC_COMMENT_STRING,
        ];
    }

    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        $fileExtension = strtolower(substr($phpcsFile->getFilename(), -8));
        if ($fileExtension !== '.install') {
            return ($phpcsFile->numTokens + 1);
        }

        if (str_contains(strtolower($tokens[$stackPtr]['content']), strtolower('hook_update_N'))) {
            $phpcsFile->addError('Remove the hook_update_N() comment and use the actual comment.', $stackPtr, 'DrupalHookUpdateNComment');
        }
    }

}
