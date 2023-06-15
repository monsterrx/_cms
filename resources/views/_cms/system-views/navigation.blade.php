{{-- Developer --}}
@if(Auth()->user()->Employee->Designation->level === 1)
    <li class="nav-item">
        <a class="nav-link" id="Dashboard" href="{{ route('home') }}">
            <i class="fas fa-home"></i>&nbsp;&nbsp;Dashboard <span class="sr-only">(current)</span>
        </a>
    </li>
    <li class="nav-item dropdown">
        <a href="#" class="nav-link dropdown-toggle" id="employeeManagerDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-diagnoses"></i>&nbsp;&nbsp;Staff
        </a>

        <div class="dropdown-menu" aria-labelledby="employeeManagerDropdown">
            <div class="dropdown-header">Employees</div>
            <a href="{{ route('employees.index') }}" class="dropdown-item">Staffs</a>
            <a href="{{ route('designations.index') }}" class="dropdown-item">Designations</a>
            <a href="{{ route('jocks.index') }}" class="dropdown-item">Jocks</a>
            <div class="dropdown-divider"></div>
            <div class="dropdown-header">  Radio1</div>
            <a href="{{ route('radioOne.batches') }}" class="dropdown-item">Batches</a>
            <a href="{{ route('radioOne.jocks') }}" class="dropdown-item">Student Jocks</a>
            <div class="dropdown-divider"></div>
            <div class="dropdown-header">  Milestones</div>
            <a href="{{ route('awards.index') }}" class="dropdown-item">Awards</a>
        </div>
    </li>
    <li class="nav-item dropdown">
        <a href="#" class="nav-link dropdown-toggle" id="musicManagerDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-music"></i>&nbsp;&nbsp;Music
        </a>

        <div class="dropdown-menu" aria-labelledby="musicManagerDropdown">
            <div class="dropdown-header">Chart Music</div>
            <a href="{{ route('artists.index') }}" class="dropdown-item">Artists</a>
            <a href="{{ route('albums.index') }}" class="dropdown-item">Albums</a>
            <a href="{{ route('songs.index') }}" class="dropdown-item">Songs</a>
            <a href="{{ route('genres.index') }}" class="dropdown-item">Genres</a>
            <div class="dropdown-divider"></div>
            <div class="dropdown-header">Charts</div>
            <a class="dropdown-item" href="{{ route('charts.index') }}">{{ env('STATION_CHART') }}</a>
            @if(env('STATION_CODE') === 'dav')
                <a class="dropdown-item" href="{{ route('outbreaks.index') }}">Monster Outbreaks</a>
            @elseif(env('STATION_CODE') === 'cbu')
                <a class="dropdown-item" href="{{ route('outbreaks.index') }}">Monster Outbreaks</a>
                <a class="dropdown-item" href="{{ route('southsides.index') }}">Southside Sounds</a>
            @else
                <a class="dropdown-item" href="{{ route('survey.votes') }}">Votes</a>
            @endif
            <a class="dropdown-item" href="{{ route('dropouts.index') }}">Dropouts</a>
            <div class="dropdown-divider"></div>
            <div class="dropdown-header">Indieground</div>
            <a class="dropdown-item" href="{{ route('indiegrounds.index') }}">Artists</a>
            <a class="dropdown-item" href="{{ route('featured.index') }}">Featured</a>
        </div>
    </li>
    <li class="nav-item dropdown">
        <a href="#" class="nav-link dropdown-toggle" id="digitalContentManagerDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-newspaper"></i>&nbsp;&nbsp;Digital Content, Programs
        </a>

        <div class="dropdown-menu" aria-labelledby="digitalContentManagerDropdown">
            <div class="dropdown-header">Digital Contents</div>
            <a class="dropdown-item" href="{{ route('articles.index') }}">Articles</a>
            <a class="dropdown-item" href="{{ route('categories.index') }}">Category</a>
            <a class="dropdown-item" href="{{ route('sliders.index') }}">Graphics Artist</a>
            <a class="dropdown-item" href="{{ route('asset.index') }}">Mobile Application</a>
            <a class="dropdown-item" href="{{ route('wallpapers.index') }}">Wallpapers</a>
            <div class="dropdown-divider"></div>
            <div class="dropdown-header">Programs</div>
            <a class="dropdown-item" href="{{ route('shows.index') }}">Shows</a>
            <a class="dropdown-item" href="{{ route('timeslots.index') }}">Timeslots</a>
            <a class="dropdown-item" href="{{ route('podcasts.index') }}">Podcasts</a>
        </div>
    </li>
    <li class="nav-item dropdown">
        <a href="#" class="nav-link dropdown-toggle" id="studentsManagerDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-school"></i>&nbsp;&nbsp;Events and Scholarship
        </a>

        <div class="dropdown-menu" aria-labelledby="studentsManagerDropdown">
            <div class="dropdown-header">Gimikboards</div>
            <a class="dropdown-item" href="{{ route('schools.index') }}">Schools</a>
            <a class="dropdown-item" href="{{ route('gimikboards.index') }}">Gimik Board</a>
            <div class="dropdown-divider"></div>
            <div class="dropdown-header">Scholars</div>
            <a class="dropdown-item" href="{{ route('batch.index') }}">Batch</a>
            <a class="dropdown-item" href="{{ route('students.index') }}">Student</a>
            <a class="dropdown-item" href="{{ route('sponsors.index') }}">Sponsors</a>
        </div>
    </li>
    <li class="nav-item dropdown">
        <a href="#" class="nav-link dropdown-toggle" id="contestManagerDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-gifts"></i>&nbsp;&nbsp;Promos
        </a>

        <div class="dropdown-menu" aria-labelledby="contestManagerDropdown">
            <div class="dropdown-header">Monster Giveaways</div>
            <a class="dropdown-item" href="{{ route('giveaways.index') }}">Giveaways</a>
            <a class="dropdown-item" href="{{ route('contestants.index') }}">Contestants</a>
        </div>
    </li>
    <li class="nav-item dropdown">
        <a href="#" class="nav-link dropdown-toggle" id="utilitiesManagerDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-tools"></i>&nbsp;&nbsp;Utilities
        </a>

        <div class="dropdown-menu" aria-labelledby="utilitiesManagerDropdown">
            <div class="dropdown-header">Logs & Reports</div>
            <a class="dropdown-item" href="{{ route('users.index') }}">Users</a>
            <a class="dropdown-item" href="{{ route('reports') }}">Reports</a>
            <a class="dropdown-item" href="{{ route('user_logs.index') }}">Logs</a>
            <a class="dropdown-item" href="{{ route('archives.index') }}">Archives</a>
            {{--<a class="dropdown-item" href="{{ route('softdeletes') }}">Recovery</a>--}}
        </div>
    </li>
    {{-- Admin --}}
@elseif(Auth()->user()->Employee->Designation->level === 2)
    <li class="nav-item">
        <a class="nav-link" id="Dashboard" href="{{ route('home') }}">
            <i class="fas fa-home"></i>&nbsp;&nbsp;Dashboard <span class="sr-only">(current)</span>
        </a>
    </li>
    <li class="nav-item dropdown">
        <a href="#" class="nav-link dropdown-toggle" id="employeeManagerDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-diagnoses"></i>&nbsp;&nbsp;Staff
        </a>

        <div class="dropdown-menu" aria-labelledby="employeeManagerDropdown">
            <div class="dropdown-header">Employees</div>
            <a href="{{ route('employees.index') }}" class="dropdown-item">Staffs</a>
            <a href="{{ route('designations.index') }}" class="dropdown-item">Designations</a>
            <a href="{{ route('jocks.index') }}" class="dropdown-item">Jocks</a>
            <div class="dropdown-divider"></div>
            <div class="dropdown-header">  Radio1</div>
            <a href="{{ route('radioOne.batches') }}" class="dropdown-item">Batches</a>
            <a href="{{ route('radioOne.jocks') }}" class="dropdown-item">Student Jocks</a>
            <div class="dropdown-divider"></div>
            <div class="dropdown-header">  Milestones</div>
            <a href="{{ route('awards.index') }}" class="dropdown-item">Awards</a>
        </div>
    </li>
    <li class="nav-item dropdown">
        <a href="#" class="nav-link dropdown-toggle" id="musicManagerDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-music"></i>&nbsp;&nbsp;Music
        </a>

        <div class="dropdown-menu" aria-labelledby="musicManagerDropdown">
            <div class="dropdown-header">Chart Music</div>
            <a href="{{ route('artists.index') }}" class="dropdown-item">Artists</a>
            <a href="{{ route('albums.index') }}" class="dropdown-item">Albums</a>
            <a href="{{ route('songs.index') }}" class="dropdown-item">Songs</a>
            <a href="{{ route('genres.index') }}" class="dropdown-item">Genres</a>
            <div class="dropdown-divider"></div>
            <div class="dropdown-header">Charts</div>
            <a class="dropdown-item" href="{{ route('charts.index') }}">{{ env('STATION_CHART') }}</a>
            @if(env('STATION_CODE') === 'dav')
                <a class="dropdown-item" href="{{ route('outbreaks.index') }}">Monster Outbreaks</a>
            @elseif(env('STATION_CODE') === 'cbu')
                <a class="dropdown-item" href="{{ route('outbreaks.index') }}">Monster Outbreaks</a>
                <a class="dropdown-item" href="{{ route('southsides.index') }}">Southside Sounds</a>
            @else
                <a class="dropdown-item" href="{{ route('survey.votes') }}">Votes</a>
            @endif
            <a class="dropdown-item" href="{{ route('dropouts.index') }}">Dropouts</a>
            <div class="dropdown-divider"></div>
            <div class="dropdown-header">Indieground</div>
            <a class="dropdown-item" href="{{ route('indiegrounds.index') }}">Artists</a>
            <a class="dropdown-item" href="{{ route('featured.index') }}">Featured</a>
        </div>
    </li>
    <li class="nav-item dropdown">
        <a href="#" class="nav-link dropdown-toggle" id="digitalContentManagerDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-newspaper"></i>&nbsp;&nbsp;Digital Content, Programs
        </a>

        <div class="dropdown-menu" aria-labelledby="digitalContentManagerDropdown">
            <div class="dropdown-header">Digital Contents</div>
            <a class="dropdown-item" href="{{ route('articles.index') }}">Articles</a>
            <a class="dropdown-item" href="{{ route('categories.index') }}">Category</a>
            <a class="dropdown-item" href="{{ route('sliders.index') }}">Graphics Artist</a>
            <a class="dropdown-item" href="{{ route('wallpapers.index') }}">Wallpapers</a>
            <div class="dropdown-divider"></div>
            <div class="dropdown-header">Programs</div>
            <a class="dropdown-item" href="{{ route('shows.index') }}">Shows</a>
            <a class="dropdown-item" href="{{ route('timeslots.index') }}">Timeslots</a>
            <a class="dropdown-item" href="{{ route('podcasts.index') }}">Podcasts</a>
        </div>
    </li>
    <li class="nav-item dropdown">
        <a href="#" class="nav-link dropdown-toggle" id="studentsManagerDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-school"></i>&nbsp;&nbsp;Events and Scholarship
        </a>

        <div class="dropdown-menu" aria-labelledby="studentsManagerDropdown">
            <div class="dropdown-header">Gimikboards</div>
            <a class="dropdown-item" href="{{ route('schools.index') }}">Schools</a>
            <a class="dropdown-item" href="{{ route('gimikboards.index') }}">Gimik Board</a>
            <div class="dropdown-divider"></div>
            <div class="dropdown-header">Scholars</div>
            <a class="dropdown-item" href="{{ route('batch.index') }}">Batch</a>
            <a class="dropdown-item" href="{{ route('students.index') }}">Student</a>
            <a class="dropdown-item" href="{{ route('sponsors.index') }}">Sponsors</a>
        </div>
    </li>
    <li class="nav-item dropdown">
        <a href="#" class="nav-link dropdown-toggle" id="contestManagerDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-gifts"></i>&nbsp;&nbsp;Promos
        </a>

        <div class="dropdown-menu" aria-labelledby="contestManagerDropdown">
            <div class="dropdown-header">Monster Giveaways</div>
            <a class="dropdown-item" href="{{ route('giveaways.index') }}">Giveaways</a>
            <a class="dropdown-item" href="{{ route('contestants.index') }}">Contestants</a>
        </div>
    </li>
    <li class="nav-item dropdown">
        <a href="#" class="nav-link dropdown-toggle" id="utilitiesManagerDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-tools"></i>&nbsp;&nbsp;Utilities
        </a>

        <div class="dropdown-menu" aria-labelledby="utilitiesManagerDropdown">
            <div class="dropdown-header">Logs & Reports</div>
            <a class="dropdown-item" href="{{ route('users.index') }}">Users</a>
            <a class="dropdown-item" href="{{ route('reports') }}">Reports</a>
            <a class="dropdown-item" href="{{ route('user_logs.index') }}">Logs</a>
            <a class="dropdown-item" href="{{ route('archives.index') }}">Archives</a>
            {{--<a class="dropdown-item" href="{{ route('softdeletes') }}">Recovery</a>--}}
        </div>
    </li>
    {{-- Digital Content Specialist --}}
@elseif(Auth()->user()->Employee->Designation->level === 3)
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" id="employeesDropdown" href="#" role="button" data-toggle="dropdown" aria-expanded="false"><i class="fas fa-diagnoses"></i>&nbsp;&nbsp;Employees</a>
        <div class="dropdown-menu" aria-labelledby="employeesDropdown">
            <a href="{{ route('jocks.index') }}" class="dropdown-item">Jocks</a>
        </div>
    </li>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" id="Radio1Dropdown" href="#" role="button" data-toggle="dropdown" aria-expanded="false"><i class="fas fa-compact-disc"></i>&nbsp;&nbsp;Radio-1 Jocks</a>
        <div class="dropdown-menu" aria-labelledby="Radio1Dropdown">
            <a href="{{ route('radioOne.batches') }}" class="dropdown-item">Batches</a>
            <a href="{{ route('radioOne.jocks') }}" class="dropdown-item">Student Jocks</a>
        </div>
    </li>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" id="DigitalDropdown" href="#" role="button" data-toggle="dropdown" aria-expanded="false"><i class="fas fa-blog"></i>&nbsp;&nbsp;Digital Content</a>
        <div class="dropdown-menu" aria-labelledby="DigitalDropdown">
            <div class="dropdown-header">Articles</div>
            <a class="dropdown-item" href="{{ route('articles.index') }}">Articles</a>
            <a class="dropdown-item" href="{{ route('categories.index') }}">Category</a>
            <div class="dropdown-divider"></div>
            <div class="dropdown-header">Programs</div>
            <a class="dropdown-item" href="{{ route('shows.index') }}">Shows</a>
            <a class="dropdown-item" href="{{ route('timeslots.index') }}">Timeslots</a>
            <a class="dropdown-item" href="{{ route('podcasts.index') }}">Podcasts</a>
        </div>
    </li>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" id="MusicDropdown" href="#" role="button" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-play-circle"></i>&nbsp;&nbsp;Music</a>
        <div class="dropdown-menu" aria-labelledby="MusicDropdown">
            <a href="{{ route('artists.index') }}" class="dropdown-item">Artists</a>
            <a href="{{ route('albums.index') }}" class="dropdown-item">Albums</a>
            <a href="{{ route('songs.index') }}" class="dropdown-item">Songs</a>
            <a href="{{ route('genres.index') }}" class="dropdown-item">Genres</a>
        </div>
    </li>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" id="IndiegroundsDropdown" href="#" role="button" data-toggle="dropdown" aria-expanded="false"><i class="fas fa-guitar"></i>&nbsp;&nbsp;Indiegrounds</a>
        <div class="dropdown-menu" aria-labelledby="IndiegroundsDropdown">
            <a class="dropdown-item" href="{{ route('indiegrounds.index') }}">Artists</a>
            <a class="dropdown-item" href="{{ route('featured.index') }}">Featured</a>
        </div>
    </li>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" id="GiveawayDropdown" href="#" role="button" data-toggle="dropdown" aria-expanded="false"><i class="fas fa-sitemap"></i>&nbsp;&nbsp;Monster Giveaway</a>
        <div class="dropdown-menu" aria-labelledby="GiveawayDropdown">
            <a class="dropdown-item" href="{{ route('giveaways.index') }}">Giveaways</a>
            <a class="dropdown-item" href="{{ route('contestants.index') }}">Contestants</a>
        </div>
    </li>

    {{-- Graphics Artist --}}
@elseif(Auth()->user()->Employee->Designation->level === 4)
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" id="EmployeesDropdown" href="#" role="button" data-toggle="dropdown" aria-expanded="false"><i class="fas fa-diagnoses"></i>&nbsp;&nbsp;Employees</a>

        <div class="dropdown-menu" aria-labelledby="EmployeesDropdown">
            <a href="{{ route('jocks.index') }}" class="dropdown-item">Jocks</a>
        </div>
    </li>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" id="DigitalDropdown" href="#" role="button" data-toggle="dropdown" aria-expanded="false"><i class="fas fa-blog"></i>&nbsp;&nbsp;Digital Content</a>

        <div class="dropdown-menu" aria-labelledby="DigitalDropdown">
            <a class="dropdown-item" href="{{ route('sliders.index') }}">Graphics Artist</a>
            <a class="dropdown-item" href="{{ route('wallpapers.index') }}">Wallpapers</a>
        </div>
    </li>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" id="ProgramsDropdown" href="#" role="button" data-toggle="dropdown" aria-expanded="false"><i class="fas fa-tv"></i>&nbsp;&nbsp;Shows and Programs</a>

        <div class="dropdown-menu" aria-labelledby="ProgramsDropdown">
            <a class="dropdown-item" href="{{ route('shows.index') }}">Shows</a>
        </div>
    </li>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" id="" href="#" role="button" data-toggle="dropdown" aria-expanded="false"><i class="fas fa-school"></i>&nbsp;&nbsp;Education</a>

        <div class="dropdown-menu" aria-labelledby="EducationDropdown">
            <a class="dropdown-item" href="{{ route('schools.index') }}">Schools</a>
            <a class="dropdown-item" href="{{ route('gimikboards.index') }}">Gimik Board</a>
        </div>
    </li>
    {{-- Receptionist --}}
@elseif(Auth()->user()->Employee->Designation->level === 6)
    <li class="nav-item">
        <a class="nav-link" id="Dashboard" href="{{ route('home') }}"><i class="fas fa-tachometer-alt"></i>&nbsp;&nbsp;Dashboard <span class="sr-only">(current)</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="Awards" href="{{ route('awards.index') }}"><i class="fas fa-award"></i>&nbsp;&nbsp;Awards</a>
    </li>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" id="MusicDropdown" href="#" role="button" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-play-circle"></i>&nbsp;&nbsp;Music</a>
        <div class="dropdown-menu" aria-labelledby="MusicDropdown">
            <a href="{{ route('artists.index') }}" class="dropdown-item">Artists</a>
            <a href="{{ route('albums.index') }}" class="dropdown-item">Albums</a>
            <a href="{{ route('songs.index') }}" class="dropdown-item">Songs</a>
            <a href="{{ route('genres.index') }}" class="dropdown-item">Genres</a>
        </div>
    </li>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" id="EmployeesDropdown" href="#" role="button" data-toggle="dropdown" aria-expanded="false"><i class="fas fa-diagnoses"></i>&nbsp;&nbsp;Employees</a>
        <div class="dropdown-menu" aria-labelledby="EmployeesDropdown">
            <a href="{{ route('employees.index') }}" class="dropdown-item">Staffs</a>
        </div>
    </li>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" id="Radio1Dropdown" href="#" role="button" data-toggle="dropdown" aria-expanded="false"><i class="fas fa-compact-disc"></i>&nbsp;&nbsp;Radio-1 Jocks</a>
        <div class="dropdown-menu" aria-labelledby="Radio1Dropdown">
            <a href="{{ route('radioOne.jocks') }}" class="dropdown-item">Student Jocks</a>
        </div>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="Messages" href="{{ route('messages.index') }}"><i class="fas fa-paper-plane"></i>&nbsp;&nbsp;Messages</a>
    </li>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" id="ChartsDropdown" href="#" role="button" data-toggle="dropdown" aria-expanded="false"><i class="fas fa-list-ol"></i>&nbsp;&nbsp;Charts</a>
        <div class="dropdown-menu" aria-labelledby="ChartsDropdown">
            <a class="dropdown-item" href="{{ route('charts.index') }}">{{ env('STATION_CHART') }}</a>
            <a class="dropdown-item" href="{{ route('dropouts.index') }}">Dropouts</a>
        </div>
    </li>
    {{-- On Job Trainee --}}
@elseif(Auth()->user()->Employee->Designation->level === 7)
    <li class="nav-item dropdown">
        <a href="#" class="nav-link dropdown-toggle" id="musicManagerDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-music"></i>&nbsp;&nbsp;Music
        </a>

        <div class="dropdown-menu" aria-labelledby="musicManagerDropdown">
            <div class="dropdown-header">Chart Music</div>
            <a href="{{ route('artists.index') }}" class="dropdown-item">Artists</a>
            <a href="{{ route('albums.index') }}" class="dropdown-item">Albums</a>
            <a href="{{ route('songs.index') }}" class="dropdown-item">Songs</a>
            <a href="{{ route('genres.index') }}" class="dropdown-item">Genres</a>
            <div class="dropdown-divider"></div>
            <div class="dropdown-header">Charts</div>
            <a class="dropdown-item" href="{{ route('charts.index') }}">{{ env('STATION_CHART') }}</a>
            @if(env('STATION_CODE') === 'dav')
                <a class="dropdown-item" href="{{ route('outbreaks.index') }}">Monster Outbreaks</a>
            @elseif(env('STATION_CODE') === 'cbu')
                <a class="dropdown-item" href="{{ route('outbreaks.index') }}">Monster Outbreaks</a>
                <a class="dropdown-item" href="{{ route('southsides.index') }}">Southside Sounds</a>
            @else
                <a class="dropdown-item" href="{{ route('survey.votes') }}">Votes</a>
            @endif
            <a class="dropdown-item" href="{{ route('dropouts.index') }}">Dropouts</a>
            <div class="dropdown-divider"></div>
            <div class="dropdown-header">Indieground</div>
            <a class="dropdown-item" href="{{ route('indiegrounds.index') }}">Artists</a>
            <a class="dropdown-item" href="{{ route('featured.index') }}">Featured</a>
        </div>
    </li>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" id="Radio1Dropdown" href="#" role="button" data-toggle="dropdown" aria-expanded="false"><i class="fas fa-compact-disc"></i>&nbsp;&nbsp;Radio-1 Jocks</a>
        <div class="dropdown-menu" aria-labelledby="Radio1Dropdown">
            <a href="{{ route('radioOne.batches') }}" class="dropdown-item">Batches</a>
            <a href="{{ route('radioOne.jocks') }}" class="dropdown-item">Student Jocks</a>
        </div>
    </li>
    {{-- Plain User --}}
@elseif(Auth()->user()->Employee->Designation->level === 9)
    <li class="nav-item">
        <a class="nav-link" id="Dashboard" href="{{ route('home') }}"><i class="fas fa-tachometer-alt"></i>&nbsp;&nbsp;Dashboard <span class="sr-only">(current)</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="Awards" href="{{ route('awards.index') }}"><i class="fas fa-award"></i>&nbsp;&nbsp;Awards</a>
    </li>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" id="MusicDropdown" href="#" role="button" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-play-circle"></i>&nbsp;&nbsp;Music</a>
        <div class="dropdown-menu" aria-labelledby="MusicDropdown">
            <a href="{{ route('artists.index') }}" class="dropdown-item">Artists</a>
            <a href="{{ route('albums.index') }}" class="dropdown-item">Albums</a>
            <a href="{{ route('songs.index') }}" class="dropdown-item">Songs</a>
            <a href="{{ route('genres.index') }}" class="dropdown-item">Genres</a>
        </div>
    </li>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" id="ChartsDropdown" href="#" role="button" data-toggle="dropdown" aria-expanded="false"><i class="fas fa-list-ol"></i>&nbsp;&nbsp;Charts</a>
        <div class="dropdown-menu" aria-labelledby="ChartsDropdown">
            <a class="dropdown-item" href="{{ route('charts.index') }}">{{ env('STATION_CHART') }}</a>
            @if(env('STATION_CODE') !== 'mnl')
                <a class="dropdown-item" href="{{ route('outbreaks.index') }}">Monster Outbreaks</a>
            @endif
            <a class="dropdown-item" href="{{ route('dropouts.index') }}">Dropouts</a>
        </div>
    </li>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" id="EmployeesDropdown" href="#" role="button" data-toggle="dropdown" aria-expanded="false"><i class="fas fa-diagnoses"></i>&nbsp;&nbsp;Employees</a>
        <div class="dropdown-menu" aria-labelledby="EmployeesDropdown">
            <a href="{{ route('employees.index') }}" class="dropdown-item">Staffs</a>
        </div>
    </li>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" id="Radio1Dropdown" href="#" role="button" data-toggle="dropdown" aria-expanded="false"><i class="fas fa-compact-disc"></i>&nbsp;&nbsp;Radio-1 Jocks</a>
        <div class="dropdown-menu" aria-labelledby="Radio1Dropdown">
            <a href="{{ route('radioOne.jocks') }}" class="dropdown-item">Student Jocks</a>
        </div>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="Messages" href="{{ route('messages.index') }}"><i class="fas fa-paper-plane"></i>&nbsp;&nbsp;Messages</a>
    </li>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" id="EducationDropdown" href="#" role="button" data-toggle="dropdown" aria-expanded="false"><i class="fas fa-school"></i>&nbsp;&nbsp;Education</a>
        <div class="dropdown-menu" aria-labelledby="EducationDropdown">
            <a class="dropdown-item" href="{{ route('schools.index') }}">Schools</a>
            <a class="dropdown-item" href="{{ route('gimikboards.index') }}">Gimik Board</a>
            <h6 class="dropdown-header">Monster Scholar</h6>
            <a class="dropdown-item" href="{{ route('batch.index') }}">Batch</a>
            <a class="dropdown-item" href="{{ route('students.index') }}">Student</a>
            <a class="dropdown-item" href="{{ route('sponsors.index') }}">Sponsors</a>
        </div>
    </li>
@endif
