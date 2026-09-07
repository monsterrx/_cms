import axios from 'axios';
import { Head, Link } from '@inertiajs/react';
import { useCallback, useEffect, useMemo, useState } from 'react';
import AppShell from '../../Components/AppShell';
import ResourceDetails from '../../Components/ResourceDetails';
import ResourceForm from '../../Components/ResourceForm';
import { appPath } from '../../lib/appUrl';
import { translateError } from '../../lib/errorTranslator';
import { resourceFormValues, resourceRequestPayload } from '../../lib/resourceForm';

const endpoint = '/api/resources/digital-content-programs/shows';

export default function Edit({ recordId }) {
    const [record, setRecord] = useState(null);
    const [resource, setResource] = useState({ can_write: false });
    const [fields, setFields] = useState([]);
    const [values, setValues] = useState({});
    const [initialValues, setInitialValues] = useState({});
    const [details, setDetails] = useState({});
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

    const loadDetails = useCallback(async () => {
        setDetailsLoading(true);
        setDetailsError('');
        try {
            const response = await axios.get(`${recordEndpoint}/details`, { silent: true });
            setDetails(response.data.data.details ?? {});
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
            setMessage('Apply every selected image crop before saving the show.');
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
            setMessage('Show details saved successfully.');
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
            <Head title={record?.title ? `Edit ${record.title}` : 'Edit Show'} />
            <main className="mx-auto max-w-[96rem] px-4 py-8 sm:px-6 lg:px-8 lg:py-10">
                <nav className="flex flex-wrap items-center gap-2 text-xs uppercase tracking-[0.14em] text-ink-muted">
                    <Link href={appPath('/dashboard')}>Dashboard</Link>
                    <span>/</span>
                    <Link href={appPath('/workspace/digital-content-programs/shows')}>Shows</Link>
                    <span>/</span>
                    <span className="text-ink">{record?.title ?? `Show #${recordId}`}</span>
                </nav>

                <header className="mb-8 mt-5 flex flex-col justify-between gap-5 lg:flex-row lg:items-end">
                    <div>
                        <p className="rx-kicker">Show editor</p>
                        <h1 className="mt-2 font-heading text-3xl font-bold uppercase tracking-tight sm:text-5xl">{record?.title ?? 'Loading show'}</h1>
                        <p className="mt-3 max-w-2xl text-sm leading-6 text-ink-muted">Manage the show profile, Jocks, timeslots, images, and podcast episodes in one workspace.</p>
                    </div>
                    <Link
                        className="rx-button-secondary self-start"
                        href={appPath('/workspace/digital-content-programs/shows')}
                        onClick={(event) => {
                            if (dirty && !window.confirm('Leave without saving your show changes?')) event.preventDefault();
                        }}
                    >
                        Back to Shows
                    </Link>
                </header>

                {message && <p className={`mb-5 border-l-4 p-4 text-sm ${Object.keys(errors).length > 0 ? 'border-red-500 bg-red-500/10 text-red-500' : 'border-rx-blue bg-rx-blue/10 text-ink'}`}>{message}</p>}

                {loading ? (
                    <section className="rx-panel p-12 text-center text-sm text-ink-muted">Loading show editor...</section>
                ) : !record ? (
                    <section className="rx-panel p-12 text-center text-sm text-red-500">The show could not be loaded.</section>
                ) : (
                    <div className="grid gap-6 xl:grid-cols-[minmax(0,1fr)_22rem]">
                        <div className="min-w-0">
                            <section className="rx-panel p-5 sm:p-7">
                                <form id="show-record-form" onSubmit={save}>
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
                                            <button className="rx-button" disabled={saving || hasPendingCrop} type="submit">{saving ? 'Saving...' : 'Save Show'}</button>
                                        </div>
                                    )}
                                </form>
                            </section>

                            <ResourceDetails
                                canWrite={resource.can_write}
                                details={details}
                                endpoint={recordEndpoint}
                                error={detailsError}
                                itemSlug="shows"
                                loading={detailsLoading}
                                record={record}
                                reload={loadDetails}
                            />
                        </div>

                        <aside className="space-y-4">
                            {record._display?.secondary_image && <img alt={`${record.title} header`} className="aspect-[16/5] w-full rounded-lg border border-line bg-canvas object-cover" src={record._display.secondary_image} />}
                            {record._display?.background_image && <img alt={`${record.title} background`} className="aspect-square w-full rounded-lg border border-line bg-canvas object-cover" src={record._display.background_image} />}
                            {record._display?.image && <img alt={`${record.title} icon`} className="aspect-square w-full rounded-lg border border-line bg-canvas object-cover" src={record._display.image} />}
                            <section className="rx-panel p-5 text-sm leading-6 text-ink-muted">
                                Related records save independently. Use <strong className="text-ink">Save Show</strong> for changes to the main show fields.
                            </section>
                        </aside>
                    </div>
                )}
            </main>
        </AppShell>
    );
}
