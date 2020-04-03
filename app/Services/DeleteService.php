<?php
namespace App\Services;

class DeleteService
{
    public function deleteElement($id,$class){
        //$object = $class::findOrFail($id); # Con éste método Laravel lanza por sí sólo una excepción 400

        $object = $class::find($id);
       /* if(!$object){
            throw new \Exception('Object Not Found');
        } # Éste error se maneja desde el index con el bloque try catch y lo maneja como error 500 y no 400 */
        $object->delete();
    }
}