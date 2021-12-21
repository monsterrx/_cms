{{-- Developer --}}
@if(Auth()->user()->Employee->Designation->level === '1')
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
    {{-- Admin --}}
@elseif(Auth()->user()->Employee->Designation->level === '2')
    <li class="nav-item">
        <a class="nav-link" id="Dashboard" href="{{ route('home') }}">
            <i class="fas fa-tachometer-alt"></i>&nbsp;&nbsp;Dashboard <span class="sr-only">(current)</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="Awards" href="{{ route('awards.index') }}"><i class="fas fa-award"></i>&nbsp;&nbsp;Awards</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="Messages" href="{{ route('messages.index') }}"><i class="fas fa-paper-plane"></i>&nbsp;&nbsp;Messages</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="Employees" href="#employeesCollapse" role="button" data-toggle="collapse" aria-expanded="false" aria-controls="employeesCollapse"><i class="fas fa-diagnoses"></i>&nbsp;&nbsp;Employees</a>
        <div class="collapse" id="employeesCollapse">
            <a href="{{ route('employees.index') }}" class="dropdown-item">Staffs</a>
            <a href="{{ route('designations.index') }}" class="dropdown-item">Designations</a>
            <a href="{{ route('jocks.index') }}" class="dropdown-item">Jocks</a>
        </div>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="Radio1" href="#radio1Collapse" role="button" data-toggle="collapse" aria-expanded="false" aria-controls="radio1Collapse"><i class="fas fa-compact-disc"></i>&nbsp;&nbsp;Radio-1 Jocks</a>
        <div class="collapse" id="radio1Collapse">
            <a href="{{ route('radioOne.batches') }}" class="dropdown-item">Batches</a>
            <a href="{{ route('radioOne.jocks') }}" class="dropdown-item">Student Jocks</a>
        </div>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="Music" href="#collapseMusicLink" role="button" data-toggle="collapse" aria-expanded="false" aria-controls="collapseMusicLink"><i class="fa fa-play-circle"></i>&nbsp;&nbsp;Music</a>
        <div class="collapse" id="collapseMusicLink">
            <a href="{{ route('artists.index') }}" class="dropdown-item">Artists</a>
            <a href="{{ route('albums.index') }}" class="dropdown-item">Albums</a>
            <a href="{{ route('songs.index') }}" class="dropdown-item">Songs</a>
            <a href="{{ route('genres.index') }}" class="dropdown-item">Genres</a>
        </div>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="Charts" href="#chartsLink" role="button" data-toggle="collapse" aria-expanded="false" aria-controls="chartsLink"><i class="fas fa-list-ol"></i>&nbsp;&nbsp;Charts</a>
        <div class="collapse" id="chartsLink">
            <a class="dropdown-item" href="{{ route('charts.index') }}">{{ env('STATION_CHART') }}</a>
            @if(env('STATION_CODE') !== 'mnl' && env('STATION_CODE') === 'cbu')
                <a class="dropdown-item" href="{{ route('outbreaks.index') }}">Monster Outbreaks</a>
                <a href="dropdown-item" href="{{ route('southsides.index') }}">Southside Sounds</a>
            @else
                <a class="dropdown-item" href="{{ route('outbreaks.index') }}">Monster Outbreaks</a>
            @endif
            <a class="dropdown-item" href="{{ route('dropouts.index') }}">Dropouts</a>
        </div>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="Digital" href="#digitalContentLink" role="button" data-toggle="collapse" aria-expanded="false" aria-controls="digitalContentLink"><i class="fas fa-blog"></i>&nbsp;&nbsp;Digital Content</a>
        <div class="collapse" id="digitalContentLink">
            <a class="dropdown-item" href="{{ route('articles.index') }}">Articles</a>
            <a class="dropdown-item" href="{{ route('categories.index') }}">Category</a>
            <a class="dropdown-item" href="{{ route('sliders.index') }}">Graphics Artist</a>
            <a class="dropdown-item" href="{{ route('wallpapers.index') }}">Wallpapers</a>
        </div>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="Programs" href="#showsLink" role="button" data-toggle="collapse" aria-expanded="false" aria-controls="showsLink"><i class="fas fa-tv"></i>&nbsp;&nbsp;Shows and Programs</a>
        <div class="collapse" id="showsLink">
            <a class="dropdown-item" href="{{ route('shows.index') }}">Shows</a>
            <a class="dropdown-item" href="{{ route('timeslots.index') }}">Timeslots</a>
            <a class="dropdown-item" href="{{ route('podcasts.index') }}">Podcasts</a>
        </div>
    </li>
    <li class="nav-item">
        <div class="dropdown">
            <a class="nav-link" id="Education" href="#educationLink" role="button" data-toggle="collapse" aria-expanded="false" aria-controls="educationLink"><i class="fas fa-school"></i>&nbsp;&nbsp;Education</a>
            <div class="collapse" id="educationLink">
                <a class="dropdown-item" href="{{ route('schools.index') }}">Schools</a>
                <a class="dropdown-item" href="{{ route('gimikboards.index') }}">Gimik Board</a>
                <h6 class="dropdown-header">Monster Scholar</h6>
                <a class="dropdown-item" href="{{ route('batch.index') }}">Batch</a>
                <a class="dropdown-item" href="{{ route('students.index') }}">Student</a>
                <a class="dropdown-item" href="{{ route('sponsors.index') }}">Sponsors</a>
            </div>
        </div>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="Indiegrounds" href="#indiegroundsLink" role="button" data-toggle="collapse" aria-expanded="false" aria-controls="indiegroundsLink"><i class="fas fa-guitar"></i>&nbsp;&nbsp;Indiegrounds</a>
        <div class="collapse" id="indiegroundsLink">
            <a class="dropdown-item" href="{{ route('indiegrounds.index') }}">Artists</a>
            <a class="dropdown-item" href="{{ route('featured.index') }}">Featured</a>
        </div>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="Giveaway" href="#monsterGiveawayLink" role="button" data-toggle="collapse" aria-expanded="false" aria-controls="monsterGiveawayLink"><i class="fas fa-sitemap"></i>&nbsp;&nbsp;Monster Giveaway</a>
        <div class="collapse" id="monsterGiveawayLink">
            <a class="dropdown-item" href="{{ route('giveaways.index') }}">Giveaways</a>
            <a class="dropdown-item" href="{{ route('contestants.index') }}">Contestants</a>
        </div>
    </li>

    <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-danger">
        <span>Logs & Reports</span>
    </h6>

    <li class="nav-item">
        <a class="nav-link" id="Users" href="{{ route('users.index') }}">Users</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="Users" href="{{ route('reports') }}">Reports</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="Logs" href="{{ route('user_logs.index') }}">Employee Logs</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="Logs" href="{{ route('archives.index') }}">Archived Logs</a>
    </li>
    <li class="nav-item">
        {{--<a class="nav-link" id="Recovery" href="{{ route('softdeletes') }}">Data Recovery</a>--}}
    </li>

    {{-- Digital Content Specialist --}}
@elseif(Auth()->user()->Employee->Designation->level === '3')
    <li class="nav-item">
        <a class="nav-link" id="Employees" href="#employeesCollapse" role="button" data-toggle="collapse" aria-expanded="false" aria-controls="employeesCollapse"><i class="fas fa-diagnoses"></i>&nbsp;&nbsp;Employees</a>
        <div class="collapse" id="employeesCollapse">
            <a href="{{ route('jocks.index') }}" class="dropdown-item">Jocks</a>
        </div>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="Radio1" href="#radio1Collapse" role="button" data-toggle="collapse" aria-expanded="false" aria-controls="radio1Collapse"><i class="fas fa-compact-disc"></i>&nbsp;&nbsp;Radio-1 Jocks</a>
        <div class="collapse" id="radio1Collapse">
            <a href="{{ route('radioOne.batches') }}" class="dropdown-item">Batches</a>
            <a href="{{ route('radioOne.jocks') }}" class="dropdown-item">Student Jocks</a>
        </div>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="Digital" href="#digitalContentLink" role="button" data-toggle="collapse" aria-expanded="false" aria-controls="digitalContentLink"><i class="fas fa-blog"></i>&nbsp;&nbsp;Digital Content</a>
        <div class="collapse" id="digitalContentLink">
            <a class="dropdown-item" href="{{ route('articles.index') }}">Articles</a>
            <a class="dropdown-item" href="{{ route('categories.index') }}">Category</a>
        </div>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="Music" href="#collapseMusicLink" role="button" data-toggle="collapse" aria-expanded="false" aria-controls="collapseMusicLink"><i class="fa fa-play-circle"></i>&nbsp;&nbsp;Music</a>
        <div class="collapse" id="collapseMusicLink">
            <a href="{{ route('artists.index') }}" class="dropdown-item">Artists</a>
            <a href="{{ route('albums.index') }}" class="dropdown-item">Albums</a>
            <a href="{{ route('songs.index') }}" class="dropdown-item">Songs</a>
            <a href="{{ route('genres.index') }}" class="dropdown-item">Genres</a>
        </div>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="Indiegrounds" href="#indiegroundsLink" role="button" data-toggle="collapse" aria-expanded="false" aria-controls="indiegroundsLink"><i class="fas fa-guitar"></i>&nbsp;&nbsp;Indiegrounds</a>
        <div class="collapse" id="indiegroundsLink">
            <a class="dropdown-item" href="{{ route('indiegrounds.index') }}">Artists</a>
            <a class="dropdown-item" href="{{ route('featured.index') }}">Featured</a>
        </div>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="Giveaway" href="#monsterGiveawayLink" role="button" data-toggle="collapse" aria-expanded="false" aria-controls="monsterGiveawayLink"><i class="fas fa-sitemap"></i>&nbsp;&nbsp;Monster Giveaway</a>
        <div class="collapse" id="monsterGiveawayLink">
            <a class="dropdown-item" href="{{ route('giveaways.index') }}">Giveaways</a>
            <a class="dropdown-item" href="{{ route('contestants.index') }}">Contestants</a>
        </div>
    </li>

    {{-- Graphics Artist --}}
@elseif(Auth()->user()->Employee->Designation->level === '4')
    <li class="nav-item">
        <a class="nav-link" id="Employees" href="#employeesCollapse" role="button" data-toggle="collapse" aria-expanded="false" aria-controls="employeesCollapse"><i class="fas fa-diagnoses"></i>&nbsp;&nbsp;Employees</a>
        <div class="collapse" id="employeesCollapse">
            <a href="{{ route('jocks.index') }}" class="dropdown-item">Jocks</a>
        </div>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="Digital" href="#digitalContentLink" role="button" data-toggle="collapse" aria-expanded="false" aria-controls="digitalContentLink"><i class="fas fa-blog"></i>&nbsp;&nbsp;Digital Content</a>
        <div class="collapse" id="digitalContentLink">
            <a class="dropdown-item" href="{{ route('sliders.index') }}">Graphics Artist</a>
            <a class="dropdown-item" href="{{ route('wallpapers.index') }}">Wallpapers</a>
        </div>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="Programs" href="#showsLink" role="button" data-toggle="collapse" aria-expanded="false" aria-controls="showsLink"><i class="fas fa-tv"></i>&nbsp;&nbsp;Shows and Programs</a>
        <div class="collapse" id="showsLink">
            <a class="dropdown-item" href="{{ route('shows.index') }}">Shows</a>
        </div>
    </li>
    <li class="nav-item">
        <div class="dropdown">
            <a class="nav-link" id="Education" href="#educationLink" role="button" data-toggle="collapse" aria-expanded="false" aria-controls="educationLink"><i class="fas fa-school"></i>&nbsp;&nbsp;Education</a>
            <div class="collapse" id="educationLink">
                <a class="dropdown-item" href="{{ route('schools.index') }}">Schools</a>
                <a class="dropdown-item" href="{{ route('gimikboards.index') }}">Gimik Board</a>
            </div>
        </div>
    </li>

@elseif(Auth()->user()->Employee->Designation->level === '6')
    <li class="nav-item">
        <a class="nav-link" id="Dashboard" href="{{ route('home') }}"><i class="fas fa-tachometer-alt"></i>&nbsp;&nbsp;Dashboard <span class="sr-only">(current)</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="Awards" href="{{ route('awards.index') }}"><i class="fas fa-award"></i>&nbsp;&nbsp;Awards</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="Music" href="#collapseMusicLink" role="button" data-toggle="collapse" aria-expanded="false" aria-controls="collapseMusicLink"><i class="fa fa-play-circle"></i>&nbsp;&nbsp;Music</a>
        <div class="collapse" id="collapseMusicLink">
            <a href="{{ route('artists.index') }}" class="dropdown-item">Artists</a>
            <a href="{{ route('albums.index') }}" class="dropdown-item">Albums</a>
            <a href="{{ route('songs.index') }}" class="dropdown-item">Songs</a>
            <a href="{{ route('genres.index') }}" class="dropdown-item">Genres</a>
        </div>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="Employees" href="#employeesCollapse" role="button" data-toggle="collapse" aria-expanded="false" aria-controls="employeesCollapse"><i class="fas fa-diagnoses"></i>&nbsp;&nbsp;Employees</a>
        <div class="collapse" id="employeesCollapse">
            <a href="{{ route('employees.index') }}" class="dropdown-item">Staffs</a>
        </div>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="Radio1" href="#radio1Collapse" role="button" data-toggle="collapse" aria-expanded="false" aria-controls="radio1Collapse"><i class="fas fa-compact-disc"></i>&nbsp;&nbsp;Radio-1 Jocks</a>
        <div class="collapse" id="radio1Collapse">
            <a href="{{ route('radioOne.jocks') }}" class="dropdown-item">Student Jocks</a>
        </div>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="Messages" href="{{ route('messages.index') }}"><i class="fas fa-paper-plane"></i>&nbsp;&nbsp;Messages</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="Charts" href="#chartsLink" role="button" data-toggle="collapse" aria-expanded="false" aria-controls="chartsLink"><i class="fas fa-list-ol"></i>&nbsp;&nbsp;Charts</a>
        <div class="collapse" id="chartsLink">
            <a class="dropdown-item" href="{{ route('charts.index') }}">{{ env('STATION_CHART') }}</a>
            <a class="dropdown-item" href="{{ route('dropouts.index') }}">Dropouts</a>
        </div>
    </li>
@elseif(Auth()->user()->Employee->Designation->level === '7')
    <li class="nav-item">
        <a class="nav-link" id="Music" href="#collapseMusicLink" role="button" data-toggle="collapse" aria-expanded="false" aria-controls="collapseMusicLink"><i class="fa fa-play-circle"></i>&nbsp;&nbsp;Music</a>
        <div class="collapse" id="collapseMusicLink">
            <a href="{{ route('artists.index') }}" class="dropdown-item">Artists</a>
            <a href="{{ route('albums.index') }}" class="dropdown-item">Albums</a>
            <a href="{{ route('songs.index') }}" class="dropdown-item">Songs</a>
            <a href="{{ route('genres.index') }}" class="dropdown-item">Genres</a>
        </div>
    </li>
@elseif(Auth()->user()->Employee->Designation->level === '9')
    <li class="nav-item">
        <a class="nav-link" id="Dashboard" href="{{ route('home') }}"><i class="fas fa-tachometer-alt"></i>&nbsp;&nbsp;Dashboard <span class="sr-only">(current)</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="Awards" href="{{ route('awards.index') }}"><i class="fas fa-award"></i>&nbsp;&nbsp;Awards</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="Music" href="#collapseMusicLink" role="button" data-toggle="collapse" aria-expanded="false" aria-controls="collapseMusicLink"><i class="fa fa-play-circle"></i>&nbsp;&nbsp;Music</a>
        <div class="collapse" id="collapseMusicLink">
            <a href="{{ route('artists.index') }}" class="dropdown-item">Artists</a>
            <a href="{{ route('albums.index') }}" class="dropdown-item">Albums</a>
            <a href="{{ route('songs.index') }}" class="dropdown-item">Songs</a>
            <a href="{{ route('genres.index') }}" class="dropdown-item">Genres</a>
        </div>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="Charts" href="#chartsLink" role="button" data-toggle="collapse" aria-expanded="false" aria-controls="chartsLink"><i class="fas fa-list-ol"></i>&nbsp;&nbsp;Charts</a>
        <div class="collapse" id="chartsLink">
            <a class="dropdown-item" href="{{ route('charts.index') }}">{{ env('STATION_CHART') }}</a>
            @if(env('STATION_CODE') !== 'mnl')
                <a class="dropdown-item" href="{{ route('outbreaks.index') }}">Monster Outbreaks</a>
            @endif
            <a class="dropdown-item" href="{{ route('dropouts.index') }}">Dropouts</a>
        </div>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="Employees" href="#employeesCollapse" role="button" data-toggle="collapse" aria-expanded="false" aria-controls="employeesCollapse"><i class="fas fa-diagnoses"></i>&nbsp;&nbsp;Employees</a>
        <div class="collapse" id="employeesCollapse">
            <a href="{{ route('employees.index') }}" class="dropdown-item">Staffs</a>
        </div>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="Radio1" href="#radio1Collapse" role="button" data-toggle="collapse" aria-expanded="false" aria-controls="radio1Collapse"><i class="fas fa-compact-disc"></i>&nbsp;&nbsp;Radio-1 Jocks</a>
        <div class="collapse" id="radio1Collapse">
            <a href="{{ route('radioOne.jocks') }}" class="dropdown-item">Student Jocks</a>
        </div>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="Messages" href="{{ route('messages.index') }}"><i class="fas fa-paper-plane"></i>&nbsp;&nbsp;Messages</a>
    </li>
    <li class="nav-item">
        <div class="dropdown">
            <a class="nav-link" id="Education" href="#educationLink" role="button" data-toggle="collapse" aria-expanded="false" aria-controls="educationLink"><i class="fas fa-school"></i>&nbsp;&nbsp;Education</a>
            <div class="collapse" id="educationLink">
                <a class="dropdown-item" href="{{ route('schools.index') }}">Schools</a>
                <a class="dropdown-item" href="{{ route('gimikboards.index') }}">Gimik Board</a>
                <h6 class="dropdown-header">Monster Scholar</h6>
                <a class="dropdown-item" href="{{ route('batch.index') }}">Batch</a>
                <a class="dropdown-item" href="{{ route('students.index') }}">Student</a>
                <a class="dropdown-item" href="{{ route('sponsors.index') }}">Sponsors</a>
            </div>
        </div>
    </li>
@endif
