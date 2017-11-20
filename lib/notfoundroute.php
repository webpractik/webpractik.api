<?php

namespace Webpractik\Api;

class NotFoundRoute extends Response
{
	public function handler() {
		$this->response->addError('Не найден роут');
		$this->response->send();
	}
}