<?php

/**
 * Property names and variables MUST be declared in snake case (i.e. $my_property).
 *
 * https://wiki.hostnetbv.nl/Coding_Standards#3.3.1
 *
 * @todo Change from addWarning to addError.
 *
 * @author Maarten Steltenpool <msteltenpool@hostnet.nl>
 */
class Hostnet_Sniffs_Classes_VariableAndPropertyMustBeInSnakeCaseSniff implements \PHP_CodeSniffer_Sniff
{
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
        $f_name = $phpcs_file->getTokens()[$stack_ptr]['content'];
        $f_name = str_replace('$', '', $f_name);
        if (preg_match('/^[a-z]([a-z0-9_]*[a-z0-9])?$/', $f_name)) {
            return;
        }

        $name = $phpcs_file->getTokens()[$stack_ptr]['content'];
        $phpcs_file->addWarning(sprintf('%1$s is invalid, %1$s should be in snake_case.', $name), $stack_ptr);

        return;
    }
}
