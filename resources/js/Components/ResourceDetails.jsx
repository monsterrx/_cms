import axios from 'axios';
import { Children, isValidElement, useEffect, useMemo, useState } from 'react';
import ImageCropField from './ImageCropField';
import RichTextEditor from './RichTextEditor';
import SelectControl from './SelectControl';
import { translateError } from '../lib/errorTranslator';

function Panel({ children, title }) {
    return (
        <section className="mt-6 min-w-0 overflow-hidden rounded-lg border border-line bg-canvas/30 p-4">
            <h3 className="font-heading text-sm font-semibold uppercase tracking-wide text-ink">{title}</h3>
            <div className="mt-4">{children}</div>
        </section>
    );
}

function FlatSection({ children, title }) {
    return (
        <section className="mt-8 min-w-0 border-t border-line pt-6">
            <h3 className="font-heading text-sm font-semibold uppercase tracking-wide text-ink">{title}</h3>
            <div className="mt-5">{children}</div>
        </section>
    );
}

function Empty({ children }) {
    return <p className="rounded-md border border-dashed border-line px-4 py-6 text-center text-sm text-ink-muted">{children}</p>;
}

function FlatInput(props) {
    return <input {...props} className={`min-h-11 rounded-md border border-line bg-surface px-3 text-sm text-ink outline-none focus:border-rx-blue focus:ring-0 ${props.className || ''}`} />;
}

function FlatSelect({ children, className = '', ...props }) {
    const { onChange, value, ...controlProps } = props;
    const options = Children.toArray(children)
        .filter(isValidElement)
        .map((option) => ({
            label: String(option.props.children ?? option.props.value ?? ''),
            value: option.props.value ?? option.props.children ?? '',
        }));

    return (
        <SelectControl
            {...controlProps}
            className={className}
            onChange={(selectedValue) => onChange?.({ target: { value: selectedValue } })}
            options={options}
            value={value}
        />
    );
}

function applyImageFallback(event, fallbackUrl) {
    if (!fallbackUrl || event.currentTarget.dataset.fallbackApplied === 'true') {
        return;
    }

    event.currentTarget.dataset.fallbackApplied = 'true';
    event.currentTarget.src = fallbackUrl;
}

function SocialEditor({ details, endpoint, relation, reload, run }) {
    const [website, setWebsite] = useState('Facebook');
    const [url, setUrl] = useState('');
    const [drafts, setDrafts] = useState({});
    const rows = relation === 'links' ? (details.links ?? []) : (details.socials ?? []);

    useEffect(() => {
        setDrafts(Object.fromEntries(rows.map((row) => [row.id, { website: row.website, url: row.url }])));
    }, [details]);

    const saveNew = () => run(async () => {
        await axios.post(`${endpoint}/children/${relation}`, { website, url });
        setUrl('');
        reload();
    });

    return (
        <Panel title="Social links">
            <div className="grid gap-2 sm:grid-cols-[10rem_minmax(0,1fr)_auto]">
                <FlatSelect onChange={(event) => setWebsite(event.target.value)} value={website}>
                    {(details.social_networks ?? []).map((network) => <option key={network}>{network}</option>)}
                </FlatSelect>
                <FlatInput onChange={(event) => setUrl(event.target.value)} placeholder="https://..." type="url" value={url} />
                <button className="rx-button" disabled={!url} onClick={saveNew} type="button">Add link</button>
            </div>
            <div className="mt-4 space-y-2">
                {rows.length === 0 && <Empty>No social links yet.</Empty>}
                {rows.map((row) => (
                    <div className="grid gap-2 rounded-md border border-line p-3 sm:grid-cols-[10rem_minmax(0,1fr)_auto_auto]" key={row.id}>
                        <FlatSelect
                            onChange={(event) => setDrafts((current) => ({ ...current, [row.id]: { ...current[row.id], website: event.target.value } }))}
                            value={drafts[row.id]?.website ?? row.website}
                        >
                            {(details.social_networks ?? []).map((network) => <option key={network}>{network}</option>)}
                        </FlatSelect>
                        <FlatInput
                            onChange={(event) => setDrafts((current) => ({ ...current, [row.id]: { ...current[row.id], url: event.target.value } }))}
                            type="url"
                            value={drafts[row.id]?.url ?? row.url}
                        />
                        <button className="rx-button-secondary" onClick={() => run(async () => {
                            await axios.put(`${endpoint}/children/${relation}/${row.id}`, drafts[row.id]);
                            reload();
                        })} type="button">Save</button>
                        <button className="text-xs font-semibold uppercase text-red-500" onClick={() => run(async () => {
                            if (window.confirm('Remove this social link?')) {
                                await axios.delete(`${endpoint}/children/${relation}/${row.id}`);
                                reload();
                            }
                        })} type="button">Delete</button>
                    </div>
                ))}
            </div>
        </Panel>
    );
}

function JockDetails({ details, endpoint, reload, run }) {
    const [fact, setFact] = useState('');
    const [factDrafts, setFactDrafts] = useState({});
    const [imageName, setImageName] = useState('');
    const [imageFile, setImageFile] = useState(null);
    const [cropReady, setCropReady] = useState(true);
    const [imageDrafts, setImageDrafts] = useState({});
    const [showId, setShowId] = useState('');

    useEffect(() => {
        setFactDrafts(Object.fromEntries((details.facts ?? []).map((row) => [row.id, row.content])));
        setImageDrafts(Object.fromEntries((details.images ?? []).map((row) => [row.id, row.name || ''])));
    }, [details]);

    const imageField = {
        name: 'file',
        label: 'Extra Jock Image',
        crop: { width: 500, height: 500, label: 'Square extra image' },
    };

    return (
        <>
            <Panel title="Fun facts">
                <div className="flex gap-2">
                    <FlatInput className="flex-1" onChange={(event) => setFact(event.target.value)} placeholder="Add a fun fact" value={fact} />
                    <button className="rx-button" disabled={!fact.trim()} onClick={() => run(async () => {
                        await axios.post(`${endpoint}/children/facts`, { content: fact });
                        setFact('');
                        reload();
                    })} type="button">Add</button>
                </div>
                <div className="mt-4 space-y-2">
                    {(details.facts ?? []).length === 0 && <Empty>No fun facts yet.</Empty>}
                    {(details.facts ?? []).map((row) => (
                        <div className="flex gap-2" key={row.id}>
                            <FlatInput className="flex-1" onChange={(event) => setFactDrafts((current) => ({ ...current, [row.id]: event.target.value }))} value={factDrafts[row.id] ?? row.content} />
                            <button className="rx-button-secondary" onClick={() => run(async () => {
                                await axios.put(`${endpoint}/children/facts/${row.id}`, { content: factDrafts[row.id] });
                                reload();
                            })} type="button">Save</button>
                            <button className="text-xs font-semibold uppercase text-red-500" onClick={() => run(async () => {
                                if (window.confirm('Delete this fun fact?')) {
                                    await axios.delete(`${endpoint}/children/facts/${row.id}`);
                                    reload();
                                }
                            })} type="button">Delete</button>
                        </div>
                    ))}
                </div>
            </Panel>

            <Panel title="Extra images">
                <ImageCropField
                    disabled={false}
                    error=""
                    field={imageField}
                    onChange={(_name, file) => setImageFile(file)}
                    onCropStatusChange={(_name, ready) => setCropReady(ready)}
                    value=""
                />
                <div className="mt-3 flex gap-2">
                    <FlatInput className="flex-1" onChange={(event) => setImageName(event.target.value)} placeholder="Image name" value={imageName} />
                    <button className="rx-button" disabled={!imageFile || !imageName.trim() || !cropReady} onClick={() => run(async () => {
                        const payload = new FormData();
                        payload.append('name', imageName);
                        payload.append('file', imageFile);
                        await axios.post(`${endpoint}/children/images`, payload);
                        setImageFile(null);
                        setImageName('');
                        reload();
                    })} type="button">Upload</button>
                </div>
                <div className="mt-4 grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                    {(details.images ?? []).map((row) => (
                        <article className="overflow-hidden rounded-md border border-line" key={row.id}>
                            <img
                                alt={row.name || ''}
                                className="aspect-square w-full object-cover"
                                onError={(event) => applyImageFallback(event, row.fallback_image_url)}
                                src={row.image_url}
                            />
                            <div className="space-y-2 p-3">
                                <FlatInput className="w-full" onChange={(event) => setImageDrafts((current) => ({ ...current, [row.id]: event.target.value }))} value={imageDrafts[row.id] ?? ''} />
                                <div className="flex justify-between">
                                    <button className="text-xs font-semibold uppercase text-rx-blue" onClick={() => run(async () => {
                                        await axios.put(`${endpoint}/children/images/${row.id}`, { name: imageDrafts[row.id] });
                                        reload();
                                    })} type="button">Save name</button>
                                    <button className="text-xs font-semibold uppercase text-red-500" onClick={() => run(async () => {
                                        if (window.confirm('Delete this image?')) {
                                            await axios.delete(`${endpoint}/children/images/${row.id}`);
                                            reload();
                                        }
                                    })} type="button">Delete</button>
                                </div>
                            </div>
                        </article>
                    ))}
                </div>
            </Panel>

            <SocialEditor details={details} endpoint={endpoint} relation="links" reload={reload} run={run} />

            <Panel title="Shows">
                <div className="flex gap-2">
                    <FlatSelect className="flex-1" onChange={(event) => setShowId(event.target.value)} value={showId}>
                        <option value="">Choose a show</option>
                        {(details.availableShows ?? []).map((show) => <option key={show.id} value={show.id}>{show.title}</option>)}
                    </FlatSelect>
                    <button className="rx-button" disabled={!showId} onClick={() => run(async () => {
                        await axios.post(`${endpoint}/children/shows`, { show_id: showId });
                        setShowId('');
                        reload();
                    })} type="button">Add show</button>
                </div>
                <div className="mt-3 flex flex-wrap gap-2">
                    {(details.shows ?? []).map((show) => (
                        <span className="flex items-center gap-2 rounded-md bg-surface px-3 py-2 text-sm" key={show.id}>
                            {show.title}
                            <button className="text-red-500" onClick={() => run(async () => {
                                await axios.delete(`${endpoint}/children/shows/${show.id}`);
                                reload();
                            })} type="button">×</button>
                        </span>
                    ))}
                </div>
            </Panel>
        </>
    );
}

function BatchDetails({ details, endpoint, reload, run }) {
    const [studentId, setStudentId] = useState('');
    const [position, setPosition] = useState('1');

    return (
        <Panel title="Student Jocks in this batch">
            <div className="grid gap-2 sm:grid-cols-[minmax(0,1fr)_10rem_auto]">
                <FlatSelect onChange={(event) => setStudentId(event.target.value)} value={studentId}>
                    <option value="">Choose a Student Jock</option>
                    {(details.availableStudents ?? []).map((student) => (
                        <option key={student.id} value={student.id}>{student.first_name} {student.last_name} ({student.nickname})</option>
                    ))}
                </FlatSelect>
                <FlatSelect onChange={(event) => setPosition(event.target.value)} value={position}>
                    {Object.entries(details.positions ?? {}).map(([value, label]) => <option key={value} value={value}>{label}</option>)}
                </FlatSelect>
                <button className="rx-button" disabled={!studentId} onClick={() => run(async () => {
                    await axios.post(`${endpoint}/children/students`, { student_jock_id: studentId, position });
                    setStudentId('');
                    reload();
                })} type="button">Add</button>
            </div>
            <div className="mt-4 grid gap-3 sm:grid-cols-2">
                {(details.students ?? []).map((student) => (
                    <article className="flex gap-3 rounded-md border border-line p-3" key={student.id}>
                        <div className="h-16 w-16 shrink-0 overflow-hidden rounded-md bg-canvas">
                            {student.image_url && (
                                <img
                                    alt=""
                                    className="h-full w-full object-cover"
                                    onError={(event) => applyImageFallback(event, student.fallback_image_url)}
                                    src={student.image_url}
                                />
                            )}
                        </div>
                        <div className="min-w-0 flex-1">
                            <p className="font-semibold">{student.first_name} {student.last_name}</p>
                            <p className="text-xs text-ink-muted">{student.position_label} · {student.school || 'No school'}</p>
                        </div>
                        <button className="text-xs font-semibold uppercase text-red-500" onClick={() => run(async () => {
                            await axios.delete(`${endpoint}/children/students/${student.id}`);
                            reload();
                        })} type="button">Remove</button>
                    </article>
                ))}
            </div>
        </Panel>
    );
}

function ScholarBatchDetails({ details, endpoint, reload, run }) {
    const [studentId, setStudentId] = useState('');
    const [studentType, setStudentType] = useState('0');
    const [sponsorId, setSponsorId] = useState('');
    const [newStudent, setNewStudent] = useState({ school_id: '', first_name: '', last_name: '', course: '', year_level: '', scholar_type: '0' });
    const [newSponsor, setNewSponsor] = useState({ name: '', remarks: '' });
    const [studentDrafts, setStudentDrafts] = useState({});
    const [sponsorDrafts, setSponsorDrafts] = useState({});

    useEffect(() => {
        setStudentDrafts(Object.fromEntries((details.students ?? []).map((student) => [student.id, {
            school_id: student.school_id ?? '', first_name: student.first_name ?? '', middle_name: student.middle_name ?? '',
            last_name: student.last_name ?? '', course: student.course ?? '', year_level: student.year_level ?? '',
            scholar_type: String(student.scholar_type ?? 0),
        }])));
        setSponsorDrafts(Object.fromEntries((details.sponsors ?? []).map((sponsor) => [sponsor.id, { name: sponsor.name ?? '', remarks: sponsor.remarks ?? '' }])));
    }, [details]);

    const updateStudentDraft = (id, field, value) => setStudentDrafts((current) => ({ ...current, [id]: { ...current[id], [field]: value } }));
    const updateSponsorDraft = (id, field, value) => setSponsorDrafts((current) => ({ ...current, [id]: { ...current[id], [field]: value } }));

    return <>
        <Panel title="Students">
            <div className="grid gap-2 lg:grid-cols-[minmax(0,1fr)_10rem_auto]">
                <FlatSelect onChange={(event) => setStudentId(event.target.value)} value={studentId}><option value="">Attach an existing student</option>{(details.availableStudents ?? []).map((student) => <option key={student.id} value={student.id}>{student.last_name}, {student.first_name}</option>)}</FlatSelect>
                <FlatSelect onChange={(event) => setStudentType(event.target.value)} value={studentType}>{Object.entries(details.scholarTypes ?? {}).map(([value, label]) => <option key={value} value={value}>{label}</option>)}</FlatSelect>
                <button className="rx-button" disabled={!studentId} onClick={() => run(async () => { await axios.post(`${endpoint}/children/students`, { student_id: studentId, scholar_type: studentType }); setStudentId(''); reload(); })} type="button">Add student</button>
            </div>
            <div className="mt-4 rounded-md border border-line p-4">
                <p className="mb-3 text-xs font-semibold uppercase text-ink-muted">Create and add a student</p>
                <div className="grid gap-2 md:grid-cols-2 xl:grid-cols-6">
                    <FlatSelect onChange={(event) => setNewStudent((current) => ({ ...current, school_id: event.target.value }))} value={newStudent.school_id}><option value="">School</option>{(details.schools ?? []).map((school) => <option key={school.id} value={school.id}>{school.name}</option>)}</FlatSelect>
                    <FlatInput onChange={(event) => setNewStudent((current) => ({ ...current, first_name: event.target.value }))} placeholder="First name" value={newStudent.first_name} />
                    <FlatInput onChange={(event) => setNewStudent((current) => ({ ...current, last_name: event.target.value }))} placeholder="Last name" value={newStudent.last_name} />
                    <FlatInput onChange={(event) => setNewStudent((current) => ({ ...current, course: event.target.value }))} placeholder="Course" value={newStudent.course} />
                    <FlatSelect onChange={(event) => setNewStudent((current) => ({ ...current, scholar_type: event.target.value }))} value={newStudent.scholar_type}>{Object.entries(details.scholarTypes ?? {}).map(([value, label]) => <option key={value} value={value}>{label}</option>)}</FlatSelect>
                    <button className="rx-button" disabled={!newStudent.school_id || !newStudent.first_name.trim() || !newStudent.last_name.trim()} onClick={() => run(async () => { await axios.post(`${endpoint}/children/students`, newStudent); setNewStudent({ school_id: '', first_name: '', last_name: '', course: '', year_level: '', scholar_type: '0' }); reload(); })} type="button">Create student</button>
                </div>
            </div>
            <div className="mt-4 space-y-3">{(details.students ?? []).length === 0 && <Empty>No students assigned to this batch.</Empty>}{(details.students ?? []).map((student) => {
                const draft = studentDrafts[student.id] ?? {};
                return <article className="rounded-md border border-line p-4" key={student.id}>
                    <div className="grid gap-2 md:grid-cols-2 xl:grid-cols-6">
                        <FlatSelect onChange={(event) => updateStudentDraft(student.id, 'school_id', event.target.value)} value={draft.school_id ?? ''}>{(details.schools ?? []).map((school) => <option key={school.id} value={school.id}>{school.name}</option>)}</FlatSelect>
                        <FlatInput onChange={(event) => updateStudentDraft(student.id, 'first_name', event.target.value)} value={draft.first_name ?? ''} />
                        <FlatInput onChange={(event) => updateStudentDraft(student.id, 'last_name', event.target.value)} value={draft.last_name ?? ''} />
                        <FlatInput onChange={(event) => updateStudentDraft(student.id, 'course', event.target.value)} placeholder="Course" value={draft.course ?? ''} />
                        <FlatSelect onChange={(event) => updateStudentDraft(student.id, 'scholar_type', event.target.value)} value={draft.scholar_type ?? '0'}>{Object.entries(details.scholarTypes ?? {}).map(([value, label]) => <option key={value} value={value}>{label}</option>)}</FlatSelect>
                        <div className="flex gap-2"><button className="rx-button-secondary px-3 text-xs" onClick={() => run(async () => { await axios.put(`${endpoint}/children/students/${student.id}`, draft); reload(); })} type="button">Save</button><button className="text-xs font-semibold uppercase text-red-500" onClick={() => run(async () => { await axios.delete(`${endpoint}/children/students/${student.id}`); reload(); })} type="button">Remove</button></div>
                    </div>
                </article>;
            })}</div>
        </Panel>

        <Panel title="Sponsors">
            <div className="grid gap-2 sm:grid-cols-[minmax(0,1fr)_auto]"><FlatSelect onChange={(event) => setSponsorId(event.target.value)} value={sponsorId}><option value="">Attach an existing sponsor</option>{(details.availableSponsors ?? []).map((sponsor) => <option key={sponsor.id} value={sponsor.id}>{sponsor.name}</option>)}</FlatSelect><button className="rx-button" disabled={!sponsorId} onClick={() => run(async () => { await axios.post(`${endpoint}/children/sponsors`, { sponsor_id: sponsorId }); setSponsorId(''); reload(); })} type="button">Add sponsor</button></div>
            <div className="mt-4 grid gap-2 sm:grid-cols-[minmax(0,1fr)_minmax(0,1fr)_auto]"><FlatInput onChange={(event) => setNewSponsor((current) => ({ ...current, name: event.target.value }))} placeholder="New sponsor name" value={newSponsor.name} /><FlatInput onChange={(event) => setNewSponsor((current) => ({ ...current, remarks: event.target.value }))} placeholder="Remarks" value={newSponsor.remarks} /><button className="rx-button" disabled={!newSponsor.name.trim()} onClick={() => run(async () => { await axios.post(`${endpoint}/children/sponsors`, newSponsor); setNewSponsor({ name: '', remarks: '' }); reload(); })} type="button">Create sponsor</button></div>
            <div className="mt-4 space-y-2">{(details.sponsors ?? []).length === 0 && <Empty>No sponsors assigned to this batch.</Empty>}{(details.sponsors ?? []).map((sponsor) => <div className="grid gap-2 rounded-md border border-line p-3 sm:grid-cols-[minmax(0,1fr)_minmax(0,1fr)_auto_auto]" key={sponsor.id}><FlatInput onChange={(event) => updateSponsorDraft(sponsor.id, 'name', event.target.value)} value={sponsorDrafts[sponsor.id]?.name ?? ''} /><FlatInput onChange={(event) => updateSponsorDraft(sponsor.id, 'remarks', event.target.value)} value={sponsorDrafts[sponsor.id]?.remarks ?? ''} /><button className="rx-button-secondary px-3 text-xs" onClick={() => run(async () => { await axios.put(`${endpoint}/children/sponsors/${sponsor.id}`, sponsorDrafts[sponsor.id]); reload(); })} type="button">Save</button><button className="text-xs font-semibold uppercase text-red-500" onClick={() => run(async () => { await axios.delete(`${endpoint}/children/sponsors/${sponsor.id}`); reload(); })} type="button">Remove</button></div>)}</div>
        </Panel>
    </>;
}

function ArticleDetails({ details, endpoint, onPreviewChange, reload, run }) {
    const [articleId, setArticleId] = useState('');
    const [newContent, setNewContent] = useState('');
    const [contentDrafts, setContentDrafts] = useState({});

    useEffect(() => {
        setContentDrafts(Object.fromEntries(
            (details.contents ?? []).map((content) => [content.id, content.content ?? '']),
        ));
    }, [details.contents]);

    useEffect(() => {
        if (!onPreviewChange) return;

        const previewContents = (details.contents ?? []).map((content) => ({
            ...content,
            content: contentDrafts[content.id] ?? content.content ?? '',
        }));

        if (newContent.trim()) {
            previewContents.push({ id: 'new', content: newContent });
        }

        onPreviewChange(previewContents);
    }, [contentDrafts, details.contents, newContent, onPreviewChange]);

    return (
        <>
            <FlatSection title="Article contents">
                <div className="space-y-4">
                    {(details.contents ?? []).length === 0 && <Empty>No article sub-content has been added.</Empty>}
                    {(details.contents ?? []).map((content, index) => (
                        <article className="min-w-0 border-b border-line pb-5 last:border-b-0" key={content.id}>
                            <p className="mb-3 text-xs font-semibold uppercase tracking-wide text-ink-muted">Content block {index + 1}</p>
                            <RichTextEditor
                                disabled={false}
                                error=""
                                field={{ name: `content-${content.id}`, label: `Content block ${index + 1}` }}
                                onChange={(_name, value) => setContentDrafts((current) => ({ ...current, [content.id]: value }))}
                                required
                                value={contentDrafts[content.id] ?? ''}
                            />
                            <div className="mt-3 flex justify-end gap-3">
                                <button className="rx-button-secondary" onClick={() => run(async () => {
                                    await axios.put(`${endpoint}/children/sub-contents/${content.id}`, { content: contentDrafts[content.id] });
                                    reload();
                                })} type="button">Save content</button>
                                <button className="text-xs font-semibold uppercase text-red-500" onClick={() => {
                                    if (!window.confirm('Delete this article content block?')) return;
                                    run(async () => {
                                        await axios.delete(`${endpoint}/children/sub-contents/${content.id}`);
                                        reload();
                                    });
                                }} type="button">Delete</button>
                            </div>
                        </article>
                    ))}
                </div>
                <div className="mt-5 min-w-0 border-t border-dashed border-line pt-5">
                    <p className="mb-3 text-xs font-semibold uppercase tracking-wide text-ink-muted">Add content block</p>
                    <RichTextEditor
                        disabled={false}
                        error=""
                        field={{ name: 'new-article-content', label: 'New article content' }}
                        onChange={(_name, value) => setNewContent(value)}
                        required
                        value={newContent}
                    />
                    <button className="rx-button mt-3" disabled={!newContent.trim()} onClick={() => run(async () => {
                        await axios.post(`${endpoint}/children/sub-contents`, { content: newContent });
                        setNewContent('');
                        reload();
                    })} type="button">Add content</button>
                </div>
            </FlatSection>

            <FlatSection title="Related articles">
                <div className="grid min-w-0 gap-2 sm:grid-cols-[minmax(0,1fr)_auto]">
                    <FlatSelect onChange={(event) => setArticleId(event.target.value)} value={articleId}>
                        <option value="">Choose an article</option>
                        {(details.options ?? []).map((article) => <option key={article.id} value={article.id}>{article.title}</option>)}
                    </FlatSelect>
                    <button className="rx-button" disabled={!articleId} onClick={() => run(async () => {
                        await axios.post(`${endpoint}/children/related`, { related_article_id: articleId });
                        setArticleId('');
                        reload();
                    })} type="button">Add related</button>
                </div>
                <div className="mt-3 space-y-2">
                    {(details.related ?? []).map((related) => (
                        <div className="flex min-w-0 items-center justify-between gap-3 rounded-md border border-line px-3 py-3 text-sm" key={related.id}>
                            <span className="min-w-0 break-words">{related.title}</span>
                            <button className="shrink-0 text-xs font-semibold uppercase text-red-500" onClick={() => run(async () => {
                                await axios.delete(`${endpoint}/children/related/${related.id}`);
                                reload();
                            })} type="button">Remove</button>
                        </div>
                    ))}
                </div>
            </FlatSection>
        </>
    );
}

function MobileAssetEditor({ asset, endpoint, reload, run }) {
    const [file, setFile] = useState(null);
    const [cropReady, setCropReady] = useState(true);

    return (
        <article className="rounded-md border border-line bg-surface p-3">
            {asset.image_url && (
                <img
                    alt=""
                    className="mb-3 aspect-square w-full rounded-md bg-canvas object-cover"
                    onError={(event) => applyImageFallback(event, asset.fallback_image_url)}
                    src={asset.image_url}
                />
            )}
            <ImageCropField
                disabled={false}
                error=""
                field={{
                    name: asset.name,
                    label: asset.label,
                    crop: asset.crop,
                }}
                onChange={(_name, selectedFile) => setFile(selectedFile)}
                onCropStatusChange={(_name, ready) => setCropReady(ready)}
                value={asset.value}
            />
            <button
                className="rx-button mt-3 w-full justify-center"
                disabled={!file || !cropReady}
                onClick={() => run(async () => {
                    const payload = new FormData();
                    payload.append('_method', 'PUT');
                    payload.append(asset.name, file);
                    await axios.post(endpoint, payload);
                    setFile(null);
                    reload();
                })}
                type="button"
            >
                Save {asset.label}
            </button>
        </article>
    );
}

function MobileTitleField({ endpoint, label, name, reload, run, titleId, value }) {
    const [draft, setDraft] = useState(value ?? '');

    useEffect(() => setDraft(value ?? ''), [value]);

    return (
        <div className="rounded-md border border-line p-3">
            <label className="text-xs font-semibold uppercase tracking-wide text-ink-muted">
                {label}
                <FlatInput
                    className="mt-1 w-full normal-case"
                    onChange={(event) => setDraft(event.target.value)}
                    value={draft}
                />
            </label>
            <button
                className="rx-button-secondary mt-2"
                disabled={draft === (value ?? '')}
                onClick={() => run(async () => {
                    await axios.put(`${endpoint}/children/titles/${titleId}`, { [name]: draft });
                    reload();
                })}
                type="button"
            >
                Save
            </button>
        </div>
    );
}

function MobileDetails({ details, endpoint, reload, run }) {
    const title = details.title ?? {};
    const fields = Object.keys(title).filter((name) => !['id', 'asset_id', 'location', 'created_at', 'updated_at'].includes(name));

    return (
        <>
            <Panel title={`${details.app_name || 'Mobile App'} images`}>
                <p className="mb-4 text-sm text-ink-muted">Crop and save each application image independently.</p>
                <div className="grid gap-4 lg:grid-cols-2">
                    {(details.assets ?? []).map((asset) => (
                        <MobileAssetEditor
                            asset={asset}
                            endpoint={endpoint}
                            key={asset.name}
                            reload={reload}
                            run={run}
                        />
                    ))}
                </div>
            </Panel>
            <Panel title={`${details.app_name || 'Mobile App'} titles`}>
                {!title.id ? (
                    <Empty>No title record is linked to this theme.</Empty>
                ) : (
                    <div className="grid gap-3 md:grid-cols-2">
                        {fields.map((name) => (
                            <MobileTitleField
                                endpoint={endpoint}
                                key={name}
                                label={name.replaceAll('_', ' ')}
                                name={name}
                                reload={reload}
                                run={run}
                                titleId={title.id}
                                value={title[name]}
                            />
                        ))}
                    </div>
                )}
            </Panel>
        </>
    );
}

function MusicAwardsDetails({ details, endpoint, reload, run }) {
    const [awardName, setAwardName] = useState('');
    const [type, setType] = useState('artist');
    const [awardeeId, setAwardeeId] = useState('');
    const choices = details.choices?.[type] ?? [];

    useEffect(() => setAwardeeId(''), [type]);

    return (
        <Panel title="Awards in this release">
            <div className="grid gap-2 md:grid-cols-[minmax(0,1fr)_8rem_minmax(0,1fr)_auto]">
                <FlatInput onChange={(event) => setAwardName(event.target.value)} placeholder="Award text" value={awardName} />
                <FlatSelect onChange={(event) => setType(event.target.value)} value={type}>
                    <option value="artist">Artist</option>
                    <option value="album">Album</option>
                    <option value="song">Song</option>
                </FlatSelect>
                <FlatSelect onChange={(event) => setAwardeeId(event.target.value)} value={awardeeId}>
                    <option value="">Choose {type}</option>
                    {choices.map((choice) => <option key={choice.id} value={choice.id}>{choice.name}</option>)}
                </FlatSelect>
                <button className="rx-button" disabled={!awardName.trim() || !awardeeId} onClick={() => run(async () => {
                    await axios.post(`${endpoint}/children/awards`, {
                        award_name: awardName,
                        award_type: type,
                        awardee_id: awardeeId,
                    });
                    setAwardName('');
                    setAwardeeId('');
                    reload();
                })} type="button">Add award</button>
            </div>
            <div className="mt-4 space-y-2">
                {(details.awards ?? []).map((award) => (
                    <div className="flex items-center justify-between rounded-md border border-line px-3 py-3" key={award.id}>
                        <div>
                            <p className="text-sm font-semibold">{award.award_name}</p>
                            <p className="text-xs capitalize text-ink-muted">{award.awardee_type}: {award.awardee_name}</p>
                        </div>
                        <button className="text-xs font-semibold uppercase text-red-500" onClick={() => run(async () => {
                            if (window.confirm('Delete this award?')) {
                                await axios.delete(`${endpoint}/children/awards/${award.id}`);
                                reload();
                            }
                        })} type="button">Delete</button>
                    </div>
                ))}
            </div>
        </Panel>
    );
}

function spotifyEmbedUrl(value) {
    if (!value) return '';
    try {
        const url = new URL(value);
        if (!['open.spotify.com', 'spotify.com', 'www.spotify.com'].includes(url.hostname)) return '';
        const parts = url.pathname.split('/').filter(Boolean);
        if (parts[0] === 'embed') return url.toString();
        if (parts.length >= 2) return `https://open.spotify.com/embed/${parts[0]}/${parts[1]}`;
    } catch {
        return '';
    }
    return '';
}

function SongDetails({ details, endpoint, reload, run }) {
    const [sample, setSample] = useState(null);
    const embed = spotifyEmbedUrl(details.track_url);

    return (
        <Panel title="Track preview">
            {details.type === 'spotify' ? (
                embed ? (
                    <iframe
                        allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture"
                        className="h-40 w-full rounded-md border-0"
                        loading="lazy"
                        src={embed}
                        title="Spotify track preview"
                    />
                ) : <Empty>The Spotify track link is missing or invalid.</Empty>
            ) : details.track_url ? (
                <audio className="w-full" controls preload="metadata" src={details.track_url}>
                    <track kind="captions" />
                </audio>
            ) : (
                <p className="border-l-4 border-rx-yellow bg-rx-yellow/10 p-4 text-sm text-ink">Track sample is missing.</p>
            )}
            <div className="mt-4 flex flex-col gap-2 sm:flex-row sm:items-center">
                <input
                    accept="audio/mpeg,audio/mp4,audio/x-m4a,audio/ogg,audio/wav"
                    className="block min-h-11 flex-1 rounded-md border border-line bg-surface px-3 py-2 text-sm"
                    onChange={(event) => setSample(event.target.files?.[0] ?? null)}
                    type="file"
                />
                <button className="rx-button" disabled={!sample} onClick={() => run(async () => {
                    const payload = new FormData();
                    payload.append('sample', sample);
                    await axios.post(`${endpoint}/actions/upload-sample`, payload);
                    setSample(null);
                    reload();
                })} type="button">Save sample track</button>
            </div>
        </Panel>
    );
}

function PodcastPlayer({ link }) {
    if (!link) {
        return <p className="border-l-4 border-rx-yellow bg-rx-yellow/10 px-3 py-2 text-xs text-ink">Podcast audio is missing.</p>;
    }

    return (
        <audio className="h-10 w-full min-w-48" controls preload="metadata" src={link}>
            Your browser does not support podcast audio playback.
        </audio>
    );
}

function PodcastDetails({ record }) {
    return (
        <FlatSection title="Podcast preview">
            <PodcastPlayer link={record.link} />
        </FlatSection>
    );
}

function ShowDetails({ details, endpoint, reload, run }) {
    const [jockId, setJockId] = useState('');
    const [slot, setSlot] = useState({ day: 'Monday', start: '', end: '' });
    const [imageName, setImageName] = useState('');
    const [imageFile, setImageFile] = useState(null);
    const [imageReady, setImageReady] = useState(true);
    const [podcast, setPodcast] = useState({ episode: '', date: '', link: '' });
    const [podcastImage, setPodcastImage] = useState(null);
    const [podcastReady, setPodcastReady] = useState(true);
    const [editingPodcastId, setEditingPodcastId] = useState(null);
    const [podcastDraft, setPodcastDraft] = useState({ episode: '', date: '', link: '' });
    const squareField = (name, label) => ({ name, label, crop: { width: 500, height: 500, label: 'Square image' } });

    return (
        <>
            <Panel title="Jocks">
                <div className="flex gap-2">
                    <FlatSelect onChange={(event) => setJockId(event.target.value)} value={jockId}>
                        <option value="">Choose a Jock</option>
                        {(details.availableJocks ?? []).map((jock) => <option key={jock.id} value={jock.id}>{jock.name}</option>)}
                    </FlatSelect>
                    <button className="rx-button" disabled={!jockId} onClick={() => run(async () => {
                        await axios.post(`${endpoint}/children/jocks`, { jock_id: jockId });
                        setJockId(''); reload();
                    })} type="button">Add Jock</button>
                </div>
                <div className="mt-3 flex flex-wrap gap-2">
                    {(details.jocks ?? []).map((jock) => (
                        <span className="flex items-center gap-2 rounded-md border border-line px-3 py-2 text-sm" key={jock.id}>
                            {jock.name}
                            <button className="text-red-500" onClick={() => run(async () => {
                                await axios.delete(`${endpoint}/children/jocks/${jock.id}`); reload();
                            })} type="button">Remove</button>
                        </span>
                    ))}
                </div>
            </Panel>
            <Panel title="On-air timeslots">
                <div className="grid gap-2 md:grid-cols-[10rem_1fr_1fr_auto]">
                    <FlatSelect onChange={(event) => setSlot((current) => ({ ...current, day: event.target.value }))} value={slot.day}>
                        {(details.days ?? []).map((day) => <option key={day}>{day}</option>)}
                    </FlatSelect>
                    <FlatInput onChange={(event) => setSlot((current) => ({ ...current, start: event.target.value }))} type="time" value={slot.start} />
                    <FlatInput onChange={(event) => setSlot((current) => ({ ...current, end: event.target.value }))} type="time" value={slot.end} />
                    <button className="rx-button" disabled={!slot.start || !slot.end} onClick={() => run(async () => {
                        await axios.post(`${endpoint}/children/timeslots`, slot); reload();
                    })} type="button">Add</button>
                </div>
                <div className="mt-3 space-y-2">
                    {(details.timeslots ?? []).map((row) => (
                        <div className="flex items-center justify-between rounded-md border border-line px-3 py-2 text-sm" key={row.id}>
                            <span>{row.day} · {String(row.start).slice(0, 5)}-{String(row.end).slice(0, 5)}</span>
                            <button className="text-xs font-semibold uppercase text-red-500" onClick={() => run(async () => {
                                await axios.delete(`${endpoint}/children/timeslots/${row.id}`); reload();
                            })} type="button">Remove</button>
                        </div>
                    ))}
                </div>
            </Panel>
            <Panel title="Extra show images">
                <ImageCropField disabled={false} error="" field={squareField('file', 'Extra image')} onChange={(_name, file) => setImageFile(file)} onCropStatusChange={(_name, ready) => setImageReady(ready)} value="" />
                <div className="mt-2 flex gap-2">
                    <FlatInput className="flex-1" onChange={(event) => setImageName(event.target.value)} placeholder="Image name" value={imageName} />
                    <button className="rx-button" disabled={!imageFile || !imageReady || !imageName} onClick={() => run(async () => {
                        const payload = new FormData(); payload.append('name', imageName); payload.append('file', imageFile);
                        await axios.post(`${endpoint}/children/images`, payload); setImageName(''); setImageFile(null); reload();
                    })} type="button">Save image</button>
                </div>
                <div className="mt-4 grid gap-3 sm:grid-cols-3">
                    {(details.images ?? []).map((image) => <article className="rounded-md border border-line p-2" key={image.id}>
                        <img alt={image.name} className="aspect-square w-full object-cover" src={image.image_url} />
                        <div className="mt-2 flex justify-between gap-2 text-xs"><span>{image.name}</span><button className="text-red-500" onClick={() => run(async () => { await axios.delete(`${endpoint}/children/images/${image.id}`); reload(); })} type="button">Delete</button></div>
                    </article>)}
                </div>
            </Panel>
            <Panel title="Podcasts for this show">
                <div className="grid gap-2 md:grid-cols-3">
                    <FlatInput onChange={(event) => setPodcast((current) => ({ ...current, episode: event.target.value }))} placeholder="Episode title" value={podcast.episode} />
                    <FlatInput onChange={(event) => setPodcast((current) => ({ ...current, date: event.target.value }))} type="date" value={podcast.date} />
                    <FlatInput onChange={(event) => setPodcast((current) => ({ ...current, link: event.target.value }))} placeholder="Podcast URL" type="url" value={podcast.link} />
                </div>
                <div className="mt-3"><ImageCropField disabled={false} error="" field={squareField('image', 'Podcast image')} onChange={(_name, file) => setPodcastImage(file)} onCropStatusChange={(_name, ready) => setPodcastReady(ready)} value="" /></div>
                <button className="rx-button mt-3" disabled={!podcast.episode || !podcast.date || !podcast.link || !podcastReady} onClick={() => run(async () => {
                    const payload = new FormData(); Object.entries(podcast).forEach(([key, value]) => payload.append(key, value)); if (podcastImage) payload.append('image', podcastImage);
                    await axios.post(`${endpoint}/children/podcasts`, payload); setPodcast({ episode: '', date: '', link: '' }); setPodcastImage(null); reload();
                })} type="button">Add podcast</button>
                <div className="mt-5 overflow-x-auto rounded-md border border-line">
                    <table className="min-w-full text-left text-sm">
                        <thead className="bg-canvas/70 text-xs uppercase tracking-wide text-ink-muted">
                            <tr>
                                <th className="px-3 py-3">Image</th>
                                <th className="px-3 py-3">Episode</th>
                                <th className="px-3 py-3">Date</th>
                                <th className="min-w-64 px-3 py-3">Preview</th>
                                <th className="px-3 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-line">
                            {(details.podcasts ?? []).map((row) => (
                                <tr className="align-middle hover:bg-canvas/40" key={row.id}>
                                    <td className="px-3 py-3"><img alt="" className="h-12 w-12 rounded-sm bg-canvas object-cover" src={row.image_url} /></td>
                                    <td className="max-w-xs whitespace-normal break-words px-3 py-3 font-semibold">{row.episode}</td>
                                    <td className="whitespace-nowrap px-3 py-3 text-ink-muted">{row.date}</td>
                                    <td className="px-3 py-3"><PodcastPlayer link={row.link} /></td>
                                    <td className="px-3 py-3">
                                        <div className="flex justify-end gap-3">
                                            <button className="text-xs font-semibold uppercase text-rx-blue" onClick={() => {
                                                setEditingPodcastId(row.id);
                                                setPodcastDraft({ episode: row.episode ?? '', date: row.date ?? '', link: row.link ?? '' });
                                            }} type="button">Edit</button>
                                            <button className="text-xs font-semibold uppercase text-red-500" onClick={() => run(async () => {
                                                if (!window.confirm(`Delete ${row.episode}?`)) return;
                                                await axios.delete(`${endpoint}/children/podcasts/${row.id}`);
                                                if (editingPodcastId === row.id) setEditingPodcastId(null);
                                                reload();
                                            })} type="button">Delete</button>
                                        </div>
                                    </td>
                                </tr>
                            ))}
                            {(details.podcasts ?? []).length === 0 && (
                                <tr><td className="px-4 py-8 text-center text-ink-muted" colSpan="5">No podcast episodes have been added.</td></tr>
                            )}
                        </tbody>
                    </table>
                </div>
                {editingPodcastId && (
                    <div className="mt-5 border-t border-line pt-5">
                        <p className="mb-3 text-xs font-semibold uppercase tracking-wide text-ink-muted">Edit podcast episode</p>
                        <div className="grid gap-2 md:grid-cols-3">
                            <FlatInput onChange={(event) => setPodcastDraft((current) => ({ ...current, episode: event.target.value }))} placeholder="Episode title" value={podcastDraft.episode} />
                            <FlatInput onChange={(event) => setPodcastDraft((current) => ({ ...current, date: event.target.value }))} type="date" value={podcastDraft.date} />
                            <FlatInput onChange={(event) => setPodcastDraft((current) => ({ ...current, link: event.target.value }))} placeholder="Podcast URL" type="url" value={podcastDraft.link} />
                        </div>
                        <div className="mt-3"><PodcastPlayer link={podcastDraft.link} /></div>
                        <div className="mt-3 flex gap-2">
                            <button className="rx-button" disabled={!podcastDraft.episode || !podcastDraft.date || !podcastDraft.link} onClick={() => run(async () => {
                                await axios.put(`${endpoint}/children/podcasts/${editingPodcastId}`, podcastDraft);
                                setEditingPodcastId(null);
                                reload();
                            })} type="button">Save podcast</button>
                            <button className="rx-button-secondary" onClick={() => setEditingPodcastId(null)} type="button">Cancel</button>
                        </div>
                    </div>
                )}
            </Panel>
        </>
    );
}

function TimeslotDetails({ details, endpoint, reload, run }) {
    const [jockId, setJockId] = useState('');
    return <Panel title="Jocks on this timeslot">
        <div className="flex gap-2"><FlatSelect onChange={(event) => setJockId(event.target.value)} value={jockId}><option value="">Choose a Jock</option>{(details.availableJocks ?? []).map((jock) => <option key={jock.id} value={jock.id}>{jock.name}</option>)}</FlatSelect><button className="rx-button" disabled={!jockId} onClick={() => run(async () => { await axios.post(`${endpoint}/children/jocks`, { jock_id: jockId }); setJockId(''); reload(); })} type="button">Add</button></div>
        <div className="mt-3 flex flex-wrap gap-2">{(details.jocks ?? []).map((jock) => <span className="rounded-md border border-line px-3 py-2 text-sm" key={jock.id}>{jock.name} <button className="ml-2 text-red-500" onClick={() => run(async () => { await axios.delete(`${endpoint}/children/jocks/${jock.id}`); reload(); })} type="button">Remove</button></span>)}</div>
    </Panel>;
}

export default function ResourceDetails({
    canWrite,
    details,
    endpoint,
    error,
    itemSlug,
    loading,
    onPreviewChange,
    record,
    reload,
}) {
    const [message, setMessage] = useState('');

    const run = async (operation) => {
        setMessage('');
        try {
            await operation();
        } catch (requestError) {
            setMessage(translateError(requestError).message);
        }
    };

    const content = useMemo(() => {
        if (!canWrite) {
            return null;
        }
        const props = { details, endpoint, onPreviewChange, reload, run, record };
        if (itemSlug === 'jocks') return <JockDetails {...props} />;
        if (itemSlug === 'radio1-batches') return <BatchDetails {...props} />;
        if (itemSlug === 'student-jocks') return <SocialEditor details={details} endpoint={endpoint} relation="socials" reload={reload} run={run} />;
        if (itemSlug === 'articles') return <ArticleDetails {...props} />;
        if (itemSlug === 'songs') return <SongDetails {...props} />;
        if (itemSlug === 'podcasts') return <PodcastDetails {...props} />;
        if (itemSlug === 'shows') return <ShowDetails {...props} />;
        if (itemSlug === 'timeslots') return <TimeslotDetails {...props} />;
        if (itemSlug === 'mobile-application') return <MobileDetails {...props} />;
        if (itemSlug === 'monster-music-awards') return <MusicAwardsDetails {...props} />;
        if (itemSlug === 'scholar-batches') return <ScholarBatchDetails {...props} />;
        return null;
    }, [canWrite, details, endpoint, itemSlug, onPreviewChange, record]);

    if (loading) {
        return <p className="mt-6 text-sm text-ink-muted">Loading related records...</p>;
    }
    if (error) {
        return <p className="mt-6 border-l-4 border-red-500 bg-red-500/10 p-4 text-sm text-red-500">{error}</p>;
    }

    return (
        <>
            {message && <p className="mt-6 border-l-4 border-red-500 bg-red-500/10 p-4 text-sm text-red-500">{message}</p>}
            {content}
        </>
    );
}
