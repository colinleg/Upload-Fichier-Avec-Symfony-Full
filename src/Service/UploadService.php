<?php 

    namespace App\Service;

class UploadService{

    public function upload($form, $directory)
    {

            # on récupère le fichier avec :
            $file = $form->get('fileName')->getData();

            # Le nom du fichier aura comme forme : 12:50-nomFichier.ext
            $fileName = time() . '.' . $file->guessExtension();

            # on déplace le fichier vers un chemin 
            $file->move(
                # 1e param : le chemin
                $directory,
                # 2e param : le nom du fichier 
                $fileName
            );

            return $fileName;
    }

}