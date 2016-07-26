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
use Zephia\ZLeader\Model\Company;

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
        $this->setModel(new Company);

        $this->prepareCrudeSetup();

        $this->crudeSetup
            ->usePopup()
            ->setTitle("Empresas")
            ->setColumn([
                'name',
            ])
            ->setTrans([
                'id'    => 'ID',
                'name'  => 'Nombre',
                'image' => 'Imagen',
                'email' => 'E-mail',
                'phone_number' => 'Teléfono',
                'website_url' => 'Sitio web URL',
                'facebook_url' => 'Facebook URL',
                'twitter_url' => 'Twitter URL',
                'googleplus_url' => 'Google+ URL',
                'terms_url' => 'Términos y condiciones URL',
                'privacy_url' => 'Política de privacidad URL',
            ])
            ->setFilters([
                'name',
            ]);

        $this->setValidationRules([
            'name' => 'required|max:100',
        ]);
    }
}