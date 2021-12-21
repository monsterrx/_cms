<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <h4 style="font-weight: 300">Employee Number:</h4>
                <p class="lead" style="color: red;">{{ $employee->employee_number }}</p>
                <div class="row">
                    <div class="col-md-12">
                        <div id="Name" class="lead text-center">{{ $employee->first_name }} {{ $employee->last_name }}</div>
                    </div>
                </div>
                <hr class="my-4">
                <div class="row">
                    <div class="col-md-4">
                        <label for="age" class="h5">Age:</label>
                        <div id="age" class="lead" style="font-weight: 300;">@if($employee->birthday === null || $employee->birthday === '') Well, you haven't given your birthday @else {{ Carbon\Carbon::parse($employee->birthday)->age }} @endif</div>
                    </div>
                    <div class="col-md-4">
                        <label for="gender" class="h5">Gender:</label>
                        <div id="gender" class="lead" style="font-weight: 300">{{ $employee->gender }}</div>
                    </div>
                    <div class="col-md-4">
                        <label for="contact_number" class="h5">Contact Number:</label>
                        <div id="contact_number" class="lead" style="font-weight: 300;">@if($employee->contact_number === null || $employee->contact_number === '') Give us a contact number @else {{ $employee->contact_number }} @endif</div>
                    </div>
                </div>
                <div class="my-4"></div>
                <div class="row">
                    <div class="col-md-12">
                        <label for="Designation" class="h5">Designation:</label>
                        <div id="Designation" class="lead" style="font-weight: 300;">{{ $employee->Designation->name }}</div>
                    </div>
                </div>
                <div class="my-4"></div>
                <div class="row">
                    <div class="col-md-12">
                        <label for="address" class="h5">Address:</label>
                        <div id="address" class="lead" style="font-weight: 300;">@if($employee->address === null || $employee->address === '') Please tell us where you live @else {{ $employee->address }} @endif</div>
                    </div>
                </div>
                <div class="my-4"></div>
                <div class="row">
                    <div class="col-md-6">
                        <label for="DateCreated" class="h5">Date Joined:</label>
                        <div id="DateCreated" class="lead" style="font-weight: 300;">{{ date('F d, Y', strtotime($employee->created_at)) }}</div>
                    </div>
                    <div class="col-md-6">
                        <label for="DateUpdated" class="h5">Last Updated:</label>
                        <div id="DateUpdated" class="lead" style="font-weight: 300;">{{ date('F d, Y', strtotime($employee->updated_at)) }}</div>
                    </div>
                </div>
                <br>

            </div>
        </div>
    </div>
</div>
