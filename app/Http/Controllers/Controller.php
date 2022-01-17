<?php

namespace App\Http\Controllers;

use App\Models\Chart;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Collection;
use Illuminate\Support\Env;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function websiteUrl() {
        return env('APP_URL');
    }

    public function getAssetUrl($asset): string {
        return 'https://rx931.com/images/'.$asset.'/';
    }

    public function getStationCode() {
        return Env::get('STATION_CODE');
    }

    public function getAppVersion() {
        return Env::get('APP_VERSION');
    }

    public function storePhoto(Request $request, string $path, string $directory, bool $universal, $profile_pic = false, $header_pic = false, $main_pic = false): string
    {
        $image = $request->file('image');

        $image_name = date('Ymd') . '-' . mt_rand() . '.' . $image->getClientOriginalExtension();

        $image->move($path, $image_name);

        $file = 'images/'.$directory.'/' . $image_name;

        if($universal) {
            $this->storeAsset($directory, $image_name, $file);
        } else {
            Storage::disk($directory)->put($image_name, file_get_contents($file));
        }

        return $image_name;
    }

    public function storeAsset($directory, $asset_name, $source) {
        Storage::disk($directory)->put($asset_name, file_get_contents($source));

        if($this->getStationCode() === 'mnl') {
            Storage::disk('cbu_'.$directory.'')->put($asset_name, file_get_contents($source));
            Storage::disk('cbu_'.$directory.'_cms')->put($asset_name, file_get_contents($source));
            Storage::disk('dav_'.$directory.'')->put($asset_name, file_get_contents($source));
            Storage::disk('dav_'.$directory.'_cms')->put($asset_name, file_get_contents($source));
        }

        if($this->getStationCode() === 'cbu') {
            Storage::disk('mnl_'.$directory.'')->put($asset_name, file_get_contents($source));
            Storage::disk('mnl_'.$directory.'_cms')->put($asset_name, file_get_contents($source));
            Storage::disk('dav_'.$directory.'')->put($asset_name, file_get_contents($source));
            Storage::disk('dav_'.$directory.'_cms')->put($asset_name, file_get_contents($source));
        }

        if($this->getStationCode() === 'dav') {
            Storage::disk('mnl_'.$directory.'')->put($asset_name, file_get_contents($source));
            Storage::disk('mnl_'.$directory.'_cms')->put($asset_name, file_get_contents($source));
            Storage::disk('cbu_'.$directory.'')->put($asset_name, file_get_contents($source));
            Storage::disk('cbu_'.$directory.'_cms')->put($asset_name, file_get_contents($source));
        }
    }

    public function verifyPhoto($fileName, $directory, $longPhoto = false, $banner = false, $banner500 = false, $mobileWallpaper = false, $desktopWallpaper = false): string
    {
        if($fileName === null || $fileName === "") {
            $fileName = $this->getFileName($longPhoto, $banner, $banner500, $mobileWallpaper, $desktopWallpaper);
        } else {
            // $photoDirectory = '/images/'.$directory.'/'.$fileName;

            if(Storage::disk(''.$directory)->exists(''.$fileName.'')) {
                return $fileName;
            } else {
                $fileName = $this->getFileName($longPhoto, $banner, $banner500, $mobileWallpaper, $desktopWallpaper);
            }
        }

        return $fileName;
    }

    public function getFileName(string $longPhoto, string $banner, string $banner500, string $mobileWallpaper, string $desktopWallpaper): string
    {
        $fileName = 'default.png';

        if ($longPhoto) {
            $fileName = 'default-long.png';
        } elseif ($banner) {
            $fileName = 'default-banner.png';
        } elseif ($banner500) {
            $fileName = 'default-banner-sm.png';
        } elseif ($mobileWallpaper) {
            $fileName = 'mobile-wallpaper-missing.png';
        } elseif ($desktopWallpaper) {
            $fileName = 'desktop-wallpaper-missing.png';
        }

        return $fileName;
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

    public function getNoCaptcha() {
        return Env::get('NOCAPTCHA_SITEKEY');
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

    // More Functions
    public function getLatestChartDate()
    {
        return Chart::with('Song.Album.Artist')
            ->whereNull('deleted_at')
            ->where('daily', '=', 0)
            ->where('is_posted', '=', 1)
            ->where('location', $this->getStationCode())
            ->select('dated')
            ->max('dated');
    }

    public function getLatestDailyChartDate()
    {
        return Chart::with('Song.Album.Artist')
            ->whereNull('deleted_at')
            ->where('daily', '=', 1)
            ->where('is_posted', '=', 1)
            ->where('location', $this->getStationCode())
            ->select('dated')
            ->max('dated');
    }

    public function jocksQuery($time, $day): Collection
    {
        return DB::table('jock_show')
            ->join('jocks', 'jock_show.jock_id', '=', 'jocks.id')
            ->join('timeslots', 'jock_show.show_id', '=', 'timeslots.show_id')
            ->join('employees', 'jocks.employee_id', '=', 'employees.id')
            ->join('shows', 'jock_show.show_id', '=', 'shows.id')
            ->whereNull('timeslots.deleted_at')
            ->where('timeslots.end', '>', $time)
            ->where('timeslots.start', '<=', $time)
            ->where('timeslots.day', $day)
            ->where('timeslots.location', $this->getStationCode())
            ->orderBy('timeslots.start')
            ->select('jock_show.jock_id', 'jocks.name', 'employees.first_name', 'employees.last_name', 'shows.title', 'jocks.profile_image')
            ->get();
    }

    public function removeTMRJock($id, $time, $day): Collection
    {
        return DB::table('jock_show')
            ->join('jocks', 'jock_show.jock_id', '=', 'jocks.id')
            ->join('timeslots', 'jock_show.show_id', '=', 'timeslots.show_id')
            ->join('employees', 'jocks.employee_id', '=', 'employees.id')
            ->join('shows', 'jock_show.show_id', '=', 'shows.id')
            ->whereNull('timeslots.deleted_at')
            ->where('jock_show.jock_id', '!=', $id)
            ->where('timeslots.end', '>', $time)
            ->where('timeslots.start', '<=', $time)
            ->where('timeslots.day', $day)
            ->where('timeslots.location', $this->getStationCode())
            ->orderBy('timeslots.start')
            ->select('jock_show.jock_id', 'jocks.name', 'employees.first_name', 'employees.last_name', 'shows.title', 'jocks.profile_image')
            ->get();
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
