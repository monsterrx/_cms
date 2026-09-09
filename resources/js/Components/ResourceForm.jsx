import { useState } from 'react';
import CalendarInput from './CalendarInput';
import CardDropdown from './CardDropdown';
import ImageCropField from './ImageCropField';
import RichTextEditor from './RichTextEditor';
import { eventDuration } from '../lib/resourceForm';

function normalizeDateValue(value, type) {
    if (!value) {
        return '';
    }

    if (type === 'datetime-local') {
        return String(value).replace(' ', 'T').slice(0, 16);
    }

    return String(value).slice(0, 10);
}

function fieldError(errors, name) {
    const error = errors?.[name];

    return Array.isArray(error) ? error[0] : (error ?? '');
}

function hasValue(value) {
    return value !== null && value !== undefined && String(value).trim() !== '';
}

function cropFieldForValues(field, values) {
    const variants = field.crop_variants;
    const selector = variants?.selector;
    const selected = selector ? variants[values[selector]] : null;

    return selected ? { ...field, crop: selected } : field;
}

function FloatingLabel({ error, field, floated, focused, required }) {
    const color = error
        ? 'text-red-500'
        : (focused ? 'text-rx-blue' : 'text-ink-muted');
    const textareaAtRest = field.type === 'textarea' && !floated;

    return (
        <label
            className={`pointer-events-none absolute left-3 z-10 origin-left bg-surface px-1 font-heading font-semibold uppercase tracking-[0.1em] transition-all duration-200 ease-out ${color}`}
            htmlFor={`field-${field.name}`}
            style={{
                fontSize: floated ? '0.65rem' : '0.875rem',
                top: floated ? '0' : (textareaAtRest ? '1rem' : '50%'),
                transform: floated ? 'translateY(-50%)' : (textareaAtRest ? 'translateY(0)' : 'translateY(-50%)'),
            }}
        >
            {field.label}{required && <span className="ml-1 text-red-500">*</span>}
        </label>
    );
}

export default function ResourceForm({
    errors,
    editing = false,
    fields,
    onChange,
    onCropStatusChange,
    readOnly,
    values,
}) {
    const [focusedField, setFocusedField] = useState(null);
    const formFields = fields.filter((field) => {
        if (!field.form || (!editing && field.create_hidden)) {
            return false;
        }

        const condition = field.show_when;
        return !condition || String(values[condition.field] ?? '') === String(condition.value);
    }).sort((a, b) => Number(['file', 'audio'].includes(a.type)) - Number(['file', 'audio'].includes(b.type)));

    return (
        <div className="grid pt-2 md:grid-cols-2" style={{ columnGap: '1.5rem', rowGap: '2rem' }}>
            {formFields.map((field) => {
                const error = fieldError(errors, field.name);
                const errorId = `field-${field.name}-error`;
                const disabled = readOnly || !field.writable;
                const required = !field.nullable && field.default === null && field.type !== 'file';
                const value = field.name === 'event_duration' ? eventDuration(values.start_date, values.end_date) : ['date', 'datetime-local'].includes(field.type)
                    ? normalizeDateValue(values[field.name], field.type)
                    : (values[field.name] ?? '');
                const focused = focusedField === field.name;
                const floated = focused || hasValue(value) || ['date', 'datetime-local'].includes(field.type);
                const activeState = error
                    ? 'border-red-500 focus:border-red-500 focus:bg-red-500/5'
                    : 'border-line focus:border-rx-blue focus:bg-rx-blue/10';
                const controlClass = `block min-h-14 w-full rounded-md border bg-transparent px-3 pb-2 pt-5 text-sm text-ink shadow-none outline-none transition-[border-color,background-color,opacity] duration-200 placeholder:text-transparent hover:bg-canvas/40 focus:ring-0 disabled:cursor-not-allowed disabled:bg-canvas/40 disabled:opacity-60 ${activeState}`;

                if (field.type === 'audio') {
                    return <div className="md:col-span-2" key={field.name}><label htmlFor={`field-${field.name}`}>{field.label}</label><input id={`field-${field.name}`} type="file" accept="audio/mpeg,audio/mp4,audio/ogg,audio/wav,.mp3,.m4a,.ogg,.wav" disabled={readOnly} onChange={(event) => onChange(field.name, event.target.files?.[0] ?? null)} aria-invalid={Boolean(error)} className="mt-2 block w-full" />{error && <p className="text-sm text-red-500">{error}</p>}</div>;
                }

                if (field.type === 'file') {
                    if (field.upload_supported && field.crop) {
                        return (
                            <ImageCropField
                                disabled={disabled}
                                error={error}
                                field={cropFieldForValues(field, values)}
                                key={field.name}
                                onChange={onChange}
                                onCropStatusChange={onCropStatusChange}
                                value={value}
                            />
                        );
                    }

                    return (
                        <div className="md:col-span-2" key={field.name}>
                            <label className="font-heading text-[0.65rem] font-semibold uppercase tracking-[0.1em] text-ink-muted" htmlFor={`field-${field.name}`}>
                                {field.label}
                            </label>
                            <input
                                className="mt-1 block w-full rounded-md border border-line bg-transparent px-3 py-3 text-sm text-ink opacity-60 shadow-none focus:border-rx-blue focus:ring-0"
                                disabled
                                id={`field-${field.name}`}
                                type="file"
                            />
                            <p className="mt-2 text-xs leading-5 text-ink-muted">
                                Existing value: {values[field.name] || 'No file assigned'}. This module does not have an approved crop size yet.
                            </p>
                        </div>
                    );
                }

                if (field.type === 'rich-text') {
                    return (
                        <RichTextEditor
                            disabled={disabled}
                            error={error}
                            field={field}
                            key={field.name}
                            onChange={onChange}
                            required={required}
                            value={value}
                        />
                    );
                }

                if (field.type === 'checkbox') {
                    return (
                        <div key={field.name}>
                            <label className={`flex min-h-14 items-center gap-3 rounded-md border bg-transparent px-3 py-3 text-sm shadow-none transition-colors hover:bg-canvas/40 ${error ? 'border-red-500' : 'border-line focus-within:border-rx-blue'}`}>
                                <input
                                    aria-describedby={error ? errorId : undefined}
                                    aria-invalid={Boolean(error)}
                                    checked={Boolean(Number(values[field.name])) || values[field.name] === true}
                                    className="h-5 w-5 rounded-sm border-2 border-line bg-transparent text-rx-blue focus:ring-2 focus:ring-rx-blue focus:ring-offset-0"
                                    disabled={disabled}
                                    name={field.name}
                                    onChange={(event) => onChange(field.name, event.target.checked ? 1 : 0)}
                                    type="checkbox"
                                />
                                <span className="font-heading text-xs font-semibold uppercase tracking-[0.12em] text-ink">
                                    {field.label}
                                </span>
                            </label>
                            {error && <p className="mt-1.5 text-xs text-red-500" id={errorId}>{error}</p>}
                        </div>
                    );
                }

                return (
                    <div className={field.type === 'textarea' ? 'md:col-span-2' : ''} key={field.name}>
                        <div className="relative">
                            {field.type === 'date' ? (
                                <CalendarInput id={`field-${field.name}`} name={field.name} value={value} disabled={disabled}
                                    required={required} error={error} errorId={errorId} className={controlClass}
                                    onChange={(next) => onChange(field.name, next)} onFocus={() => setFocusedField(field.name)} onBlur={() => setFocusedField(null)} />
                            ) : field.type === 'textarea' ? (
                                <textarea
                                    aria-describedby={error ? errorId : undefined}
                                    aria-invalid={Boolean(error)}
                                    className={`${controlClass} min-h-32 resize-y px-3 pt-6`}
                                    disabled={disabled}
                                    id={`field-${field.name}`}
                                    maxLength={field.max_length ?? undefined}
                                    name={field.name}
                                    onBlur={() => setFocusedField(null)}
                                    onChange={(event) => onChange(field.name, event.target.value)}
                                    onFocus={() => setFocusedField(field.name)}
                                    placeholder=" "
                                    required={required}
                                    value={value}
                                />
                            ) : field.type === 'select' ? (
                                <CardDropdown
                                    ariaDescribedBy={error ? errorId : undefined}
                                    ariaInvalid={Boolean(error)}
                                    ariaRequired={required}
                                    buttonClassName={`${controlClass} relative cursor-pointer pr-10 text-left`}
                                    disabled={disabled}
                                    id={`field-${field.name}`}
                                    onBlur={() => setFocusedField(null)}
                                    onChange={(selectedValue) => onChange(field.name, selectedValue)}
                                    onFocus={() => setFocusedField(field.name)}
                                    onOpenChange={(isOpen) => setFocusedField(isOpen ? field.name : null)}
                                    options={field.name === 'album_id' && fields.some((candidate) => candidate.name === 'artist_id' && candidate.virtual) ? field.options.filter((option) => String(option.artist_id) === String(values.artist_id)) : field.options}
                                    value={value}
                                />
                            ) : (
                                <input
                                    aria-describedby={error ? errorId : undefined}
                                    aria-invalid={Boolean(error)}
                                    className={controlClass}
                                    disabled={disabled}
                                    id={`field-${field.name}`}
                                    maxLength={field.max_length ?? undefined}
                                    name={field.name}
                                    onBlur={() => setFocusedField(null)}
                                    onChange={(event) => onChange(field.name, event.target.value)}
                                    onFocus={() => setFocusedField(field.name)}
                                    placeholder=" "
                                    required={required}
                                    type={field.type}
                                    onClick={['date', 'datetime-local'].includes(field.type) ? (event) => { try { event.currentTarget.showPicker?.(); } catch {} } : undefined}
                                    value={value}
                                />
                            )}

                            <FloatingLabel error={error} field={field} floated={floated} focused={focused} required={required} />
                        </div>


                        {field.help && <p className="mt-2 text-xs leading-5 text-ink-muted">{field.help}</p>}
                        {error && <p className="mt-1.5 text-xs text-red-500" id={errorId}>{error}</p>}
                    </div>
                );
            })}
        </div>
    );
}
