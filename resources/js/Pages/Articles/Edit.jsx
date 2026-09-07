import axios from 'axios';
import { Head, Link } from '@inertiajs/react';
import { useCallback, useEffect, useMemo, useState } from 'react';
import AppShell from '../../Components/AppShell';
import ArticlePreview from '../../Components/ArticlePreview';
import ResourceDetails from '../../Components/ResourceDetails';
import ResourceForm from '../../Components/ResourceForm';
import { appPath } from '../../lib/appUrl';
import { translateError } from '../../lib/errorTranslator';
import { resourceFormValues, resourceRequestPayload } from '../../lib/resourceForm';

const endpoint = '/api/resources/digital-content-programs/articles';

export default function Edit({ recordId }) {
    const [record, setRecord] = useState(null);
    const [resource, setResource] = useState({ can_write: false });
    const [fields, setFields] = useState([]);
    const [values, setValues] = useState({});
    const [initialValues, setInitialValues] = useState({});
    const [details, setDetails] = useState({});
    const [previewContents, setPreviewContents] = useState([]);
    const [view, setView] = useState('edit');
    const [loading, setLoading] = useState(true);
    const [detailsLoading, setDetailsLoading] = useState(true);
    const [saving, setSaving] = useState(false);
    const [errors, setErrors] = useState({});
    const [message, setMessage] = useState('');
    const [detailsError, setDetailsError] = useState('');
    const [cropStatus, setCropStatus] = useState({});
    const dirty = useMemo(() => JSON.stringify(values) !== JSON.stringify(initialValues), [initialValues, values]);
    const hasPendingCrop = Object.values(cropStatus).some((ready) => !ready);
    const recordEndpoint = `${endpoint}/${recordId}`;
    const previewRecord = useMemo(() => ({ ...(record ?? {}), ...values }), [record, values]);
    const category = useMemo(() => {
        const options = fields.find((field) => field.name === 'category_id')?.options ?? [];
        return options.find((option) => String(option.value) === String(values.category_id))?.label;
    }, [fields, values.category_id]);

    const loadDetails = useCallback(async () => {
        setDetailsLoading(true);
        setDetailsError('');
        try {
            const response = await axios.get(`${recordEndpoint}/details`, { silent: true });
            const nextDetails = response.data.data.details ?? {};
            setDetails(nextDetails);
            setPreviewContents(nextDetails.contents ?? []);
        } catch (error) {
            setDetailsError(translateError(error).message);
        } finally {
            setDetailsLoading(false);
        }
    }, [recordEndpoint]);

    const loadRecord = useCallback(async () => {
        setLoading(true);
        setMessage('');
        try {
            const response = await axios.get(recordEndpoint, { silent: true });
            const data = response.data.data;
            const nextValues = resourceFormValues(data.fields ?? [], data.record);

            setRecord(data.record);
            setResource(data.resource ?? { can_write: false });
            if (!data.resource?.can_write) setView('preview');
            setFields(data.fields ?? []);
            setValues(nextValues);
            setInitialValues(nextValues);
            setCropStatus({});
        } catch (error) {
            setMessage(translateError(error).message);
        } finally {
            setLoading(false);
        }
    }, [recordEndpoint]);

    useEffect(() => {
        loadRecord();
        loadDetails();
    }, [loadDetails, loadRecord]);

    useEffect(() => {
        const warnBeforeLeaving = (event) => {
            if (!dirty) return;
            event.preventDefault();
            event.returnValue = '';
        };

        window.addEventListener('beforeunload', warnBeforeLeaving);
        return () => window.removeEventListener('beforeunload', warnBeforeLeaving);
    }, [dirty]);

    const save = async (event) => {
        event.preventDefault();
        if (hasPendingCrop) {
            setMessage('Apply every selected image crop before saving the article.');
            return;
        }

        setSaving(true);
        setErrors({});
        setMessage('');
        try {
            const payload = resourceRequestPayload(fields, values);
            if (payload instanceof FormData) {
                payload.append('_method', 'PUT');
                await axios.post(recordEndpoint, payload);
            } else {
                await axios.put(recordEndpoint, payload);
            }

            await loadRecord();
            setMessage('Article details saved successfully.');
        } catch (error) {
            const translated = translateError(error);
            setErrors(translated.fieldErrors);
            setMessage(translated.message);
        } finally {
            setSaving(false);
        }
    };

    return (
        <AppShell>
            <Head title={record?.title ? `Edit ${record.title}` : 'Edit Article'} />
            <main className="mx-auto max-w-[96rem] px-4 py-8 sm:px-6 lg:px-8 lg:py-10">
                <nav className="flex flex-wrap items-center gap-2 text-xs uppercase tracking-[0.14em] text-ink-muted">
                    <Link href={appPath('/dashboard')}>Dashboard</Link>
                    <span>/</span>
                    <Link href={appPath('/workspace/digital-content-programs/articles')}>Articles</Link>
                    <span>/</span>
                    <span className="max-w-lg truncate text-ink">{record?.title ?? `Article #${recordId}`}</span>
                </nav>

                <header className="mb-8 mt-5 flex flex-col justify-between gap-5 lg:flex-row lg:items-end">
                    <div className="min-w-0">
                        <p className="rx-kicker">Article editor</p>
                        <h1 className="mt-2 max-w-4xl font-heading text-3xl font-bold uppercase tracking-tight sm:text-5xl">{record?.title ?? 'Loading article'}</h1>
                        <p className="mt-3 max-w-2xl text-sm leading-6 text-ink-muted">Edit article details and ordered content, then review how the saved article will appear on RX93.1.</p>
                    </div>
                    <div className="flex flex-wrap gap-2">
                        <div className="flex rounded-md border border-line bg-surface p-1">
                            {['edit', 'preview'].map((option) => (
                                <button
                                    aria-pressed={view === option}
                                    className={`rounded-sm px-4 py-2 text-xs font-semibold uppercase tracking-wide transition-colors ${view === option ? 'bg-rx-blue text-neutral-950' : 'text-ink-muted hover:text-ink'}`}
                                    key={option}
                                    onClick={() => setView(option)}
                                    type="button"
                                >
                                    {option}
                                </button>
                            ))}
                        </div>
                        <Link
                            className="rx-button-secondary"
                            href={appPath('/workspace/digital-content-programs/articles')}
                            onClick={(event) => {
                                if (dirty && !window.confirm('Leave without saving your article changes?')) event.preventDefault();
                            }}
                        >
                            Back to Articles
                        </Link>
                    </div>
                </header>

                {message && <p className={`mb-5 border-l-4 p-4 text-sm ${Object.keys(errors).length > 0 ? 'border-red-500 bg-red-500/10 text-red-500' : 'border-rx-blue bg-rx-blue/10 text-ink'}`}>{message}</p>}

                {loading ? (
                    <section className="rx-panel p-12 text-center text-sm text-ink-muted">Loading article editor...</section>
                ) : !record ? (
                    <section className="rx-panel p-12 text-center text-sm text-red-500">The article could not be loaded.</section>
                ) : (
                    <>
                        <div className={view === 'edit' ? 'block' : 'hidden'}>
                            <section className="rx-panel p-5 sm:p-7">
                                <form id="article-record-form" onSubmit={save}>
                                    <ResourceForm
                                        errors={errors}
                                        fields={fields}
                                        onChange={(name, value) => {
                                            setValues((current) => ({ ...current, [name]: value }));
                                            setErrors((current) => ({ ...current, [name]: undefined }));
                                        }}
                                        onCropStatusChange={(name, ready) => setCropStatus((current) => ({ ...current, [name]: ready }))}
                                        readOnly={!resource.can_write}
                                        values={values}
                                    />
                                    {resource.can_write && (
                                        <div className="mt-7 flex justify-end border-t border-line pt-5">
                                            <button className="rx-button" disabled={saving || hasPendingCrop} type="submit">{saving ? 'Saving...' : 'Save Article'}</button>
                                        </div>
                                    )}
                                </form>

                                <ResourceDetails
                                    canWrite={resource.can_write}
                                    details={details}
                                    endpoint={recordEndpoint}
                                    error={detailsError}
                                    itemSlug="articles"
                                    loading={detailsLoading}
                                    onPreviewChange={setPreviewContents}
                                    record={record}
                                    reload={loadDetails}
                                />
                            </section>
                        </div>

                        <div className={view === 'preview' ? 'block' : 'hidden'}>
                            <ArticlePreview
                                category={category}
                                contents={previewContents}
                                preview={{ ...(details.preview ?? {}), related: details.related ?? [] }}
                                record={previewRecord}
                            />
                        </div>
                    </>
                )}
            </main>
        </AppShell>
    );
}
