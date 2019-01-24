<?php

class Controller {

	public $model;
	public $view;

	function run()
	{
		$this->model = new Model();
		$this->model->runny();
		if ($error = $this->model->getError())
		{
			var_dump($error);
		}
		else
		{
			$this->view = new View();
			$this->view->render($this->model->getData(), $this->model->getNumber(), $this->model->getPagesCount());
		}
	}
}
