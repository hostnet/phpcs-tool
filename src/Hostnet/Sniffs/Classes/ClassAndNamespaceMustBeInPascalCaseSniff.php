<?php

/**
 * Namespaces MUST be written in PascalCase (UpperCamelCase) (i.e. Hostnet/Xml/Formatter).
 * https://wiki.hostnetbv.nl/Coding_Standards#2.1.3
 *
 * Class names MUST be declared in PascalCase (i.e. XmlFormatter).
 * https://wiki.hostnetbv.nl/Coding_Standards#3.1.2
 */
class Hostnet_Sniffs_Classes_ClassAndNamespaceMustBeInPascalCaseSniff implements \PHP_CodeSniffer_Sniff
{
    /**
     * @return string[]
     */
    public function register()
    {
        return [T_CLASS, T_INTERFACE, T_TRAIT, T_NAMESPACE];
    }

    /**
     * @param \PHP_CodeSniffer_File $phpcs_file
     * @param int                   $stack_ptr
     */
    public function process(\PHP_CodeSniffer_File $phpcs_file, $stack_ptr)
    {
        $index = 0;
        // Find first string (= name).
        while (isset($phpcs_file->getTokens()[$stack_ptr + $index])
            && $phpcs_file->getTokens()[$stack_ptr + ($index)]['type'] !== 'T_STRING'
        ) {
            $index++;
        }

        $ptr     = $stack_ptr + $index;
        $f_names = [$phpcs_file->getTokens()[$ptr]['content']];

        // If it's a namespace, get all parts.
        $ptr++;
        while ($phpcs_file->getTokens()[$ptr]['type'] === 'T_NS_SEPARATOR') {
            $ptr++;
            $f_names[] = $phpcs_file->getTokens()[$ptr]['content'];
            $ptr++;
        }

        foreach ($f_names as $f_name) {
            if (preg_match('/^([A-Z][a-z0-9]+)*[A-Z][a-z0-9]*$/', $f_name)) {
                continue;
            }

            $type = $phpcs_file->getTokens()[$stack_ptr]['content'];
            $phpcs_file->addError(sprintf('Invalid %1$s name, %1$s name should be in PascalCase.', $type), $ptr);
        }

        return;
    }
}
