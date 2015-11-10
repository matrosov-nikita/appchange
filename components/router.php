<?php 
class Router {

	private $routes;

	public function __construct()
	{
		$routesPath = ROOT.'/config/routes.php';
		$this->routes = include($routesPath);
	}
	private function getURI() {
		if (!empty($_SERVER['REQUEST_URI'])) {
			return trim($_SERVER['REQUEST_URI'],'/');
		}
	}
	public function run() {
		
		$uri = $this->getURI();

		$time = date('H:i:s', time());
		file_put_contents("log.txt",'начинаю искать роут '.$time."\r\n",FILE_APPEND);
		if ($uri=="") {
			header("Location: get/1");
			return null;
		}
		foreach ($this->routes as $uriPattern => $path) {


			if (preg_match("~$uriPattern~", $uri))
			{
				$time = date('H:i:s', time());
				$internalRoute = preg_replace("~$uriPattern~",$path,$uri);
				file_put_contents("log.txt",'нашел роут '.$uri."\r\n",FILE_APPEND);
				$segments = explode('/',$internalRoute);	
				$controllerName = array_shift($segments).'Controller';
				$controllerName = ucfirst($controllerName);
				$actionName = 'action'.ucfirst(array_shift($segments));
				file_put_contents("log.txt",'нашел роут '.$actionName.$time."\r\n",FILE_APPEND);
				$parameters = $segments;
				$controllerFile = ROOT.'/controllers/'.$controllerName.'.php';
				if (file_exists($controllerFile))
				{
					include_once($controllerFile);
				}
				$controllerObject = new $controllerName;
				$time = date('H:i:s', time());
				file_put_contents("log.txt",'вызываю контроллер '.$time."\r\n",FILE_APPEND);

				$result = call_user_func_array(array($controllerObject,$actionName),$parameters);
				if ($result!=null)
				{

					if ($result=="end") return "";
					if ($result) return json_encode($result);
				}
				break;
			}
		}

		 include_once(ROOT.'/views/404.php');
	}

}