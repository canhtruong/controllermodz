<?php
namespace Os\DsfhCustomer\Model;
class City extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource 
{
    public function getAllOptions() 
    {
        $option=[
            [
                'value' => '',
                'label' => ''
            ],
            [
                'value' => 'ABHA',
                'label' => 'ABHA'
            ],
            [
                'value' => 'ABU AREESH',
                'label' => 'ABU AREESH'
            ],
            [
                'value' => 'AFEEF',
                'label' => 'AFEEF'
            ],
            [
                'value' => 'AHAD AL MASARHA',
                'label' => 'AHAD AL MASARHA'
            ],
            [
                'value' => 'AJJUF',
                'label' => 'AJJUF'
            ],
            [
                'value' => 'AL AQIQ',
                'label' => 'AL AQIQ'
            ],
            [
                'value' => 'AL ARDAH',
                'label' => 'AL ARDAH'
            ],
            [
                'value' => 'AL BADAYAA',
                'label' => 'AL BADAYAA'
            ],
            [
                'value' => 'AL BAJADIYA',
                'label' => 'AL BAJADIYA'
            ],
            [
                'value' => 'AL BASHAYIER',
                'label' => 'AL BASHAYIER'
            ],
            [
                'value' => 'AL DARB',
                'label' => 'AL DARB'
            ],
            [
                'value' => 'AL HARJAH',
                'label' => 'AL HARJAH'
            ],
            [
                'value' => 'AL IHSAA',
                'label' => 'AL IHSAA'
            ],
            [
                'value' => 'AL MAJARDAH',
                'label' => 'AL MAJARDAH'
            ],
            [
                'value' => 'AL MANDAQ',
                'label' => 'AL MANDAQ'
            ],
            [
                'value' => 'AL RUWEIDHA',
                'label' => 'AL RUWEIDHA'
            ],
            [
                'value' => 'AL WADEEN',
                'label' => 'AL WADEEN'
            ],
            [
                'value' => 'AL-BEKAREAH',
                'label' => 'AL-BEKAREAH'
            ],
            [
                'value' => 'AL-JOUF',
                'label' => 'AL-JOUF'
            ],
            [
                'value' => 'AL-ROUDA',
                'label' => 'AL-ROUDA'
            ],
            [
                'value' => 'ALAFLAG',
                'label' => 'ALAFLAG'
            ],
            [
                'value' => 'ALASYAH',
                'label' => 'ALASYAH'
            ],
            [
                'value' => 'ALBADA',
                'label' => 'ALBADA'
            ],
            [
                'value' => 'ALJAMOUM',
                'label' => 'ALJAMOUM'
            ],
            [
                'value' => 'ALKHARJ',
                'label' => 'ALKHARJ'
            ],
            [
                'value' => 'ALMUTHANNAB',
                'label' => 'ALMUTHANNAB'
            ],
            [
                'value' => 'ALQURAIAT',
                'label' => 'ALQURAIAT'
            ],
            [
                'value' => 'ALQURAYAT',
                'label' => 'ALQURAYAT'
            ],
            [
                'value' => 'ALSHAMLEE',
                'label' => 'ALSHAMLEE'
            ],
            [
                'value' => 'ALSHNAN',
                'label' => 'ALSHNAN'
            ],
            [
                'value' => 'ALWAJEH',
                'label' => 'ALWAJEH'
            ],
            [
                'value' => 'AL_BAHA',
                'label' => 'AL_BAHA'
            ],
            [
                'value' => 'ARAR',
                'label' => 'ARAR'
            ],
            [
                'value' => 'ARRAS',
                'label' => 'ARRAS'
            ],
            [
                'value' => 'ASFAN',
                'label' => 'ASFAN'
            ],
            [
                'value' => 'ASSALIL',
                'label' => 'ASSALIL'
            ],
            [
                'value' => 'AZZULFI',
                'label' => 'AZZULFI'
            ],
            [
                'value' => 'BAHRAH',
                'label' => 'BAHRAH'
            ],
            [
                'value' => 'BAISH',
                'label' => 'BAISH'
            ],
            [
                'value' => 'BALJURASHI',
                'label' => 'BALJURASHI'
            ],
            [
                'value' => 'BARIQ',
                'label' => 'BARIQ'
            ],
            [
                'value' => 'BELASMAR',
                'label' => 'BELASMAR'
            ],
            [
                'value' => 'BISHA',
                'label' => 'BISHA'
            ],
            [
                'value' => 'BUQAIQ',
                'label' => 'BUQAIQ'
            ],
            [
                'value' => 'BURAIDAH',
                'label' => 'BURAIDAH'
            ],
            [
                'value' => 'DAHRAN',
                'label' => 'DAHRAN'
            ],
            [
                'value' => 'DAHRAN AL-JANOUB',
                'label' => 'DAHRAN AL-JANOUB'
            ],
            [
                'value' => 'DALEE RASHEED',
                'label' => 'DALEE RASHEED'
            ],
            [
                'value' => 'DAMMAM',
                'label' => 'DAMMAM'
            ],
            [
                'value' => 'DARB',
                'label' => 'DARB'
            ],
            [
                'value' => 'DAWADMY',
                'label' => 'DAWADMY'
            ],
            [
                'value' => 'DAWMAT ALJANDAL',
                'label' => 'DAWMAT ALJANDAL'
            ],
            [
                'value' => 'DEBA',
                'label' => 'DEBA'
            ],
            [
                'value' => 'DEBAH',
                'label' => 'DEBAH'
            ],
            [
                'value' => 'DEREYAH',
                'label' => 'DEREYAH'
            ],
            [
                'value' => 'DULEMIYA',
                'label' => 'DULEMIYA'
            ],
            [
                'value' => 'EYEN ALJAWA',
                'label' => 'EYEN ALJAWA'
            ],
            [
                'value' => 'HAEL',
                'label' => 'HAEL'
            ],
            [
                'value' => 'HAFER ALBATEN',
                'label' => 'HAFER ALBATEN'
            ],
            [
                'value' => 'HAFUOF',
                'label' => 'HAFUOF'
            ],
            [
                'value' => 'HALET AMMAR',
                'label' => 'HALET AMMAR'
            ],
            [
                'value' => 'HAWIYA',
                'label' => 'HAWIYA'
            ],
            [
                'value' => 'JEDDAH',
                'label' => 'JEDDAH'
            ],
            [
                'value' => 'JEZAN',
                'label' => 'JEZAN'
            ],
            [
                'value' => 'JUBAIL',
                'label' => 'JUBAIL'
            ],
            [
                'value' => 'JUBAIL INDASTRIAL CITY',
                'label' => 'JUBAIL INDASTRIAL CITY'
            ],
            [
                'value' => 'KAWST',
                'label' => 'KAWST'
            ],
            [
                'value' => 'KFSH',
                'label' => 'KFSH'
            ],
            [
                'value' => 'KHABRA',
                'label' => 'KHABRA'
            ],
            [
                'value' => 'KHAFJEH',
                'label' => 'KHAFJEH'
            ],
            [
                'value' => 'KHAIBAR',
                'label' => 'KHAIBAR'
            ],
            [
                'value' => 'KHAMIS MUSHAIT',
                'label' => 'KHAMIS MUSHAIT'
            ],
            [
                'value' => 'KHOBAR',
                'label' => 'KHOBAR'
            ],
            [
                'value' => 'KHRJ',
                'label' => 'KHRJ'
            ],
            [
                'value' => 'KING SAUD MEDICAL CITY',
                'label' => 'KING SAUD MEDICAL CITY'
            ],
            [
                'value' => 'MABRAZ',
                'label' => 'MABRAZ'
            ],
            [
                'value' => 'MADINA',
                'label' => 'MADINA'
            ],
            [
                'value' => 'MAHAEIL',
                'label' => 'MAHAEIL'
            ],
            [
                'value' => 'MAJARDAH',
                'label' => 'MAJARDAH'
            ],
            [
                'value' => 'MAKKAH',
                'label' => 'MAKKAH'
            ],
            [
                'value' => 'MUJAMMAAH',
                'label' => 'MUJAMMAAH'
            ],
            [
                'value' => 'MUKHWAH',
                'label' => 'MUKHWAH'
            ],
            [
                'value' => 'MUZALIF',
                'label' => 'MUZALIF'
            ],
            [
                'value' => 'MUZNUB',
                'label' => 'MUZNUB'
            ],
            [
                'value' => 'NAJRAN',
                'label' => 'NAJRAN'
            ],
            [
                'value' => 'NUAIREIAH',
                'label' => 'NUAIREIAH'
            ],
            [
                'value' => 'ONAIZAH',
                'label' => 'ONAIZAH'
            ],
            [
                'value' => 'ONAIZAH OSH ALSHARMIAH',
                'label' => 'ONAIZAH OSH ALSHARMIAH'
            ],
            [
                'value' => 'QASSEM',
                'label' => 'QASSEM'
            ],
            [
                'value' => 'QATIEF',
                'label' => 'QATIEF'
            ],
            [
                'value' => 'QUNFUTHAH',
                'label' => 'QUNFUTHAH'
            ],
            [
                'value' => 'QUWAIEYAH',
                'label' => 'QUWAIEYAH'
            ],
            [
                'value' => 'RABIGH',
                'label' => 'RABIGH'
            ],
            [
                'value' => 'RAFAYA JAMS',
                'label' => 'RAFAYA JAMS'
            ],
            [
                'value' => 'RAHMAH',
                'label' => 'RAHMAH'
            ],
            [
                'value' => 'RASTANURA',
                'label' => 'RASTANURA'
            ],
            [
                'value' => 'RIYADH',
                'label' => 'RIYADH'
            ],
            [
                'value' => 'RIYADH - MEZAHMIA',
                'label' => 'RIYADH - MEZAHMIA'
            ],
            [
                'value' => 'RIYADH ALKHABRA',
                'label' => 'RIYADH ALKHABRA'
            ],
            [
                'value' => 'RIYADH GENERAL STORES',
                'label' => 'RIYADH GENERAL STORES'
            ],
            [
                'value' => 'SABT AL ALAIA',
                'label' => 'SABT AL ALAIA'
            ],
            [
                'value' => 'SABYA',
                'label' => 'SABYA'
            ],
            [
                'value' => 'SAFWA',
                'label' => 'SAFWA'
            ],
            [
                'value' => 'SAJER',
                'label' => 'SAJER'
            ],
            [
                'value' => 'SAMTHA',
                'label' => 'SAMTHA'
            ],
            [
                'value' => 'SEDARE',
                'label' => 'SEDARE'
            ],
            [
                'value' => 'SEHAN',
                'label' => 'SEHAN'
            ],
            [
                'value' => 'SHAROARA',
                'label' => 'SHAROARA'
            ],
            [
                'value' => 'SHAROURA',
                'label' => 'SHAROURA'
            ],
            [
                'value' => 'SHUQARA',
                'label' => 'SHUQARA'
            ],
            [
                'value' => 'SIHAT',
                'label' => 'SIHAT'
            ],
            [
                'value' => 'SKAKA',
                'label' => 'SKAKA'
            ],
            [
                'value' => 'TABOUK',
                'label' => 'TABOUK'
            ],
            [
                'value' => 'TAIF',
                'label' => 'TAIF'
            ],
            [
                'value' => 'TAIMA',
                'label' => 'TAIMA'
            ],
            [
                'value' => 'TURAIF',
                'label' => 'TURAIF'
            ],
            [
                'value' => 'TURBAH',
                'label' => 'TURBAH'
            ],
            [
                'value' => 'UGLAT ASUGOUR',
                'label' => 'UGLAT ASUGOUR'
            ],
            [
                'value' => 'UM AL-SAIK',
                'label' => 'UM AL-SAIK'
            ],
            [
                'value' => 'WADI ADDAWASER',
                'label' => 'WADI ADDAWASER'
            ],
            [
                'value' => 'WADI BIN HASHBAL',
                'label' => 'WADI BIN HASHBAL'
            ],
            [
                'value' => 'WADY ADDAWASER',
                'label' => 'WADY ADDAWASER'
            ],
            [
                'value' => 'WASLY',
                'label' => 'WASLY'
            ],
            [
                'value' => 'YANBU',
                'label' => 'YANBU'
            ]
            
        ]; 

        return $option; 
    }
    
    public function getOptionText($value) 
    {
        foreach ($this->getAllOptions() as $option) {
            if ($option['value'] == $value) {
                return $option['label'];
            }
        }
        return false;
    }
}
