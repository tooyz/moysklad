<?php

namespace MoySklad\Entities\Documents;

use MoySklad\Components\Http\RequestConfig;
use MoySklad\Components\Http\RequestLog;
use MoySklad\Components\Specs\QuerySpecs\QuerySpecs;
use MoySklad\Entities\AbstractEntity;
use MoySklad\Entities\Counterparty;
use MoySklad\Entities\Documents\Templates\AbstractTemplate;
use MoySklad\Entities\Documents\Templates\CustomTemplate;
use MoySklad\Entities\Documents\Templates\EmbeddedTemplate;
use MoySklad\Entities\Misc\Attribute;
use MoySklad\Entities\Misc\Export;
use MoySklad\Entities\Misc\Publication;
use MoySklad\Exceptions\UnknownEntityException;
use MoySklad\Lists\EntityList;
use MoySklad\MoySklad;
use MoySklad\Registers\ApiUrlRegistry;

class AbstractDocument extends AbstractEntity{
    public static $entityName = 'a_document';

    /**
     * @param MoySklad $sklad
     * @param Attribute $attribute
     * @return \stdClass
     * @throws \Throwable
     */
    public static function getAttributeMetaData(MoySklad $sklad, Attribute $attribute){
        return $sklad->getClient()->get(
            ApiUrlRegistry::instance()->getMetadataAttributeUrl(static::$entityName, $attribute->id)
        );
    }

    /**
     * Create document template
     * @param bool $makeEmptyTemplate
     * @return \stdClass
     * @throws \Exception
     * @throws \Throwable
     */
    public function newTemplate($makeEmptyTemplate = false){
        $requestConfig = new RequestConfig();
        if ( $makeEmptyTemplate ) {
            $requestConfig->set("ignoreRequestBody", true);
        }
        return $this->getSkladInstance()->getClient()->put(
            ApiUrlRegistry::instance()->getNewDocumentTemplateUrl(static::$entityName),
            $this->mergeFieldsWithLinks(),
            $requestConfig
        );
    }

    /**
     * @param QuerySpecs $querySpecs
     * @return EntityList
     * @throws \MoySklad\Exceptions\EntityHasNoIdException
     */
    public function getPublications(QuerySpecs $querySpecs = null){
        return Publication::query($this->getSkladInstance(), $querySpecs)
            ->setCustomQueryUrl(ApiUrlRegistry::instance()->getDocumentPublicationsUrl($this::$entityName, $this->findEntityId()))
            ->getList();
    }

    /**
     * @param CustomTemplate $template
     * @return Publication
     * @throws \MoySklad\Exceptions\EntityHasNoIdException
     * @throws \MoySklad\Exceptions\IncompleteCreationFieldsException
     * @throws \Throwable
     */
    public function createPublication(CustomTemplate $template){
        $template->validateFieldsRequiredForCreation();
        $res = $this->getSkladInstance()->getClient()->post(
            ApiUrlRegistry::instance()->getDocumentPublicationsUrl(static::$entityName, $this->findEntityId()),
            [
                "template" => $template->mergeFieldsWithLinks()
            ]
        );
        return new Publication($this->getSkladInstance(), $res);
    }

    /**
     * @param Publication $publication
     * @return bool
     * @throws \MoySklad\Exceptions\EntityHasNoIdException
     * @throws \Throwable
     */
    public function deletePublication(Publication $publication){
        $this->getSkladInstance()->getClient()->delete(
            ApiUrlRegistry::instance()->getDocumentPublicationWithIdUrl(static::$entityName, $this->findEntityId(), $publication->findEntityId())
        );
        return true;
    }

    /**
     * @param $id
     * @return Publication
     * @throws \MoySklad\Exceptions\EntityHasNoIdException
     * @throws \Throwable
     */
    public function getPublicationById($id){
        $res = $this->getSkladInstance()->getClient()->get(
            ApiUrlRegistry::instance()->getDocumentPublicationWithIdUrl(static::$entityName, $this->findEntityId(), $id)
        );
        return new Publication($this->getSkladInstance(), $res);
    }

    /**
     * @param CustomTemplate|EntityList $templateOrTemplates
     * @param string $extension
     * @return Export
     * @throws \Exception
     * @throws \MoySklad\Exceptions\EntityHasNoIdException
     * @throws \Throwable
     */
    public function createExport($templateOrTemplates, $extension = 'pdf'){
        $supportedExtensions = ['xls', 'pdf', 'html', 'ods'];
        if ( !in_array($extension, $supportedExtensions) ){
            throw new \Exception("Extension must be one of: " . implode(',', $supportedExtensions));
        }
        if ( $templateOrTemplates instanceof EntityList ){
            foreach ( $templateOrTemplates as $template ){
                if ( empty($template->count) || $template->count <= 0) $template->count = 1;
                else if ( $template->count > 10 ) throw new \Exception("Template count field is more then 10");
            }
            $exportRequest = [
                "templates" => $templateOrTemplates->map(function(AbstractTemplate $template){
                    return [
                        "template" => $template,
                        "count" => $template->count
                    ];
                })
            ];
        } else if ( $templateOrTemplates instanceof AbstractTemplate ){
            $exportRequest = [
                "template" => $templateOrTemplates,
                "extension" => $extension,
            ];
        } else {
            throw new \Exception("First argument must be either template or EntityList of templates");
        }
        $res = $this->getSkladInstance()->getClient()->post(
            ApiUrlRegistry::instance()->getDocumentExportUrl(static::$entityName, $this->findEntityId()),
            $exportRequest,
            new RequestConfig(['followRedirects' => false])
        );
        return new Export($this->getSkladInstance(), [
            'file' => $res
        ]);
    }

    /**
     * @param CustomTemplate|EntityList $templateOrTemplates
     * @param string $extension
     * @return Export
     * @throws \Exception
     * @throws \MoySklad\Exceptions\EntityHasNoIdException
     * @throws \Throwable
     */
    public function createExportSrickers($templateOrTemplates, $extension = 'pdf', $counterparty, $count, $salePrice){
        $supportedExtensions = ['xls', 'pdf', 'html', 'ods'];
        if ( !in_array($extension, $supportedExtensions) ){
            throw new \Exception("Extension must be one of: " . implode(',', $supportedExtensions));
        }
        if ( $templateOrTemplates instanceof EntityList ){
            foreach ( $templateOrTemplates as $template ){
                if ( empty($template->count) || $template->count <= 0) $template->count = 1;
                else if ( $template->count > 10 ) throw new \Exception("Template count field is more then 10");
            }
            $exportRequest = [
                "templates" => $templateOrTemplates->map(function(AbstractTemplate $template){
                    return [
                        "template" => $template,
                        "count" => $count,
                        'salePrice' => $counterparty,
                        'salePrice' => $salePrice,

                    ];
                })
            ];
        } else if ( $templateOrTemplates instanceof AbstractTemplate ){
            $exportRequest = [
                "template" => $templateOrTemplates,
                "extension" => $extension,
                'organization' => $counterparty,
                'count' => $count,
                'salePrice' => $salePrice,
            ];
        } else {
            throw new \Exception("First argument must be either template or EntityList of templates");
        }
        $res = $this->getSkladInstance()->getClient()->post(
            ApiUrlRegistry::instance()->getDocumentExportUrl(static::$entityName, $this->findEntityId()),
            $exportRequest,
            new RequestConfig(['followRedirects' => false])
        );
        return new Export($this->getSkladInstance(), [
            'file' => $res
        ]);
    }

    /**
     * @param QuerySpecs $querySpecs
     * @return EntityList
     */
    public function getExportEmbeddedTemplates(QuerySpecs $querySpecs = null){
        $res = EmbeddedTemplate::query($this->getSkladInstance(), $querySpecs)
            ->setCustomQueryUrl(ApiUrlRegistry::instance()->getMetadataExportEmbeddedTemplateUrl(static::$entityName))
            ->getList();
        return $res;
    }

    /**
     * @param QuerySpecs $querySpecs
     * @return EntityList
     */
    public function getExportCustomTemplates(QuerySpecs $querySpecs = null){
        $res = CustomTemplate::query($this->getSkladInstance(), $querySpecs)
            ->setCustomQueryUrl(ApiUrlRegistry::instance()->getMetadataExportCustomTemplateUrl(static::$entityName))
            ->getList();
        return $res;
    }

    /**
     * @param $id
     * @return CustomTemplate
     * @throws \Throwable
     */
    public function getExportCustomTemplateById($id){
        $res = $this->getSkladInstance()->getClient()->get(
            ApiUrlRegistry::instance()->getMetadataExportCustomTemplateWithIdUrl(static::$entityName, $id)
        );
        return new CustomTemplate($this->getSkladInstance(), $res);
    }

    /**
     * @param $id
     * @return EmbeddedTemplate
     * @throws \Throwable
     */
    public function getExportEmbeddedTemplateById($id){
        $res = $this->getSkladInstance()->getClient()->get(
            ApiUrlRegistry::instance()->getMetadataExportEmbeddedTemplateWithIdUrl(static::$entityName, $id)
        );
        return new EmbeddedTemplate($this->getSkladInstance(), $res);
    }
}
