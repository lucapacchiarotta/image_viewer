<?php

declare(strict_types=1);

namespace App\Model;

interface ParametersInterface
{
    public const string PARAM_SEARCH_TERMS = 'searchTerms';
    public const string PARAM_SORT_FIELD = 'sortField';
    public const string PARAM_SORT_DIRECTION = 'sortDirection';
    public const string PARAM_SHOW_EXCLUDED_IMAGES = 'showExcludedImages';
}
