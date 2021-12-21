@extends('layouts.base')

@section('content')
    <div class="container">
        <div class="mt-md-4 mt-lg-4 mt-sm-0 mb-5">
            <div class="display-4 mb-3">
                Artists
            </div>
            <div class="row my-4">
                <div class="col-md-12 col-sm-12 col-12 col-lg-12">
                    <a href="#new-artist" class="btn btn-outline-dark fa-pull-right" data-toggle="modal">New Artist</a>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-hover" id="artistsTable">
                                <thead>
                                <tr>
                                    <th scope="col">Name</th>
                                    <th scope="col">Country</th>
                                    <th scope="col">Artist Type</th>
                                    <th scope="col">Options</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="new-artist" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="lead">New Artist</h3>
                    <button class="close" type="button" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form id="artistForm" method="POST" action="{{ route('artists.store') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" id="ArtistImage" name="image">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fa fa-user-alt"></i>
                                        </div>
                                    </div>
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Artist Name" required>
                                </div>
                            </div>
                        </div>
                        <div class="my-3"></div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fa fa-flag"></i>
                                        </div>
                                    </div>
                                    <?php $country = array("Afghanistan", "Albania", "Algeria",
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
                                        "Yemen", "Yugoslavia", "Zambia", "Zimbabwe"); ?>
                                    <select id="country" name="country" class="custom-select" required>
                                        <option value selected>--</option>
                                        @foreach($country as $countries)
                                            <option value="{{ $countries }}">{{ $countries }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12 mt-3">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fa fa-user-friends"></i>
                                        </div>
                                    </div>
                                    <select class="custom-select" id="type" name="type" required>
                                        <option value="" selected>Select Type</option>
                                        <option value="Solo">Solo</option>
                                        <option value="Duo">Duo</option>
                                        <option value="Trio">Trio</option>
                                        <option value="Group">Group</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12 mt-3 mb-3">
                                <button id="add-artist-image" type="button" class="btn btn-outline-dark btn-block" role="button"><i class="fas fa-plus"></i>  Image</button>
                            </div>
                        </div>
                        <div class="btn-group fa-pull-right">
                            <button id="submit" type="submit" class="btn btn-outline-dark">Save</button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-outline-dark" data-dismiss="modal">Cancel</a>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="update-artist" tabindex="-1" role="dialog" aria-labelledby="Update Artist" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Artist</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="updateArtistForm" action="" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <div class="modal-body">
                        <input type="hidden" id="update_artist_image" name="image">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="text-center mb-3">
                                    <img id="artistImage" src="" alt="" class="img-thumbnail img-responsive" width="220px">
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fa fa-user-alt"></i>
                                                </div>
                                            </div>
                                            <input type="text" class="form-control" id="update_artist_name" name="name" placeholder="Artist Name">
                                        </div>
                                    </div>
                                </div>
                                <div class="my-3"></div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fa fa-flag"></i>
                                                </div>
                                            </div>
                                            <?php $country = array("Afghanistan", "Albania", "Algeria",
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
                                                "Yemen", "Yugoslavia", "Zambia", "Zimbabwe"); ?>
                                            <select id="update_artist_country" name="country" class="custom-select" required>
                                                <option value>--</option>
                                                @foreach($country as $countries)
                                                    <option value="{{ $countries }}">{{ $countries }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="my-3"></div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fa fa-user-friends"></i>
                                                </div>
                                            </div>
                                            <select class="custom-select" id="update_artist_type" name="type" required>
                                                <option value="Solo">Solo</option>
                                                <option value="Duo">Duo</option>
                                                <option value="Trio">Trio</option>
                                                <option value="Group">Group</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mt-3 mb-3">
                                        <div class="form-group">
                                            <button id="update-artist-image-button" type="button" class="btn btn-outline-dark btn-block" role="button"><i class="fas fa-plus"></i>  Image</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <label for="view_albums">View Albums</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fa fa-compact-disc"></i>
                                        </div>
                                    </div>
                                    <select class="custom-select" id="view_albums">
                                        <option value selected>--</option>
                                    </select>
                                </div>
                                <div class="my-4"></div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="text-center">
                                            <img id="view_album_image" src="" alt="" class="img-thumbnail img-responsive" width="220px">
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <table class="table table-hover">
                                            <thead>
                                            <tr>
                                                <th>Song</th>
                                                <th>Genre</th>
                                            </tr>
                                            </thead>
                                            <tbody id="view_songs">
                                            <tr>
                                                <td colspan="2">
                                                    <div class="alert alert-danger lead text-center">
                                                        Error Occurred, Contact IT - Web Developer
                                                    </div>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="btn-group fa-pull-right">
                            <button type="submit" class="btn btn-outline-dark">Save</button>
                            <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="delete-artist" tabindex="-1" role="dialog" aria-labelledby="Delete Artist" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Artist</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="deleteArtistForm" action="" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body">
                        <div id="delete-artist-body" class="h5 text-center">
                            Loading ...
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="btn-group">
                            <button type="submit" class="btn btn-outline-dark">Yes</button>
                            <button type="button" class="btn btn-outline-dark" data-dismiss="modal">No</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="update-artist-image" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Artist Photo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="artistImageForm" method="POST" action="" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="update_artist_id" name="artist_id">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12 my-3">
                                <div class="row">
                                    <div class="col-12">
                                        <div id="croppingImage" class="cropper img-fluid"></div>
                                    </div>
                                </div>
                                <input type="hidden" id="artist_image_name" name="artist_image_name" style="display: none;" />
                                <div class="my-2"></div>
                                <div class="custom-file" id="custom">
                                    <input type="file" id="artist_image" name="image" class="custom-file-input" accept="image/*">
                                    <label id="artist_image" for="image" class="custom-file-label">Click here</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="fa-pull-right">
                                    <button id="artistCropButton" type="button" class="btn btn-outline-dark" hidden>Crop</button>
                                    <button id="saveButton" type="submit" class="btn btn-outline-dark" hidden>Save</button>
                                    <button id="cancelButton" type="button" class="btn btn-outline-dark" data-role="none" hidden>Cancel</button>
                                    <button id="doneButton" type="button" class="btn btn-outline-dark" data-dismiss="modal">Done</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
