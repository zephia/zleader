<?php

namespace Zephia\ZLeader\Crude;

use Illuminate\Support\Facades\URL;
use Crude;
use CrudeListInterface;
use CrudeStoreInterface;
use CrudeUpdateInterface;
use CrudeDeleteInterface;
use CrudeWithValidationInterface;
use CrudeFromModelTrait;
use CrudeWithValidationTrait;
use Zephia\ZLeader\Model\Form;
use Zephia\ZLeader\Model\Area;

class FormCRUD extends Crude implements 
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
        $this->setModel(new Form);

        $this->prepareCrudeSetup();

        $this->crudeSetup
            ->usePopup()
            ->setTitle("Formularios")
            ->setColumn([
                'id',
                'name',
                'area_name',
            ])
            ->setFilters([
                'name',
                'area_name' => 'Area',
            ])
            ->setAddAndEditForm([
                'name', 
                'area_id',
                'feedback_url',
                'notification_emails',
                'notification_subject',
                'user_notification_subject',
                'slug',
                'form_code',
            ])
            ->setTypes([
                'area_id' => 'select',
                'slug' => 'info',
                'form_code' => 'markdown',
            ])
            ->setSelectOptions([
                'area_id' => $this->getAreas()
            ])
            ->setTrans([
                'id' => 'ID',
                'name' => 'Nombre',
                'area_id' => 'Area',
                'area_name' => 'Area',
                'feedback_url' => 'Thank you page URL',
                'notification_emails' => 'E-mails de notificación',
                'notification_subject' => 'Asunto de notificación (interna)',
                'user_notification_subject' => 'Asunto de notificación (usuario)',
                'slug' => 'Slug',
                'form_code' => 'Código',
            ]);

        $this->setValidationRules([
            'name' => 'required|max:100',
            'area_id' => 'required',
        ]);
    }

    public function prepareQuery()
    {
        $query = $this->model
            ->leftJoin('zleader_areas', 'zleader_forms.area_id', '=', 'zleader_areas.id')
            ->select(
                'zleader_forms.id',
                'zleader_forms.name',
                'zleader_forms.area_id',
                'zleader_forms.feedback_url',
                'zleader_forms.notification_emails',
                'zleader_forms.notification_subject',
                'zleader_forms.user_notification_subject',
                'zleader_forms.slug',
                'zleader_areas.name as area_name'
            );

        return $query;
    }

    public function formatModel($model)
    {
        $script_url = str_replace(['http://','https://'],['',''],URL::to('/')) . '/zl.js';

        $model->form_code = '<form class="zlform" id="zlform-' . $model->id . '" action="' . URL::action('\Zephia\ZLeader\Http\Controllers\Api\LeadController@store', ['slug' => $model->slug]) . '" method="post">' . "\r\n<!-- Fields: (zlfield_example) -->\r\n" . '</form>' . "\r\n";
        $model->form_code.= "<script type=\"text/javascript\">" . "\r\n" . "(function(d,s,e,t){e=d.createElement(s);e.type='text/java'+s;e.async='async';" . "\r\n" . "e.src='http'+('https:'===location.protocol?'s://s':'://')+'" . $script_url . "';t=d.getElementsByTagName(s)[0];" . "\r\n" . "t.parentNode.insertBefore(e,t);})(document,'script');" . "\r\n" . "</script>";
        return $model;
    }

    public function getAreas()
    {
        return (new Area)
            ->select(
                'id',
                'name as label'
            )
            ->get();
    }
}