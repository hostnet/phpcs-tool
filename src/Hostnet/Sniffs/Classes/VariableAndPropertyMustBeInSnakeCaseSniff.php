<?php

/**
 * Property names and variables MUST be declared in snake case (i.e. $my_property).
 *
 * https://wiki.hostnetbv.nl/Coding_Standards#3.3.1
 */
class Hostnet_Sniffs_Classes_VariableAndPropertyMustBeInSnakeCaseSniff implements \PHP_CodeSniffer_Sniff
{
    const SUPER_GLOBALS = ['GLOBALS', '_SERVER', '_GET', '_POST', '_FILES', '_COOKIE', '_SESSION', '_REQUEST', '_ENV'];

    /**
     * @return string[]
     */
    public function register()
    {
        return [T_VARIABLE, T_PROPERTY];
    }

    /**
     * @param \PHP_CodeSniffer_File $phpcs_file
     * @param int                   $stack_ptr
     */
    public function process(\PHP_CodeSniffer_File $phpcs_file, $stack_ptr)
    {
        // If variable is used statically from other class, skip checks
        // If variable is read from object, skip checks
        if ($stack_ptr > 0) {
            $type = $phpcs_file->getTokens()[$stack_ptr-1]['type'];
            if ($type == 'T_DOUBLE_COLON'  || $type == 'T_OBJECT_OPERATOR') {
                return;
            }
        }

        $f_name = $phpcs_file->getTokens()[$stack_ptr]['content'];
        $f_name = str_replace('$', '', $f_name);
        if (preg_match('/^[a-z]([a-z0-9_]*[a-z0-9])?$/', $f_name)) {
            return;
        }

        if (in_array($f_name, self::SUPER_GLOBALS)) {
            return;
        }

        $name = $phpcs_file->getTokens()[$stack_ptr]['content'];
        $phpcs_file->addError(sprintf('%1$s is invalid, %1$s should be in snake_case.', $name), $stack_ptr);

        return;
    }
}
