<?php
declare(strict_types=1);

class ErrorController extends Controller
{
	protected $_exception = null;

	public function setException(Exception $exception)
	{
		$this->_exception = $exception;
	}
	
	public function errorAction()
	{
		header("HTTP/1.0 404 Not Found");
		
		$this->view->error = $this->_exception->getMessage();
	}
}
