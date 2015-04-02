<?php

/**
 * @author wrong
 */
class MyClass
{
    /**
     * @author not good
     */
    private $bar;

    /**
     * @author Someone <javascript@confuses.nu>
     */
    protected $foo;

    public $public_variable;

    /**
     * @author Someone <php@confuses.nu>
     * @return number
     */
    public function find()
    {
      $a_variable = 1;
      return $a_variable;
    }

    /**
     * @author Hmmm <comments>
     */
    protected function huh()
    {
    }

   /**
   *
   * @return boolean
   */
    protected function getBoolean()
    {
      return true;
    }

    /**
     * @author Nico <nshoonmaker@gmail.com>
     */
    protected function getNicoAuthor()
    {
    }

    /**
     * @return int
     */
    protected function getShouldHaveReturnValue()
    {
    }

    /**
     * @author Halleh Duffels-Khoshnevis <hkhoshnevis@hostnet.nl>
     */
    protected function getCorrectMarriedAuthor()
    {
    }

    /**
     * @author Kassandra Chäralampidøu <kassandra@hostnet.nl>
     */
    protected function getCorrectSpecialCharAuthor()
    {
    }

    protected $bleh;

    const COMPLICATED = 'public';
}