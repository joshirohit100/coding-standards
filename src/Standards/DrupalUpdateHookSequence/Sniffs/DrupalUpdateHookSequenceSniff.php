<?php

/**
 * \RohitJoshi\CodingStandards\DrupalUpdateHookSequenceSniff\Sniffs\DrupalUpdateHookSequenceSniff.
 *
 * @category PHP
 * @package  PHP_CodeSniffer
 * @link     http://pear.php.net/package/PHP_CodeSniffer
 */

namespace RohitJoshi\CodingStandards\DrupalUpdateHookSequenceSniff\Sniffs;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

class DrupalUpdateHookSequenceSniff implements Sniff
{
    protected string $fileName;

    public function register(): array
    {
        return [
            T_FUNCTION,
        ];
    }

    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        $this->fileName = explode('.', basename($phpcsFile->getFilename()))[0];

        $fileExtension = strtolower(substr($phpcsFile->getFilename(), -8));
        // Only for *.install files.
        if ($fileExtension !== '.install') {
            return ($phpcsFile->numTokens + 1);
        }

        if ($tokens[$stackPtr]['code'] == T_FUNCTION) {
            $pointer_location = $stackPtr + 2;

            $positions = [];
            $position = $this->getForErrorPositions($tokens, $pointer_location);
            if (!empty($position)) {
                $positions[] = $position;
                $pointer_location = $pointer_location + 1;
                while ($pointer_location = $phpcsFile->findNext(T_FUNCTION, $pointer_location)) {
                    $position = $this->getForErrorPositions($tokens, $pointer_location + 2);
                    if (!empty($position)) {
                        $positions[] = $position;
                    }
                    $pointer_location = $pointer_location + 1;
                }
            }

            if (count($positions) > 1) {
                for ($i = 0; $i < count($positions) - 1; $i++) {
                    // If this is not last hook_update in list
                    // and hook version is not sorted.
                    if (!empty($positions[$i+1])
                        && $positions[$i]['hook_version'] < $positions[$i+1]['hook_version']) {
                        $phpcsFile->addWarning('Hook version is not in sequence. Latest update hook should be on top.', $positions[$i]['position'], 'DrupalUpdateHookNotInSequence');
                        break;
                    }
                }
            }

        }
    }

    private function getForErrorPositions(array $tokens, int $position): array {
        $func_name = $tokens[$position]['content'];
        $error_positions = [];
        if (str_starts_with($func_name, $this->fileName . '_update_')) {
            $pattern = "/^{$this->fileName}_update_([0-9]*)$/";
            if (preg_match($pattern, $func_name, $matches)) {
                $error_positions = [
                    'position' => $position,
                    'hook_version' => $matches[1],
                ];
            }
        }

        return  $error_positions;
    }

}
