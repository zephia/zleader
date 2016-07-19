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
use Zephia\ZLeader\Model\Area;
use Zephia\ZLeader\Model\Company;

class AreaCRUD extends Crude implements 
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
        $this->setModel(new Area);

        $this->prepareCrudeSetup();

        $this->crudeSetup
            ->usePopup()
            ->setTitle("Areas")
            ->setColumn([
                'name',
                'company_name',
            ])
            ->setTrans([
                'id' => 'ID',
                'name' => 'Nombre',
                'company_id' => 'Empresa',
                'company_name' => 'Empresa',
            ])
            ->setFilters([
                'name',
                'company_name' => 'Empresa',
            ])
            ->setTypes([
                'company_id' => 'select'
            ])
            ->setSelectOptions([
                'company_id' => $this->getCompanies()
            ]);

        $this->setValidationRules([
            'name' => 'required|max:100',
        ]);
    }

    public function prepareQuery()
    {
        $query = $this->model
            ->leftJoin('zleader_companies', 'zleader_areas.company_id', '=', 'zleader_companies.id')
            ->select(
                'zleader_areas.id',
                'zleader_areas.name',
                'zleader_areas.company_id',
                'zleader_companies.name as company_name'
            );

        return $query;
    }

    public function getCompanies()
    {
        return (new Company)
            ->select(
                'id',
                'name as label'
            )
            ->get();
    }
}