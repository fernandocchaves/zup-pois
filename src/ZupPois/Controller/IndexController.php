<?php
namespace ZupPois\Controller;

use ZupPois\Entities\Pois;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class IndexController extends BaseController
{

    public function mount($controller)
    {
        $controller->get('/', array($this, 'index'))->bind('home');
        $controller->post('/', array($this, 'create'))->bind('create');
        $controller->put('/{id}', array($this, 'update'))->bind('update');
        $controller->delete('/{id}', array($this, 'delete'))->bind('delete');
    }

    public function index(Request $request, Application $app)
    {
        $userService = $app['pois.service'];
        $records = $userService->getAll();

        return $app["serializer"]->serialize(['result' => $records], "json");
    }

    public function create(Request $request, Application $app)
    {
        $data = json_decode($request->getContent());

        if ($this->validateFields($data)) {
            $poisService = $app['pois.service'];

            $record = $this->saveData($data);
            $poisService->save($record);

            return $app["serializer"]->serialize(['result' => $record], "json");
        } else {
            $error = array('message' => 'Dados inválidos!');
            return $app->json(['error' => $error], 400);
        }
    }

    public function update(Request $request, Application $app, $id)
    {
        $data = json_decode($request->getContent());

        if ($this->validateFields($data)) {
            $poisService = $app['pois.service'];

            $record = $poisService->getRecord($id);

            if(!$record){
                $error = array('message' => 'Registro não encontrado!');
                return $app->json(['error' => $error], 404);
            }

            $record = $this->saveData($data, $record);

            $poisService->save($record);
            return $app["serializer"]->serialize(['result' => $record], "json");
        } else {
            $error = array('message' => 'Dados inválidos!');
            return $app->json(['error' => $error], 400);
        }
    }

    public function delete(Request $request, Application $app, $id)
    {
        $poisService = $app['pois.service'];

        $record = $poisService->getRecord($id);
        if(!$record){
            $error = array('message' => 'Registro não encontrado!');
            return $app->json(['error' => $error], 404);
        }

        $poisService->delete($record);

        $response = array('message' => 'Removido!');
        return $app->json(['result' => $response], 200);
    }

    private function saveData($data, $record = null){
        if(!$record){
            $record = new Pois();
        }

        $record->setName($data->name);
        $record->setLatitude($data->latitude);
        $record->setLongitude($data->longitude);

        return $record;
    }

    private function validateFields($data){
        if (property_exists($data, 'name') && property_exists($data, 'latitude') && property_exists($data, 'longitude')) {
            return true;
        }

        return false;
    }
}