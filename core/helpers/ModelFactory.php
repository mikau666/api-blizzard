<?php

namespace Apis\Factory;

class ModelFactory
{
    private $model = false;

    function __construct(Array $model_information)
    {
        $this->destinyValidation($model_information);
        $archive = $this->archiveValidation($model_information);

        require_once $archive;

        $class = "\\Apis\\Model\\Modules\\" . ucfirst($model_information['model']);

        try
        { $model_object = new $class(); }
        catch (\Exception $e) { throw new \Exception('N�o foi poss�vel instanciar o model informado. Verifique se os par�metros "module_name" e "model_name" foram informados adequadamente.'); }

        $this->model = $model_object;
    }

    private function destinyValidation($destiny = null)
    {
        if(!isset($destiny['module']) || empty($destiny['module']) || !is_string($destiny['module']))
        { throw new \Exception('O M�dulo n�o foi corretamente especificado para cria��o do model. Verifique se est� passando corretamente o par�metro "module_name" como string.'); }

        if(!isset($destiny['model']) || empty($destiny['model']) || !is_string($destiny['model']))
        { throw new \Exception('O Model n�o foi corretamente especificado. Verifique se est� passando corretamente o par�metro "model_name" como string.'); }
    }

    private function archiveValidation($destiny = null)
    {
        $archive_url = (!is_null($destiny)) ?
            MODEL_PATH . 'modules/' . $destiny['module'] . '/' . $destiny['model'] . '.php' : false;

        if(!file_exists($archive_url))
        { throw new \Exception('N�o encontramos o arquivo dentro do diret�rio referente ao model. Verifique se o arquivo existe ou se os par�metros "module_name" e model_name" foram informados adequadamente.'); }

        return $archive_url;
    }

    public function getObject() { return $this->model; }

}