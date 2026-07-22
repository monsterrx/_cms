import axios from 'axios';
import { Head, Link, usePage } from '@inertiajs/react';
import { useEffect, useMemo, useState } from 'react';
import AppShell from '../Components/AppShell';
import ResourceDataTable from '../Components/ResourceDataTable';
import ResourceForm from '../Components/ResourceForm';
import Icon from '../Components/Icon';
import PersistentModal from '../Components/PersistentModal';
import { getCreateAction } from '../Config/resourceActions';
import { useAppState } from '../Contexts/AppStateContext';
import { translateError } from '../lib/errorTranslator';

const emptyMeta = {
    current_page: 1,
    last_page: 1,
    per_page: 25,
    total: 0,
    from: null,
    to: null,
    sort: 'id',
    direction: 'desc',
};

function resolveWorkspace(navigation, sectionSlug, itemSlug) {
    const section = navigation.find((candidate) => candidate.slug === sectionSlug);
    const groups = section?.groups ?? [];
    const item = groups
        .flatMap((group) => group.items.map((entry) => ({ ...entry, group: group.label })))
        .find((candidate) => candidate.slug === itemSlug);

    return { section, item };
}

function formValuesFor(fields, record = null) {
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

function SectionOverview({ section }) {
    return (
        <div className="grid gap-5 lg:grid-cols-2">
            {section.groups.map((group) => (
                <section className="rx-panel p-6" key={group.label}>
                    <div className="flex items-center justify-between gap-4">
                        <div>
                            <p className="rx-kicker">{section.label}</p>
                            <h2 className="mt-1 font-heading text-lg font-semibold uppercase tracking-wide">{group.label}</h2>
                        </div>
                        <span className="rounded-full bg-canvas px-3 py-1 text-xs font-semibold uppercase tracking-wide text-ink-muted">
                            {group.items.length} tools
                        </span>
                    </div>

                    <div className="mt-5 space-y-2">
                        {group.items.map((item) => (
                            <Link
                                className="group flex items-start gap-4 rounded-lg bg-canvas px-4 py-4 transition-all duration-200 hover:translate-x-1 hover:bg-surface-muted"
                                href={`/workspace/${section.slug}/${item.slug}`}
                                key={item.slug}
                            >
                                <span className="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-md bg-rx-blue/15 text-rx-blue transition-colors group-hover:bg-rx-blue group-hover:text-neutral-950">
                                    <Icon className="h-4 w-4" name="arrow" />
                                </span>
                                <span className="min-w-0 flex-1">
                                    <span className="block font-heading text-sm font-semibold uppercase tracking-wide">{item.label}</span>
                                    <span className="mt-1 block text-sm leading-5 text-ink-muted">{item.description}</span>
                                </span>
                            </Link>
                        ))}
                    </div>
                </section>
            ))}
        </div>
    );
}

function ItemWorkspace({ section, item }) {
    const { notify } = useAppState();
    const createAction = getCreateAction(item.slug);
    const endpoint = `/api/resources/${section.slug}/${item.slug}`;
    const [records, setRecords] = useState([]);
    const [fields, setFields] = useState([]);
    const [tableFields, setTableFields] = useState([]);
    const [meta, setMeta] = useState(emptyMeta);
    const [resource, setResource] = useState({ can_write: false, has_uploads: false, read_only: false });
    const [loading, setLoading] = useState(true);
    const [loadError, setLoadError] = useState('');
    const [searchInput, setSearchInput] = useState('');
    const [search, setSearch] = useState('');
    const [page, setPage] = useState(1);
    const [sort, setSort] = useState('');
    const [direction, setDirection] = useState('desc');
    const [refreshToken, setRefreshToken] = useState(0);
    const [modalOpen, setModalOpen] = useState(false);
    const [selectedRecord, setSelectedRecord] = useState(null);
    const [formValues, setFormValues] = useState({});
    const [initialFormValues, setInitialFormValues] = useState({});
    const [formErrors, setFormErrors] = useState({});
    const [formMessage, setFormMessage] = useState('');
    const [saving, setSaving] = useState(false);
    const relatedItems = section.groups
        .flatMap((group) => group.items)
        .filter((candidate) => candidate.slug !== item.slug)
        .slice(0, 4);

    useEffect(() => {
        const timeout = window.setTimeout(() => {
            setSearch(searchInput.trim());
            setPage(1);
        }, 300);

        return () => window.clearTimeout(timeout);
    }, [searchInput]);

    useEffect(() => {
        let active = true;

        setLoading(true);
        setLoadError('');

        axios.get(endpoint, {
            params: {
                direction,
                page,
                per_page: meta.per_page,
                search: search || undefined,
                sort: sort || undefined,
            },
            silent: true,
        }).then(({ data }) => {
            if (!active) {
                return;
            }

            setRecords(data.data.records);
            setFields(data.data.fields);
            setTableFields(data.data.table_fields);
            setMeta(data.data.meta);
            setResource(data.data.resource);
            setSort(data.data.meta.sort);
            setDirection(data.data.meta.direction);
        }).catch((error) => {
            if (active) {
                setLoadError(translateError(error).message);
            }
        }).finally(() => {
            if (active) {
                setLoading(false);
            }
        });

        return () => {
            active = false;
        };
    }, [direction, endpoint, page, refreshToken, search, sort]);

    useEffect(() => {
        setPage(1);
        setSearchInput('');
        setSearch('');
        setSort('');
        setDirection('desc');
        setModalOpen(false);
    }, [item.slug]);

    const updatedToday = useMemo(() => {
        const today = new Date().toDateString();

        return records.filter((record) => {
            const value = record.updated_at ?? record.created_at;
            const date = value ? new Date(value) : null;

            return date && !Number.isNaN(date.getTime()) && date.toDateString() === today;
        }).length;
    }, [records]);

    const dirty = useMemo(
        () => JSON.stringify(formValues) !== JSON.stringify(initialFormValues),
        [formValues, initialFormValues],
    );

    const openCreate = () => {
        const values = formValuesFor(fields);

        setSelectedRecord(null);
        setInitialFormValues(values);
        setFormValues(values);
        setFormErrors({});
        setFormMessage('');
        setModalOpen(true);
    };

    const openEdit = (record) => {
        const values = formValuesFor(fields, record);

        setSelectedRecord(record);
        setInitialFormValues(values);
        setFormValues(values);
        setFormErrors({});
        setFormMessage('');
        setModalOpen(true);
    };

    const saveRecord = async (event) => {
        event.preventDefault();
        setSaving(true);
        setFormErrors({});
        setFormMessage('');

        try {
            if (selectedRecord) {
                await axios.put(`${endpoint}/${selectedRecord.id}`, formValues);
            } else {
                await axios.post(endpoint, formValues);
            }

            setModalOpen(false);
            setRefreshToken((current) => current + 1);
        } catch (error) {
            const translated = translateError(error);

            setFormErrors(translated.fieldErrors);
            setFormMessage(translated.message);
        } finally {
            setSaving(false);
        }
    };

    const deleteRecord = async (record) => {
        if (!window.confirm(`Delete record #${record.id}? This action cannot be undone for records without archive support.`)) {
            return;
        }

        try {
            await axios.delete(`${endpoint}/${record.id}`);
            setRefreshToken((current) => current + 1);
        } catch {
            // The global Axios handler displays the translated failure.
        }
    };

    const sortRecords = (column) => {
        setPage(1);
        if (sort === column) {
            setDirection((current) => (current === 'asc' ? 'desc' : 'asc'));
            return;
        }

        setSort(column);
        setDirection('asc');
    };

    const exportCurrentPage = () => {
        if (records.length === 0) {
            notify({ type: 'error', title: 'Nothing to export', message: 'There are no records in the current view.' });
            return;
        }

        const csvValue = (value) => `"${String(value ?? '').replace(/"/g, '""')}"`;
        const header = tableFields.map((field) => csvValue(field.label)).join(',');
        const rows = records.map((record) => tableFields.map((field) => csvValue(record[field.name])).join(','));
        const blob = new Blob([[header, ...rows].join('\n')], { type: 'text/csv;charset=utf-8' });
        const url = URL.createObjectURL(blob);
        const link = document.createElement('a');

        link.href = url;
        link.download = `${item.slug}-page-${meta.current_page}.csv`;
        link.click();
        URL.revokeObjectURL(url);
    };

    return (
        <div className="grid gap-6 xl:grid-cols-[minmax(0,1.6fr)_minmax(18rem,0.55fr)]">
            <div className="min-w-0 space-y-6">
                <section aria-label={`${item.label} summary`} className="grid gap-3 sm:grid-cols-3">
                    {[
                        ['Total records', meta.total],
                        ['Current page', records.length],
                        ['Updated today', updatedToday],
                    ].map(([label, value], index) => (
                        <article className="rx-panel p-5" key={label}>
                            <div className="flex items-center justify-between gap-3">
                                <p className="font-heading text-xs font-semibold uppercase tracking-[0.14em] text-ink-muted">{label}</p>
                                <span className={`h-2 w-2 rounded-full ${index % 2 === 0 ? 'bg-rx-blue' : 'bg-rx-yellow'}`} />
                            </div>
                            <p className="mt-4 font-heading text-3xl font-bold">{value}</p>
                        </article>
                    ))}
                </section>

                <section className="min-w-0" aria-labelledby="records-heading">
                    <div className="flex flex-col gap-4 px-5 py-5 sm:flex-row sm:items-center sm:justify-between">
                        <div className="min-w-0">
                            <p className="rx-kicker">{item.group}</p>
                            <h2 className="mt-1 font-heading text-lg font-semibold" id="records-heading">Live records</h2>
                        </div>
                        <div className="flex flex-col gap-2 sm:flex-row">
                            <button className="rx-button-secondary" onClick={exportCurrentPage} type="button">
                                Export current page
                            </button>
                            {resource.can_write && (
                                <button className="rx-button" disabled={loading || fields.length === 0} onClick={openCreate} type="button">
                                    <Icon className="h-4 w-4" name="plus" />
                                    {createAction}
                                </button>
                            )}
                        </div>
                    </div>

                    <div className="flex flex-col gap-3 rounded-t-xl border border-b-0 border-line bg-surface-muted/50 px-5 py-4 sm:flex-row">
                        <label className="relative flex-1">
                            <span className="sr-only">Search {item.label}</span>
                            <Icon className="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-ink-muted" name="search" />
                            <input
                                className="w-full rounded-lg border-0 bg-surface py-2.5 pl-10 pr-3 text-sm text-ink placeholder:text-ink-muted focus:ring-rx-blue"
                                onChange={(event) => setSearchInput(event.target.value)}
                                placeholder={`Search ${item.label.toLowerCase()}...`}
                                type="search"
                                value={searchInput}
                            />
                        </label>
                        <button
                            className="rx-button-secondary"
                            disabled={loading}
                            onClick={() => setRefreshToken((current) => current + 1)}
                            type="button"
                        >
                            <Icon className="h-4 w-4" name="refresh" />
                            Refresh
                        </button>
                    </div>

                    {loadError ? (
                        <div className="rounded-b-xl border border-red-500/40 bg-red-500/10 px-6 py-10 text-center">
                            <p className="font-heading text-sm font-semibold uppercase tracking-wide text-red-500">Records could not be loaded</p>
                            <p className="mt-2 text-sm text-ink-muted">{loadError}</p>
                            <button className="rx-button-secondary mt-5" onClick={() => setRefreshToken((current) => current + 1)} type="button">Try again</button>
                        </div>
                    ) : (
                        <ResourceDataTable
                            canWrite={resource.can_write}
                            direction={direction}
                            fields={tableFields}
                            loading={loading}
                            meta={meta}
                            onDelete={deleteRecord}
                            onEdit={openEdit}
                            onPageChange={setPage}
                            onSort={sortRecords}
                            records={records}
                            sort={sort}
                        />
                    )}
                </section>
            </div>

            <aside className="space-y-6">
                <section className="rx-panel p-5">
                    <p className="rx-kicker">Current tool</p>
                    <h2 className="mt-2 font-heading text-lg font-semibold uppercase tracking-wide">About {item.label}</h2>
                    <p className="mt-3 text-sm leading-6 text-ink-muted">{item.description}</p>
                    <div className="mt-5 rounded-lg bg-canvas p-4">
                        <p className="text-xs font-semibold uppercase tracking-[0.16em] text-ink-muted">Resource group</p>
                        <p className="mt-1 font-heading text-sm font-semibold uppercase tracking-wide">{item.group}</p>
                    </div>
                    <div className="mt-5">
                        <p className="text-xs font-semibold uppercase tracking-[0.16em] text-ink-muted">Data connection</p>
                        <p className="mt-2 text-sm leading-6 text-ink-muted">
                            {resource.read_only ? 'This system-managed resource is read-only.' : 'Live CRUD API connected.'}
                        </p>
                        {resource.has_uploads && (
                            <p className="mt-2 rounded-lg bg-rx-yellow/10 px-3 py-2 text-xs leading-5 text-ink-muted">
                                File fields are shown in forms, but uploads are deferred.
                            </p>
                        )}
                    </div>
                </section>

                <section className="rx-panel p-5">
                    <p className="rx-kicker">Related tools</p>
                    <div className="mt-4 space-y-1">
                        {relatedItems.map((related) => (
                            <Link
                                className="group flex items-center justify-between gap-3 rounded-lg px-3 py-2.5 text-sm transition-colors duration-200 hover:bg-canvas"
                                href={`/workspace/${section.slug}/${related.slug}`}
                                key={related.slug}
                            >
                                <span>{related.label}</span>
                                <Icon className="h-4 w-4 text-ink-muted transition-transform group-hover:translate-x-1" name="arrow" />
                            </Link>
                        ))}
                    </div>
                </section>
            </aside>

            <PersistentModal
                dirty={resource.can_write && dirty}
                footer={resource.can_write ? (
                    <div className="flex items-center justify-between gap-4">
                        <p className="text-xs text-ink-muted">Close with the X button. Unsaved changes require confirmation.</p>
                        <button className="rx-button" disabled={saving} form="resource-record-form" type="submit">
                            {saving ? 'Saving...' : (selectedRecord ? 'Save changes' : 'Create record')}
                        </button>
                    </div>
                ) : (
                    <p className="text-right text-xs text-ink-muted">This record is read-only. Use the X button to close.</p>
                )}
                onClose={() => setModalOpen(false)}
                open={modalOpen}
                title={selectedRecord ? `Edit ${item.label} #${selectedRecord.id}` : `New ${item.label}`}
            >
                {resource.has_uploads && (
                    <div className="mb-6 flex gap-3 rounded-lg border border-rx-yellow/30 bg-rx-yellow/10 p-4 text-sm text-ink-muted">
                        <Icon className="mt-0.5 h-5 w-5 shrink-0 text-rx-yellow" name="alert" />
                        <p>Photo and file controls are included for completeness, but upload processing is not enabled yet.</p>
                    </div>
                )}
                {formMessage && <div className="mb-5 border-l-4 border-red-500 bg-red-500/10 p-4 text-sm text-red-500" role="alert">{formMessage}</div>}
                <form id="resource-record-form" onSubmit={saveRecord}>
                    <ResourceForm
                        errors={formErrors}
                        fields={fields}
                        onChange={(name, value) => {
                            setFormValues((current) => ({ ...current, [name]: value }));
                            setFormErrors((current) => ({ ...current, [name]: undefined }));
                        }}
                        readOnly={!resource.can_write}
                        values={formValues}
                    />
                </form>
            </PersistentModal>
        </div>
    );
}

export default function Workspace({ sectionSlug, itemSlug = null }) {
    const { props } = usePage();
    const { section, item } = resolveWorkspace(props.navigation ?? [], sectionSlug, itemSlug);
    const title = item?.label ?? section?.label ?? 'Workspace';

    return (
        <AppShell>
            <Head title={title} />

            <div className="mx-auto max-w-[96rem] px-4 py-8 sm:px-6 lg:px-8 lg:py-10">
                <nav aria-label="Breadcrumb" className="flex flex-wrap items-center gap-2 text-xs uppercase tracking-[0.14em] text-ink-muted">
                    <Link className="transition-colors hover:text-rx-blue" href="/dashboard">Dashboard</Link>
                    <span>/</span>
                    {item ? (
                        <>
                            <Link className="transition-colors hover:text-rx-blue" href={`/workspace/${section.slug}`}>{section.label}</Link>
                            <span>/</span>
                            <span className="text-ink">{item.label}</span>
                        </>
                    ) : (
                        <span className="text-ink">{section.label}</span>
                    )}
                </nav>

                <section className="mb-8 mt-5 flex flex-col justify-between gap-6 lg:flex-row lg:items-end">
                    <div className="flex items-start gap-4">
                        <span className="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-rx-blue text-neutral-950">
                            <Icon className="h-6 w-6" name={section.icon} />
                        </span>
                        <div>
                            <p className="rx-kicker">{item?.group ?? 'Management area'}</p>
                            <h1 className="mt-2 font-heading text-3xl font-bold uppercase tracking-tight sm:text-5xl">{title}</h1>
                            <p className="mt-3 max-w-2xl text-base leading-6 text-ink-muted">
                                {item?.description ?? section.description}
                            </p>
                        </div>
                    </div>
                    {item && (
                        <Link className="rx-button-secondary self-start lg:self-auto" href={`/workspace/${section.slug}`}>
                            Module overview
                        </Link>
                    )}
                </section>

                {item ? <ItemWorkspace item={item} section={section} /> : <SectionOverview section={section} />}
            </div>
        </AppShell>
    );
}
