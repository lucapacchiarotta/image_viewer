<?php

declare(strict_types=1);

namespace App\DTO;

use App\Model\ParametersInterface;

class FindingParametersDTO
{
    public ?string $searchTerms;
    public string $sortField;
    public string $sortDirection;
    public bool $showExcludedImages;

    public function __construct(array $parametersFromRequest)
    {
        $this->searchTerms = $parametersFromRequest[ParametersInterface::PARAM_SEARCH_TERMS] ?? null;
        $this->sortField = $parametersFromRequest[ParametersInterface::PARAM_SORT_FIELD];
        $this->sortDirection = $parametersFromRequest[ParametersInterface::PARAM_SORT_DIRECTION];
        $this->showExcludedImages = $parametersFromRequest[ParametersInterface::PARAM_SHOW_EXCLUDED_IMAGES];
    }
}
