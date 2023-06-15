<?php

namespace App\Traits;

use App\Models\Show;
use Illuminate\Http\Request;
use Illuminate\Support\Env;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

trait SystemFunctions {
    public function getAppEnvironment() {
        return Env::get('APP_ENV') === 'local' ? 'dev' : 'prod';
    }

    public function getAssetUrl($asset): string {
        return Env::get('APP_URL').'/images/'.$asset.'/';
    }

    public function getStationName() {
        return $this->getStationCode() === 'mnl' ? 'Monster RX93.1' : ($this->getStationCode() === 'cbu' ? 'Monster BT105.9 Cebu' : 'Monster BT99.5 Davao');
    }

    public function getFileLocation($folderName, $file) {
        return $this->getAppEnvironment() === 'dev' ? 'http://127.0.0.6/images/'.$folderName.'/'.$file : $this->getAppUrl() . '/images/'.$folderName.'/'.$file;
    }

    public function getStream() {
        $station_code = $this->getStationCode();

        if ($station_code === 'cbu') {
            return 'https://sg-icecast.eradioportal.com:8443/rxcebu';
        } else if ($station_code === 'dav') {
            return 'https://sg-icecast.eradioportal.com:8443/monsterrx_davao';
        } else {
            return 'https://ph-icecast-win.eradioportal.com:8443/monsterrx';
        }
    }

    public function getAppUrl() {
        $application_environment = $this->getAppEnvironment();
        $station_code = $this->getStationCode();

        if ($application_environment !== 'dev') {
            if ($station_code === 'cbu') {
                return 'https://monstercebu.com';
            }

            if ($station_code === 'dav') {
                return 'https://monsterdavao.com';
            }

            if ($station_code === 'mnl') {
                return 'https://rx931.com';
            }
        } else {
            return Env::get('APP_URL');
        }
    }

    public function getStationCode() {
        return Env::get('APP_CODE');
    }

    public function getAppVersion() {
        return Env::get('APP_VERSION');
    }

    public function IdGenerator($length) : string
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = 'RX';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[mt_rand(0, $charactersLength - 1)];
        }

        return $randomString.'931';
    }

    public function getSecret() {
        return Env::get('APP_RECAPTCHA_SECRET');
    }

    public function getSiteKey() {
        return Env::get('APP_RECAPTCHA_SITEKEY');
    }

    public function getStationChart() {
        $id = $this->getStationCode() === 'mnl' ? 17 : ($this->getStationCode() === 'cbu' ? 38 : 29);

        $show = Show::with('Jock')->findOrFail($id);

        return $show->title;
    }

    // for the session in chart voting page
    public function generateUniqueID($length, $string = ""): string
    {
        $chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charLength = strlen($chars);

        for ($i = 0; $i < $length; $i++) {
            $string .= $chars[rand(0, $charLength - 1)];
        }

        return Hash::make($string);
    }

    public function downloadFile(Request $request): BinaryFileResponse
    {
        $headers = [
            'Content-Type' => 'image/jpeg',
        ];

        $file = public_path('images/wallpapers/' . $request['image']);

        return response()->download($file, $request['image'], $headers);
    }

    public function getCountries() : array {
        return ["Afghanistan", "Albania", "Algeria",
            "American Samoa", "Andorra", "Angola",
            "Anguilla", "Antarctica", "Antigua and Barbuda",
            "Argentina", "Armenia", "Aruba",
            "Australia", "Austria", "Azerbaijan",
            "Bahamas", "Bahrain", "Bangladesh",
            "Barbados", "Belarus", "Belgium",
            "Belize", "Benin", "Bermuda",
            "Bhutan", "Bolivia", "Bosnia and Herzegowina",
            "Botswana", "Bouvet Island", "Brazil",
            "British Indian Ocean Territory", "Brunei Darussalam", "Bulgaria",
            "Burkina Faso", "Burundi", "Cambodia",
            "Cameroon", "Canada", "Cape Verde",
            "Cayman Islands", "Central African Republic", "Chad",
            "Chile", "China", "Christmas Island",
            "Cocos (Keeling) Islands", "Colombia", "Comoros",
            "Congo", "Congo, the Democratic Republic of the", "Cook Islands",
            "Costa Rica", "Cote d'Ivoire", "Croatia (Hrvatska)",
            "Cuba", "Cyprus", "Czech Republic",
            "Denmark", "Djibouti", "Dominica",
            "Dominican Republic", "East Timor", "Ecuador",
            "Egypt", "El Salvador", "Equatorial Guinea",
            "Eritrea", "Estonia", "Ethiopia",
            "Falkland Islands (Malvinas)", "Faroe Islands", "Fiji",
            "Finland", "France", "France Metropolitan",
            "French Guiana", "French Polynesia", "French Southern Territories",
            "Gabon", "Gambia", "Georgia",
            "Germany", "Ghana", "Gibraltar",
            "Greece", "Greenland", "Grenada",
            "Guadeloupe", "Guam", "Guatemala",
            "Guinea", "Guinea-Bissau", "Guyana",
            "Haiti", "Heard and Mc Donald Islands", "Holy See (Vatican City State)", "Honduras",
            "Hong Kong", "Hungary", "Iceland",
            "India", "Indonesia", "Iran (Islamic Republic of)",
            "Iraq", "Ireland", "Israel",
            "Italy", "Jamaica", "Japan",
            "Jordan", "Kazakhstan", "Kenya",
            "Kiribati", "Korea, Democratic People's Republic of", "Korea, Republic of",
            "Kuwait", "Kyrgyzstan", "Lao, People's Democratic Republic",
            "Latvia", "Lebanon", "Lesotho",
            "Liberia", "Libyan Arab Jamahiriya", "Liechtenstein",
            "Lithuania", "Luxembourg", "Macau",
            "Macedonia, The Former Yugoslav Republic of", "Madagascar", "Malawi",
            "Malaysia", "Maldives", "Mali",
            "Malta", "Marshall Islands", "Martinique",
            "Mauritania", "Mauritius", "Mayotte",
            "Mexico", "Micronesia, Federated States of", "Moldova, Republic of",
            "Monaco", "Mongolia", "Montserrat",
            "Morocco", "Mozambique", "Myanmar",
            "Namibia", "Nauru", "Nepal",
            "Netherlands", "Netherlands Antilles", "New Caledonia",
            "New Zealand", "Nicaragua", "Niger",
            "Nigeria", "Niue", "Norfolk Island",
            "Northern Mariana Islands", "Norway", "Oman",
            "Pakistan", "Palau", "Panama",
            "Papua New Guinea", "Paraguay", "Peru",
            "Philippines", "Pitcairn", "Poland",
            "Portugal", "Puerto Rico", "Qatar",
            "Reunion", "Romania", "Russian Federation",
            "Rwanda", "Saint Kitts and Nevis", "Saint Lucia",
            "Saint Vincent and the Grenadines", "Samoa", "San Marino",
            "Sao Tome and Principe", "Saudi Arabia", "Senegal",
            "Seychelles", "Sierra Leone", "Singapore",
            "Slovakia (Slovak Republic)", "Slovenia", "Solomon Islands",
            "Somalia", "South Africa", "South Georgia and the South Sandwich Islands",
            "Spain", "Sri Lanka", "St. Helena",
            "St. Pierre and Miquelon", "Sudan", "Suriname",
            "Svalbard and Jan Mayen Islands", "Swaziland", "Sweden",
            "Switzerland", "Syrian Arab Republic", "Taiwan, Province of China",
            "Tajikistan", "Tanzania, United Republic of", "Thailand",
            "Togo", "Tokelau", "Tonga",
            "Trinidad and Tobago", "Tunisia", "Turkey",
            "Turkmenistan", "Turks and Caicos Islands", "Tuvalu",
            "Uganda", "Ukraine", "United Arab Emirates",
            "United Kingdom", "United States of America", "United States Minor Outlying Islands",
            "Uruguay", "Uzbekistan", "Vanuatu",
            "Venezuela", "Vietnam", "Virgin Islands (British)",
            "Virgin Islands (U.S.)", "Wallis and Futuna Islands", "Western Sahara",
            "Yemen", "Yugoslavia", "Zambia", "Zimbabwe"];
    }
}
