import assert from 'node:assert/strict';
import {test} from 'node:test';
import {resourceFormValues, resourceRequestPayload, validateResourceForm, eventDuration} from '../resources/js/lib/resourceForm.js';

const field = (name, type = 'text', extra = {}) => ({name, label: name, type, form: true, writable: true, nullable: false, default: null, options: [], ...extra});

test('editing a song derives its artist from the selected album', () => {
    const fields = [field('artist_id', 'select', {virtual:true}), field('album_id', 'select', {options:[{value:3, artist_id:8}]})];
    assert.equal(resourceFormValues(fields, {album_id:3}).artist_id, 8);
});

test('sample audio is sent as multipart data and inactive Spotify fields are omitted', () => {
    const audio = new File(['test audio'], 'sample.mp3', {type:'audio/mpeg'});
    const fields = [field('type'), field('sample', 'audio', {virtual:true}), field('track_link', 'url', {show_when:{field:'type',value:'spotify'}})];
    const payload = resourceRequestPayload(fields, {type:'sample', sample:audio, track_link:'https://example.test/old-track'});
    assert.ok(payload instanceof FormData);
    assert.equal(payload.get('sample').name, 'sample.mp3');
    assert.equal(payload.has('track_link'), false);
});

test('invalid calendar dates and missing required fields produce inline errors', () => {
    const errors = validateResourceForm([field('name'), field('date', 'date')], {name:' ', date:'2026-02-30'});
    assert.equal(Object.keys(errors).length, 2);
    assert.deepEqual(validateResourceForm([field('date', 'date')], {date:'2026-09-09'}), {});
});

test('conditional and managed fields do not block form submission', () => {
    const fields = [field('is_active', 'checkbox', {create_hidden:true}), field('track_link', 'url', {show_when:{field:'type',value:'spotify'}})];
    assert.deepEqual(validateResourceForm(fields, {type:'sample'}), {});
});

test('event durations use calendar months and readable day and week labels', () => {
    assert.equal(eventDuration('2026-09-01','2026-09-06'), '5 days');
    assert.equal(eventDuration('2026-09-01','2026-09-08'), '1 week');
    assert.equal(eventDuration('2026-09-01','2026-10-01'), '1 month');
    assert.equal(eventDuration('2026-09-01','2026-10-16'), '1 month and 15 days');
    assert.equal(eventDuration('2026-09-09','2026-09-01'), '');
});
