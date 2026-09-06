const createActions = {
    albums: 'New album',
    artists: 'New artist',
    articles: 'Create article',
    contestants: 'New contestant',
    'daily-survey-top-5': 'New daily chart',
    giveaways: 'New giveaway',
    'graphics-artist': 'New slider',
    jocks: 'New jock',
    'mobile-application': 'New asset',
    'monster-music-awards': 'New release',
    shows: 'New show',
    songs: 'New song',
    'station-chart': 'New chart entry',
    'student-jocks': 'New student jock',
    timeslots: 'New timeslot',
};

export function getCreateAction(resourceName) {
    return createActions[resourceName] ?? 'New record';
}
