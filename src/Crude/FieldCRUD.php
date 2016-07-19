<?php

namespace Zephia\ZLeader\Crude;

use Crude;
use CrudeListInterface;
use CrudeStoreInterface;
use CrudeUpdateInterface;
use CrudeDeleteInterface;
use CrudeWithValidationInterface;
use CrudeFromModelTrait;
use CrudeWithValidationTrait;
use Zephia\ZLeader\Model\Field;

class FieldCRUD extends Crude implements 
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
        $this->setModel(new Field);

        $this->prepareCrudeSetup();

        $this->crudeSetup
            ->usePopup()
            ->setTitle("Campos")
            ->setColumn([
                'name',
                'key',
            ])
            ->setTrans([
                'id'         => 'ID',
                'name'       => 'Nombre',
                'key'        => 'Key',
                'filtrable'  => 'Filtrable',
                'columnable' => 'Encolumnable',
                'order'      => 'Orden',
            ])
            ->setFilters([
                'name',
            ])
            ->setTypes([
                'filtrable'  => 'checkbox',
                'columnable' => 'checkbox',
            ])
            ->useOrderedList('name', 'order');

        $this->setValidationRules([
            'name' => 'required|max:100',
        ]);
    }
}