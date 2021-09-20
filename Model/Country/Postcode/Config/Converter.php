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
                        $this->handleRange($result, $codeData, $groupName);
                    } else {
                        $result[$groupName][] = $code->nodeValue;
                    }
                }
            }
        }

        return $result;
    }

    /**
     * Handle range, they may include whitespace
     *
     * @param array $result
     * @param array $data
     * @param string $index
     */
    private function handleRange(array &$result, array $data, string $index)
    {
        $rangeStart = $data[0];
        $rangeEnd = $data[1];
        $whiteSpacePosition = strrpos($rangeStart, ' ');

        if ($whiteSpacePosition !== false) {
            $rangeStart = str_replace(' ', '', $rangeStart);
            $rangeEnd = str_replace(' ', '', $rangeEnd);
        }

        for ($i = $rangeStart; $i <= $rangeEnd; $i++) {
            $result[$index][] = ($whiteSpacePosition === false)
                ? (string)$i
                : (string)substr_replace($i, ' ', $whiteSpacePosition, 0);
        }
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
