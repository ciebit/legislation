<?php

declare(strict_types=1);

namespace Ciebit\Legislation\Documents\Factories;

use Ciebit\Legislation\Documents\Document;
use Ciebit\Legislation\Documents\Constitution;
use Ciebit\Legislation\Documents\Decree;
use Ciebit\Legislation\Documents\Factories\Constitution as ConstitutionFactory;
use Ciebit\Legislation\Documents\Factories\Decree as DecreeFactory;
use Ciebit\Legislation\Documents\Factories\Law as LawFactory;
use Ciebit\Legislation\Documents\Factories\Ordinance as OrdinanceFactory;
use Ciebit\Legislation\Documents\Law;
use Ciebit\Legislation\Documents\Ordinance;
use Exception;

class Factory
{
    private array $data;
    private ?Document $document;
    private string $type;

    public function __construct()
    {
        $this->data = [];
        $this->document = null;
        $this->type = 'unknow';
    }

    public function create(): Document
    {
        switch($this->type) 
        {
            case Constitution::getType():
                $factory = new ConstitutionFactory;
                break;
            case Decree::getType():
                $factory = new DecreeFactory;
                break;
            case Law::getType():
                $factory = new LawFactory;
                break;
            case Ordinance::getType():
                $factory = new OrdinanceFactory;
                break;
            default:
                throw new Exception('ciebit.legislation.documents.factories.type-not-found', 3);
        }

        if ($this->document instanceof Document) {
            $factory->setDocument($this->document);
        }
        $factory->setData($this->data);

        return $factory->create();
    }

    public function setData(array $data): self
    {
        $this->data = $data;
        return $this;
    }

    public function setDocument(Document $document): self
    {
        $this->document = $document;
        return $this;
    }

    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }    
}