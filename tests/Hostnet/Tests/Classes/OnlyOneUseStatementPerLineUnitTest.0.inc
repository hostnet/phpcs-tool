<?php

use SomeSpace; // comment with , comma inside
use SomeOtherSpace as Hello, ThisShouldNotBeHere;
use ANewSpace\/* comment*/CommentInsideUse;

use  LostIn\Space , FinalSpace;

class ANewBegining
{
  use SomeTrait , SomeOtherTrait; // this should pass this test (Traits are not included in the test)

  function helloWorld()
  {
    echo "Hello!" ;
  }
}