<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\FindingParametersDTO;
use App\Model\ParametersInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class FindingParametersBuilder
{
    private array $sortFields = ['title', 'creation_date'];
    private array $sortDirections = ['asc', 'desc'];

    public function build(array $requestData, SessionInterface $session): FindingParametersDTO
    {
        // Random sorting
        $sortFieldFromRequest = $requestData[ParametersInterface::PARAM_SORT_FIELD] ?? null;
        if ('random' === $sortFieldFromRequest) {
            $requestData[ParametersInterface::PARAM_SORT_FIELD] = $this->getRandomValueFromArray($this->sortFields);
            $requestData[ParametersInterface::PARAM_SORT_DIRECTION] = $this->getRandomValueFromArray($this->sortDirections);
        }
        $requestData = $this->compareAndInitValues(ParametersInterface::PARAM_SORT_FIELD, $requestData, $session, 'id');
        $requestData = $this->compareAndInitValues(ParametersInterface::PARAM_SORT_DIRECTION, $requestData, $session, 'ASC');
        $requestData = $this->compareAndInitValues(ParametersInterface::PARAM_SHOW_EXCLUDED_IMAGES, $requestData, $session, '0');

        return new FindingParametersDTO($requestData);
    }

    private function compareAndInitValues(string $valueKey, array $requestData, SessionInterface $session, $defaultValue): array
    {
        $isValueSet = false;
        $valueFromRequest = $requestData[$valueKey] ?? null;
        $valueFromSession = $session->get($valueKey);

        if (empty($valueFromRequest) && empty($valueFromSession)) {
            $session->set($valueKey, $defaultValue);
            $requestData[$valueKey] = $defaultValue;
            $isValueSet = true;
        }

        if (null !== $valueFromRequest && false === $isValueSet) {
            $session->set($valueKey, $requestData[$valueKey]);
            $isValueSet = true;
        }

        if (null !== $valueFromSession && false === $isValueSet) {
            $requestData[$valueKey] = $valueFromSession;
        }

        return $requestData;
    }

    private function getRandomValueFromArray(array $array)
    {
        $index = array_rand($array);

        return $array[$index];
    }
}
