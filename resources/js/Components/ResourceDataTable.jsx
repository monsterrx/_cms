import Icon from './Icon';

function formatValue(value, field) {
    if (value === null || value === undefined || value === '') {
        return '—';
    }

    if (field.type === 'checkbox') {
        return Number(value) === 1 ? 'Yes' : 'No';
    }

    if (['date', 'datetime-local'].includes(field.type)) {
        const date = new Date(value);

        return Number.isNaN(date.getTime()) ? String(value) : date.toLocaleString();
    }

    const option = field.options?.find((candidate) => String(candidate.value) === String(value));
    if (option) {
        return option.label;
    }

    const text = String(value).replace(/<[^>]*>/g, ' ').replace(/\s+/g, ' ').trim();

    return text.length > 90 ? `${text.slice(0, 87)}...` : text;
}

export default function ResourceDataTable({
    canWrite,
    direction,
    fields,
    loading,
    meta,
    onDelete,
    onEdit,
    onPageChange,
    onSort,
    records,
    sort,
}) {
    return (
        <div className="min-w-0 overflow-hidden rounded-xl border border-line bg-surface">
            <div className="overflow-x-auto">
                <table className="min-w-full border-collapse text-left text-sm">
                    <thead className="bg-surface-muted/70">
                        <tr>
                            {fields.map((field) => (
                                <th className="whitespace-nowrap px-4 py-3 font-heading text-[0.68rem] font-semibold uppercase tracking-[0.12em] text-ink-muted" key={field.name}>
                                    <button className="flex items-center gap-1.5 hover:text-ink" onClick={() => onSort(field.name)} type="button">
                                        {field.label}
                                        {sort === field.name && (
                                            <Icon className={`h-3.5 w-3.5 ${direction === 'asc' ? 'rotate-180' : ''}`} name="chevron" />
                                        )}
                                    </button>
                                </th>
                            ))}
                            <th className="px-4 py-3 text-right font-heading text-[0.68rem] font-semibold uppercase tracking-[0.12em] text-ink-muted">Actions</th>
                        </tr>
                    </thead>
                    <tbody className="divide-y divide-line/70">
                        {loading ? (
                            <tr>
                                <td className="px-5 py-14 text-center text-ink-muted" colSpan={fields.length + 1}>Loading records...</td>
                            </tr>
                        ) : records.length === 0 ? (
                            <tr>
                                <td className="px-5 py-14 text-center text-ink-muted" colSpan={fields.length + 1}>No matching records found.</td>
                            </tr>
                        ) : records.map((record) => (
                            <tr className="transition-colors hover:bg-surface-muted/50" key={record.id}>
                                {fields.map((field) => (
                                    <td className="max-w-xs whitespace-nowrap px-4 py-3 text-ink" key={`${record.id}-${field.name}`} title={String(record[field.name] ?? '')}>
                                        {formatValue(record[field.name], field)}
                                    </td>
                                ))}
                                <td className="whitespace-nowrap px-4 py-3 text-right">
                                    <div className="flex justify-end gap-1.5">
                                        <button className="rounded-lg bg-canvas px-3 py-2 text-xs font-semibold text-ink-muted transition-colors hover:bg-rx-blue hover:text-neutral-950" onClick={() => onEdit(record)} type="button">
                                            {canWrite ? 'Edit' : 'View'}
                                        </button>
                                        {canWrite && (
                                            <button aria-label={`Delete record ${record.id}`} className="rounded-lg px-3 py-2 text-xs font-semibold text-red-500 transition-colors hover:bg-red-500 hover:text-white" onClick={() => onDelete(record)} type="button">
                                                Delete
                                            </button>
                                        )}
                                    </div>
                                </td>
                            </tr>
                        ))}
                    </tbody>
                </table>
            </div>

            <footer className="flex flex-col gap-3 border-t border-line px-4 py-3 text-xs text-ink-muted sm:flex-row sm:items-center sm:justify-between">
                <p>
                    {meta.total > 0 ? `Showing ${meta.from}-${meta.to} of ${meta.total}` : 'No records'}
                </p>
                <div className="flex items-center gap-2">
                    <button className="rx-button-secondary px-3 py-2 text-xs" disabled={meta.current_page <= 1 || loading} onClick={() => onPageChange(meta.current_page - 1)} type="button">
                        Previous
                    </button>
                    <span className="px-2">Page {meta.current_page} of {Math.max(meta.last_page, 1)}</span>
                    <button className="rx-button-secondary px-3 py-2 text-xs" disabled={meta.current_page >= meta.last_page || loading} onClick={() => onPageChange(meta.current_page + 1)} type="button">
                        Next
                    </button>
                </div>
            </footer>
        </div>
    );
}
