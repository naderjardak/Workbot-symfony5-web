<?php

namespace App\Serializer\Normalizer;

use App\Entity\Reclamation;
use App\Entity\Categorie;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ReclamationNormalizer implements NormalizerInterface
{

    public function normalize($object, string $format = null, array $context = [])
    {
        return[
            'id'=>$object->getId(),
            'objet'=>$object->getObjet(),
            'dateajout'=>$object->getDateajout()->format('Y-m-d'),
            'description'=>$object->getDescription(),
            'nomCategorie'=>$object->getIdCategorie()->getNomcategorie(),
            'image'=>$object->getImage(),
            'etat'=>$object->getEtat(),
        ];
    }
    public function supportsNormalization($data, string $format = null)
    {
        return $data instanceof Reclamation;
    }
}