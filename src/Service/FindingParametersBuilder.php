<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\FindingParametersDTO;
use App\Model\ParametersInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class FindingParametersBuilder
{
    public function build(array $requestData, SessionInterface $session): FindingParametersDTO
    {
        $requestData = $this->compareAndInitValues(ParametersInterface::PARAM_SORT_FIELD, $requestData, $session, 'id');
        $requestData = $this->compareAndInitValues(ParametersInterface::PARAM_SORT_DIRECTION, $requestData, $session, 'ASC');
        $requestData = $this->compareAndInitValues(ParametersInterface::PARAM_SHOW_EXCLUDED_IMAGES, $requestData, $session, false);

        return new FindingParametersDTO($requestData);
    }

    private function compareAndInitValues(string $valueKey, array $requestData, SessionInterface $session, $defaultValue): array
    {
        $usedDefaultValue = false;
        $valueFromRequest = $requestData[$valueKey] ?? null;
        $valueFromSession = $session->get($valueKey);

        if (null === $valueFromRequest && null === $valueFromSession) {
            $session->set($valueKey, $defaultValue);
            $requestData[$valueKey] = $defaultValue;
            $usedDefaultValue = true;
        }

        if (null !== $valueFromRequest && false === $usedDefaultValue) {
            $session->set($valueKey, $requestData[$valueKey]);
        }

        if (null !== $valueFromSession && false === $usedDefaultValue) {
            $requestData[$valueKey] = $valueFromSession;
        }

        return $requestData;
    }
}
