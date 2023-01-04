<?php

namespace P2media\Httpmonitoring\Validator;

use P2media\Httpmonitoring\Utility\UriUtility;
use TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator;

class UriValidator extends AbstractValidator
{
    /**
     * Checks if the given value is a valid url.
     *
     * @param mixed $value The value that should be validated
     */
    protected function isValid($value): void
    {
        if (!is_string($value) || !UriUtility::isValidTrimmedUri($value)) {
            $this->addError(
                $this->translateErrorMessage(
                    'tx_httpmonitoring.validator.uri.notvalid',
                    'httpmonitoring'
                ),
                // 1662454565
                1_662_454_565
            );
        }
    }
}
