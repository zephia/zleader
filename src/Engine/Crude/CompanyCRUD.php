<?php

namespace Zephia\ZLeader\Engine\Crude;

use Crude;
use CrudeListInterface;
use CrudeStoreInterface;
use CrudeUpdateInterface;
use CrudeDeleteInterface;
use CrudeWithValidationInterface;
use CrudeFromModelTrait;
use CrudeWithValidationTrait;

class CompanyCRUD extends Crude implements 
    CrudeListInterface, 
    CrudeStoreInterface,
    CrudeUpdateInterface,
    CrudeDeleteInterface,
    CrudeWithValidationInterface
{
    use CrudeFromModelTrait;
    use CrudeWithValidationTrait;

    public function __construct()
    {
        $this->setModel(new \Company);

        $this->prepareCrudeSetup();

        $this->crudeSetup
            ->usePopup()
            ->setTitle("Empresas")
            ->setColumn([
                'name',
            ])
            ->setTrans([
                'id' => 'ID',
                'name' => 'Nombre',
            ])
            ->setFilters([
                'name',
            ]);

        $this->setValidationRules([
            'name' => 'required|max:100',
        ]);
    }
}