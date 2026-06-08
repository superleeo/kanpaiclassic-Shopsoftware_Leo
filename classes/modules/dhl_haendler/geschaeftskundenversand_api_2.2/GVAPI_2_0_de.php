<?php

include_once('Version.php');
include_once('AuthentificationType.php');
include_once('NativeAddressType.php');
include_once('ReceiverNativeAddressType.php');
include_once('PickupAddressType.php');
include_once('DeliveryAddressType.php');
include_once('BankType.php');
include_once('NameType.php');
include_once('ReceiverNameType.php');
include_once('CommunicationType.php');
include_once('ContactType.php');
include_once('PackStationType.php');
include_once('PostfilialeType.php');
include_once('ParcelShopType.php');
include_once('CustomerType.php');
include_once('ErrorType.php');
include_once('CountryType.php');
include_once('ShipmentNumberType.php');
include_once('Status.php');
include_once('Dimension.php');
include_once('TimeFrame.php');
include_once('GetVersionResponse.php');
include_once('CreateShipmentOrderRequest.php');
include_once('ValidateShipmentOrderRequest.php');
include_once('CreateShipmentOrderResponse.php');
include_once('ValidateShipmentResponse.php');
include_once('GetLabelRequest.php');
include_once('GetLabelResponse.php');
include_once('DoManifestRequest.php');
include_once('DoManifestResponse.php');
include_once('DeleteShipmentOrderRequest.php');
include_once('DeleteShipmentOrderResponse.php');
include_once('GetExportDocRequest.php');
include_once('GetExportDocResponse.php');
include_once('GetManifestRequest.php');
include_once('GetManifestResponse.php');
include_once('UpdateShipmentOrderRequest.php');
include_once('UpdateShipmentOrderResponse.php');
include_once('CreationState.php');
include_once('ValidationState.php');
include_once('Statusinformation.php');
include_once('PieceInformation.php');
include_once('ShipmentOrderType.php');
include_once('Shipment.php');
include_once('ValidateShipmentOrderType.php');
include_once('ShipperTypeType.php');
include_once('ShipperType.php');
include_once('ReceiverTypeType.php');
include_once('ReceiverType.php');
include_once('Ident.php');
include_once('ShipmentDetailsType.php');
include_once('ShipmentDetailsTypeType.php');
include_once('ShipmentItemType.php');
include_once('ShipmentItemTypeType.php');
include_once('ShipmentService.php');
include_once('Serviceconfiguration.php');
include_once('ServiceconfigurationDetails.php');
include_once('ServiceconfigurationType.php');
include_once('ServiceconfigurationEndorsement.php');
include_once('ServiceconfigurationISR.php');
include_once('ServiceconfigurationDH.php');
include_once('ServiceconfigurationVisualAgeCheck.php');
include_once('ServiceconfigurationDeliveryTimeframe.php');
include_once('ServiceconfigurationDateOfDelivery.php');
include_once('ServiceconfigurationAdditionalInsurance.php');
include_once('ServiceconfigurationCashOnDelivery.php');
include_once('ServiceconfigurationShipmentHandling.php');
include_once('ServiceconfigurationUnfree.php');
include_once('ServiceconfigurationIC.php');
include_once('ShipmentNotificationType.php');
include_once('ExportDocumentType.php');
include_once('ExportDocPosition.php');
include_once('FurtherAddressesType.php');
include_once('DeliveryAdress.php');
include_once('LabelData.php');
include_once('ExportDocData.php');
include_once('ManifestState.php');
include_once('DeletionState.php');
include_once('BookPickupRequest.php');
include_once('BookPickupResponse.php');
include_once('PickupDetailsType.php');
include_once('PickupOrdererType.php');
include_once('PickupBookingInformationType.php');
include_once('CancelPickupRequest.php');
include_once('CancelPickupResponse.php');
include_once('IdentityData.php');
include_once('DrivingLicense.php');
include_once('IdentityCard.php');
include_once('BankCard.php');
include_once('PackstationType.php');
include_once('ReadShipmentOrderResponse.php');

class GVAPI_2_0_de extends \SoapClient
{

    /**
     * @var array $classmap The defined classes
     * @access private
     */
    private static $classmap = array(
      'Version' => '\Version',
      'AuthentificationType' => '\AuthentificationType',
      'NativeAddressType' => '\NativeAddressType',
      'ReceiverNativeAddressType' => '\ReceiverNativeAddressType',
      'PickupAddressType' => '\PickupAddressType',
      'DeliveryAddressType' => '\DeliveryAddressType',
      'BankType' => '\BankType',
      'NameType' => '\NameType',
      'ReceiverNameType' => '\ReceiverNameType',
      'CommunicationType' => '\CommunicationType',
      'ContactType' => '\ContactType',
      'PackStationType' => '\PackStationType',
      'PostfilialeType' => '\PostfilialeType',
      'ParcelShopType' => '\ParcelShopType',
      'CustomerType' => '\CustomerType',
      'ErrorType' => '\ErrorType',
      'CountryType' => '\CountryType',
      'ShipmentNumberType' => '\ShipmentNumberType',
      'Status' => '\Status',
      'Dimension' => '\Dimension',
      'TimeFrame' => '\TimeFrame',
      'GetVersionResponse' => '\GetVersionResponse',
      'CreateShipmentOrderRequest' => '\CreateShipmentOrderRequest',
      'ValidateShipmentOrderRequest' => '\ValidateShipmentOrderRequest',
      'CreateShipmentOrderResponse' => '\CreateShipmentOrderResponse',
      'ValidateShipmentResponse' => '\ValidateShipmentResponse',
      'GetLabelRequest' => '\GetLabelRequest',
      'GetLabelResponse' => '\GetLabelResponse',
      'DoManifestRequest' => '\DoManifestRequest',
      'DoManifestResponse' => '\DoManifestResponse',
      'DeleteShipmentOrderRequest' => '\DeleteShipmentOrderRequest',
      'DeleteShipmentOrderResponse' => '\DeleteShipmentOrderResponse',
      'GetExportDocRequest' => '\GetExportDocRequest',
      'GetExportDocResponse' => '\GetExportDocResponse',
      'GetManifestRequest' => '\GetManifestRequest',
      'GetManifestResponse' => '\GetManifestResponse',
      'UpdateShipmentOrderRequest' => '\UpdateShipmentOrderRequest',
      'UpdateShipmentOrderResponse' => '\UpdateShipmentOrderResponse',
      'CreationState' => '\CreationState',
      'ValidationState' => '\ValidationState',
      'Statusinformation' => '\Statusinformation',
      'PieceInformation' => '\PieceInformation',
      'ShipmentOrderType' => '\ShipmentOrderType',
      'Shipment' => '\Shipment',
      'ValidateShipmentOrderType' => '\ValidateShipmentOrderType',
      'ShipperTypeType' => '\ShipperTypeType',
      'ShipperType' => '\ShipperType',
      'ReceiverTypeType' => '\ReceiverTypeType',
      'ReceiverType' => '\ReceiverType',
      'Ident' => '\Ident',
      'ShipmentDetailsType' => '\ShipmentDetailsType',
      'ShipmentDetailsTypeType' => '\ShipmentDetailsTypeType',
      'ShipmentItemType' => '\ShipmentItemType',
      'ShipmentItemTypeType' => '\ShipmentItemTypeType',
      'ShipmentService' => '\ShipmentService',
      'Serviceconfiguration' => '\Serviceconfiguration',
      'ServiceconfigurationDetails' => '\ServiceconfigurationDetails',
      'ServiceconfigurationType' => '\ServiceconfigurationType',
      'ServiceconfigurationEndorsement' => '\ServiceconfigurationEndorsement',
      'ServiceconfigurationISR' => '\ServiceconfigurationISR',
      'ServiceconfigurationDH' => '\ServiceconfigurationDH',
      'ServiceconfigurationVisualAgeCheck' => '\ServiceconfigurationVisualAgeCheck',
      'ServiceconfigurationDeliveryTimeframe' => '\ServiceconfigurationDeliveryTimeframe',
      'ServiceconfigurationDateOfDelivery' => '\ServiceconfigurationDateOfDelivery',
      'ServiceconfigurationAdditionalInsurance' => '\ServiceconfigurationAdditionalInsurance',
      'ServiceconfigurationCashOnDelivery' => '\ServiceconfigurationCashOnDelivery',
      'ServiceconfigurationShipmentHandling' => '\ServiceconfigurationShipmentHandling',
      'ServiceconfigurationUnfree' => '\ServiceconfigurationUnfree',
      'ServiceconfigurationIC' => '\ServiceconfigurationIC',
      'ShipmentNotificationType' => '\ShipmentNotificationType',
      'ExportDocumentType' => '\ExportDocumentType',
      'ExportDocPosition' => '\ExportDocPosition',
      'FurtherAddressesType' => '\FurtherAddressesType',
      'DeliveryAdress' => '\DeliveryAdress',
      'LabelData' => '\LabelData',
      'ExportDocData' => '\ExportDocData',
      'ManifestState' => '\ManifestState',
      'DeletionState' => '\DeletionState',
      'BookPickupRequest' => '\BookPickupRequest',
      'BookPickupResponse' => '\BookPickupResponse',
      'PickupDetailsType' => '\PickupDetailsType',
      'PickupOrdererType' => '\PickupOrdererType',
      'PickupBookingInformationType' => '\PickupBookingInformationType',
      'CancelPickupRequest' => '\CancelPickupRequest',
      'CancelPickupResponse' => '\CancelPickupResponse',
      'IdentityData' => '\IdentityData',
      'DrivingLicense' => '\DrivingLicense',
      'IdentityCard' => '\IdentityCard',
      'BankCard' => '\BankCard',
      'PackstationType' => '\PackstationType',
      'ReadShipmentOrderResponse' => '\ReadShipmentOrderResponse');

    /**
     * @param array $options A array of config values
     * @param string $wsdl The wsdl file to use
     * @access public
     */
    public function __construct(array $options = array(), $wsdl = 'https://cig.dhl.de/cig-wsdls/com/dpdhl/wsdl/geschaeftskundenversand-api/2.2/geschaeftskundenversand-api-2.2.wsdl')
    {
      foreach (self::$classmap as $key => $value) {
        if (!isset($options['classmap'][$key])) {
          $options['classmap'][$key] = $value;
        }
      }
      
      parent::__construct($wsdl, $options);
    }

    /**
     * Creates shipments.
     *
     * @param CreateShipmentOrderRequest $part1
     * @access public
     * @return CreateShipmentOrderResponse
     */
    public function createShipmentOrder(CreateShipmentOrderRequest $part1)
    {
      return $this->__soapCall('createShipmentOrder', array($part1));
    }

    /**
     * Creates shipments.
     *
     * @param ValidateShipmentOrderRequest $part1
     * @access public
     * @return ValidateShipmentResponse
     */
    public function validateShipment(ValidateShipmentOrderRequest $part1)
    {
      return $this->__soapCall('validateShipment', array($part1));
    }

    /**
     * Deletes the requested shipments.
     *
     * @param DeleteShipmentOrderRequest $part1
     * @access public
     * @return DeleteShipmentOrderResponse
     */
    public function deleteShipmentOrder(DeleteShipmentOrderRequest $part1)
    {
      return $this->__soapCall('deleteShipmentOrder', array($part1));
    }

    /**
     * Manifest the requested DD shipments.
     *
     * @param DoManifestRequest $part1
     * @access public
     * @return DoManifestResponse
     */
    public function doManifest(DoManifestRequest $part1)
    {
      return $this->__soapCall('doManifest', array($part1));
    }

    /**
     * Returns the request-url for getting a label.
     *
     * @param GetLabelRequest $part1
     * @access public
     * @return GetLabelResponse
     */
    public function getLabel(GetLabelRequest $part1)
    {
      return $this->__soapCall('getLabel', array($part1));
    }

    /**
     * Returns the actual version of the implementation of the whole ISService
     *         webservice.
     *
     * @param Version $part1
     * @access public
     * @return GetVersionResponse
     */
    public function getVersion(Version $part1)
    {
      return $this->__soapCall('getVersion', array($part1));
    }

    /**
     * Returns the request-url for getting a export
     *         document.
     *
     * @param GetExportDocRequest $part1
     * @access public
     * @return GetExportDocResponse
     */
    public function getExportDoc(GetExportDocRequest $part1)
    {
      return $this->__soapCall('getExportDoc', array($part1));
    }

    /**
     * Request the manifest.
     *
     * @param GetManifestRequest $part1
     * @access public
     * @return GetManifestResponse
     */
    public function getManifest(GetManifestRequest $part1)
    {
      return $this->__soapCall('getManifest', array($part1));
    }

    /**
     * Updates a shipment order.
     *
     * @param UpdateShipmentOrderRequest $part1
     * @access public
     * @return UpdateShipmentOrderResponse
     */
    public function updateShipmentOrder(UpdateShipmentOrderRequest $part1)
    {
      return $this->__soapCall('updateShipmentOrder', array($part1));
    }

}
