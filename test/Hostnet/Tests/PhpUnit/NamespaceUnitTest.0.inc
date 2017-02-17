<?php

class NamespaceTest extends PHPUnit_Framework_TestCase
{

}

class NamespaceTest extends \PHPUnit_Framework_TestCase
{

}

class NamespaceTest extends TestCase\Nope
{

}

class NamespaceTest extends \TestCase
{

}

class NamespaceTest extends Nope\TestCase
{

}

class NamespaceTest extends TestCase
{

}
