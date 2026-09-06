export function resourceFormValues(fields, record = null) {
    return fields.reduce((values, field) => {
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
}

export function resourceRequestPayload(fields, values) {
    const payload = fields.reduce((result, field) => {
        if (!field.form || !field.writable || field.virtual) {
            return result;
        }

        const value = values[field.name];
        if (field.type === 'file') {
            if (typeof File !== 'undefined' && value instanceof File) {
                result[field.name] = value;
            }

            return result;
        }

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
