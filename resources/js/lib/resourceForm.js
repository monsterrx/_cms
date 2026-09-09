export function resourceFormValues(fields, record = null) {
    const result = fields.reduce((values, field) => {
        if (!field.form) {
            return values;
        }

        if (record) {
            values[field.name] = record[field.name] ?? '';
        } else if (field.type === 'checkbox') {
            values[field.name] = Number(field.default ?? 0);
        } else {
            values[field.name] = field.default ?? '';
        }

        return values;
    }, {});
    if (fields.some((field) => field.name === 'artist_id' && field.virtual) && record?.album_id) {
        result.artist_id = fields.find((field) => field.name === 'album_id')?.options.find((option) => String(option.value) === String(record.album_id))?.artist_id ?? '';
    }
    return result;
}

export function resourceRequestPayload(fields, values) {
    const payload = fields.reduce((result, field) => {
        if (!field.form || !field.writable || (field.virtual && !['artist_id', 'sample'].includes(field.name))) {
            return result;
        }

        const value = values[field.name];
        if (['file', 'audio'].includes(field.type)) {
            if (typeof File !== 'undefined' && value instanceof File) {
                if (field.show_when && String(values[field.show_when.field] ?? '') !== String(field.show_when.value)) return result;
        result[field.name] = value;
            }

            return result;
        }

        if (field.show_when && String(values[field.show_when.field] ?? '') !== String(field.show_when.value)) return result;
        result[field.name] = value;
        return result;
    }, {});
    const hasFiles = Object.values(payload).some(
        (value) => typeof File !== 'undefined' && value instanceof File,
    );

    if (!hasFiles) {
        return payload;
    }

    const formData = new FormData();
    Object.entries(payload).forEach(([name, value]) => {
        if (value !== undefined && value !== null) {
            formData.append(name, value);
        }
    });

    return formData;
}

export function validateResourceForm(fields, values, editing = false) {
    const errors = {};
    for (const field of fields) {
        if (!field.form || !field.writable || (!editing && field.create_hidden)) continue;
        if (field.show_when && String(values[field.show_when.field] ?? '') !== String(field.show_when.value)) continue;
        const value = values[field.name];
        const empty = value === null || value === undefined || String(value).trim() === '' || (field.type === 'rich-text' && !/<img\b/i.test(value) && !String(value).replace(/<[^>]*>|&nbsp;/g, '').trim());
        if (field.name === 'sample' && values.type === 'sample' && !editing && empty) errors[field.name] = 'Choose an audio file for the sample track.';
        if (!field.nullable && field.default === null && empty) errors[field.name] = `${field.label} is required.`;
        if (!empty && field.type === 'email' && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) errors[field.name] = 'Enter a valid email address.';
        if (!empty && field.type === 'date') {
            const date = new Date(`${value}T12:00:00Z`);
            if (!/^\d{4}-\d{2}-\d{2}$/.test(value) || Number.isNaN(date.getTime()) || date.toISOString().slice(0, 10) !== value) errors[field.name] = 'Enter a valid date in YYYY-MM-DD format.';
        }
        if (!empty && field.type === 'url') {
            try { const url = new URL(value); if (!['http:', 'https:'].includes(url.protocol)) throw new Error(); }
            catch { errors[field.name] = 'Enter a valid HTTP or HTTPS URL.'; }
        }
        if (!empty && field.type === 'select' && !field.options.some((option) => String(option.value) === String(value))) errors[field.name] = `Choose a valid ${field.label.toLowerCase()}.`;
        if (!empty && field.type === 'number' && !Number.isFinite(Number(value))) errors[field.name] = 'Enter a valid number.';
    }
    return errors;
}

export function eventDuration(start, end) {
    if (!start || !end) return '';
    const from = new Date(`${String(start).slice(0, 10)}T00:00:00Z`);
    const to = new Date(`${String(end).slice(0, 10)}T00:00:00Z`);
    if (Number.isNaN(from.getTime()) || Number.isNaN(to.getTime()) || to < from) return '';
    let months = (to.getUTCFullYear() - from.getUTCFullYear()) * 12 + to.getUTCMonth() - from.getUTCMonth();
    if (to.getUTCDate() < from.getUTCDate()) months--;
    const cursor = new Date(from); cursor.setUTCDate(1); cursor.setUTCMonth(cursor.getUTCMonth() + months);
    const lastDay = new Date(Date.UTC(cursor.getUTCFullYear(), cursor.getUTCMonth() + 1, 0)).getUTCDate();
    cursor.setUTCDate(Math.min(from.getUTCDate(), lastDay));
    const days = Math.round((to - cursor) / 86400000);
    const plural = (count, word) => `${count} ${word}${count === 1 ? '' : 's'}`;
    const parts = [];
    if (months) parts.push(plural(months, 'month'));
    if (days) parts.push(!months && days % 7 === 0 ? plural(days / 7, 'week') : plural(days, 'day'));
    return parts.join(' and ') || '1 day';
}
