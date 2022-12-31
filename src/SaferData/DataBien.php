<?php

namespace App\SaferData;

use App\Entity\Bien;
use Shuchkin\SimpleXLSX;
use Doctrine\Persistence\ObjectManager;

class DataBien
{

    public function load(ObjectManager $em)
    {

        $file = '../public/data/data_safer.csv';

        dd($file);
        if ($xlsx = \SimpleXLSX::parse($file)) {

            foreach ($xlsx->rows() as $key => $r) {
                $bien = new Bien();
                $bien->setReference($r[0]);
                $bien->setTitre($r[1]);
                $bien->setDescription($r[2]);
                $bien->setPrix($r[3]);
                $em->persist($bien);
            }
            $em->flush();
        } else {
            echo \SimpleXLSX::parseError();
        }


        return new JsonResponse();
    }
}
