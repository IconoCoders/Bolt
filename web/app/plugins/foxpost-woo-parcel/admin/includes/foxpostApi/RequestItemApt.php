<?php
/**
 * @author Foxpost-GZ
 */

namespace Foxpost\FoxpostApi;

/**
 * Class RequestItemApt
 */
class RequestItemApt extends RequestItemAbstract {
    /**
     * @var string Címzett neve
     */
    protected $recipient_name;
    /**
     * @var string Címzett telefonszáma
     */
    protected $recipient_phone;
    /**
     * @var string Címzett email címe
     */
    protected $recipient_email;
    /**
     * @var string Cél automata azonosítója (pl.: hu39)
     */
    protected $destination;
    /**
     * @var int Utánvétel összege
     */
    protected $cash_on_delivery_money;
    /**
     * @var string eg.: S,XS,L,M,XL
     */
    protected $parcel_size_id;
    /**
     * @var string A feladó megjegyzése (Saját adatok)
     */
    protected $sender_own_data;
    /**
     * @var int Kér-e címkenyomtatás szolgáltatást. 1 on true otherwise 0
     */
    protected $extra_service_label_print;
    /**
     * @var int Kér-e törékeny szolgáltatást. 1 on true otherwise 0
     */
    protected $extra_service_fragile;
    /**
     * @var string Egyedi vonalkód
     */
    protected $unique_barcode;
    /**
     * @var string Referencia kód
     */
    protected $reference_code;
    /**
     * @var string A rendelés egyedi azonosítója
     */
    protected $api_external_id;

    /**
     * @return string
     */
    public function getRecipientName()
    {
        return $this->recipient_name;
    }

    /**
     * @param string $recipient_name
     */
    public function setRecipientName($recipient_name)
    {
        $this->recipient_name = $recipient_name;
    }

    /**
     * @return string
     */
    public function getRecipientPhone()
    {
        return $this->recipient_phone;
    }

    /**
     * @param string $recipient_phone
     */
    public function setRecipientPhone($recipient_phone)
    {
        $this->recipient_phone = $recipient_phone;
    }

    /**
     * @return string
     */
    public function getRecipientEmail()
    {
        return $this->recipient_email;
    }

    /**
     * @param string $recipient_email
     */
    public function setRecipientEmail($recipient_email)
    {
        $this->recipient_email = $recipient_email;
    }

    /**
     * @return string
     */
    public function getDestination()
    {
        return $this->destination;
    }

    /**
     * @param string $destination
     */
    public function setDestination($destination)
    {
        $this->destination = $destination;
    }

    /**
     * @return int
     */
    public function getCashOnDeliveryMoney()
    {
        return $this->cash_on_delivery_money;
    }

    /**
     * @param int $cash_on_delivery_money
     */
    public function setCashOnDeliveryMoney($cash_on_delivery_money)
    {
        $this->cash_on_delivery_money = $cash_on_delivery_money;
    }

    /**
     * @return string
     */
    public function getParcelSizeId()
    {
        return $this->parcel_size_id;
    }

    /**
     * @param string $parcel_size_id
     */
    public function setParcelSizeId($parcel_size_id)
    {
        $this->parcel_size_id = $parcel_size_id;
    }

    /**
     * @return string
     */
    public function getSenderOwnData()
    {
        return $this->sender_own_data;
    }

    /**
     * @param string $sender_own_data
     */
    public function setSenderOwnData($sender_own_data)
    {
        $this->sender_own_data = $sender_own_data;
    }

    /**
     * @return int
     */
    public function getExtraServiceLabelPrint()
    {
        return $this->extra_service_label_print;
    }

    /**
     * @param int $extra_service_label_print
     */
    public function setExtraServiceLabelPrint($extra_service_label_print)
    {
        $this->extra_service_label_print = $extra_service_label_print;
    }

    /**
     * @return int
     */
    public function getExtraServiceFragile()
    {
        return $this->extra_service_fragile;
    }

    /**
     * @param int $extra_service_fragile
     */
    public function setExtraServiceFragile($extra_service_fragile)
    {
        $this->extra_service_fragile = $extra_service_fragile;
    }

    /**
     * @return string
     */
    public function getUniqueBarcode()
    {
        return $this->unique_barcode;
    }

    /**
     * @param string $unique_barcode
     */
    public function setUniqueBarcode($unique_barcode)
    {
        $this->unique_barcode = $unique_barcode;
    }

    /**
     * @return string
     */
    public function getReferenceCode()
    {
        return $this->reference_code;
    }

    /**
     * @param string $reference_code
     */
    public function setReferenceCode($reference_code)
    {
        $this->reference_code = $reference_code;
    }

    /**
     * @return string
     */
    public function getApiexternalid()
    {
        return $this->api_external_id;
    }

    /**
     * @param string $api_external_id
     */
    public function setApiexternalid($api_external_id)
    {
        $this->api_external_id = $api_external_id;
    }

    /**
     * @{@inheritdoc}
     */
    public function getParcelType()
    {
        return self::PARCEL_TYPE_APT;
    }

}