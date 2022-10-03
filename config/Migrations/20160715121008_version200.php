<?php

use Migrations\AbstractMigration;

class Version200 extends AbstractMigration
{
    public $autoId = false;

    public function up()
    {
        $this->execute("SET SESSION sql_mode = ''");

        $table_prefix = $this->getAdapter()->getOption('table_prefix');

        $this->execute('ALTER TABLE `' . $table_prefix . 'links` CHANGE `alias` `alias` VARCHAR(256) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL;');

        $rows = [
            [
                'name' => 'banner_price',
                'value' => 'a:240:{s:3:"all";a:2:{s:10:"advertiser";i:1;s:9:"publisher";d:0.40000000000000002;}s:2:"AF";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"AL";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"DZ";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"AS";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"AD";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"AO";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"AI";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"AQ";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"AG";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"AR";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"AM";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"AW";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"AU";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"AT";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"AZ";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"BS";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"BH";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"BD";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"BB";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"BY";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"BE";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"BZ";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"BJ";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"BM";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"BT";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"BO";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"BA";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"BW";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"BV";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"BR";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"IO";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"BN";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"BG";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"BF";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"BI";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"KH";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"CM";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"CA";a:2:{s:10:"advertiser";i:4;s:9:"publisher";d:1.6000000000000001;}s:2:"CV";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"KY";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"CF";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"TD";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"CL";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"CN";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"CX";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"CC";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"CO";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"KM";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"CG";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"CD";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"CK";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"CR";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"CI";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"HR";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"CU";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"CY";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"CZ";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"DK";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"DJ";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"DM";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"DO";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"TP";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"EC";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"EG";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"SV";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"GQ";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"ER";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"EE";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"ET";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"FK";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"FO";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"FJ";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"FI";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"FR";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"FX";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"GF";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"PF";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"TF";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"GA";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"GM";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"GE";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"DE";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"GH";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"GI";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"GR";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"GL";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"GD";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"GP";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"GU";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"GT";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"GN";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"GW";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"GY";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"HT";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"HM";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"VA";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"HN";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"HK";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"HU";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"IS";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"IN";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"ID";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"IR";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"IQ";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"IE";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"IL";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"IT";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"JM";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"JP";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"JO";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"KZ";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"KE";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"KI";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"KP";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"KR";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"KW";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"KG";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"LA";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"LV";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"LB";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"LS";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"LR";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"LY";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"LI";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"LT";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"LU";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"MO";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"MK";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"MG";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"MW";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"MY";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"MV";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"ML";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"MT";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"MH";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"MQ";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"MR";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"MU";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"YT";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"MX";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"FM";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"MD";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"MC";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"MN";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"MS";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"MA";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"MZ";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"MM";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"NA";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"NR";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"NP";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"NL";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"AN";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"NC";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"NZ";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"NI";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"NE";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"NG";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"NU";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"NF";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"MP";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"NO";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"OM";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"PK";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"PW";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"PA";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"PG";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"PY";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"PE";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"PH";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"PN";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"PL";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"PT";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"PR";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"QA";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"RE";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"RO";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"RU";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"RW";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"KN";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"LC";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"VC";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"WS";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"SM";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"ST";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"SA";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"SN";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"SC";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"SL";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"SG";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"SK";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"SI";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"SB";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"SO";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"ZA";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"GS";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"ES";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"LK";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"SH";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"PM";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"SD";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"SR";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"SJ";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"SZ";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"SE";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"CH";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"SY";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"TW";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"TJ";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"TZ";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"TH";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"TG";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"TK";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"TO";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"TT";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"TN";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"TR";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"TM";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"TC";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"TV";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"UG";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"UA";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"AE";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"GB";a:2:{s:10:"advertiser";i:5;s:9:"publisher";i:2;}s:2:"US";a:2:{s:10:"advertiser";i:5;s:9:"publisher";i:2;}s:2:"UM";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"UY";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"UZ";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"VU";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"VE";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"VN";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"VG";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"VI";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"WF";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"EH";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"YE";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"YU";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"ZM";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}s:2:"ZW";a:2:{s:10:"advertiser";s:0:"";s:9:"publisher";s:0:"";}}',
            ],
            [
                'name' => 'banner_728x90',
                'value' => '',
            ],
            [
                'name' => 'banner_468x60',
                'value' => '',
            ],
            [
                'name' => 'banner_336x280',
                'value' => '',
            ],
            [
                'name' => 'referral_percentage',
                'value' => '20',
            ],
            [
                'name' => 'enable_captcha',
                'value' => 'yes',
            ],
            [
                'name' => 'enable_captcha_shortlink',
                'value' => 'yes',
            ],
            [
                'name' => 'enable_captcha_signup',
                'value' => 'yes',
            ],
            [
                'name' => 'enable_captcha_forgot_password',
                'value' => 'yes',
            ],
            [
                'name' => 'logo_url',
                'value' => '',
            ],
            [
                'name' => 'logo_url_alt',
                'value' => '',
            ],
            [
                'name' => 'ad_member',
                'value' => '',
            ],
            [
                'name' => 'ad_captcha',
                'value' => '',
            ],
        ];

        $this->table('options')
            ->insert($rows)
            ->saveData();

        $this->table('campaigns')
            ->renameColumn('ad_id', 'ad_type')
            ->changeColumn('ad_type', 'integer', [
                'comment' => '1 = inter., 2 = banner',
                'default' => 1,
                'limit' => 2,
                'null' => false,
                'signed' => false,
            ])
            ->renameColumn('title', 'website_title')
            ->renameColumn('url', 'website_url')
            ->addColumn('name', 'string', [
                'default' => '',
                'limit' => 256,
                'null' => false,
                'after' => 'status',
            ])
            ->addColumn('banner_name', 'string', [
                'default' => '',
                'limit' => 256,
                'null' => false,
                'after' => 'website_url',
            ])
            ->addColumn('banner_size', 'text', [
                'default' => null,
                'limit' => 256,
                'null' => true,
                'after' => 'banner_name',
            ])
            ->addColumn('banner_code', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
                'after' => 'banner_size',
            ])
            ->addColumn('traffic_source', 'integer', [
                'comment' => '1 = all, 2 = Desktop, 3 = Mobile',
                'default' => 1,
                'limit' => 2,
                'null' => false,
                'signed' => false,
                'after' => 'price',
            ])
            ->update();

        $this->execute("UPDATE `{$table_prefix}campaigns` SET `ad_type` = 1;");

        $this->table('campaigns')
            ->insert([
                'default_campaign' => 1,
                'user_id' => 2,
                'ad_type' => 2,
                'status' => 'Active',
                'name' => 'Default Banner Campaign',
                'banner_name' => 'Default Banner',
                'banner_size' => '468x60',
                'banner_code' => '<a href="#"><img src="https://via.placeholder.com/468x60?text=Banner+468x60"></a>',
                'price' => 10,
                'transaction_id' => '',
                'transaction_details' => '',
                'started' => date("Y-m-d H:i:s"),
                'completed' => date("Y-m-d H:i:s"),
                'modified' => date("Y-m-d H:i:s"),
                'created' => date("Y-m-d H:i:s"),
            ])
            ->saveData();

        $max_row = $this->fetchRow("SELECT * FROM `{$table_prefix}campaigns` ORDER BY `id` DESC LIMIT 1");

        $this->table('campaign_items')
            ->insert([
                'campaign_id' => $max_row['id'],
                'country' => 'all',
                'advertiser_price' => 1.000000,
                'publisher_price' => 0.400000,
                'purchase' => 10,
                'views' => 0,
                'weight' => 0,
            ])
            ->saveData();

        $this->table('links')
            ->renameColumn('ad_id', 'ad_type')
            ->changeColumn('ad_type', 'integer', [
                'comment' => '1 = inter., 2 = banner',
                'default' => 1,
                'limit' => 2,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('image', 'string', [
                'default' => '',
                'limit' => 256,
                'null' => false,
                'after' => 'description',
            ])
            ->update();

        $this->execute("UPDATE `{$table_prefix}links` SET `ad_type` = 1;");

        $this->table('statistics')
            ->renameColumn('ad_id', 'ad_type')
            ->changeColumn('ad_type', 'integer', [
                'comment' => '1 = inter., 2 = banner',
                'default' => 1,
                'limit' => 2,
                'null' => false,
                'signed' => false,
            ])
            ->update();

        $this->execute("UPDATE `{$table_prefix}statistics` SET `ad_type` = 1;");

        $this->table('users')
            ->renameColumn('publisher_balance', 'publisher_earnings')
            ->changeColumn('publisher_earnings', 'float', [
                'default' => 0,
                'null' => false,
                'precision' => 50,
                'scale' => 6,
                'signed' => false,
            ])
            ->changeColumn('advertiser_balance', 'float', [
                'default' => 0,
                'null' => false,
                'precision' => 50,
                'scale' => 6,
                'signed' => false,
            ])
            ->addColumn('referral_earnings', 'float', [
                'default' => 0,
                'null' => false,
                'precision' => 50,
                'scale' => 6,
                'signed' => false,
                'after' => 'publisher_earnings',
            ])
            ->addColumn('referred_by', 'integer', [
                'default' => 0,
                'limit' => 10,
                'null' => false,
                'signed' => false,
                'after' => 'referral_earnings',
            ])
            ->update();

        $this->table('withdraws')
            ->changeColumn('amount', 'float', [
                'default' => 0,
                'null' => false,
                'precision' => 50,
                'scale' => 6,
                'signed' => false,
            ])
            ->addColumn('publisher_earnings', 'float', [
                'default' => 0,
                'null' => false,
                'precision' => 50,
                'scale' => 6,
                'signed' => false,
                'after' => 'status',
            ])
            ->addColumn('referral_earnings', 'float', [
                'default' => 0,
                'null' => false,
                'precision' => 50,
                'scale' => 6,
                'signed' => false,
                'after' => 'publisher_earnings',
            ])
            ->update();

        $this->execute("UPDATE `{$table_prefix}withdraws` SET `publisher_earnings` = `amount`;");
    }

    public function down()
    {
        $this->execute("SET SESSION sql_mode = ''");

        $exists = $this->hasTable('ads');
        if ($exists) {
            $this->dropTable('ads');
        }

        $table_prefix = $this->getAdapter()->getOption('table_prefix');

        $items = implode(",", [
            "'banner_price'",
            "'banner_728x90'",
            "'banner_468x60'",
            "'banner_336x280'",
            "'referral_percentage'",
            "'enable_captcha'",
            "'enable_captcha_shortlink'",
            "'enable_captcha_signup'",
            "'enable_captcha_forgot_password'",
            "'logo_url'",
            "'logo_url_alt'",
            "'ad_member'",
            "'ad_captcha'",
        ]);
        $this->execute("DELETE FROM `{$table_prefix}options` WHERE `name` IN ({$items});");

        $this->table('campaigns')
            ->renameColumn('ad_type', 'ad_id')
            ->changeColumn('ad_id', 'integer', [
                'default' => 0,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->renameColumn('website_title', 'title')
            ->renameColumn('website_url', 'url')
            ->removeColumn('name')
            ->removeColumn('banner_name')
            ->removeColumn('banner_size')
            ->removeColumn('banner_code')
            ->removeColumn('traffic_source')
            ->update();

        $this->execute("UPDATE `{$table_prefix}campaigns` SET `ad_id` = 1;");

        $this->table('links')
            ->renameColumn('ad_type', 'ad_id')
            ->changeColumn('ad_id', 'integer', [
                'default' => 0,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->removeColumn('image')
            ->update();

        $this->execute("UPDATE `{$table_prefix}links` SET `ad_id` = 1;");

        $this->table('statistics')
            ->renameColumn('ad_type', 'ad_id')
            ->changeColumn('ad_id', 'integer', [
                'default' => 0,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->update();

        $this->execute("UPDATE `{$table_prefix}statistics` SET `ad_id` = 1;");

        $this->table('users')
            ->renameColumn('publisher_earnings', 'publisher_balance')
            ->removeColumn('referral_earnings')
            ->removeColumn('referred_by')
            ->update();


        $this->table('withdraws')
            ->removeColumn('publisher_earnings')
            ->removeColumn('referral_earnings')
            ->update();
    }
}
