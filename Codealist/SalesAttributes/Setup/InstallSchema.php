<?php
namespace Codealist\SalesAttributes\Setup;

use Magento\Sales\Setup\SalesSetupFactory;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{
    /**
     * @var string
     */
    private static $connectionName = 'sales';
    /**
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        $connection = $installer->getConnection(self::$connectionName);

        /** Add column to SALES ORDER table */
        $connection->addColumn(
            $installer->getTable('sales_order', self::$connectionName),
            'my_new_attribute',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'nullable' => true,
                'comment' => 'codealist.net',
                'after' => 'updated_at'
            ]
        );

        /** Add column to SALES INVOICE table */
        $connection->addColumn(
            $installer->getTable('sales_invoice', self::$connectionName),
            'my_new_attribute',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'nullable' => true,
                'comment' => 'codealist.net',
                'after' => 'updated_at'
            ]
        );
        $installer->endSetup();
    }
}
