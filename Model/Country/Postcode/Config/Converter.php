<?php

declare(strict_types=1);

namespace Team23\RestrictZipCodes\Model\Country\Postcode\Config;

use Magento\Framework\Config\ConverterInterface;
use Magento\Framework\Stdlib\BooleanUtils;

/**
 * Class Converter
 *
 * Convert XML data to use within array list.
 */
class Converter implements ConverterInterface
{
    /**
     * @var BooleanUtils
     */
    private BooleanUtils $booleanUtils;

    /**
     * Converter constructor
     *
     * @param BooleanUtils $booleanUtils
     */
    public function __construct(BooleanUtils $booleanUtils)
    {
        $this->booleanUtils = $booleanUtils;
    }

    /**
     * @inheritDoc
     */
    public function convert($source)
    {
        $result = [];

        /** @var \DOMNode $zipNode */
        foreach ($source->documentElement->childNodes as $zipNode) {
            if (!$this->isValid($zipNode)) {
                continue;
            }

            $groupName = $zipNode->attributes->getNamedItem('countryCode')->nodeValue;

            /** @var \DOMNode $codeNodes */
            foreach ($zipNode->childNodes as $codeNodes) {
                if (!$this->isValid($codeNodes)) {
                    continue;
                }

                /** @var \DOMNode $code */
                foreach ($codeNodes->childNodes as $code) {
                    if (!$this->isValid($code, true)) {
                        continue;
                    }

                    if ($this->isRange($code)) {
                        $codeData = explode('-', $code->nodeValue);

                        for ($i = $codeData[0]; $i <= $codeData[1]; $i++) {
                            $result[$groupName][] = (string)$i;
                        }
                    } else {
                        $result[$groupName][] = $code->nodeValue;
                    }
                }
            }
        }

        return $result;
    }

    /**
     * Check if dom node attribute is set to range
     *
     * @param \DOMNode $node
     * @return bool
     */
    private function isRange(\DOMNode $node): bool
    {
        return $this->booleanUtils->toBoolean($node->attributes->getNamedItem('range')->nodeValue);
    }

    /**
     * Check if valid dom document node
     *
     * @param \DOMNode $node
     * @param bool $useIsActive
     * @return bool
     */
    private function isValid(\DOMNode $node, bool $useIsActive = false): bool
    {
        $result = true;

        if ($node->nodeType != XML_ELEMENT_NODE) {
            $result = false;
        }

        if ($result
            && $useIsActive
            && !$this->booleanUtils->toBoolean($node->attributes->getNamedItem('active')->nodeValue)
        ) {
            $result = false;
        }

        return $result;
    }
}
