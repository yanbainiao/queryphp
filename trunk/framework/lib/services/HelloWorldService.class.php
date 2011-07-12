<?php
/**
 * AMF enabled service class HelloWorldService
 *
 * Project: sf_sandbox
 * 
 * @package   de.shiftup.flextest
 * @author    Your name here
 *
 * @version SVN: $Id $
 */
class HelloWorldService extends sfAmfService {
     public function sayHello($who) {
      return "Hello ".$who;
    }
}