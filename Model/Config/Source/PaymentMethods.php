<?php

declare(strict_types=1);

namespace Team23\RestrictZipCodes\Model\Config\Source;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Data\OptionSourceInterface;
use Magento\Framework\DataObject;
use Magento\Payment\Helper\Data;

/**
 * Class PaymentMethods
 *
 * Show all available payment methods in multiselect UI component.
 */
class PaymentMethods extends DataObject implements OptionSourceInterface
{
    /**
     * @var ScopeConfigInterface
     */
    private ScopeConfigInterface $scopeConfig;

    /**
     * @var Data
     */
    private Data $paymentHelper;

    /**
     * PaymentMethods constructor
     *
     * @param ScopeConfigInterface $scopeConfig
     * @param Data $paymentHelper
     * @param array $data
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        Data $paymentHelper,
        array $data = []
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->paymentHelper = $paymentHelper;
        parent::__construct($data);
    }

    /**
     * @inheritDoc
     */
    public function toOptionArray()
    {
        $paymentMethods = $this->getAllPaymentMethods();
        $result = [];

        foreach ($paymentMethods as $code => $paymentMethod) {
            $title = $this->getPaymentTitle($code);

            if ($title === null) {
                continue;
            }

            $result[$code] = [
                'label' => $title,
                'value' => $code,
            ];
        }

        return $result;
    }

    /**
     * Retrieve all payment methods
     *
     * @return array
     */
    private function getAllPaymentMethods(): array
    {
        return $this->paymentHelper->getPaymentMethods();
    }

    /**
     * Retrieve payment title
     *
     * @param string $code
     * @return string|null
     */
    private function getPaymentTitle(string $code): ?string
    {
        return $this->scopeConfig->getValue('payment/' . $code . '/title');
    }
}
