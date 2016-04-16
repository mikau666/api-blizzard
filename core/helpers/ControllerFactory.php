<?php

namespace Apis\ControllerFactory;

use \Apis\Controller;

class ControllerFactory
{
    function __construct($loader_information , \Smarty $object_smarty)
    {
        $this->destinyValidation($loader_information['destiny']);
        $archive = $this->archiveValidation($loader_information['destiny']);

        require_once $archive;

        $class = ucfirst($loader_information['destiny']['controller']);
        $action = $loader_information['destiny']['action'] . 'Action';

        try
        {
            $controller_object = new $class($loader_information['url_parameters'] , $object_smarty);
            $controller_object->$action();
        }
        catch (\Exception $e) { throw new \Exception('N�o foi poss�vel instanciar o controller informado. Verifique o nome da classe do controller.'); }
    }

    private function destinyValidation($destiny = null)
    {
        if(!isset($destiny['module']) || 
            empty($destiny['module']) ||
            !is_string($destiny['module']))
        {
            throw new \Exception('O M�dulo da rota n�o foi corretamente especificado. Verifique sua especifica��o no arquivo "/routes.php"');
        }

        if(!isset($destiny['controller']) || 
            empty($destiny['controller']) ||
            !is_string($destiny['controller']))
        {
            throw new \Exception('O Controller da rota n�o foi corretamente especificado. Verifique sua especifica��o no arquivo "/routes.php"');
        }

        if(!isset($destiny['action']) || 
            empty($destiny['action']) ||
            !is_string($destiny['action']))
        {
            throw new \Exception('A Action da rota n�o foi corretamente especificada. Verifique sua especifica��o no arquivo "/routes.php"');
        }
    }

    private function archiveValidation($destiny = null)
    {   
        $archive_url = (!is_null($destiny)) ? 
                        CONTROLLER_PATH . 'modules/' . $destiny['module'] . '/' . $destiny['controller'] . '.php' :
                        false;

        if(!file_exists($archive_url))
        {
            throw new \Exception('N�o encontramos o arquivo dentro do diret�rio referente ao controller. Verifique se o arquivo existe ou se alguma configura��o est� inadequada no arquivo "/routes.php".');
        }

        return $archive_url;
    }

}