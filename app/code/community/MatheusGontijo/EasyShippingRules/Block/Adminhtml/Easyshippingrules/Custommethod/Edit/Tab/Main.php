<?php
/**
 * This file is part of the "Easy Shipping Rules" module for Magento eCommerce
 * developed by (c) Matheus Gontijo <matheus@matheusgontijo.com>
 */

/**
 * Adminhtml Easy Shipping Rules Custom Methods Edit Tab Main block
 *
 * @category    MatheusGontijo
 * @package     MatheusGontijo_EasyShippingRules
 * @author      Matheus Gontijo <matheus@matheusgontijo.com>
 * @license     OSL v3.0
 */
class MatheusGontijo_EasyShippingRules_Block_Adminhtml_Easyshippingrules_Custommethod_Edit_Tab_Main
    extends Mage_Adminhtml_Block_Widget_Form
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    /**
     * Prepare main form
     *
     * @return MatheusGontijo_EasyShippingRules_Block_Adminhtml_Easyshippingrules_Custommethod_Edit_Tab_Main
     */
    protected function _prepareForm()
    {
        $model = Mage::registry('current_easyshippingrules_custom_method');

        $form = new Varien_Data_Form();

        $form->setHtmlIdPrefix('custom_method_');
        $form->setFieldNameSuffix('custom_method');

        $fieldset = $form->addFieldset('base_fieldset',
            array('legend' => $this->__('General Information'))
        );

        if ($model->getId()) {
            $fieldset->addField('easyshippingrules_custom_method_id', 'hidden', array(
                'name' => 'easyshippingrules_custom_method_id',
            ));
        }

        $carrierValues = Mage::getResourceSingleton('easyshippingrules/carrier_collection')->toOptionArray();
        array_unshift($carrierValues, array('label' => '', 'value' => ''));

        $fieldset->addField('easyshippingrules_carrier_id', 'select', array(
            'name'   => 'easyshippingrules_carrier_id',
            'label'  => $this->__('Carrier'),
            'title'  => $this->__('Carrier'),
            'values' => $carrierValues,
        ));

        $fieldset->addField('name', 'text', array(
            'name'     => 'name',
            'label'    => $this->__('Name'),
            'title'    => $this->__('Name'),
            'required' => true,
        ));

        $fieldset->addField('is_active', 'select', array(
            'label'    => Mage::helper('adminhtml')->__('Status'),
            'title'    => Mage::helper('adminhtml')->__('Status'),
            'name'     => 'is_active',
            'required' => true,
            'options'  => Mage::getModel('easyshippingrules/system_status')->toArray(),
        ));

        if (!$model->getId()) {
            $model->setIsActive(1);
        }

        /**
         * Check is single store mode
         */
        if (!Mage::app()->isSingleStoreMode()) {
            $field = $fieldset->addField('store_id', 'multiselect', array(
                'name'     => 'stores[]',
                'label'    => Mage::helper('adminhtml')->__('Store View'),
                'title'    => Mage::helper('adminhtml')->__('Store View'),
                'required' => true,
                'values'   => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
            ));

            if (!$model->getId()) {
                $model->setData('store_id', Mage_Core_Model_App::ADMIN_STORE_ID);
            }

            $field->setRenderer(
                $this->getLayout()->createBlock('adminhtml/store_switcher_form_renderer_fieldset_element')
            );
        } else {
            $fieldset->addField('store_id', 'hidden', array(
                'name'  => 'stores[]',
                'value' => Mage::app()->getStore(true)->getId(),
            ));

            $model->setStoreId(Mage::app()->getStore(true)->getId());
        }

        $form->setValues($model->getData());

        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * Prepare content for tab
     *
     * @return string
     */
    public function getTabLabel()
    {
        return $this->__('General');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return $this->__('General');
    }

    /**
     * Returns status flag about this tab can be showed or not
     *
     * @return boolean
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * Returns status flag about this tab hidden or not
     *
     * @return boolean
     */
    public function isHidden()
    {
        return false;
    }
}
