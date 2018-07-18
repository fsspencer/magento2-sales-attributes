<?php
namespace Codealist\SalesAttributes\Setup;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Sales\Setup\SalesSetupFactory;
use Magento\Quote\Setup\QuoteSetupFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;

class InstallData implements InstallDataInterface
{
    /**
     * @var SalesSetupFactory
     */
    private $salesSetupFactory;
    /**
     * @var QuoteSetupFactory
     */
    private $quoteSetupFactory;

    /**
     * InstallSchema constructor.
     * @param SalesSetupFactory $salesSetupFactory
     * @param QuoteSetupFactory $quoteSetupFactory
     */
    public function __construct(
        SalesSetupFactory $salesSetupFactory,
        QuoteSetupFactory $quoteSetupFactory
    ) {
        $this->salesSetupFactory = $salesSetupFactory;
        $this->quoteSetupFactory = $quoteSetupFactory;
    }

    /**
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        /** @var \Magento\Quote\Setup\QuoteSetup $quoteInstaller */
        $quoteInstaller = $this->quoteSetupFactory->create(['resourceName' => 'quote_setup', 'setup' => $setup]);

        /** @var \Magento\Sales\Setup\SalesSetup $salesInstaller */
        $salesInstaller = $this->salesSetupFactory->create(['resourceName' => 'sales_setup', 'setup' => $setup]);

        //Add attributes to quote
        $entityAttributesCodes = [
            'my_custom_attribute' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            'my_custom_attribute_again' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT

        ];

        foreach ($entityAttributesCodes as $code => $type) {
            // For type you can use:
            // quote, quote_item, quote_address, quote_address_item, quote_address_rate and quote_payment
            $quoteInstaller
                ->addAttribute('quote', $code, [
                    'type' => $type, 'length'=> 255, 'visible' => false, 'nullable' => true]);
            $quoteInstaller
                ->addAttribute('quote_item', $code, [
                    'type' => $type, 'length'=> 255, 'visible' => false, 'nullable' => true]);

            // For type you can use:
            // order, order_payment, order_item, order_address, order_status_history, invoice, invoice_item, etc.
            // For more types check Magento/Sales/Setup/SalesSetup.php
            $salesInstaller
                ->addAttribute('order', $code, [
                    'type' => $type, 'length'=> 255, 'visible' => false,'nullable' => true]);
            $salesInstaller
                ->addAttribute('invoice', $code, [
                    'type' => $type, 'length'=> 255, 'visible' => false, 'nullable' => true]);
        }

        $setup->endSetup();
    }
}
